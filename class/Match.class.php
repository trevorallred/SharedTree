<?
class Match
{
	/**
	 * get a list of people we need to process (ie find dupes)
	 */
	static public function process($where="", $print=true)
	{
		global $db, $T;
		set_time_limit(0);

		$limit = 100;
		$include_rank = 0; // Set this high to include records that are not as complete
		# 0 - brand new, not validated at all
		# 1 - matched (may or may not have found matches)
		# 2 - changes discouraged
		# 3 - record locked

		if ($print) {
			$T->assign("title", "Matching Engine");
			$T->display('header.tpl');
			$sql = "SELECT count(*) total 
					FROM tree_person p
					JOIN tree_person_calc pc ON p.person_id = pc.person_id AND pc.merge_rank = 0
					WHERE p.merged_into IS NULL AND ".actualClause();
			$data = $db->select($sql);
			echo "<h2>Running Matching Engine</h2> Remaining records to process: ".$data[0]["total"]."<br> <div align='left'>";
			if (ob_get_level() == 0) {
			   ob_start();
			}
		}

		$continue = true;
		while($continue) {
			$sql = "SELECT p.person_id, given_name, family_name, p.birth_year, pc.merge_rank 
					FROM tree_person p
					JOIN tree_person_calc pc ON p.person_id = pc.person_id
					WHERE merged_into IS NULL AND ".actualClause()."
					AND pc.merge_rank = 0 $where
					ORDER BY p.person_id ASC LIMIT 0, $limit";
			$data = $db->select($sql);
			if (count($data) < $limit) $continue = false;
			echo "<h1>Processing ".count($data)." records</h1>";
			foreach ($data as $row) {
				$out = Match::find($row["person_id"], .5, 0);
				if ($print) {
					echo "<h4>Searching for matches for <a href='/person/".$row['person_id']."' target='_BLANK'>". $row['given_name'] . " " . $row['family_name'] . "(".$row['birth_year'].")</a></h4> $out";
					flush();
					ob_flush();
				}
			}
		}
		if ($print) {
			echo "</div><br><br><br><br>Click Refresh in your Browser to Process the next group of names";
			$T->display('footer.tpl');
			ob_end_flush();
		}
	}

	/**
	 * Take one person and search the database for possible duplicates
	 */
	static public function find($person_id, $min=.5, $all=1)
	{
		global $db, $user;
		include_class("Person");
		include_class("Family");
		//if ( empty($user->id) ) return "not signed as a valid user";
		
		$p1 = new Person();
		$p1->superuser = true;
		$p1->getPerson($person_id);
		if (empty($p1->id)) return "could not query person";
		if ($p1->restricted) return "don't have permission to view restricted record";
		
		if (
			empty($p1->data['family_name']) ||
			empty($p1->data['given_name']) ||
			empty($p1->data['birth_year'])
			) {
			# family_name, given_name and birth_year (atleast estimated) are all required
			$p1->saveCalc('merge_rank', -1);
			return "person doesn't have enough data to match with";
		}
		
		############################################################
		## Figure out all the criteria we should use to eliminate potential matches
		$where = "";
		if ($all == 0) $where .= " AND p.person_id < $person_id";
		$where .= " AND p.family_name = '".fixTick($p1->data['family_name'])."'";
		// TODO split the first name and do OR
		$where .= " AND (p.given_name LIKE '%".fixTick($p1->data['given_name'])."%')";
		
		if ($p1->data['gender'] > '') {
			$where .= " AND p.gender = '".$p1->data['gender']."'";
		}
		$start = $p1->data['birth_year'] - 15;
		$end = $p1->data['birth_year'] + 15;
		$where .= " AND (p.birth_year BETWEEN $start AND $end)";
		
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
		$weight['parents'] = .1/4;
		$weight['spouses'] = .1;

		############################################################
		# Now search for potential matches
		$sql = "SELECT p.person_id FROM tree_person p 
				WHERE p.merged_into IS NULL AND ".actualClause("p")." $where";
		//echo $sql;
		$data = $db->select($sql);
		$matches = $db->select($sql);
		
		if (count($matches) == 0) {
			# We didn't find any matches: update the person and return
			if ($p1->data['merge_rank'] < 1) {
				$p1->saveCalc('merge_rank', -1);
			}
			return "<font color='#999999'>found no matches</font>";
		}
		$f1 = new Family($p1->data["bio_family_id"]);
		
		## We may have a possible match
		## Let's search for spouses and children
		$people[] = $person_id;
		foreach ($matches as $match) {
			$people[] = $match["person_id"];
		}
		$spouses = Family::getSpouses($people);
		
		$match_count = 0;
		foreach ($matches as $match) {
			$p2 = new Person($match["person_id"]);
			$f2 = new Family($p2->data["bio_family_id"]);
	
			#########################################################
			# Now calculate each score independently
			# Store these scores in the table so we use the to compare with results in the future
			# We'll use these to run the regression analysis
			$score['family_name'] = scoreValue($p1->data, $p2->data, "family_name");
			$score['given_name'] = scoreValue($p1->data, $p2->data, "given_name");
			$score['gender'] = scoreValue($p1->data, $p2->data, "gender");
			$score['birthplace'] = scoreValue($p1->data["e"]["BIRT"], $p2->data["e"]["BIRT"], "location");
			$score['deathplace'] = scoreValue($p1->data["e"]["DEAT"], $p2->data["e"]["DEAT"], "location");
			$score['birthdate'] = scoreDates($p1->data["e"]["BIRT"], $p2->data["e"]["BIRT"]);
			$score['deathdate'] = scoreDates($p1->data["e"]["DEAT"], $p2->data["e"]["DEAT"]);

			$score['parents'] = 0;
			if ($f1->id == $f2->id) $score['parents'] = 4; // exactly the same parents
			else {
				$score['parents'] =+ scoreValue($f1->data, $f2->data, "family_name1");
				$score['parents'] =+ scoreValue($f1->data, $f2->data, "given_name1");
				$score['parents'] =+ scoreValue($f1->data, $f2->data, "family_name2");
				$score['parents'] =+ scoreValue($f1->data, $f2->data, "given_name2");
			}

			if ($spouses[$p1->id] > "" && $spouses[$p2->id] > "") {
				# See if one is contained in the other (either way)
				// This is a really crude way but it surprisingly catches a lot
				if (strpos($spouses[$p1->id], $spouses[$p2->id]) > 0) $score['spouses'] = 1;
				if (strpos($spouses[$p2->id], $spouses[$p1->id]) > 0) $score['spouses'] = 1;
			} else {
				# One or both spouses are blank
				if ($spouses[$p2->id] == $spouses[$p1->id]) $score['spouses'] = .2;
			}

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
			if ($final_score >= $min) {
				$scores[] = "similarity_score = $final_score";
				$score_sql = implode(",", $scores);
				Match::save($person_id, $match['person_id'], "Q", $score_sql);
				$match_count++;
			}
		}
		$p1->saveCalc('merge_rank', 1);
		return "found ".count($matches)." potential duplicate(s), of which $match_count exceeded the minumum threshold";
	}
	/**
	 * remove poor matches
	 * TODO finish this
	 */
	static public function delete($match_id) {
		global $db;
	}
	
