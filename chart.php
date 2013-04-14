<?
if ($_REQUEST["instructions"]) {
	echo "Create a chart by setting the options on the left";
	die();
}

require_once("config.php");

# Print marriage-based charts
if ($_REQUEST["chart"] && $_REQUEST['family_id']) {
	# Display the family chart
	require_once("inc/main.php");
	switch ($_REQUEST["chart"]) {
		case "familygroup":
			include_class("Family");
			$fam = new Family($_REQUEST['family_id']);
			$fam->getChildren();
			include_class("PDFChartFamilyGroup");
			$chart = new PDFChartFamilyGroup();
			$chart->drawPage($fam->data, $fam->children);
			break;
		default:
			echo "no chart defined ".$_REQUEST["chart"];
			break;
	}
	die();
}

$person_id = (int)$_REQUEST['person_id'];
if (empty($person_id)) {
	if (empty($person_id)) die("You must supply a person_id to view charts");
}

# Print Individual-based charts
if ($_REQUEST["chart"]) {
	# Display the actual chart
	require_once("inc/main.php");
	$gen = (int)$_REQUEST["gen"];
	switch ($_REQUEST["chart"]) {
		case "desc_circle":
			include_class("FamilyTreeDB");
			$data = FamilyTreeDB::getDescendants($person_id, $gen);
			include_class("PDFChartCircle");
			$chart = new PDFChartCircle();
			$chart->drawPage($data, $gen);
			break;
		case "desc_photo":
			include_class("FamilyTreeDB");
			$data = FamilyTreeDB::getDescendants($person_id, 3);
			include_class("PDFChartDescPhoto");
			$chart = new PDFChartDescPhoto();
			$chart->drawPage($data, $gen);
			break;
		case "desc_table":
			redirect("descendants.php?person_id=$person_id");
			break;
		case "pedigree":
			include_class("FamilyTreeDB");
			$data = FamilyTreeDB::getAncestors($person_id, $gen);
			include_class("PDFChartPedigree");
			$chart = new PDFChartPedigree();
			$chart->drawPage($data, $gen);
			break;
		default:
			echo "no chart defined ".$_REQUEST["chart"];
			break;
	}
	die();
}

if ($_REQUEST["sidebar"]) {
	# Display the left navigation bar
	require_once("inc/main.php");
	include_class("Person");
	$person = new Person($person_id);
	?>
<html>
<head>
<link href="styles.css" type="text/css" rel="stylesheet" />
</head>
<body class="notop">
<p align="center"><img src="img/stree_logo_small.png" width="150" height="133" title="SharedTree" /></p>

<a href="/family/<? echo $person_id ?>" target="_parent">&lt;&lt; Return</a><br>

<h3>Charts for <? echo $person->data["given_name"]." ".$person->data["family_name"]; ?></h3>

<table class="table1">
<tr><td class="content">
<form id="frmChart" target="chart">
<input type="hidden" name="person_id" value="<? echo $person_id ?>" />
	Chart Type: <select name="chart">
	<option value="pedigree">Pedigree</option>
	<option selected="selected" value="desc_circle">Descendent Circle</option>
	<option value="desc_table">Descendent Table</option>
	<option value="desc_photo">Descendent Photos</option>
	</select>
	Generations:
	<select name="gen">
		<option>2</option>
		<option>3</option>
		<option selected="selected">4</option>
		<option>5</option>
		<option>6</option>
		<option>7</option>
		<option>8</option>
		<option>9</option>
	</select>
	<input type="submit" value="View Chart">
</form>
<br />
<br />
<br />
<br />
<img src="http://www.familycharting.com/img/logo_flat_small.png" width="170" height="41" title="FamilyCharting" />
<br />
<a href="http://www.familycharting.com/" target="_top">Order a Printed Version of this Chart</a>
</td></tr>
</table>
</body>
</html>
	<?
	die();
}
?>
<HTML>
<HEAD>
<TITLE>SharedTree Charts</TITLE>
</HEAD>
<FRAMESET COLS="200, *">
   <FRAME name="sidebar" SRC="chart.php?sidebar=1&person_id=<? echo $person_id ?>">
   <FRAME name="chart" SRC="chart.php?instructions=1">
   <NOFRAMES>Get a real browser</NOFRAMES>
</FRAMESET>

</HTML>

