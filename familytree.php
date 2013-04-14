<?

require_once("config.php");
require_once("inc/main.php");

if ($_REQUEST["show"]=="relationships") {
	$T->display('header.tpl');
	echo "<h2>Possible Family Relationships</h2> See <a href='/w/Family_Tree_Index'>Family Tree Index</a> for more information. <table class='grid'>";
	echo "<tr><th>Trace</th><th>Description</th><th>Security</th></tr>";
	$sql = "SELECT * FROM ref_relation ORDER BY distance, trace";
	# * = trace, distance, description, reverse, permission
	$data = $db->select($sql);
	//print_pre($data);
	foreach($data as $row) {
		echo "<tr><td>$row[trace]</td><td>$row[description]</td><td>$row[permission]</td></tr>";
	}
	echo "</table>";
	$T->display('footer.tpl');
	exit();
}

include_class("Person");
$relatives = Person::relatives($user->data["person_id"], $_REQUEST["trace"]);


$T->assign("help", "Family Tree Index");
$T->assign("trace", $_REQUEST["trace"]);
$T->assign("count", count($relatives));
$T->assign("relatives", $relatives);

if (!empty($_REQUEST["ajax"])) {
	$T->display('familytree_partial.tpl');
	die();
}
$T->display('familytree.tpl');
?>
