<?
require_once("config.php");
require_once("inc/main.php");
//ini_set('display_errors','1');

include_class("Person");

## General settings
$TIME_LIMIT = 60*5; //5 minutes
$individuals = array();
$fcount = 0;
$families = array();

if (!isset($_REQUEST["temple"])) {

	$person_id = $_REQUEST["person_id"];
	if (empty($person_id)) errorMessage("Person ID is required");
	$T->assign('person_id', $person_id);
	$filename = "sharedtree_p".$person_id.".ged";

	if (!isset($_REQUEST["download"])) {
		# Show the GEDCOM export form
		$person = new Person($person_id);
		$T->assign('person', $person->data);
		$title = $person->data['full_name'];
		if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
		if ($person->data['birth_year'] < 0) $title .= " (".-1*$person->data['birth_year']." B.C.)";
		$T->assign("primary_person", $title);

		$T->assign('help', "Exporting_Data");
		$T->display('export.tpl');
		exit;
	}
} else {
	$filename = "sharedtree_templeready.ged";
}

################################
# Export the GEDCOM File

set_time_limit ($TIME_LIMIT);

################################
# GET DATA                     #
################################

if ($_REQUEST["temple"] == 1) {
	if (empty($user->id)) errorMessage("UserID is missing, when exporting temple work");

	$sql = "SELECT p.person_id, p.given_name, p.family_name
		FROM tree_person p
		JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id = $user->id 
		JOIN ref_relation r ON r.trace = l.trace
		WHERE p.temple_status = 0 AND p.public_flag = 1 AND ".actualClause("p")." ORDER BY r.distance LIMIT 0,100";

	//echo $sql;
	$data = $db->select( $sql );
	foreach ($data as $row) addIndividual($row["person_id"]);
}


# Add the person's parents' family to the stack to process
$famlist[] = addIndividual($person_id);

include_class("Family");
if ($_REQUEST["gen_up"] > 0) {
	$x = 1;
	while ($x <= $_REQUEST["gen_up"]) {
		# Each generation, we process each ancestor family
		# x = 1 process my parents' family
		# x = 2 process both grand parents' families
		# x = 3 process all four of my great grand parents' families
		$newlist = array();
		foreach ($famlist as $family_id) {
			$famobj = new Family($family_id);
			if ($famobj->id > 0) {
				$temp = $families[$famobj->id]["children"];
				$families[$famobj->id] = $famobj->data;
				$families[$famobj->id]["children"] = $temp;
				$fcount++;
				$families[$famobj->id]["fcount"] = $fcount;
				if ($_REQUEST["siblings"] == 1) {
					# If we need the siblings then get the children and add them to the $indiv array
					$kids = $famobj->getChildren($family_id);
					foreach ($kids as $kid) {
						addIndividual($kid["person_id"]);
						$families[$famobj->id]["children"][$kid["person_id"]] = 1;
					}
				}
				# Get the parents person data and add their parents' families to the new array
				$newlist[] = addIndividual($famobj->data["person1_id"]);
				$newlist[] = addIndividual($famobj->data["person2_id"]);
			}
		}
		$famlist = $newlist;
		unset($newlist);
		$x++;
	}
}

# Add the person to the stack of people to find spouses and children for
$kidlist[] = $person_id;

if ($_REQUEST["gen_down"] > 0) {
	$x = 1;
	while ($x <= $_REQUEST["gen_down"]) {
		# Each generation, we process each descendent family
		# x = 1 process my family
		# x = 2 process my children's families
		# x = 3 process my grandchildren's families
		$newlist = array();
		foreach ($kidlist as $kid_id) {
			$persobj = new Person($kid_id);
			if ($persobj->id > 0) {
				# For each person we process, find their spouses and children
				$marrs = $persobj->getMarriages(true);
				if (is_array($marrs)) {
					foreach ($marrs as $spouse) {
						# Add each marriage/spouse
						addIndividual($spouse["person_id"]);
						$famobj = new Family($spouse["family_id"]);
						if ($famobj->id > 0) {
							# preserve the ["children"]
							$temp = $families[$famobj->id]["children"];
							$families[$famobj->id] = $famobj->data;
							$families[$famobj->id]["children"] = $temp;
							$fcount++;
							$families[$famobj->id]["fcount"] = $fcount;
						}

						# Add the children
						foreach ($spouse["children"] as $kid) {
							addIndividual($kid["person_id"]);
							$newlist[] = $kid["person_id"];
						}
					}
				}
			}
		}
		$kidlist = $newlist;
		unset($newlist);
		$x++;
	}
}

