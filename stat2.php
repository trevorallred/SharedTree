<?
if (isset($_GET["xml"])) {
require_once("config.php");
require_once("inc/main.php");

switch ($_GET["chart"]) {
	case "users_added_by_weekday":
		$caption = "User Signups/Weekday";
		$sql = "SELECT DATE_FORMAT(creation_date,'%W') label, DATE_FORMAT(creation_date,'%w') sortby, count(*) value FROM app_user GROUP BY label ORDER BY sortby";
		break;
	case "common_families":
	default:
		$caption = "Common Families";
		$sql = "SELECT * FROM (
SELECT family_name label, count(*) value FROM tree_person 
WHERE family_name like '__%' AND actual_end_date > Now() 
GROUP BY family_name HAVING count(*) > 50 ORDER BY value DESC LIMIT 0,25
) t ORDER BY label";
		break;
}

# Print the graph XML now
echo "<graph caption='$caption'>\n";
$data = $db->select($sql);
foreach ($data as $row) {
    echo "<set name='".$row["label"]."' value='".$row["value"]."' color='F23456'/>\n"; 
}
echo "</graph>";
die();

}
$url = urlencode("stat2.php?xml=1&chart=".$_GET["chart"]);
//$swf = "FC_2_3_Column3D";
$swf = "FC2Column";

?>
<HTML>
<HEAD>
<TITLE>SharedTree Stats</TITLE>
</HEAD>
<BODY bgcolor="#FFFFFF">
<CENTER>
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" WIDTH="565" HEIGHT="420" id="FC2Column" ALIGN="">
<PARAM NAME=movie VALUE="inc/charts/<? echo $swf ?>.swf?dataUrl=<? echo $url ?>">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=bgcolor VALUE=#FFFFFF>
<EMBED src="inc/charts/<? echo $swf ?>.swf?dataUrl=<? echo $url ?>" quality=high bgcolor=#FFFFFF WIDTH="565" HEIGHT="420" NAME="FC2Column" ALIGN="" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>
<br>
<a href="?chart=common_families">Common Families</a>
<a href="?chart=users_added_by_weekday">User Signups/Weekday</a>

</CENTER>
</BODY>
</HTML>
