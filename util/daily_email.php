<?
# Used to calculate birth years for individuals
require_once("../config.php");
require_once("../inc/main.php");

require_login(); // remove this when it's a cron job
set_time_limit(10000);

include_class("Person");
include_class("Family");

echo "
<a href='../index.php'>Home</a>
<h3>Daily Email Processor</h3>
";

$i = 2;
$sql = "
SELECT t.person_id, t.family_name, t.given_name, u.user_id, u.email FROM (
		SELECT w.person_id, w.user_id
		FROM app_watch w
		JOIN tree_person t ON t.person_id = w.person_id AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY)
		and w.user_id <> t.updated_by
		UNION
		SELECT w.person_id, w.user_id
		FROM app_watch w
		JOIN tree_event t ON t.key_id = w.person_id AND t.table_type = 'P' AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY)
		and w.user_id <> t.updated_by
) c
JOIN tree_person t ON t.person_id = c.person_id AND ".actualClause("t")."
JOIN app_user u ON c.user_id = u.user_id
ORDER BY u.user_id";
$data = $db->select($sql);

print_pre($data);

$sql = "
SELECT f.family_id, p1.family_name, p1.given_name, p2.family_name family_name2, p2.given_name given_name2, u.user_id, u.email FROM (
		SELECT t.family_id, w.user_id FROM app_watch w
		JOIN tree_family t ON t.person1_id = w.person_id AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY) and w.user_id <> t.updated_by
		UNION

		SELECT t.family_id, w.user_id FROM app_watch w
		JOIN tree_family t ON t.person2_id = w.person_id AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY) and w.user_id <> t.updated_by
		UNION

		SELECT t.family_id, w.user_id FROM app_watch w
		JOIN tree_person p ON p.person_id = w.person_id AND ".actualClause("p")."
		JOIN tree_family t ON t.family_id = p.bio_family_id AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY) and w.user_id <> t.updated_by
		UNION

		SELECT f.family_id, w.user_id FROM app_watch w
		JOIN tree_family f ON f.person1_id = w.person_id AND ".actualClause("f")."
		JOIN tree_event t ON t.key_id = f.family_id AND t.table_type = 'F' AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY) and w.user_id <> t.updated_by
		UNION

		SELECT f.family_id, w.user_id FROM app_watch w
		JOIN tree_family f ON f.person2_id = w.person_id AND ".actualClause("f")."
		JOIN tree_event t ON t.key_id = f.family_id AND t.table_type = 'F' AND ".actualClause("t")."
		WHERE t.update_date > DATE_SUB(CURDATE(),INTERVAL $i DAY) and w.user_id <> t.updated_by
) c
JOIN tree_family f ON c.family_id = f.family_id AND ".actualClause("f")."
LEFT JOIN tree_person p1 ON p1.person_id = f.person1_id AND ".actualClause("p1")."
LEFT JOIN tree_person p2 ON p2.person_id = f.person2_id AND ".actualClause("p2")."
JOIN app_user u ON c.user_id = u.user_id
ORDER BY u.user_id";
//echo $sql;
$data = $db->select($sql);

print_pre($data);

foreach ($data as $row) {
}

?>