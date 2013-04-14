<?
require_once("config.php");
require_once("inc/main.php");

set_time_limit(10000);

include_class("User");
include_class("Family");

$family_id = (int)$_REQUEST["family_id"];
if(empty($family_id)) errorMessage("Variable family_id is required");

$fam = new Family($family_id);
if(empty($fam->data["person1_id"])) errorMessage("A husband is required");
if(empty($fam->data["person2_id"])) errorMessage("A wife is required");

$AREA = "dev";

saveAncestors($fam->data["person1_id"]);
saveAncestors($fam->data["person2_id"]);

$sql = "SELECT a1.trace dad_trace, length( a1.trace ) , length( a2.trace ) , a2.trace mom_trace, p.person_id, p.given_name, p.family_name
FROM app_person_ancestor a1
JOIN app_person_ancestor a2 USING ( ancestor_id )
JOIN tree_person p ON ancestor_id = p.person_id AND ".actualClause("p")."
WHERE a1.person_id < a2.person_id
AND a1.person_id = ".$fam->data["person1_id"]."
AND a2.person_id = ".$fam->data["person2_id"]."
ORDER BY a1.trace, a2.trace";

$data = $db->select($sql);
echo "Searching for common ancestors within 15 generations ";
print_pre($data);

/*
$person_id = 330;

$sql = "SELECT person_id, family_name, given_name FROM tree_person 
	WHERE ".actualClause()." AND person_id > $person_id LIMIT 0,10";
$data = $db->select($sql);

foreach($data as $p) {
	saveAncestors($p["person_id"]);
}
*/

function getAncestors($person_id, $gen, $trace, &$people) {
	if (empty($person_id)) return;
	if ($gen <= 0) return;
	$gen--;

	global $db;
	if ($trace > "") $people[$person_id] = $trace;

	$sql = "SELECT f.person1_id, f.person2_id FROM tree_family f
		JOIN tree_person p ON f.family_id = p.bio_family_id AND ".actualClause("p")."
		WHERE ".actualClause("f")." AND p.person_id = $person_id";
	$data = $db->select($sql);
	foreach($data as $row) {
		getAncestors($row["person1_id"], $gen, $trace."F", $people); //Father
		getAncestors($row["person2_id"], $gen, $trace."M", $people); //Mother
	}
}

function saveAncestors($person_id) {
	global $db;

	$people = array();
	getAncestors($person_id, 15, "", $people);

	$sql = "DELETE FROM app_person_ancestor WHERE person_id = $person_id";
	$db->sql_query($sql);

	//print_pre($people);
	if (count($people) == 0) return;

	$sql = "";
	foreach($people as $pid=>$trace) {
		if ($sql > "") $sql .= ", ";
		$sql .= "($person_id, $pid, '$trace')";
	}
	$sql = "INSERT INTO app_person_ancestor (person_id, ancestor_id, trace) VALUES ".$sql;
	$db->sql_query($sql);
	echo "Rebuilt tree for person_id $person_id<br>";
}
echo "<p>done</p>";

?>
