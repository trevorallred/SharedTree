<?
require_once("config.php");
require_once("inc/main.php");

$value = $_REQUEST["value"];

switch($_REQUEST["field"]) {
	case "prefix":
	case "suffix":
	case "title":
		$sql = "SELECT ".$_REQUEST["field"]." as value, count(*) total FROM tree_person 
			WHERE ".$_REQUEST["field"]." LIKE '%".fixTick($value)."%' AND ".actualClause()."
			GROUP BY ".$_REQUEST["field"]." ORDER BY total DESC LIMIT 0,10";
		break;
	case "location":
		$sql = "SELECT ".$_REQUEST["field"]." as value, count(*) total FROM tree_event 
			WHERE ".$_REQUEST["field"]." LIKE '%".fixTick($value)."%' AND ".actualClause()."
			GROUP BY ".$_REQUEST["field"]." ORDER BY total DESC LIMIT 0,10";
		break;
	default:
		die("not a valid field type");
}

$data = $db->select($sql);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\"?><Suggestions>";
foreach($data as $row) echo "<suggestion>".htmlspecialchars($row["value"])."</suggestion>";
echo "</Suggestions>";

//print_pre($data);

?>
