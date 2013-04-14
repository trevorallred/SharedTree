<?

require_once("config.php");
require_once("inc/main.php");

$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year, p.creation_date, p.created_by, CONCAT(u.given_name, ' ', u.family_name) user_name
		FROM tree_person p
		LEFT JOIN app_user u ON p.created_by = u.user_id
		WHERE ".actualClause("p")." AND p.bio_family_id IS NULL AND p.adopt_family_id IS NULL
		AND p.person_id NOT IN (SELECT person1_id FROM tree_family WHERE person1_id > 0 AND ".actualClause().")
		AND p.person_id NOT IN (SELECT person2_id FROM tree_family WHERE person2_id > 0 AND ".actualClause().")
		ORDER BY p.family_name, p.given_name";
$orphans = $db->select($sql);
$T->assign('orphans',$orphans);
$T->display('orphans.tpl');

?>