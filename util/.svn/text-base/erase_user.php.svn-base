<?
require_once("../config.php");
require_once("../inc/main.php");

require_login();

if ($user->id <> 1) die("must be trevor");

$uid = (int)$_REQUEST['uid'];

if (empty($uid)) die("must have uid");
# Delete existing individuals
showdel("app_user_line_person","WHERE user_id = $uid");
showdel("tree_event","WHERE updated_by = $uid");
showdel("tree_family","WHERE created_by = $uid");
showdel("tree_person","WHERE created_by = $uid");
showdel("app_user","WHERE user_id = $uid");

function showdel($table, $where) {
	global $db;
	if (isset($_REQUEST["delete"])) {
		$db->sql_query("DELETE FROM $table $where");
	} else {
		$data = $db->select("SELECT * FROM $table $where");
		echo "<p>$table $where</p>";
		print_pre($data);
	}
}
echo "<a href='?uid=$uid&delete=1'>Delete</a>";
?>
