<?
require_once("config.php");
require_once("inc/main.php");

require_login();

$page = empty($_REQUEST["page"]) ? 1 : (int)$_REQUEST["page"];
$T->assign('page', $page);
$start = 100 * ($page - 1);

$sql = "SELECT p.person_id, p.given_name, p.family_name, l.trace, r.description, 
		eb.event_date baptism_date, eb.status baptism_status, 
		ee.event_date endow_date, eb.status endow_status, 
		es.event_date seal_date, eb.status seal_status
	FROM tree_person p
	JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id =$user->id 
	JOIN ref_relation r ON r.trace = l.trace
	LEFT JOIN tree_event eb ON eb.event_type = 'BAPL' AND eb.table_type = 'P' AND eb.key_id = p.person_id AND ".actualClause("eb")."
	LEFT JOIN tree_event ee ON ee.event_type = 'ENDL' AND ee.table_type = 'P' AND ee.key_id = p.person_id AND ".actualClause("ee")."
	LEFT JOIN tree_event es ON es.event_type = 'SLGC' AND es.table_type = 'P' AND es.key_id = p.person_id AND ".actualClause("es")."
	WHERE p.temple_status = 0 AND p.public_flag = 1 AND ".actualClause("p")." ORDER BY r.distance LIMIT $start, 100";

//echo $sql;
$data = $db->select( $sql );

foreach ($data as $key=>$row) {
	$done = "";
	if (WorkDone($row["baptism_date"], $row["baptism_status"]) && WorkDone($row["endow_date"], $row["endow_status"]) && WorkDone($row["seal_date"], $row["seal_status"])) {
		#This person has their work completed
		$sql = "UPDATE tree_person SET temple_status = 1 WHERE person_id = ".$row["person_id"];
		$db->sql_query($sql);
		$done = "DONE ";
	}
	$data[$key]["done"] = $done;
}
$T->assign('results', $data);

$sql = "SELECT count(*) total FROM tree_person p
	JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id =$user->id 
	WHERE p.temple_status = 0 AND p.public_flag = 1 AND ".actualClause("p");
$data = $db->select( $sql );
$T->assign('pages', ceil($data[0]["total"]/100));

$T->display('templework_list.tpl');

function WorkDone($date, $status) {
	if ($date > "") return true;
	$status = trim($status);
	if ($status == "CHILD") return true;
	if ($status == "BIC") return true;
	if ($status == "STILLBORN") return true;
	if ($status == "INFANT") return true;
	return false;
}
?>
