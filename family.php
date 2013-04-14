<?
if ( isset($_REQUEST["family_id"]) && !isset($_REQUEST["person_id"]) ) {
	# family.php?family_id was renamed to marriage.php?family_id
	# gen.php?person_id was renamed to family.php?person_id
	# we can remove this check starting Jan 2007
	include("marriage.php");
	exit();
}

require_once("config.php");
require_once("inc/main.php");

include_class("Person");
include_class("Family");

############################################################################
# Set common variables and assign them to the smarty template
$person_id = (int)$_REQUEST['person_id'];
// $person_id = (int)substr($_SERVER['PATH_INFO'],1,16);
$T->assign('person_id',$person_id);
$family_id = (int)$_REQUEST['family_id'];
$T->assign('family_id',$family_id);
$do = $_REQUEST['do'];
$time = $_REQUEST['time'];
$T->assign('time',$time);
if ($time) $T->assign('noindex',1); // don't index this page

$T->assign("show_lds", $user->data['show_lds']);
$T->assign("adbc", array(1=>"A.D.", 0=>"B.C."));
$T->assign('gender_options',array("M"=>"Male","F"=>"Female", "U"=>"Unknown"));
$marriage_statuses['M'] = "Married";
$marriage_statuses['D'] = "Divorced";
$marriage_statuses['S'] = "Separated";
$marriage_statuses['U'] = "Unknown";
$T->assign('marriage_statuses',$marriage_statuses);

function errorLogin() {
	if (!is_logged_on()) {
		echo "ERROR: You must be logged in to do this action!";
		exit();
	}
}
############################################################################
# Add/save individuals

if ($do == "saveFather" || $do == "saveMother") {
	if ($_REQUEST['person']['person_id'] > 0) {
		# Save a new or existing individual
		require_login();
		$_GLOBAL['update_process'] = __FILE__;
		$person = new Person();
		$person_id = $person->save($_REQUEST['person']);
		redirect("/family/$person_id&family_id=$family_id");
	}
}

if ($do == "saveIndividual") {
	# Save a new or existing individual
	require_login();
	$_GLOBAL['update_process'] = __FILE__;
	$person = new Person();
	$person_id = $person->save($_REQUEST['person']);
	redirect("/family/$person_id&family_id=$family_id");
	echo "Successfully saved to database";
	exit();
}

############################################################################
# Add/save parents
if (!empty($_REQUEST['removeparent'])) {
	if ($_REQUEST['removeparent'] == 1) $save['person1_id'] = null;
	if ($_REQUEST['removeparent'] == 2) $save['person2_id'] = null;
	if (count($save)) {
		require_login();
		$save['family_id'] = $family_id;
		$family->save($save);
		echo "Successfully saved to database";
		exit();
	}
}

if (!empty($_REQUEST['addparent'])) {
	$save['family_id'] = $family_id;
	if (empty($family->data['person1_id'])) $save['person1_id'] = $_REQUEST['addparent'];
	elseif (empty($family->data['person2_id'])) $save['person2_id'] = $_REQUEST['addparent'];
	if (count($save) > 1) {
		require_login();
		$save['family_id'] = $_REQUEST['family_id'];
		$family->save($save);
		echo "Successfully saved to database";
		exit();
	}
}

############################################################################
# Add/save spouses & marriages
if ($do == "savefamily") {
	require_login();
	$family = new Family();
	$_GLOBAL['update_process'] = __FILE__;
	$family_id = $family->save($_REQUEST['family']);
	// TODO if adding a new spouse, you need to redirect
	// redirect("/family/".$person_id."&family_id=".$family_id);
	echo "Successfully saved to database";
	exit();
}

############################################################################
# Add/save children (requires marriage)

$child_id = $_REQUEST['removechild'];
if ($child_id > 0) {
	require_login();
	include_class("Person");
	$child_person = new Person($child_id);
	$save['bio_family_id'] = null;
	$child_person->save($save);
	//echo "Successfully saved to database";
}

############################################################################
# View the Individual and their Parents and their Spouses and Children
if (empty($person_id)) {
	$T->display("header.tpl");
	errorMessage("You must supply a person_id to view or edit an individual","search.php", false);
}

$person = new Person($person_id, $time);
if ($person->restricted) privateRecord();
include_class("Log");
Log::track($person->id);

$peaps[] = $person_id;

$T->assign('person',$person->data);
$T->assign('individual',$person->data);
$parent_family = new Family($person->data['bio_family_id'], $time );
$T->assign('parents',$parent_family->data);

if ($parent_family->data["person1_id"] > 0) {
	$father = new Person($parent_family->data["person1_id"], $time);
	$peaps[] = $father->id;
} else {
	$father = new Person();
	$father->data["child_id"] = $person_id;
	$father->data["spouse"] = 1;
	$father->data["gender"] = "M";
}
$T->assign('father',$father->data);

if ($parent_family->data["person2_id"] > 0) {
	$mother = new Person($parent_family->data["person2_id"], $time);
	$peaps[] = $mother->id;
} else {
	$mother = new Person();
	$mother->data["child_id"] = $person_id;
	$mother->data["spouse"] = 2;
	$mother->data["gender"] = "F";
}
$T->assign('mother',$mother->data);

$marriages = $person->getMarriages(true);
//print_pre($marriages);
if (is_array($marriages)) {
	foreach($marriages as $row) {
		if ($row["person_id"] > 0) $peaps[] = $row["person_id"];
	}
	$T->assign('marriages',$marriages);
}
if (count($peaps)) {
	$peaps_sql = implode(",", $peaps);
	$temp = $db->select("SELECT person_id, image_id FROM tree_image WHERE person_id IN ($peaps_sql) AND image_order >= 1 AND event_id IS NULL ORDER BY image_order");
	$photos = array();
	foreach($temp as $row) {
		# For each person, set their photo in increasing order, the highest order will remain to be displayed
		$photos[$row["person_id"]] = $row["image_id"];
	}
	$T->assign("photos", $photos);
}

if ($user->id == -9) {
	print_pre($parent_family->data);
	print_pre($father->data);
	print_pre($mother->data);
	print_pre($marriages);
	die("done");
}

# Track the view
$person->recordView();

# Create the title for this page
$title = $person->data['full_name'];
if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
if ($person->data['birth_year'] < 0) $title .= " (".-1*$person->data['birth_year']." B.C.)";

$T->assign("primary_person", $title);
$T->assign("title", $title);

# Finally! Display the page
$T->display('gen_view.tpl');
?>
