<?

require_once("config.php");
require_once("inc/main.php");

include_class("Locations");

$T->assign("help", "Browse_Places");

$usa = 2; // LocationID for United States

$action = $_REQUEST['action'];
$suggest = $_REQUEST['suggest'];

if ($suggest > "") {
	$suggest = fixTick($suggest);
	$sql = "SELECT l.location_id, l.display_name, l.location_type, r.meaning FROM (
		SELECT * FROM ref_location WHERE display_name like '$suggest%' 
		UNION
		SELECT * FROM ref_location WHERE location_name like '$suggest%' 
		UNION
		SELECT * FROM ref_location WHERE location_id IN (SELECT location_id FROM ref_location_spellings WHERE alt_spelling like '$suggest%')
		) l LEFT JOIN ref_codes r ON r.ref_code = l.location_type AND r.ref_type = 'LocationTypes'
		ORDER BY r.seq DESC";
        $data = $db->select($sql);
	header('Content-Type: text/xml');
        echo "<?xml version=\"1.0\"?><Suggestions>";
	foreach($data as $row) {
		$type_meaning = ($row["location_type"] == "C") ? " (county)" : "";
		echo "<suggestion value=\"".$row["location_id"]."\">".$row["display_name"]."$type_meaning</suggestion>";
		echo "<suggestion>".$row["display_name"]."$type_meaning</suggestion>";
	}
	echo "</Suggestions>";
        exit();
}

if ($action == 'save') {
	require_login();
	$location = $_REQUEST['location'];
	if (empty($location['display_name'])) $location['display_name'] = $location['location_name'];
	if ($location['location_type'] == 'N') {
		// This is a country which cannot have a parent and the name should always be the same
		$location['parent_id'] == null;
	} else {
		if ($location['parent_id'] == -1) unset($location['parent_id']);
	}
	$loc = new Locations();
	$loc->save($location);
	if ($loc->id > 0) {
		$loc->getSpellings();
		if ($_REQUEST['new_spelling'] > '') {
			$loc->saveSpelling($_REQUEST['new_spelling']);
		}
		redirect("locations.php?location_id=$loc->id");
	}
	$action = 'add';
}

if ($action == 'delete_spelling') {
	require_login();
	$loc = new Locations($_REQUEST['location_id']);
	$loc->deleteSpelling($_REQUEST['spelling']);
	redirect("locations.php?location_id=$loc->id");
}

if ($action == 'delete') {
	require_login();
	$loc = new Locations($_REQUEST['location_id']);
	if ($loc->delete()) {
		redirect("locations.php");
	}
}

if ($action == 'add') {
	require_login();
	include_class("RefCodes");
	$location = $_REQUEST['location'];
	$T->assign("types", Locations::getTypes());
//	$T->assign("types", RefCodes::getList("LocationTypes"));
	//$T->assign("existing", Locations::findLocationByName($location['location_name']));
	$parent_id = $_REQUEST['parent_id'];
	if ($parent_id > 0) {
		$ploc_ob = new Locations($parent_id);
		switch ($ploc_ob->data['location_type']) {
			case "N":
				$location['location_type'] = "S";
				break;
			case "S":
				$location['location_type'] = "C";
				break;
			case "P":
			case "C":
				$location['location_type'] = "T";
				break;
		}
		$T->assign("parent", $ploc_ob->data);
		$location['display_name'] = $location['location_name'].", ".$ploc_ob->data['display_name'];
	}
	$loc_type = (empty($parent_id) ? $loc_type = 'N' : $loc_type = null);
	$ploc = Locations::findLocationByParent($parent_id, $loc_type);
	if (is_array($ploc)) {
		$ploc2 = array();
		$ploc2[$value[0]] = "";
		foreach ($ploc as $value) {
			$ploc2[$value['location_id']] = $value['location_name'];
		}
		$T->assign("parent_locations", $ploc2);
	}
	$T->assign("location", $location);
	$T->display("location_add.tpl");
	exit();
}

if ($_REQUEST['location_id'] > 0) {
	$loc = new Locations($_REQUEST['location_id']);
	//print_pre($loc->data);
	$loc->getSpellings();
	$loc->getChildren();
	$T->assign("location", $loc->data);
	$T->assign("spellings", $loc->spellings);
	$T->assign("children", $loc->children);
	$T->assign("similar", $loc->getSimilar());
	
	if ($loc->data["location_type"] == "C" || $loc->data["location_type"] == "T") {
		# this location is a town/city or county
		# list the events in this town or county
		$where_locs = $loc->id;
		foreach($loc->children as $temp) {
			$where_locs .= "," . $temp["location_id"];
		}

		$sql = "SELECT e.ad, YEAR(e.event_date) event_year, count(*) total FROM tree_event e
				WHERE ".actualClause()." AND YEAR(e.event_date) <= 1930 AND e.event_date is not null and e.location_id IN ($where_locs)
				GROUP by e.ad, YEAR(e.event_date)";
		//echo $sql;
		$data = $db->select($sql);

		$T->assign("event_years", $data);
		if (!empty($_REQUEST["year"])) {
			if ($_REQUEST["year"] > 1930) die("can't view public events after 1930");
			$year_sql = "AND YEAR(e.event_date) = ".(int)$_REQUEST["year"];
			$sql = "SELECT e.table_type, e.key_id, e.event_type, e.ad, e.event_date, e.location, p.family_name, p.given_name, p1.family_name hfamily_name, p1.given_name hgiven_name, p2.family_name wfamily_name, p2.given_name wgiven_name
					FROM tree_event e
					LEFT JOIN tree_person p ON p.person_id = e.key_id AND e.table_type = 'P' AND ".actualClause("p")."
					LEFT JOIN tree_family f ON f.family_id = e.key_id AND e.table_type = 'F' AND ".actualClause("f")."
					LEFT JOIN tree_person p1 ON p1.person_id = f.person1_id AND ".actualClause("p1")."
					LEFT JOIN tree_person p2 ON p2.person_id = f.person2_id AND ".actualClause("p2")."
					WHERE ".actualClause("e")." AND YEAR(e.event_date) = ".(int)$_REQUEST["year"]." and e.location_id IN ($where_locs)";
			//echo $sql;
			$data = $db->select($sql);
			//print_pre($data);
			$T->assign("events", $data);
		}
	}
	$parent_id = (empty($_REQUEST['parent_id']) ? $loc->data['parent_id'] : $_REQUEST['parent_id']);
	$loc_type = (empty($parent_id) ? $loc_type = 'N' : $loc_type = null);
	$ploc = Locations::findLocationByParent($parent_id, $loc_type);
	if (is_array($ploc)) {
		$ploc2 = array();
		$ploc2[-1] = "";
		$ploc2[0] = "Earth";
		foreach ($ploc as $value) {
			$ploc2[$value['location_id']] = $value['location_name'];
		}
		$T->assign("parent_locations", $ploc2);
	}
	
	include_class("RefCodes");
	$T->assign("types", Locations::getTypes());
	//$T->assign("types", RefCodes::getList("LocationTypes"));
	$T->display("location_edit.tpl");
	exit();
}

