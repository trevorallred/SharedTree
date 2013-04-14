<?
# Used to calculate birth years for individuals
set_time_limit(10000);

require_once("../config.php");
require_once("../inc/main.php");

require_once("../inc/buildline.php");

set_time_limit(1000);

/**
 * Family Tree indexes (FTI) need to be rebuilt on a regular basis
 *
 * We run this file every so often:
 * type 1 = every few minutes we see who's making changes and rebuild their FTIs
 * type 2 = every day we see who has visited and rebuild their FTIs
 * type 3 = every month we see who hasn't had their FTI rebuilt and we rebuild it anyway
 */
$sql = "SELECT user_id, given_name, family_name, line_update_date, last_visit_date
		FROM app_user WHERE person_id > 0 AND 
		(line_update_date IS NULL OR
		 DATEDIFF(Now(), line_update_date) > 30 OR
		 DATEDIFF(last_visit_date, line_update_date) > 1)";
//$sql = "SELECT user_id, family_name, given_name, line_update_date, last_visit_date from app_user where user_id in (6,29,93,100,106,1,117)";

$user_list = $db->select($sql);

if (ob_get_level() == 0) {
   ob_start();
}

echo "<h3>Family Tree Index Builder</h3>";

foreach($user_list as $row) {
	buildFamilyTreeIndex($row["user_id"]);
	echo $row["user_id"].$row["given_name"].$row["family_name"].$row["line_update_date"]."<br>";
	flush();
	ob_flush();
}

ob_end_flush();
?>
<b>Done</b>
