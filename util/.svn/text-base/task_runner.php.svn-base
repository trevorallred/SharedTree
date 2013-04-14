<?
$config['BASE_DIR'] = "/var/www/sharedtree/htdocs";

require($config['BASE_DIR'] . "/config.php");
require($config['BASE_DIR'] . "/inc/main.php");

include_class("CronTask");
$data = CronTask::getPendingList();

foreach ($data as $row) {
	$task = new CronTask();
	$task->setByResultSet($row);
	$task->start();
	$output = "";
	$success = false;
	try {
		$file_name = $row["file_name"];
		if ($file_name == "") throw new Exception("file_name was blank");
		$file_name .= ".php";
		if (file_exists($file_name)) throw new Exception("$file_name was not found");
		@include($file_name);
	} catch(Exception $e) {
		$success = false;
		$output .= "\n\nException thrown:" . $e->getMessage();
	}
	$task->complete($success, $output);
}
?>
