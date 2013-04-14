<?php

require_once("config.php");
if (isset($_REQUEST['size'])) $track_hit = false;
require_once("inc/main.php");

$action = $_REQUEST["action"];
$image_id = (int)$_REQUEST["image_id"];
$data = $_REQUEST["data"];
if ($data["person_id"] > 0) {
	$T->assign("person_id", $data["person_id"]);
}


include_class("Image");

$T->assign("help", "Photos");

if ($action == "save") {
	require_login();
	$image_id = Image::save($data);
	if ($image_id) redirect("image.php?image_id=$image_id&action=summary");
	//else die("wrong image type");
	$T->assign("error", "Wrong image type or no image at all");
	$action = "edit";
}

if ($action == "save_thumb") {
	require_login();
	$id = $_REQUEST["image_id"];
	Image::saveThumb($id, $_REQUEST["X"], $_REQUEST["Y"], $_REQUEST["W"]);
	redirect("image.php?image_id=".$id."&action=summary");
}

if ($action == "list") {
	require_login();

	$where = "";
	$start = ($_REQUEST["start"] > 0) ? (int)$_REQUEST["start"] : 0;
	$startyear = ($_REQUEST["startyear"] > 0) ? (int)$_REQUEST["startyear"] : 0;
	$endyear = ($_REQUEST["endyear"] > 0) ? (int)$_REQUEST["endyear"] : 0;
	if ($startyear <> 0) $where .= " AND p.birth_year >= $startyear";
	if ($endyear <> 0) $where .= " AND p.birth_year <= $endyear";
	switch($_REQUEST["type"]) {
		case "Only Sources":
			$where .= " AND i.event_id > 0";
			break;
		case "Exclude Sources":
			$where .= " AND i.event_id IS NULL";
			break;
	}
	$perpage = 25;
	$where .= "";
	$sql = "SELECT i.image_id, i.image_order, i.age_taken, i.image_name, i.description, i.event_id, p.person_id, p.given_name, p.family_name, p.created_by, p.public_flag, p.birth_year, l.trace, gc.prompt, i.update_date, i.updated_by, u.given_name as ugiven_name, u.family_name as ufamily_name, r.description trace_meaning
			FROM tree_image i
			JOIN tree_person p ON i.person_id = p.person_id AND ".actualClause("p")."
			JOIN app_user_line_person l ON l.person_id = p.person_id AND l.user_id = '$user->id'
			LEFT JOIN ref_relation r ON l.trace = r.trace
			LEFT JOIN tree_event e ON i.event_id = e.event_id AND ".actualClause("e")."
			LEFT JOIN ref_gedcom_codes gc ON e.event_type = gc.gedcom_code AND gc.table_type = 'P'
			LEFT JOIN app_user u ON i.updated_by = u.user_id
			WHERE 1=1 $where ORDER BY p.birth_year ASC LIMIT $start, $perpage";
	$data = $db->select( $sql );
	$T->assign("title", "Image List");
	$T->display('header.tpl');
	$i = 0;
?>
<h2>Family Photo Album</h2>
<form method="GET" action="/image.php">
	<input type="hidden" name="action" value="list">
<label>Filter</label>
<label>Born between</label>
	<input type="text" name="startyear" size="5" value="<? echo $_REQUEST["startyear"]; ?>" class="textfield">
	and
	<input type="text" name="endyear" size="5" value="<? echo $_REQUEST["endyear"]; ?>" class="textfield">
	<select name="type" class="textfield">
		<option>All photos</option>
		<option <? if ($_REQUEST["type"]=="Only Sources") echo "SELECTED"; ?>>Only Sources</option>
		<option <? if ($_REQUEST["type"]=="Exclude Sources") echo "SELECTED"; ?>>Exclude Sources</option>
	</select>
	<input type="submit" value="Filter">
</form>
<table border=1>
<?
	foreach($data as $row) {
		if ($i%5 == 0) echo "\n<tr align='center' valign='bottom'>";
		echo "\n<td> <a href='image.php?image_id=$row[image_id]&action=summary'><img src='image.php?image_id=$row[image_id]&size=thumb' border=0></a><br> <font size='1'><a href='image.php?image_id=$row[image_id]&action=summary'>$row[given_name] $row[family_name]</a><br> Born: $row[birth_year]<br>$row[trace_meaning]</font></td>";
		if ($i%5 == 4) echo "\n</tr>";
		$i++;
	}
	echo "\n</table>";
	$T->display('footer.tpl');
	exit();
}

if ($action == "edit") {
	require_login();
	if ($image_id > 0) $data = Image::info($image_id);
	# if image_id is 0 then this must be an ADD, so use what is passed as _REQUEST
	$T->assign("data", $data);
	$T->assign("image_id", $image_id);
	$T->display('image_edit.tpl');
	exit();
}

if ($action == "delete") {
	require_login();
	Image::delete($image_id);
	redirect("image.php?action=list");
}

if ($action == "summary") {
	if (empty($image_id)) errorMessage("image_id is required!!");
	$data = Image::info($image_id);
	//print_pre($data);
	$T->assign("data", $data);
	$T->assign("image_id", $image_id);
	$T->assign("person_id", $data["person_id"]);
	$T->assign("primary_person", $data["given_name"]." ".$data["family_name"]);
	$image_type = "";
	if ($data["event_id"] > 0) $image_type = $data["prompt"]." Source Document";
	else {
		if ($data["image_order"] == 1) $image_type = "Childhood Photo";
		if ($data["image_order"] == 2) $image_type = "Adulthood Photo";
		if ($data["image_order"] == 3) $image_type = "Later Years Photo";
	}
	$T->assign("image_type", $image_type);
	$T->assign("title", "$image_type for ".$data["given_name"]." ".$data["family_name"]);
	$T->display('image_summary.tpl');
	exit();
}

# image.php?image_id=1 then show the actual image
if (isset($_REQUEST["person_id"])) {
	Image::showByPerson($_REQUEST["person_id"], $_REQUEST["size"]);
} else {
	Image::show($image_id, $_REQUEST["size"]);
}

?>
