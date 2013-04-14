<?
require_once("config.php");
require_once("inc/main.php");

include_class("Person");
include_class("Family");

############################################################################
# Set common variables and assign them to the smarty template
$person_id = (int)$_REQUEST['id'];
if (empty($person_id)) {
	$T->display('mobile_search.tpl');
	exit;
}
$T->assign('person_id',$person_id);

$marriage_statuses['M'] = "Married";
$marriage_statuses['D'] = "Divorced";
$marriage_statuses['S'] = "Separated";
$marriage_statuses['U'] = "Unknown";
$T->assign('marriage_statuses',$marriage_statuses);

############################################################################
# View the Individual and their Parents and their Spouses and Children

$person = new Person($person_id);
if ($person->restricted) errorMessage("This person is a private record, to which you do not have access.","search.php");
$T->assign('person',$person->data);

# Get the parents and grandparents
$parents = getParents($person->data['bio_family_id']);
$T->assign('father',$parents["father"]);
$T->assign('mother',$parents["mother"]);
$fathers_parents = getParents($parents['father']['bio_family_id']);
$T->assign('ffather',$fathers_parents["father"]);
$T->assign('fmother',$fathers_parents["mother"]);
$mothers_parents = getParents($parents['mother']['bio_family_id']);
$T->assign('mfather',$mothers_parents["father"]);
$T->assign('mmother',$mothers_parents["mother"]);

function getParents($family_id) {
	$family = new Family($family_id);
	$parents = array();

	if ($family->data["person1_id"] > 0) {
		$father = new Person($family->data["person1_id"]);
		$parents["father"] = $father->data;
	}
	if ($family->data["person2_id"] > 0) {
		$mother = new Person($family->data["person2_id"]);
		$parents["mother"] = $mother->data;
	}
	return $parents;
}

# Get spouses and children
$marriages = $person->getMarriages(true);
$T->assign('marriages',$marriages);

# Track the view
$person->recordView();

# Create the title for this page
$title = $person->data['full_name'];
if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
if ($person->data['birth_year'] < 0) $title .= " (".-1*$person->data['birth_year']." B.C.)";
$T->assign("title", $title);

$T->display('mobile_person.tpl');
exit;
?>
