<?
include_once("config.php");
include_once("inc/main.php");

$T->display('header.tpl');

$sql = "SELECT trace, distance, description, reverse, permission FROM ref_relation ORDER BY permission DESC, distance, trace";
$data = $db->select($sql);

echo "<table class='grid'>
<tr>
<th>Trace</th>
<th>Description</th>
<th>Reversed description</th>
<th>Permission</th>
<th>Distance</th>
</tr>";
foreach($data as $row) {
	echo "<tr><td>$row[trace]</td><td>$row[description]</td><td>$row[reverse]</td><td>$row[permission]</td><td>$row[distance]</td></tr>";
}
echo "</table>";

$T->display('footer.tpl');
?>
