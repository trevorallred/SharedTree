<?
require_once("config.php");
require_once("inc/main.php");

$sql = "SELECT * FROM tree_person WHERE wikipedia > '' $where AND ".actualClause();
$data = $db->select($sql);

$T->assign('people', $data);

$T->display('wikipedia_list.tpl');
?>
