<?
require_once("../config.php");
require_once("../inc/main.php");

$sql = "SELECT user_id FROM app_user WHERE email = 'johndoe@foobar.org'";
$data = $db->select($sql);
$user_id = (int) $data[0]['user_id'];

if (empty($user_id)) die("No test user exists");
echo "DELETING user_id = $user_id";

deleteQuery("Import", "DELETE FROM app_import WHERE user_id = $user_id");
deleteQuery("Recent View", "DELETE FROM app_recent_view WHERE user_id = $user_id");
deleteQuery("Session", "DELETE FROM app_session WHERE user_id = $user_id");
deleteQuery("Line Family", "DELETE FROM app_user_line_family WHERE user_id = $user_id");
deleteQuery("Line Person", "DELETE FROM app_user_line_person WHERE user_id = $user_id");
deleteQuery("Relative", "DELETE FROM app_user_relative WHERE user1_id = $user_id OR user2_id = $user_id");
deleteQuery("Post", "DELETE FROM discuss_post WHERE created_by = $user_id");
deleteQuery("Wiki", "DELETE FROM discuss_wiki WHERE person_id IN (SELECT person_id FROM tree_person WHERE created_by = $user_id)");
deleteQuery("Person Event", "DELETE FROM tree_event WHERE table_type = 'P' AND key_id IN (SELECT person_id FROM tree_person WHERE created_by = $user_id)");
deleteQuery("Family Event", "DELETE FROM tree_event WHERE table_type = 'F' AND key_id IN (SELECT family_id FROM tree_family WHERE created_by = $user_id)");
deleteQuery("Residence", "DELETE FROM tree_residence WHERE family_id IN (SELECT family_id FROM tree_family WHERE created_by = $user_id)");
deleteQuery("Family", "DELETE FROM tree_family WHERE created_by = $user_id");
deleteQuery("Person", "DELETE FROM tree_person WHERE created_by = $user_id");
deleteQuery("User", "DELETE FROM app_user WHERE user_id = $user_id");

function deleteQuery($table, $sql) {
	global $db;
	$db->sql_query($sql);
	$rows = $db->sql_affectedrows();
	echo "<p>$rows from $table</p>";
}
?>