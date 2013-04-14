<?
require_once("config.php");
require_once("inc/main.php");

$action = $_REQUEST["action"];
if ($user->id > 0 && $action == "mytree") {

$sql= "SELECT p.person_id, p.given_name, p.family_name, p.title, birth_year, r.description, p.public_flag, p.creation_date, u.user_id, merge_names(u.given_name, u.family_name) user_name
	FROM app_user_line_person l
	JOIN tree_person p ON l.person_id = p.person_id and p.actual_end_date > NOW()
	LEFT JOIN app_user u ON p.created_by = u.user_id
	LEFT JOIN ref_relation r ON l.trace = r.trace
	WHERE l.user_id = $user->id AND p.created_by <> $user->id
	ORDER BY p.creation_date DESC LIMIT 0, 100";
} else {
$sql = "SELECT p.person_id, p.creation_date, p.family_name, p.given_name, p.title, birth_year, p.public_flag, u.user_id, CONCAT(u.given_name, ' ', u.family_name) user_name
		FROM (SELECT * FROM tree_person ORDER BY person_id DESC LIMIT 0, 500) p 
		LEFT JOIN app_user u ON u.user_id = p.created_by
		WHERE actual_end_date > Now() ORDER BY p.creation_date DESC LIMIT 0, 50";
}

$changes = $db->select($sql);
$T->assign("changes", $changes);

$T->display('recent_changes.tpl');
?>
