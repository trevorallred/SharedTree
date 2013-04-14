<?
# Used to calculate birth years for individuals
require_once("../config.php");
require_once("../inc/main.php");

require_login();
set_time_limit(10000);

include_class("Person");
include_class("Family");

$include_rank = 1; // Set this high to include records that are not as complete
# 0 - brand new, not validated at all
# 1 - no matches found
# 2 - changes discouraged
# 3 - record locked

echo "
<a href='../index.php'>Home</a>
<h3>Matching Script</h3>
Searching for individuals who are similar enough for matching
<br><br>
THIS IS COMPUTER LOGGING, NOT INTENDED FOR HUMANS
<br>
Merge rank &lt; $include_rank";

$limit = 100;

$import_id = (int)$_REQUEST["import_id"];
$import_sql = "";
if ($import_id > 0) {
	$import_sql = "AND p.person_id IN (SELECT person_id FROM tree_person_gedcom WHERE import_id = $import_id) ";
	$limit = 1000;
}

$sql = "SELECT p.person_id, pc.merge_rank, p.gender, p.family_name, p.given_name, p.birth_year, 
			DATE_FORMAT(eb.event_date, '%Y') b_year, DATE_FORMAT(eb.event_date, '%m') b_month, DATE_FORMAT(eb.event_date, '%d') b_day, eb.location b_location, 
			DATE_FORMAT(ed.event_date, '%Y') d_year, DATE_FORMAT(ed.event_date, '%m') d_month, DATE_FORMAT(ed.event_date, '%d') d_day, ed.location d_location, 
			p.bio_family_id, pf.given_name fgiven_name, pf.family_name ffamily_name, pf.birth_year fbirth, pm.given_name mgiven_name, pm.family_name mfamily_name, pm.birth_year mbirth
		FROM tree_person p
		JOIN tree_person_calc pc ON p.person_id = pc.person_id
		LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb", $time_sql)."
		LEFT JOIN tree_event ed ON p.person_id = ed.key_id AND ed.table_type = 'P' AND ed.event_type = 'DEAT' AND ".actualClause("ed", $time_sql)."
		LEFT JOIN tree_family f ON p.bio_family_id = f.family_id AND ".actualClause("f", $time_sql)."
		LEFT JOIN tree_person pf ON pf.person_id = f.person1_id AND ".actualClause("pf", $time_sql)."
		LEFT JOIN tree_person pm ON pm.person_id = f.person1_id AND ".actualClause("pm", $time_sql)."
		WHERE p.person_id > 0 AND p.merged_into IS NULL AND ".actualClause("p")."
		AND pc.merge_rank < $include_rank $import_sql
		ORDER BY p.person_id DESC LIMIT 0, $limit";
$data = $db->select($sql);

