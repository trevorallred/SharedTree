<?
require_once("config.php");
require_once("inc/main.php");

require_login();

include_class("Person");
include_class("Family");

############################################################################
# Set common variables and assign them to the smarty template
$person_id = (int)$_REQUEST['person_id'];
$T->assign('person_id',$person_id);
$time = $_REQUEST['time'];
$T->assign('time',$time);
$T->assign("show_lds", $user->data['show_lds']);
$T->assign("date_approx", array(""=>"", "ABT"=>"About", "CAL"=>"Calculated", "EST"=>"Estimated", "BEF"=>"Before", "AFT"=>"After"));
$T->assign("adbc", array(1=>"A.D.", 0=>"B.C."));
$T->assign('gender_options',array("M"=>"Male","F"=>"Female", "U"=>"Unknown"));
$T->assign('return_to', $_REQUEST['return_to']);
$T->assign('noindex',1); // don't index this page

$action = $_REQUEST['action'];

$marriage_id = (int)$_REQUEST["person"]["marriage_id"];
$spouse_id = (int)$_REQUEST["person"]["spouse_id"];
$child_id = (int)$_REQUEST["person"]["child_id"];
$parents_id = (int)$_REQUEST["person"]["parents_id"];


############################################################################

# Delete existing individuals
if ($action == "delete") {
	# Save a new or existing individual
	$person = new Person($person_id);
	$person->delete();
	
	redirect("/person/$person_id");
	exit();
}
# Delete event
if ($action == "deleteevent") {
	include_class("Event");
	# Save a new or existing individual
	Event::deleteByEvent($_REQUEST["event_id"]);
	
	redirect("/person/$person_id");
	exit();
}
# Restore delete individuals
if ($action == "restore") {
	# Save a new or existing individual
	if (Person::restore($person_id)) {
		redirect("/person/$person_id");
		exit();
	}
}

# Save new individuals
if ($action == "save") {
	//print_pre($_REQUEST);
	//die();
	# Save a new or existing individual
	$person = new Person();
	$save = $_REQUEST['person'];
	$save["person_id"] = $person_id;
	$person_id = $person->save($save);

	include_class("Watch");
	Watch::watchPerson($person_id, $_REQUEST["watch"]);
	
	# We can do 4 things once the person has now been saved
	# Create Person
	# Create Family
	# Add Family to Person (attach child)
	# Add Person to Family (attach spouse)
	
	# We pass in four "weird" fields as a person
	# marriage_id - ADD SPOUSE TO MARRIAGE; //skip spouse_id & child_id
	# spouse_id - CREATE MARRIAGE AND ADD SPOUSE TO THAT MARRIAGE //skip child_id
	# child_id - ADD SPOUSE TO THAT MARRIAGE, CREATE MARRIAGE, UPDATE child_id's family to THAT MARRIAGE
	# parents_id - ADD CHILD TO FAMILY; update person's family_id
	$done = false;
	// Always do the easiest one first and skip the rest (hardest is child_id so it goes last)
	if ($parents_id > 0 && !$done) {
		// Used when adding existing parents to child
		attachChild($person_id, $parents_id);
		$done = true;
	}
	if ($marriage_id > 0 && !$done) {
		// Used when adding other parent to family or other spouse to existing marriage
		attachSpouse($person_id, $marriage_id);
		$done = true;
	}
	if ($spouse_id > 0 && !$done) {
		// Used when adding spouse when marriage doesn't already exist
		createMarriage($person_id, $spouse_id);
		$done = true;
	}
	if ($child_id > 0 && !$done) {
		// Used when adding parents to child (first father or mother)
		$marriage_id = createMarriage($person_id, $spouse_id);
		attachChild($child_id, $marriage_id);
		$done = true;
	}

	## Attach this person to the current user (this should only happen on inserts)
	if (!empty($_REQUEST["attachuser"])) {
		$user->attachperson($person_id);
	}

	## rebuild the family tree index
	## only do this if you think it will be quick
	if (!empty($_REQUEST["build_fti"])) {
		$temp = $db->select("SELECT count(*) FROM app_user");
		print_pre($temp);
		require_once("inc/buildline.php");
		buildFamilyTreeIndex($user->id, false);
	}

	if ($_REQUEST['return_to'] > "") {
		redirect($_REQUEST['return_to']);
	}
	redirect("/family/$person_id");
	exit();
}

############################################################################
# Attach spouse to marriage
# $child_id = the person_id of the child you need to attach
# $family_id = the marriage/family you're attaching to
function attachSpouse($spouse_id, $family_id) {
	if (empty($spouse_id)) return "spouse_id was empty";
	if (empty($family_id)) return "family_id was empty";
	
	$spouse = new Person($spouse_id);
	$family = new Family($family_id);
	
	//print_pre($family->data);
	// Make sure we aren't adding a duplicate or loop or something
	$children = $family->getChildren();
	foreach ($children as $sibling) {
		if ($sibling['person_id'] == $spouse_id) return "The spouse you attempted to attach is already a parent of this family";
	}
	$s1 = $family->data['person1_id'];
	$s2 = $family->data['person2_id'];
	if ($s1 == $spouse_id) return "The spouse you attempted to attach is already listed as the husband";
	if ($s2 == $spouse_id) return "The spouse you attempted to attach is already listed as the wife";
	if ($s1 > 0 && $s2 > 0) return "This family already has two parents";

	// OK, we have one or more blank places and we haven't already added them to this family
	$save['family_id'] = $family->id;
	
	// see if this is the wife first
	if (!empty($s1) || $spouse->data["gender"] == "F") {
		$save['person2_id'] = $spouse_id;
	} else {
		// Either only the first slot is open or the person is Male, either way, put them in the first
		$save['person1_id'] = $spouse_id;
	}
	//print_pre($save);
	//die();
	$family->save($save);
	return "";
}

