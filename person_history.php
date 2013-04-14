<?
require_once("config.php");
require_once("inc/main.php");

$person_id = (int)$_REQUEST["person_id"];
if (empty($person_id)) {
	$T->display("header.tpl");
	errorMessage("person_id is required", "search.php", false);
}
$T->assign("person_id", $person_id);

include_class("Person");
$person = new Person($person_id);
if ($person->restricted) privateRecord();

$sql = "SELECT p.*, merge_names(u.given_name, u.family_name) username 
FROM tree_person p LEFT JOIN app_user u ON u.user_id = p.updated_by 
WHERE p.person_id = $person_id ORDER BY p.actual_start_date";
$person_hist = $db->select( $sql );
$T->assign("person_hist", $person_hist);

$sql = "SELECT e.*, merge_names(u.given_name, u.family_name) username 
FROM tree_event e LEFT JOIN app_user u ON u.user_id = e.updated_by 
WHERE e.key_id = $person_id AND table_type = 'P' ORDER BY event_type, e.actual_start_date";
$event_hist = $db->select( $sql );
$T->assign("event_hist", $event_hist);

$sql = "SELECT import_id, individual, gedcom_text FROM tree_person_gedcom WHERE person_id = $person_id";
$gedcom = $db->select( $sql );
$T->assign("gedcom", $gedcom);

$sql = "SELECT p.*, merge_names(u.given_name, u.family_name) username 
FROM tree_person p LEFT JOIN app_user u ON u.user_id = p.updated_by
WHERE p.merged_into = $person_id ORDER BY p.actual_start_date DESC";
$temp = $db->select( $sql );
$merge_hist = array();
foreach ($temp as $row) {
	if (!in_array($row["person_id"], $merge_hist) ) {
		$merge_hist[$row["person_id"]] = $row;
	}
}
$T->assign("merge_hist", $merge_hist);
//print_pre($merge_hist);

$title = $person_hist[0]["given_name"]." ".$person_hist[0]["family_name"];
if ($person_hist[0]['birth_year'] > 0) $title .= " (".$person_hist[0]['birth_year'].")";
if ($person_hist[0]['birth_year'] < 0) $title .= " (".-1*$person_hist[0]['birth_year']." B.C.)";

$T->assign("primary_person", $title);
$T->assign("title", "History Audit for ".$title);

$T->display('person_history.tpl');
?>
