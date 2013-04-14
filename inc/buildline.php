<?
set_time_limit(1000);
global $db;

if (!is_array($relations)) {
	$data = $db->select("SELECT trace, distance, description, reverse, permission FROM ref_relation");
	unset($relations);
	foreach($data as $value) {
		$relations[$value["trace"]] = $value;
	}
}

include_class("User");
function buildFamilyTreeIndex($user_id, $print=false) {
	global $relations, $db;
	# Get the user's person_id and add them to the stack of todos
	$u = new User($user_id);
	$person_id = $u->data["person_id"];

	$todoPerson = array();
	$todoPerson[$person_id]["trace"] = "";
	$todoPerson[$person_id]["thru_id"] = $person_id;

	$listPerson = array();
	$todoFamily = array();
	$listFamily = array();

	# Do "circles" around the primary person 12 times
	# Only include people who have the right permission based on their relationship
	$i = 0;
	for($i; $i <= 12; $i++) {
		foreach ($todoPerson as $key=>$value) {
			if ($value["trace"] == "" || !empty($relations[$value["trace"]])) {
				# this is myself or a valid relative
				if ($key > 0 && !isset($listPerson[$key])) {
					# We have a person todo and they haven't been done yet

					# Get the person's spouse(s)
					// unions are faster than ORs in MySQL
					$sql = "SELECT family_id, person1_id as spouse_id FROM tree_family
							WHERE person2_id = $key AND ".actualClause()."
							UNION
							SELECT family_id, person2_id as spouse_id FROM tree_family
							WHERE person1_id = $key AND ".actualClause();
					$data = $db->select($sql);
					//echo "<br> spouses of $key";
					//print_pre($data);
					foreach ($data as $row) {
						$todoFamily[$row["family_id"]]["trace"] = $value["trace"];
						if ($row["spouse_id"] > 0 && !isset($todoPerson[$row["spouse_id"]]) && !isset($listPerson[$row["spouse_id"]])) {
							$todoPerson[$row["spouse_id"]]["trace"] = $value["trace"] . "S"; //husband
							$todoPerson[$row["spouse_id"]]["thru_id"] = $key;
						}
					}

					# Get the person's parents
					$sql = "SELECT f.family_id, f.person1_id, f.person2_id, s.given_name, s.family_name
							FROM tree_person s
							LEFT JOIN tree_family f ON s.bio_family_id = f.family_id AND " . actualClause('f')."
							WHERE s.person_id = $key AND " . actualClause('s');
					$data = $db->select($sql);
					//echo "<br> parents of $key";
					//print_pre($data);

					$row = $data[0];
					$todoPerson[$key]["name"] = trim($row["given_name"] . " " . $row["family_name"]);
					if ($row["family_id"] > 0) {
						if ($row["person1_id"] > 0 && !isset($todoPerson[$row["person1_id"]]) && !isset($listPerson[$row["person1_id"]])) {
							$todoPerson[$row["person1_id"]]["trace"] = $value["trace"] . "P"; //father
							$todoPerson[$row["person1_id"]]["thru_id"] = $key;
						}
						if ($row["person2_id"] > 0 && !isset($todoPerson[$row["person2_id"]]) && !isset($listPerson[$row["person2_id"]])) {
							$todoPerson[$row["person2_id"]]["trace"] = $value["trace"] . "P"; //mother
							$todoPerson[$row["person2_id"]]["thru_id"] = $key;
						}
						$todoFamily[$row["family_id"]]["trace"] = $value["trace"] . "P"; //parents
					}
					//print_pre($todoPerson);

					$listPerson[$key] = $todoPerson[$key];
				}
			}
			unset($todoPerson[$key]);
			if ($print) {
				$added = count($listPerson);
				$remain = count($todoPerson);
				printStatus($i, $added, $remain);
			}
		}

		//print_pre($todoFamily);
		foreach ($todoFamily as $key=>$value) {
			//echo "FAM: $key<br>";
			if ($key > 0 && !isset($listFamily[$key]) ) {
				// Select the children for this family
				$sql = "SELECT person_id, given_name, family_name FROM tree_person
						WHERE bio_family_id = $key AND " . actualClause();
				$data = $db->select($sql);
				foreach ($data as $row) {
					if (!isset($todoPerson[$row["person_id"]]) ) {
						if ($row["person_id"] > 0 && !isset($todoPerson[$row["person_id"]]) && !isset($listPerson[$row["person_id"]])) {
							$todoPerson[$row["person_id"]]["trace"] = $value["trace"] . "C"; //child
							$todoPerson[$row["person_id"]]["name"] = trim($row["given_name"] . " " . $row["family_name"]);
							$todoPerson[$row["person_id"]]["thru_id"] = $key;
						}
					}
				}

				$listFamily[$key] = $todoFamily[$key];
			}
			unset($todoFamily[$key]);
			if ($print) {
				$added = count($listPerson);
				$remain = count($todoPerson);
				printStatus($i, $added, $remain);
			}
		}

		if (count($todoFamily) == 0 && count($todoPerson) == 0) {
			# we're done so we can just exit the loop now
			$i = 99;
		}
	}

	if ($print) {
		echo '<script type="text/javascript">update_progress(12, '.count($listPerson).', 0);</script>';
		echo "<b>Saving Results</b><br />";
		flush();
		ob_flush();
	}

	$sql = "DELETE FROM app_user_line_person WHERE user_id = '$u->id'";
	$db->sql_query($sql);

	//print_pre($listPerson);

	foreach($listPerson as $key=>$value) {
		$thru = empty($value["thru_id"]) ? $key : $value["thru_id"];
		$trace = empty($value["trace"]) ? "X" : $value["trace"];
		$distance = "";
		for($c = 0; $c < strlen($value["trace"]); $c++) {
			switch (trim($value["trace"][$c])) {
				case "S":
					$distance .= "1";
					break;
				case "C":
					$distance .= "2";
					break;
				case "P":
					$distance .= "3";
					break;
				default:
					$distance .= "9";
			}
		}
		$distance = (int) $distance; //force it to be an integer!
		$values[] = "($u->id, $key, $thru, '$trace', $distance)";
	}
	if (empty($values)) return false;
	$sql = implode(", ", $values);
	$sql = "INSERT INTO app_user_line_person (user_id, person_id, thru_id, trace, distance) VALUES $sql";
	$db->sql_query($sql);

	$sql = "UPDATE app_user SET line_update_date = NOW() WHERE user_id = '$u->id'";
	$db->sql_query($sql);
	$sql = "DELETE FROM app_user_line_person WHERE trace NOT IN (SELECT trace FROM ref_relation)";
	$db->sql_query($sql);
	return true;
}

function printStatus($loop, $added, $remain) {
	if (($remain + $added) == 0) $progress = 0;
	else $progress = round($added/($remain + $added), 2) * 100;
	$width = 275 * $progress / 100;

	# This div will show loading percents
	if ($added%50==0) {
		echo '<script type="text/javascript">update_progress('.$loop.', '.$added.', '.$remain.');</script>';
		flush();
		ob_flush();
	}
}
?>