	/**
	 * Saves matches into the app_merge table
	 *
	 * @param $p1 int - the older record that is being merged into
	 * @param $p2 int - the newer record that is being merged from (deleted)
	 * @param $status char - the new status
	 *                       Q = queued for merging...just a proposal
	 *                       M = merged
	 *                       R = rejected
	 * @param $sql2 string - an optional string of sql for additional fields
	 */
	static public function save($p1, $p2, $status="Q", $sql2 = "") {
		# Make sure we don't have the same or empty ids and they are in the right order
		$p1 = (int)$p1;
		$p2 = (int)$p2;
		if (empty($p1)) return false;
		if (empty($p2)) return false;
		if ($p1==$p2) return false;
		if ($p1 > $p2) {
			$temp = $p1;
			$p1 = $p2;
			$p2 = $temp;
		}
		
		global $db, $user;
		# We may not have a user if it's a cronjob
		$uid = (int)$user->id;

		# Get a list of merge records already existing
		$sql = "SELECT * FROM app_merge WHERE person_to_id = $p1 AND person_from_id = $p2
				UNION
				SELECT * FROM app_merge WHERE person_to_id = $p2 AND person_from_id = $p1";
		$data = $db->select($sql);
		if (count($data) > 1) {
			# There shouldn't be more than one, delete the reversed one
			$del = "DELETE FROM app_merge WHERE person_to_id = $p2 AND person_from_id = $p1";
			$db->sql_query($del);
			$data = $db->select($sql); // Now requery with only one
		}
		$update_sql = "status_code = '$status', updated_by = $uid, update_date = Now()";
		if ($sql2 > "") $update_sql .= ", $sql2";

		if (count($data) > 0) {
			# We already have one
			if ($data[0]["status_code"] == "R") {
				# This match has been permanently rejected, ignore it
				return false;
			} else {
				$sql = "UPDATE app_merge SET person_to_id = $p1, person_from_id = $p2, $update_sql WHERE merge_id = ".$data[0]["merge_id"];
			}
		} else {
			# We don't have any yet so add one now
			$sql = "INSERT INTO app_merge SET person_to_id = $p1, person_from_id = $p2, $update_sql";
		}
		$db->sql_query($sql);
	}
	/**
	 * alias to the Match::save function for adding people to the queue
	 */
	static public function addQueue($p1, $p2, $score=2) {
		return Match::save($p1, $p2, "Q", "similarity_score = '$score'");
	}

	/**
	 * find people who have been previously matched (but not yet merged or rejected) with $person_id
	 */
	static public function findMatches($person_id) {
		global $db;
		$sql = "SELECT p.person_id, p.family_name, p.given_name, p.birth_year FROM tree_person p
				JOIN (
					SELECT person_to_id person_id FROM app_merge WHERE status_code IN ('P','Q') AND person_from_id = $person_id
					UNION
					SELECT person_from_id person_id FROM app_merge WHERE status_code IN ('P','Q') AND person_to_id = $person_id
				) m ON m.person_id = p.person_id WHERE ".actualClause("p");
		//echo $sql;
		return $db->select($sql);
	}
}

/**
 * calculate how close two dates are
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
function scoreDates($date1, $date2){
	if ($date1=="") return 1;
	if ($date2=="") return 1;
	if ($date1==$date2) return 5;
	$score = 0;
	$dt = explode(" ", $date1);
	$dt1 = array_reverse($dt);
	$dt = explode(" ", $date2);
	$dt2 = array_reverse($dt);
	if ($dt1[0] == $dt2[0]) $score += 3;
	elseif (abs($dt1[0]-$dt2[0]) <= 5) $score += 1;
	if ($dt1[1] == $dt2[1]) $score += 1;
	if ($dt1[2] == $dt2[2]) $score += 1;
	
	return $score;
}

function scoreValue($data1, $data2, $value){
	if (empty($data1[$value])) return .2;
	if (empty($data2[$value])) return .2;
	if ($data1[$value] == $data2[$value]) return 1;
	return 0;
}

?>
