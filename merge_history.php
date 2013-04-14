<?
require_once("config.php");
require_once("inc/main.php");

$T->assign('noindex',1); // don't index this page
/*
# Remove self merges
$sql = "DELETE FROM app_merge WHERE person_from_id = person_to_id";
$db->sql_query($sql);
# Remove duplicate records
$sql = "CREATE TEMPORARY TABLE tmptable
SELECT merge_id FROM app_merge WHERE person_from_id < person_to_id
AND (person_from_id, person_to_id) IN (SELECT person_to_id, person_from_id FROM app_merge)";
$db->sql_query($sql);
$sql = "DELETE FROM app_merge WHERE merge_id IN (SELECT merge_id FROM tmptable)";
$db->sql_query($sql);
$sql = "DROP TABLE tmptable";
$db->sql_query($sql);
*/

$T->assign("title", "Merge History");
$T->display('header.tpl');

$where = "";
$by_user = (int)$_REQUEST["by_user"];
if ($by_user > 0) $where .= " AND uc.user_id = $by_user";

$page = (int)$_REQUEST["page"];
$rows = 100;
$start = $page * $rows;

/*
$sql = "SELECT merge_id, person_to_id, person_from_id FROM app_merge m WHERE person_to_id > person_from_id LIMIT 0,1000";
$data = $db->select($sql);
foreach($data as $row) {
	$sql = "UPDATE app_merge SET person_to_id = '$row[person_from_id]', person_from_id = '$row[person_to_id]' WHERE merge_id = '$row[merge_id]'";
	echo $sql . "<br>";
	$db->sql_query($sql);
}
die();
*/
$sql = "SELECT m.merge_id, m.update_date, uc.user_id, merge_names(uc.given_name, uc.family_name) user_name, m.person_to_id, merge_names(p1.given_name, p1.family_name) person_name1, m.person_from_id, merge_names(p2.given_name, p2.family_name) person_name2, p1.birth_year birth_year1, p2.birth_year birth_year2
	FROM app_merge m
	LEFT JOIN tree_person p1 ON m.person_to_id = p1.person_id 
	AND p1.actual_start_date <= m.update_date - INTERVAL 1 MINUTE 
	AND p1.actual_end_date > m.update_date - INTERVAL 1 MINUTE
	LEFT JOIN tree_person p2 ON m.person_from_id = p2.person_id 
	AND p2.actual_start_date <= m.update_date - INTERVAL 1 MINUTE 
	AND p2.actual_end_date > m.update_date - INTERVAL 1 MINUTE
	LEFT JOIN app_user uc ON uc.user_id = m.updated_by
	WHERE m.status_code = 'M' $where
	ORDER BY m.update_date DESC LIMIT $start, $rows";
$data = $db->select($sql);
echo "
<h2>Merge Log</h2>

<a href='merge.php?action=list'>View Merge List</a> |
<a href='merge_history.php'>Remove Filters</a>
<table class='grid'>
<tr>
<th>Merge date</th>
<th>Person removed</th>
<th>Merged into</th>
<th>By [filter]</th>
<th>Actions</th>
</tr>";
foreach ($data as $row) {
	echo "<tr>
<td>$row[update_date]</td>
<td><a href='/person/$row[person_from_id]'>$row[person_name2] ($row[birth_year2])</a></td>
<td><a href='/person/$row[person_to_id]'>$row[person_name1] ($row[birth_year1])</a></td>
<td><a href='profile.php?user_id=$row[user_id]'>$row[user_name]</a> <a href='?by_user=$row[user_id]'>[filter]</a></td>
<td><a href='merge.php?undo=$row[merge_id]'>UNDO</a></td>
</tr>
";
}
echo "</table>" . count($data) . " row(s)";
if (count($data) >= $rows) echo " <a href='?page=$page&by_user=$by_user'>Next Page</a>";
$T->display('footer.tpl');
?>
