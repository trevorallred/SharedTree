<?
require_once("../config.php");
require_once("../inc/main.php");

include_class("Event");

$sql = "SELECT g.person_id, e.event_type, gedcom_text FROM tree_person_gedcom g
		JOIN tree_event e ON g.person_id = e.key_id AND table_type = 'P' AND event_date = '0000-00-00' AND ".actualClause()."
		WHERE g.person_id > 0 LIMIT 0,100";
$data = $db->select( $sql );
foreach($data as $value) {
	$str = $value["gedcom_text"];
	$pos = strpos($str, '1 '.$value["event_type"]);
	preg_match("/\n2 DATE(.*)/", substr($str, $pos), $matches);

	unset($event);
	$event['key_id'] = $value["person_id"];
	$event['table_type'] = 'P';
	$event['event_type'] = $value["event_type"];
	$event['event_date'] = $matches[1];
	print_pre($event);
	Event::save($event);
}
die();

$person_id = 13;

$sql = "DELETE FROM tree_event WHERE key_id = $person_id AND table_type = 'P'";
$db->sql_query( $sql );


foreach($tests as $key=>$str) {
}

$sql = "SELECT event_date, ad,  date_approx, date_text, notes FROM tree_event WHERE key_id = $person_id AND table_type = 'P'";
$data = $db->select( $sql );

echo "<table>";
foreach($data as $row) {
	echo "<tr>
		<td>".$row["date_text"]."</td>
		<td>".$row["event_date"]."</td>
		<td>".$row["ad"]."</td>
		<td>".$row["date_approx"]."</td>
		<td>".$row["notes"]."</td></tr>";
}
echo "</table>";

#$sql = "DELETE FROM tree_event WHERE key_id = $person_id AND table_type = 'P'";
#$db->sql_query( $sql );

?>