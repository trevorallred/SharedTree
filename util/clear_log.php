<?
# Task that is called from task_runner.php
$output = "starting to clear the app_log table";

$sql = "DELETE FROM app_log
		WHERE user_id = 0
		AND visit_date < (NOW() - INTERVAL 30 DAY)";
$db->sql_query($sql);
$deleted = $db->sql_affectedrows();
$output .= "\ndeleted $deleted log entries for user_id = 0 older than 30 days";

$sql = "DELETE FROM app_log
		WHERE visit_date < (NOW() - INTERVAL 180 DAY)";
$db->sql_query($sql);
$deleted = $db->sql_affectedrows();
$output .= "\ndeleted $deleted log entries older than 180 days";

$success = true;
?>