if ($action == 'match') {
	$T->assign("title", "Location Matching");
	$T->display("header.tpl");
	$place = isset($_REQUEST["place"]) ? trim($_REQUEST["place"]) : "";
	?>
<h2>Location Matching</h2>
<table class="portal">
<tr><td>
<a href="locations.php">&lt;&lt; Return to Country Index</a><br>
	Use this screen to cleanup location names and match them to a hierarchy.<br>
	<form method="GET">
		<input type="hidden" name="action" value="match">
		<input type="textbox" name="place" value="<? echo $place; ?>">
		<input type="submit" value="Search">
	</form>
	<table class="grid">
	<tr><th>Suggested Mapping</th>
		<th>Original Location Text</th>
		<th>Events</th>
	</tr>
	<?
	if ($place > "") {
		$where = "AND location LIKE '%".fixTick($place)."%' ";
		$limit = 50;
	} else {
		$where = "";
		$limit = 25;
	}
	$sql = "SELECT * FROM (
				SELECT count(*) as total, location FROM tree_event 
				WHERE location_id is null and location > '' $where
				GROUP BY location 
				ORDER BY total DESC, location
				LIMIT 0, $limit
			) l";
	$data = $db->select($sql);
	foreach($data as $row) {
		$places = split(",", $row['location']);
		$places = array_reverse($places);
		$i = 0;
		$valid = array();
		$mapped = 0;
		echo "\n<tr><td>";
		foreach ($places as $value) {
			$value = trim($value);
			$value = str_replace("<", "", $value);
			$value = str_replace(">", "", $value);
			if ($value > '' && $value <> 'of') {
				$i++;
				$prev = $i - 1;
				if ($i == 1 || $valid[$prev] > 0) {
					$uval = fixTick($value);
					if ($i == 1) {
						$where = "(l.location_type = 'N' OR l.parent_id = $usa)";
					} else {
						$where = "l.parent_id = ".$valid[$prev];
					}
					$sql = "SELECT *
							FROM ref_location_spellings s
							JOIN ref_location l USING (location_id)
							WHERE upper(s.alt_spelling) = '$uval' AND $where";
					$data = $db->select($sql);
					if (count($data) == 1) {
						$valid[$i] = $data[0]['location_id'];
						$mapped = $valid[$i];
					} else {
						# we didn't find it, but maybe we should go down one more level
						$sql = "SELECT l.*
								FROM ref_location_spellings s
								JOIN ref_location l USING (location_id)
								JOIN ref_location l2 ON l.parent_id = l2.location_id
								WHERE upper(s.alt_spelling) = '$uval' AND $where";
						$data = $db->select($sql);
						if (count($data) == 1) {
							print_pre($data[0]);
							//$valid[$i] = $data[0]['location_id'];
							//$mapped = $valid[$i];
						}
					}
					//print_pre($data);
					//print_pre($valid);
				}
				$value2[] = $value;
				if ($valid[$i]) {
					echo "<a href='locations.php?location_id=$valid[$i]'><font color='000000'>".$data[0]['location_name']."</font></a> ";
				} else {
					$mapped = 0;
					if ($valid[$prev]) $parent_id = $valid[$prev];
					else $parent_id = 2; // Default to the USA
					echo "<a href='locations.php?action=add&location[location_name]=$value&parent_id=$parent_id'><font color='8F8FFF'>$value</font></a> ";
				}
			}
		}
		echo "</td><td>";
		if ($mapped) {
			echo "<b>Mapped to LocationID = $mapped</b> <br>";
			$uloc = fixTick(strtoupper($row[location]));
			$sql = "UPDATE tree_event SET location_id = $mapped WHERE upper(location) = '$uloc'";
			$db->sql_query($sql);
		}
		echo "$row[location]</td><td>$row[total]</td></tr>";
	}
	echo "</table></td></tr></table>";
	$T->display("footer.tpl");
	exit();
}
$nations = Locations::findLocationByParent(0, "N");
$T->assign("nations", $nations);
$T->display("location_list.tpl");

?>