foreach ($data as $row) {
	$person_id = $row['person_id'];
	echo "<br>Processing: <a href='../person.php?person_id=$person_id'>$person_id</a> - ". $row['given_name'] . " " . $row['family_name'] . "(".$row['birth_year'].")";
	print_pre($row);
	
	## Figure out all the criteria we should use to eliminate potential matches
	$where = "";
	if ($row['family_name'] > '') {
		$where .= " AND p.family_name = '".fixTick($row['family_name'])."'";
	} else {
		$where .= " AND p.family_name IS NULL";
	}
	if ($row['gender'] > '') {
		$where .= " AND p.gender = '".$row['gender']."'";
	} else {
		$where .= " AND p.gender IS NULL";
	}
	if ($row['birth_year'] > 0) {
		$start = $row['birth_year'] - 15;
		$end = $row['birth_year'] + 15;
		$where .= " AND (p.birth_year BETWEEN $start AND $end OR p.birth_year IS NULL)";
	} else {
		$where .= " AND p.birth_year IS NULL";
	}
	if ($row['given_name'] > '') {
		$where .= " AND (p.given_name LIKE '%".fixTick($row['given_name'])."%')";
	} else {
		$where .= " AND p.given_name IS NULL";
	}
	############################################################
	# Now search for potential matches
	$sql = "SELECT p.person_id, p.gender, p.family_name, p.given_name, p.birth_year, 
				DATE_FORMAT(eb.event_date, '%Y') b_year, DATE_FORMAT(eb.event_date, '%m') b_month, DATE_FORMAT(eb.event_date, '%d') b_day, eb.location b_location, 
				DATE_FORMAT(ed.event_date, '%Y') d_year, DATE_FORMAT(ed.event_date, '%m') d_month, DATE_FORMAT(ed.event_date, '%d') d_day, ed.location d_location, 
				p.bio_family_id, pf.given_name fgiven_name, pf.family_name ffamily_name, pf.birth_year fbirth, pm.given_name mgiven_name, pm.family_name mfamily_name, pm.birth_year mbirth
			FROM tree_person p
			LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb", $time_sql)."
			LEFT JOIN tree_event ed ON p.person_id = ed.key_id AND ed.table_type = 'P' AND ed.event_type = 'DEAT' AND ".actualClause("ed", $time_sql)."
			LEFT JOIN tree_family f ON p.bio_family_id = f.family_id AND ".actualClause("f", $time_sql)."
			LEFT JOIN tree_person pf ON pf.person_id = f.person1_id AND ".actualClause("pf", $time_sql)."
			LEFT JOIN tree_person pm ON pm.person_id = f.person1_id AND ".actualClause("pm", $time_sql)."
			WHERE p.person_id < $person_id $where AND p.merged_into IS NULL AND ".actualClause("p")."
			AND p.person_id NOT IN (SELECT person_from_id FROM app_merge WHERE status_code <> 'P' AND person_to_id = $person_id)";
	
	//print_pre($sql);
	$matches = $db->select($sql);
	if (count($matches)) {
		## We may have a possible match
		## Let's search for spouses and children
		$people[] = $row['person_id'];
		foreach ($matches as $match) {
			$people[] = $match['person_id'];
		}
		$spouses = Family::getSpouses($people);

		foreach ($matches as $match) {
			echo "<br>&nbsp;&nbsp;&nbsp; Comparing: ". $match['person_id'] . " - " . $match['given_name'] . " " . $match['family_name'] . "(".$match['birth_year'].")";
			
			#########################################################
			# Setup the weighting scale used for the matching equation
			# We'll auto calculate these numbers in the future using multiple regression
			$weight['family_name'] = .1;
			$weight['given_name'] = .1;
			$weight['birthdate'] = .02;
			$weight['birthplace'] = .1;
			$weight['deathdate'] = .02;
			$weight['deathplace'] = .1;
			$weight['gender'] = .1;
			$weight['parents'] = .1/6;
			$weight['spouses'] = .1;
			#########################################################
			# Now calculate each score independently
			# Store these scores in the table so we use the to compare with results in the future
			# We'll use these to run the regression analysis
			$score['family_name'] = scoreValue($row, $match, "family_name");
			$score['given_name'] = scoreValue($row, $match, "given_name");
			$score['gender'] = scoreValue($row, $match, "gender");
			$score['birthplace'] = scoreValue($row, $match, "birthplace");
			$score['deathplace'] = scoreValue($row, $match, "deathplace");
			$score['birthdate'] = scoreDates($row['b_year'], $row['b_month'], $row['b_day'], $match['b_year'], $match['b_month'], $match['b_day']);
			$score['deathdate'] = scoreDates($row['d_year'], $row['d_month'], $row['d_day'], $match['d_year'], $match['d_month'], $match['d_day']);

			$score['parents'] = 0;
			if ($row['bio_family_id'] == $match['bio_family_id']) $score['parents'] = 6;
			else {
				$score['parents'] =+ scoreValue($row, $match, "fgiven_name");
				$score['parents'] =+ scoreValue($row, $match, "ffamily_name");
				$score['parents'] =+ scoreValue($row, $match, "fbirth");
				$score['parents'] =+ scoreValue($row, $match, "mgiven_name");
				$score['parents'] =+ scoreValue($row, $match, "mfamily_name");
				$score['parents'] =+ scoreValue($row, $match, "mbirth");
			}

			if ($spouses[$row["person_id"]] == $spouses[$match["person_id"]]) $score['spouses'] = 1;

			#############################################################
			# Calculate the final score using the individual scores and the equation
			$final_score = 0;
			unset($scores);
			foreach ($score as $key=>$value) {
				if ($value > 0) {
					$i = $score[$key] * $weight[$key];
					$final_score = $final_score + $i;
					$scores[] = "$key = ".$score[$key];
				}
			}
			$scores[] = "similarity_score = $final_score";
			$score_sql = "";
			$score_sql = implode(",", $scores);
			echo "<br>&nbsp;&nbsp;&nbsp; $final_score = $score_sql";
			$sql = "INSERT INTO app_merge SET person_to_id = ". $row['person_id'] . ", person_from_id = ". $match['person_id'] . ", status_code = 'P', updated_by = 0, update_date = Now(), $score_sql
					ON DUPLICATE KEY UPDATE status_code = 'P', updated_by = 0, update_date = Now(), $score_sql";
			$db->sql_query($sql);
		}
	} else {
		# What should we do in the future? There really is a slim chance of a lower record changing and needing to process this again
		# I think we should remove it from the list and then process match searching when we save records
		echo " --- found no matches";
		if (empty($row['merge_rank'])) {
			// We could use the Person class here like this
			//$p1->saveCalc('merge_rank', 1);
			$sql = "UPDATE tree_person SET merge_rank = 1 WHERE person_id = $person_id";
			$db->sql_query($sql);
		}
	}
}

/**
 * calculate how close two dates
 * 
 * 5 = same day, month & year
 * 4 = same month & year
 * 3 = same year
 * 2 = within a year
 * 1 = within 5 years
 * 0 = more than 5 years apart
 *
 * @param integer $year1
 * @param integer $month1
 * @param integer $day1
 * @param integer $year2
 * @param integer $month2
 * @param integer $day2
 * @return integer
 */
function scoreDates($year1, $month1, $day1, $year2, $month2, $day2){
	$yeardiff = abs($year1 - $year2);
	if ($yeardiff > 5) return 0;
	if ($yeardiff > 1) return 1;
	if ($yeardiff == 1) return 2;
	if ($month1 <> $month2) return 3;
	if ($day1 <> $day1) return 4;
	return 5;
}

function scoreValue($row, $match, $value){
	if (empty($row['family_name'])) return .2;
	if (empty($match['family_name'])) return .2;
	if ($row['family_name'] == $match['family_name']) return 1;
	return 0;
}
?>