# Add a single person to the list of individuals to be exported
function addIndividual($person_id) {
	global $individuals, $icount, $families;
	if (empty($individuals[$person_id]["icount"])) {
		# This person hasn't been added to the individuals array yet
		$pobj = new Person($person_id);
		if ($pobj->id > 0) {
			$individuals[$pobj->id] = $pobj->data;
			$icount++;
			$individuals[$pobj->id]["icount"] = $icount;
			$individuals[$pobj->id]["spouses"] = $pobj->getMarriages();
			$families[$pobj->data["bio_family_id"]]["children"][$person_id] = 1;
		}
	}
	return $pobj->data["bio_family_id"];
}

//print_pre($individuals);
//print_pre($families);
//exit();

################################
# FORMAT EXPORT                #
################################
header('Content-type: application/gedcom');
//header('Content-type: text/plain');
header('Content-Disposition: attachment; filename="'.$filename.'"');

echo "0 HEAD\n1 SOUR SHAREDTREE\n2 NAME SharedTree\n2 VERS V0.1\n2 CORP SharedTree.com\n3 ADDR Irvine, CA 92604\n";
printLine ("DEST", $_REQUEST["destination"]);
echo "1 DATE ".date("j M Y")."\n1 FILE ".$filename."\n1 GEDC\n2 VERS 5.5\n1 CHAR UTF-8\n1 LANG English\n";
// 2 TIME 23:14:46

################################
# Print the Individuals
foreach($individuals as $key=>$rec) {
	echo "0 @I".$rec["icount"]."@ INDI\n";
	printLine ("NAME", $rec["given_name"]." /".$rec["family_name"]."/");
	printLine ("SURN", $rec["family_name"], 2);
	printLine ("GIVN", $rec["given_name"], 2);
	printLine ("NICK", $rec["nickname"], 2);
	printLine ("NSFX", $rec["suffix"], 2);
	printLine ("NPFX", $rec["prefix"], 2);
	if ($rec["bio_family_id"] > 0) {
		printLine ("FAMC", "@F".$families[$rec["bio_family_id"]]["fcount"]."@");
	}
	foreach($rec["spouses"] as $spouse) {
		printLine ("FAMS", "@F".$families[$spouse["family_id"]]["fcount"]."@");
	}
	printLine ("SEX", $rec["gender"]);
	printLine ("TITL", $rec["title"]);
	printLine ("AFN", $rec["afn"]);
	//printLine ("RIN", $key);
	printLine ("IDNO", $rec["national_id"]);
	printLine ("NATI", $rec["national_origin"]);
	printLine ("OCCU", $rec["occupation"]);
	//printLine ("EDUC", $rec["education"]); not supported yet
	printEvents($rec);
	printLine ("CHAN", "NULL");
	printLine ("DATE", strtoupper(date("j M Y", strtotime($rec["update_date"]))), 2);
}

################################
# Print the Families
foreach($families as $key=>$rec) {
	if ($rec["fcount"] > 0) {
		echo "0 @F".$rec["fcount"]."@ FAM\n";
		printLine ("HUSB", "@I".$individuals[$rec["person1_id"]]["icount"]."@");
		printLine ("WIFE", "@I".$individuals[$rec["person2_id"]]["icount"]."@");
		foreach($rec["children"] as $kid=>$temp) {
			printLine ("CHIL", "@I".$individuals[$kid]["icount"]."@");
		}
		//printLine ("STAT", $rec["status_code"]);
		printLine ("RIN", $key);
		printEvents($rec);
		printLine ("CHAN", "NULL");
		printLine ("DATE", strtoupper(date("j M Y", strtotime($rec["update_date"]))), 2);
	}
}

echo "0 TRLR\n";

##################################################################

function printEvents ($rec) {
	global $families;
	if (!is_array($rec["e"])) return;
	foreach ($rec["e"] as $key=>$value) {
		printLine ($key, "NULL");
		if ($key == "EVEN") printLine ("TYPE", "Event", 2); // TODO add event type to event table
		$event_date = $value["event_date"];
		if ($value["event_date"] > '') {
			$event_date = $value["date_approx"]." ".$event_date;
			if ($value["ad"]=="0") $event_date .= " B.C.";
			printLine ("DATE", $event_date, 2);
		}
		if ($key == "SLGC") printLine ("FAMC", "@F".$families[$rec["bio_family_id"]]["fcount"]."@", 2);
		printLine ("AGE", $value["age_at_event"], 2);
		printLine ("PLAC", $value["location"], 2);
		printLine ("TEMP", $value["temple_code"], 2);
		printLine ("STAT", $value["status"], 2);
		printLine ("NOTE", $value["notes"], 2);
	}
}

function printLine ($tag, $value, $level=1) {
	$value = trim($value);
	if ((strlen($value) > 0 || $value == "NULL") && $value <> "@F@" && $value <> "@I@") {
		echo "$level $tag";
		if ($value <> "NULL") echo " $value";
		echo "\n";
	}
}
?>