############################################################################
# Attach child to parents' marriage
# $child_id = the person_id of the child you need to attach
# $family_id = the marriage/family you're attaching to
function attachChild($child_id, $family_id) {
	$family = new Family($family_id);
	$children = $family->getChildren();
	if ($family->data['person1_id'] == $child_id) return "The child you attempted to attach is already listed as the father";
	if ($family->data['person2_id'] == $child_id) return "The child you attempted to attach is already listed as the mother";
	foreach ($children as $sibling) {
		if ($sibling['person_id'] == $child_id) return "The child you attempted to attach is already in this family";
	}
	$child_person = new Person();
	$save['person_id'] = $child_id;
	$save['bio_family_id'] = $family_id;
	$child_person->save($save);
	return "";
}

############################################################################
# Create a new marriage/family
function createMarriage($person1, $person2) {
	if (empty($person1) && empty($person2)) return "at least one spouse must exist to create family record";
	if ($person1 == $person2) return "a person can't be married to themselves";
	$add["person1_id"] = $person1;
	$add["person2_id"] = $person2;
	$family = new Family();
	$family_id = $family->save($add);
	return $family_id;
}

# End Saving to Database
############################################################################


############################################################################
# Start displaying data for Edits


############################################################################
# Edit or Add an Individual
if ($person_id > 0) {
	// Edit the existing ind
	$person = new Person($person_id, $time);
	$pdata = $person->data;

	$title = $person->data['full_name'];
	if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
	if ($person->data['birth_year'] < 0) $title .= " (".-1*$person->data['birth_year']." B.C.)";

	$T->assign("primary_person", $title);
	$T->assign("title", "Edit: ".$title);

} else {
	# Add a new person, but search first
	# Search, Choose Add Individuals

	$T->assign("title", "New Individual");

	$pdata = $_REQUEST['person'];
	if ($pdata) {

		# Perform the search here
		# I moved this from the Person class because the extra layer was causing unneeded abstraction
		//$results = Person::search($pdata);
		$search_data = $pdata;
		$where = "";
		if ( !empty($search_data['family_name']) ) {
			$where .= " AND p.family_name like '" . trim(fixTick($search_data['family_name'])) . "%'";
		}
		if ( !empty($search_data['given_name']) ) {
			$where .= " AND p.given_name like '%" . trim(fixTick($search_data['given_name'])) . "%'";
		}
		if ( !empty($search_data['birth_year']) ) {
			(int) $search_data['birth_year'];
			(int) $search_data['range'];
			$start_yr = $search_data['birth_year'] - $search_data['range'];
			$end_yr = $search_data['birth_year'] + $search_data['range'] + 1;
			$where .= " AND eb.event_date >= STR_TO_DATE('{$start_yr}', '%Y')";
			$where .= " AND eb.event_date <= STR_TO_DATE('{$end_yr}', '%Y')";
			if ($search_data['adbc'] == 0) $where .= " AND eb.ad = 0";
			else $where .= " AND eb.ad = 1";
		}

		$perm_sql = "OR p.created_by = $user->id OR p.person_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = '$user->id')";
		
		$sql = "SELECT p.person_id, p.given_name, p.family_name, p.gender, p.bio_family_id, eb.event_date, eb.location, p1.person_id person1_id, p1.given_name given_name1, p1.family_name family_name1, p2.person_id person2_id, p2.given_name given_name2, p2.family_name family_name2
				FROM tree_person p
				LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb")."
				LEFT JOIN tree_family f ON p.bio_family_id = f.family_id AND ".actualClause("f")."
				LEFT JOIN tree_person p1 ON p1.person_id = f.person1_id AND ".actualClause("p1")."
				LEFT JOIN tree_person p2 ON p2.person_id = f.person2_id AND ".actualClause("p2")."
				WHERE ".actualClause("p")." $where
				AND (p.public_flag = 1 $perm_sql )
				ORDER BY p.family_name, p.given_name, p.birth_year
				LIMIT 0, 25";
		//echo $sql;
		$results = $db->select( $sql );

		$T->assign("results", $results);
	}

	if (is_array($_REQUEST['add'])) {
		foreach ($_REQUEST['add'] as $key=>$value) {
			$pdata[$key] = removeSlashes($value);
		}
	}
}

// Go ahead and assign it since we may have some data in the person array
$T->assign('person', $pdata);

include_class("Event");
$gedcomcodes = Event::GedcomCodes("P");
$T->assign("gedcomcodes", $gedcomcodes);
//$T->assign('events',$pdata["e"]);

$T->assign('errors',$error);
$T->display('person_edit.tpl');

?>
