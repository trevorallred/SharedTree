<?
require_once("config.php");
require_once("inc/main.php");

$person_id = (int)$_REQUEST["person_id"];
if (empty($person_id)) die("missing person_id");

$sql = "SELECT person_to_id as person_id FROM app_merge m WHERE status_code = 'M' AND person_to_id = $person_id UNION
	SELECT person_from_id as person_id FROM app_merge m WHERE status_code = 'M' AND person_to_id = $person_id";
$data = $db->select($sql);
$id_list = $person_id;
foreach ($data as $row) {
	$id_list .= ", ".$row['person_id'];
}
$table = fixTick($_REQUEST["table"]);
$field = fixTick($_REQUEST["field"]);
//if (empty($table)) die("missing table");
if (empty($field)) die("missing field");

if ($table == '')
	$sql = "SELECT value, count(*) total FROM (
			SELECT updated_by, $field value FROM tree_person WHERE person_id IN ($id_list) AND $field IS NOT NULL UNION
			SELECT created_by, $field value FROM tree_person WHERE person_id IN ($id_list) AND $field IS NOT NULL
		) t GROUP BY value ORDER BY total DESC";
else
	$sql = "SELECT value, count(*) total FROM (
			SELECT updated_by, $field value FROM tree_event 
			WHERE table_type = 'P' and event_type = '$table' and key_id IN ($id_list) AND $field IS NOT NULL
		) t GROUP BY value ORDER BY total DESC";
$data = $db->select($sql);
echo "<table style='grid'><tr><th>value</th><th title='number of users who contributed this value'>#</th></tr>";
foreach ($data as $row) {
	echo "<tr><td>".$row['value']."</td><td>".$row['total']."</td></tr>";
}
echo "</table>";
?>
