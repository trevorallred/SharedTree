<?

require_once("config.php");
require_once("inc/main.php");

include_class("Person");
$search = $_REQUEST['search'];
if ($search) {
	$T->assign("search", $search);
	$results = Person::search($search);
	$T->assign("results", $results);
}
$T->assign("family_id", $_REQUEST['family_id']);
$T->assign("action", $_REQUEST['action']);
$T->display('person_choose.tpl');

?>