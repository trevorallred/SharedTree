<?

require_once("config.php");
require_once("inc/main.php");

$action = $_REQUEST['action'];
include_class("Person");
include_class("Family");

# Default behavior
$p1 = (int)$_REQUEST['p1'];
$p2 = (int)$_REQUEST['p2'];
if (empty($p1) || empty($p2)) {
	$T->display("header.tpl");
	errorMessage("You must supply two individuals to merge","search.php", false);
}
if ($p1 == $p2) errorMessage("For some reason you're trying to merge the same person");

if ($action == "matchspouse") {
	$spouse = (int)$_REQUEST["spouse"];
	$p = new Person(
	exit();
}

$s = new Person($p1);
if ($s->restricted) privateRecord();
$s->data["spouses"] = $s->getMarriages(true);
$T->assign('a',$s->data);

$s = new Person($p2);
if ($s->restricted) privateRecord();
$s->data["spouses"] = $s->getMarriages(true);
$T->assign('b',$s->data);

$T->assign('title',  "Merge Descendents");
$T->display('desc_merge.tpl');

?>
