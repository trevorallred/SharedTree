<?

require_once("config.php");
if ($_REQUEST['action'] == "ajax") $track_hit = false;
require_once("inc/main.php");

$person_id = $_REQUEST['person_id'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
if (empty($person_id)) {
	$T->display("header.tpl");
	errorMessage("You must supply a person_id to view a descendent chart","search.php?", false);
}

$T->assign('person_id',$person_id);

include_class("FamilyTreeDB");

$T->assign("getnew", 0);

$data = FamilyTreeDB::getDescendants($person_id, 3);
$T->assign("data", $data);

if ($action == "ajax") {
	$T->assign("child", $data);
	$T->display('desc_family.tpl');
	die();
}

include_class("Person");

$person = new Person($person_id);
if ($person->restricted) privateRecord();

$T->assign("person", $person->data);
$title = $person->data['full_name'];
if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
if ($person->data['birth_year'] < 0) $title .= " (".-1*$person->data['birth_year']." B.C.)";

$T->assign("primary_person", $title);
$T->assign("title", $title);

$parents = FamilyTreeDB::getAncestors($person_id, 2);
$T->assign("parents", $parents);
//print_pre($parents);

$T->display('descendants.tpl');
?>
