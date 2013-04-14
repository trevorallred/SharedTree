<?
require_once("config.php");
require_once("inc/main.php");

$sql = "SELECT l.trace, r.description, count(*) total FROM app_user_line_person l
	JOIN ref_relation r ON l.trace = r.trace
	WHERE user_id = '$user->id' GROUP BY l.trace, r.description";

$T->assign("title", "Family Graph");
$data = $db->select($sql);
$p = array();
foreach ($data as $row) {
	$p[$row["trace"]] = $row;
}
$T->assign("r", $p);
$T->display("family_graph.tpl");
?>
