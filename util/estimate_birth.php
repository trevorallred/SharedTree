<?
# Used to calculate birth years for individuals
set_time_limit(10000);

require_once("../config.php");
require_once("../inc/main.php");

include_class("Person");
$sql = "SELECT count(*) total FROM tree_person WHERE birth_year IS NULL AND ".actualClause();
$data = $db->select($sql);
$count = $data[0]['total'];

if (ob_get_level() == 0) {
   ob_start();
}
echo "
<a href='../index.php'>Home</a>
<h3>Birth Date Estimator Script</h3>

$count remaining people without estimated birth years<br><br>";

$next_id = (int) $_REQUEST['next_id'];
if (empty($next_id)) $next_id = 0;
$limit = (int) $_REQUEST['limit'];
if (empty($limit)) $limit = 2500;

if ($next_id == 0) {
	// Only do this at the beginning of script
	Person::updateBirthYears();
	echo "<hr><p>Updating all birth years not already set</p>";
}

$rows = 1;
while ($rows > 0) {
	$sql = "SELECT * FROM tree_person WHERE person_id > $next_id AND birth_year IS NULL AND ".actualClause()." ORDER BY person_id LIMIT $limit";
	$data = $db->select($sql);
	$rows = count($data);
	if ($rows == 0) continue;
	echo "<hr>$sql<p>set year based on valid birth event</p>";

	$i = 0;
	foreach ($data as $value) {
		$i++;
		$p = new Person($value['person_id']);
		$next_id = $p->id;
		$p->estimateBirthYear();

		if ($i%10==0) {
			$now = microtime(true);
			echo "Seconds: ".round($now - $time_start)."<br />";
		}
		if ($p->data['birth_year'] > 0) {
			echo("<a href='../person.php?person_id=$p->id'>$p->id</a> : ".$p->data['birth_year']." <br>");
		} else {
			echo("<a href='../person.php?person_id=$p->id'>$p->id</a> : unknown  <br>");
		}
		unset($p);
		flush();
		ob_flush();
	}

	echo "<br><br> <a href='estimate_birth.php?next_id=$next_id&limit=$limit'>Next</a>";
}

ob_end_flush();
?>