<?
require_once("GenTable.class.php");

class Family extends GenTable
{
	public $children = array();

	public function __construct($id=0, $time=null)
	{
		parent::__construct();
		if (!empty($id)) {
			$this->getFamily($id, $time);
		}
	}

	/**
	 * Select this Family from the database
	 */
	public function getFamily($family_id=0, $time=null)
	{
		global $user;
		$this->id = $family_id;
		if (empty($this->id)) return false;
		if (empty($this->time)) $this->time = $time;
		if ($this->time) $time_sql = "'$this->time'";
		else $time_sql = "Now()";

		$sql = "SELECT f.family_id, f.update_date, f.status_code, f.notes,
				p1.person_id person1_id, p1.family_name family_name1, p1.given_name given_name1, p1.gender gender1,
					merge_names(p1.given_name, p1.family_name) full_name1,
					p1.created_by created_by1, l1.trace trace1, p1.public_flag public_flag1,
				p2.person_id person2_id, p2.family_name family_name2, p2.given_name given_name2, p2.gender gender2,
					merge_names(p2.given_name, p2.family_name) full_name2,
					p2.created_by created_by2, l2.trace trace2, p2.public_flag public_flag2
				FROM tree_family f
				LEFT JOIN tree_person p1 ON f.person1_id = p1.person_id AND ".actualClause("p1", $time_sql)."
				LEFT JOIN app_user_line_person l1 ON p1.person_id = l1.person_id AND l1.user_id = '$user->id'
				LEFT JOIN tree_person p2 ON f.person2_id = p2.person_id AND ".actualClause("p2", $time_sql)."
				LEFT JOIN app_user_line_person l2 ON p2.person_id = l2.person_id AND l2.user_id = '$user->id'
				WHERE family_id = '$this->id' AND ".actualClause("f", $time_sql)." ";
		$data = $this->db->select( $sql );
		//echo $sql;
		//print_pre($data[0]);

		if (!$data) return false;
		$this->data = $data[0];
		$d = $this->data; // for easy reference;
		//print_pre($d);

		if (empty($d["person1_id"]) && empty($d["person2_id"])) {
			# This family has no parents, delete it
			$this->delete();
			return false;
		}

		if ( $d["person1_id"] > 0 && $d["person2_id"] > 0 &&
			($d["gender1"] == "F" || $d["gender1"] == "") &&
			($d["gender2"] == "M" || $d["gender2"] == "") ) {
			// We have this backwards!! Reverse them!!
			$sql = "UPDATE tree_family SET person1_id = ".$d["person2_id"].", person2_id = ".$d["person1_id"]." WHERE family_id = $this->id";
			$this->db->sql_query( $sql );
			$this->getFamily($this->id, $time);
		}

		## Check to see if the person can view this record
		$view = false;
		if ($this->data["created_by1"] == $user->id) $view = true;
		if ($this->data["trace1"] > "") $view = true;
		if ($this->data["public_flag1"] == 1) $view = true;
		if (!$view) {
			$this->data["family_name1"] = "";
			$this->data["given_name1"] = "LIVING";
			$this->data["public_flag1"] = 0;
			$this->restricted = true;
		}
		$view = false;
		if ($this->data["created_by2"] == $user->id) $view = true;
		if ($this->data["trace2"] > "") $view = true;
		if ($this->data["public_flag2"] == 1) $view = true;
		if (!$view) {
			unset($this->data["family_name2"]);
			$this->data["given_name2"] = "LIVING";
			$this->data["public_flag2"] = 0;
			$this->restricted = true;
		}

		if (!$this->restricted) {
			$sql = "SELECT event_type, event_id, sp_dateformat(e.event_date) event_date, DATE_FORMAT(event_date, '%Y') event_year, ad, date_approx, age_at_event, e.status, location, location_id, temple_code, temple_code temple, notes, source, g.default_flag, g.lds_flag, g.avg_age, g.prompt, g.description
				FROM tree_event	e
				LEFT JOIN ref_gedcom_codes g ON e.event_type = g.gedcom_code
				WHERE key_id = '$this->id' AND e.table_type = 'F' AND ".actualClause("e", $time_sql)." ORDER BY g.seq asc, event_date";
			$data = $this->db->select( $sql );
			foreach($data as $row) {
				if (empty($row["prompt"])) $row["prompt"] = $row["event_type"];
				$this->data["e"][$row["event_type"]] = $row;
			}
		}
		return $this->id;
	}

	/**
	 * Select this Family from the database
	 */
	public function getDeleted($family_id = 0)
	{
		if ($family_id > 0) $this->id = (int)$family_id;
		if (empty($this->id)) return false;

		# Query this person 1 second before they were deleted
		$sql = "SELECT DATE_SUB(max(actual_end_date), INTERVAL 1 SECOND) delete_date FROM tree_family  WHERE family_id = '$this->id' ";
		$data = $this->db->select( $sql );
		$delete_date = $data[0]["delete_date"];
		if (empty($delete_date)) return null;

		$found_id = $this->getFamily($this->id, $delete_date);
		if ($found_id > 0) {
			$this->data["delete_date"] = $delete_date;
			return $delete_date;
		}
		return null;
	}

	/**
	 * Return an array of changes made to this person
	 */
	public function getHistory()
	{
		if (empty($this->id)) return false;

		// I'm not sure which makes more sense here
		$sql = "SELECT c.update_date, u.user_id, u.given_name, u.family_name
				FROM (
					SELECT actual_start_date, updated_by, MAX(update_date) update_date
					FROM (
						SELECT actual_start_date, updated_by, update_date FROM tree_family WHERE family_id = $this->id
						UNION
						SELECT actual_start_date, updated_by, update_date FROM tree_event WHERE key_id = $this->id AND table_type = 'F'
					) t GROUP BY actual_start_date, updated_by) c
				LEFT JOIN app_user u ON c.updated_by = u.user_id
				ORDER BY update_date DESC";
		//echo $sql;
		$data = $this->db->select( $sql );

		if (!$data) return false;
		return $data;
	}


	/**
	 * Select this Family from the database
	 * TODO!! We should just make this static
	 */
	public function getChildren($family_id = 0)
	{
		global $user;
		if ($family_id > 0) $this->id = $family_id;
		if ($this->time) $time_sql = "'$this->time'";
		else $time_sql = "Now()";

		if (empty($this->id)) return false;
		$sql = "SELECT p.person_id, p.child_order, p.family_name, p.given_name, p.title, p.prefix, p.suffix, 
					p.gender, p.created_by, p.creation_date, l.trace, p.public_flag, p.birth_year, 
					DATE_FORMAT(eb.event_date, '%Y') b_yyyy, r.permission trace_permission, 
					r.description trace_meaning, r.distance trace_distance, pc.age, pc.descendant_count,
					sp_dateformat(eb.event_date) b_date, eb.ad b_ad, eb.location b_location,
					sp_dateformat(ed.event_date) d_date, ed.ad d_ad, ed.location d_location
				FROM tree_person p
				LEFT JOIN tree_person_calc pc ON p.person_id = pc.person_id
				LEFT JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id = '$user->id'
				LEFT JOIN ref_relation r ON l.trace = r.trace
				LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb", $time_sql)."
				LEFT JOIN tree_event ed ON p.person_id = ed.key_id AND ed.table_type = 'P' AND ed.event_type = 'DEAT' AND ".actualClause("ed", $time_sql)."
				WHERE p.bio_family_id = '$this->id' AND ".actualClause("p", $time_sql)."
				ORDER BY p.child_order, eb.event_date";
		//echo $sql;
		$data = $this->db->select( $sql );
		//print_pre($data);

		# Find out if the children are in the correct order and all have birth years
		$inorder = true;
		$has_nulls = false;
		$previous_order = 0;
		foreach ($data as $key=>$value) {
			if (empty($value["b_yyyy"])) $has_nulls = true;
			# TODO this only works right if the years are in AD, have to reverse it if it's BC
			# fix this later
			if ($value["child_order"] <> ($previous_order + 1) ) $inorder = false;
			$previous_order = $value["child_order"];
		}
		if (!$inorder && !$has_nulls) {
			//echo "reordering";
			if (Family::reorderChildren($this->id)) {
				$data = $this->db->select( $sql );
			}
		}

		foreach ($data as $key=>$value) {
			$view = false;
			if ($value["created_by"] == $user->id && is_logged_on() ) $view = true;
			if ($value["trace"] > "") $view = true;
			if ($value["public_flag"] == 1) $view = true;
			unset($data[$key]);
			if (!$view) {
				unset($data[$key]);
				$data[$key]["family_id"] = $value["family_id"];
				$data[$key]["person_id"] = $value["person_id"];
				$data[$key]["given_name"] = "LIVING";
				$data[$key]["protected"] = 1;
				$data[$key]["child_order"] = $value["child_order"];
				$data[$key]["gender"] = $value["gender"];
				//return $data;
			} else {
				$data[$key] = $value;
			}

			$data[$key]["full_name"] =
				$data[$key]["title"] ." ".
				$data[$key]["prefix"] ." ".
				$data[$key]["given_name"] ." ".
				$data[$key]["family_name"] ." ".
				$data[$key]["suffix"];
			$data[$key]["full_name"] = trim(str_replace("  ", " ", $data[$key]["full_name"]));
			
			# Is this person new?
			$created = strtotime($data[$key]["creation_date"]);
			$new_date = strtotime("-1 month");
			if ($created > $new_date) $data[$key]["new"] = 1;
			else $data[$key]["new"] = 0;
		}
		//print_pre($data);

		$this->children = $data;
		if (!$data) return array();
		return $this->children;
	}

	/**
	 */
	static public function reorderChildren($family_id)
	{
		if (empty($family_id)) return false;
		global $db;

		$sql = "SELECT p.person_id, p.child_order, DATE_FORMAT(eb.event_date, '%Y') b_year, eb.ad
				FROM tree_person p
				LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb", $time_sql)."
				WHERE p.bio_family_id = '$family_id' AND ".actualClause("p", $time_sql)."
				ORDER BY eb.event_date";
		$data = $db->select( $sql );
		//print_pre($data);

		$missing_dates = false;
		$last_date = "-9999";
		$order = 0;
		foreach ($data as $key=>$value) {
			$order++;
			if (empty($value["b_year"])) $missing_dates = true;
			$data[$key]["new_order"] = $order;
		}

		if (!$missing_dates) {
			require_once("Person.class.php");
			foreach ($data as $value) {
				//echo $value["new_order"] . $value["child_order"] ."<br>";
				if ($value["new_order"] <> $value["child_order"]) {
					Person::updateChildOrder($value["person_id"], $value["new_order"]);
				}
			}
		}
		return true;
	}

	static public function getParents($families) {
		global $db;
		$parents = array();
		//print_pre($families);
		if (count($families) > 0) {
			# Get the parent's information
			$family_ids = implode(",",$families);
			$sql = "SELECT f.family_id,
					p1.given_name fgiven_name, p1.family_name ffamily_name,
					p2.given_name mgiven_name, p2.family_name mfamily_name
					FROM tree_family f
					LEFT JOIN tree_person p1 ON f.person1_id = p1.person_id AND ".actualClause("p1")."
					LEFT JOIN tree_person p2 ON f.person2_id = p2.person_id AND ".actualClause("p2")."
					WHERE ".actualClause("f")." AND f.family_id IN ($family_ids)";
			$temp = $db->select($sql);
			foreach($temp as $row) {
				$father_name = trim($row['fgiven_name'] . " " . $row['ffamily_name']);
				$mother_name = trim($row['mgiven_name'] . " " . $row['mfamily_name']);
				$father_name = empty($father_name)?"???":$father_name;
				$mother_name = empty($mother_name)?"???":$mother_name;
				$parents[$row['family_id']] = "$father_name &amp; $mother_name";
			}
		}
		return $parents;
	}

	static public function getSpouses($people) {
		global $db;
		$spouses = array();
		//print_pre($people);
		if (count($people) > 0) {
			# Get the spouses's information
			$person_ids = implode(",",$people);
			$sql = "SELECT f.person_id, p.given_name, p.family_name, p.title
					FROM tree_person p
					JOIN
					(
						SELECT person1_id as spouse_id, person2_id person_id
						FROM tree_family WHERE person2_id IN ($person_ids) AND ".actualClause()."
						UNION
						SELECT person2_id as spouse_id, person1_id person_id
						FROM tree_family WHERE person1_id IN ($person_ids) AND ".actualClause()."
					) f ON f.spouse_id = p.person_id
					WHERE ".actualClause("p");
			$temp = $db->select($sql);
			//echo $sql;
			foreach($temp as $row) {
				if (isset($spouses[$row['person_id']])) $spouses[$row['person_id']] .= ", ";
				$spouses[$row['person_id']] .= trim($row['given_name'] . " " . $row['family_name']);
			}
		}
		return $spouses;
	}

	/**
	 * Save the family record
	 * See also Person->save and Event->save
	 */
	public function save($data)
	{
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Family.class.php";
		global $user;
		if ( empty($user->id) ) return false;
		if ( empty($this->id) ) $this->id = $data['family_id'];

		$db_time = "'".getServerTime()."'";

		$valid = false;
		if ($this->id > 0) {
			# Query the current database record to do see if we need to do an archive or a simple update
			$sql = "SELECT * FROM tree_family WHERE family_id = $this->id AND ". actualClause("", $db_time);
			$row = $this->db->select( $sql );
			if (count($row) <> 1) {
				# This is a really lame way to handle things, we should fail more gracefully
				# For example, if more than one record expires in the future, we should consider deleting all but one
				# If no records that expire in the future, we should consider "undeleting" the last archived version
				throw new ThisException("Failed to return 1 and only 1 row for query: $sql");
			}
			$current_row = $row[0];

			$archive = TRUE;
			# If the same person is trying to edit again and the change was recent, then don't bother archiving
			$elapsed_time = time() - strtotime($current_row['actual_start_date']);
			if ($current_row['updated_by'] == $user->id && $elapsed_time < ARCHIVE_SECONDS) $archive = FALSE;
			//if ($archive) die("archive");
			//else die("update");
			$valid = true;
		}
		$fields = "";
		$datachanged = FALSE;
		foreach ($data as $key=>$value) {
			if (in_array($key, array("person1_id", "person2_id", "status_code", "notes"))) {
				if (in_array($key, array("person1_id", "person2_id"))) {
					if (empty($value)) $value = ""; // Don't save zeros
					$valid = true;
				}
				$value = fixTick($value);
				if ($fields > "") $fields .= ", ";
				if ($value == "") $fields .= "$key = NULL";
				else $fields .= "$key = '$value'";
				// See if SOME person data has changed, we need to do an update on person
				if ($value <> $current_row[$key]) $datachanged = TRUE;
			}
		}
		if (!$valid) return false;

		if ($this->id > 0) {
			if ($datachanged) {
				# Update the family record
				if ($archive) {
					# Make a copy of the record for audit reasons
					$last_update_date = $current_row['actual_start_date'];

					if ($this->db->sql_affectedrows() <> 1) throw new ThisException("Failed to update 1 and only 1 row for query: $sql");

					// Maybe we can generate this list from the $current_row array above
					$field_list = "family_id, creation_date, created_by, person1_id, person2_id, status_code, notes";

					$sql = "INSERT INTO tree_family ($field_list, updated_by, update_process, update_date, actual_start_date, actual_end_date)
				                  SELECT $field_list, $user->id, '".$_GLOBAL['update_process']."', $db_time, $db_time, '". ARCHIVE_DATE ."'
							FROM tree_family WHERE family_id = $this->id AND actual_start_date = '$last_update_date' ";
					//echo $sql;
					$this->db->sql_query( $sql );
					if ($this->db->sql_affectedrows() <> 1) throw new ThisException("Failed to update 1 and only 1 row for query: $sql");

					# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
					$sql = "UPDATE tree_family SET actual_end_date = $db_time WHERE family_id = $this->id AND actual_start_date = '$last_update_date' ";
					$this->db->sql_query( $sql );
				}
				# Update the current record now
				$sql = "UPDATE tree_family SET $fields WHERE family_id = $this->id AND ".actualClause("", $db_time);
				$this->db->sql_query( $sql );
			}
		} else {
			if (empty($data["person1_id"]) && empty($data["person2_id"])) {
				// There are no people, don't insert this record
				$this->id = 0;
				return 0;
			}

			# We can't risk adding a duplicate marriage
			# Never insert a new record when we already have one out there
			$sql = "SELECT family_id FROM tree_family WHERE person1_id = '".$data["person1_id"]."' AND person2_id = '".$data["person2_id"]."' AND ".actualClause("", $db_time);
			$temp = $this->db->select( $sql );
			if ($temp[0]["family_id"] > 0) return $temp[0]["family_id"];

			$sql = "INSERT INTO tree_family SET $fields, creation_date = $db_time, created_by = $user->id, update_date=$db_time, updated_by=$user->id, update_process='".$_GLOBAL['update_process']."', actual_start_date=$db_time, actual_end_date='". ARCHIVE_DATE ."' ";
			//echo $sql;
			$this->db->sql_query( $sql );
			$this->id = $this->db->sql_nextid();
		}

		if (is_array($data[e])) {
			include_class("Event");
			foreach($data[e] as $key=>$eventdata) {
				$change = 0;
				foreach($eventdata as $colname=>$value) {
					# we have something to add or modify (skip the ad field)
					if ($value > "" && $colname <> "ad") $change++;
				}
				if ($existing_events[$key] > 0 || $change > 0) {
					// We may have something to add, modify or delete
					$eventdata['key_id'] = $this->id;
					$eventdata['table_type'] = 'F';
					$eventdata['event_type'] = $key;
					//print_pre($eventdata);
					Event::save($eventdata, $db_time);
				}
			}
		}

		# Now we have a family, update all the children (if any) to now be part of it
		//print_pre($data['children']);
		if (is_array($data['children']) && count($data['children']) > 0) {
			$kids = implode(",", $data['children']);
			$sql = "UPDATE tree_person SET bio_family_id = '$this->id' WHERE person_id IN ($kids) ";
			//echo $sql;
			$result = $this->db->sql_query( $sql );
		}
		return $this->id;
	}

	/**
	 * Undo a previous Save to a family (usually undoing a merge)
	 *
	 * See also Person::unsave
	 */
	static public function rollback($family_id, $savedate)
	{
		//echo "<br>Family::unsave family $family_id date $savedate";
		# Basic setup first
		global $db;
		if ( empty($family_id) ) return false;
		if ( empty($savedate) ) return false;

		###########################################################################
		# Undo the saves to any events associated with this marriage
		include_class("Event");
		Event::unsave("F", $family_id, $savedate);

		# Find the record associated with this save date. Find all records
		# saved within +/- 60 seconds of the save and then sort them to get the closest one.
		# The reason we search around the date is because we could be off by a few seconds due
		# to a large process like a merge not processing all at once, but we should probably
		# just change that use a single date the whole time.
		$sql = "SELECT actual_start_date, actual_end_date, ABS(UNIX_TIMESTAMP('$savedate')-UNIX_TIMESTAMP(actual_end_date)) diff
				FROM tree_family
				WHERE family_id = $family_id AND actual_end_date BETWEEN '$savedate' - INTERVAL 1 MINUTE AND '$savedate' + INTERVAL 1 MINUTE
				ORDER BY diff ASC LIMIT 1";
		$data = $db->select( $sql );
		if (count($data) == 0) return false; //couldn't find any changes during this time period

		# reset the $savedate to the actual date the bad data was saved
		$startdate = $data[0]["actual_start_date"];

		# Remove all future changes
		$sql = "DELETE FROM tree_family WHERE family_id = $family_id AND actual_start_date > '$startdate'";
		$db->sql_query( $sql );

		# Restore the rollback segment into the future
		$sql = "UPDATE tree_family SET actual_end_date = '". ARCHIVE_DATE ."'
				WHERE family_id = $family_id AND actual_start_date = '$startdate'";
		$db->sql_query( $sql );

		return true;
	}

	/**
	 * Delete the family
	 */
	public function delete($data=array()) {
		//die("trying to delete");
		# we don't update the children since it's less work if we restore the family
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Family.class.php";
		global $user;
		if ( empty($user->id) ) return false;
		if ( empty($this->id) ) $this->id = $data['family_id'];

		$db_time = "'".getServerTime()."'";

		include_class("Event");
		// Don't delete events either since it's easier to restore them if we don't delete them
		Event::delete("F", $this->id, $db_time);

		# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
		//echo $sql;

		$sql = "UPDATE tree_family SET actual_end_date=$db_time, update_date=$db_time, updated_by=$user->id, update_process='".$_GLOBAL['update_process']."'
				WHERE family_id = $this->id AND actual_end_date > $db_time ";
		$this->db->sql_query( $sql );
		return true;
	}

	/**
		* Attach a person to a marriage
		*
		* @param spouse int defines if the person is husband (1) or wife (2)
		*/
	public function updateSpouse($family_id, $person_id, $spouse) {
		$spouse = (int) $spouse;
		if ($spouse == 2) {
			$data['person2_id'] = $person_id;
		} else {
			# Spouse 1 (husband) is the default
			$data['person1_id'] = $person_id;
		}
		$data['family_id'] = $family_id;
		return $this->save($data);
	}

	static public function moveChildren($from_family, $to_family) {
		if (empty($from_family)) errorMessage("Failed to provide family_id to move from");
		if (empty($to_family)) errorMessage("Failed to provide family_id to move to");
		//global $db;
		$f = new Family();
		$children_from = $f->getChildren($from_family);
		$children_to = $f->getChildren($to_family);
		//print_pre($children_from);
		//print_pre($children_to);
		foreach($children_from as $child_from) {
			foreach($children_to as $child_to) {
				if ( compareString($child_from["given_name"].$child_from["family_name"], $child_to["given_name"].$child_to["family_name"]) ) {
					# The children's names are identical so add to queue for later review
					//echo "adding to queue ".$child_from["person_id"]. $child_to["person_id"];
					addQueue($child_from["person_id"], $child_to["person_id"]);
				}
			}
			$p = new Person();
			unset($save);
			$save["person_id"] = $child_from["person_id"];
			$save["bio_family_id"] = $to_family;
			$p->save($save);
			unset($p);
		}
		/*
		# Archiving the children records is unnecessary because the change is with the family, not the person record
		# Although this is debatable. We'll have to see if it makes sense over time
		$sql = "UPDATE tree_person SET bio_family_id = $to_family
				WHERE bio_family_id = $from_family AND ".actualClause();
		return $db->sql_query($sql);
		*/
	}

	static public function countFamilies($where="")
	{
		global $db;
		$sql = "SELECT count(DISTINCT family_id) as total FROM tree_family WHERE 1=1 $where";
		$data = $db->select($sql);
		return $data[0]['total'];
	}

	static public function mergeMarriages($from_person, $to_person) {
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Family.class.php";
		global $user, $db;
		if ( empty($user->id) ) return false;

		# get a list of spouses for both persons
		$sql = "SELECT f.person_id, f.family_id, f.spouse_id, s.given_name, s.family_name FROM (
					SELECT family_id, person2_id spouse_id, person1_id person_id, 2 spouse_order FROM tree_family
					WHERE person1_id IN ($from_person, $to_person)  AND ".actualClause()."
					UNION
					SELECT family_id, person1_id spouse_id, person2_id person_id, 1 spouse_order FROM tree_family
					WHERE person2_id IN ($from_person, $to_person) AND ".actualClause()."
				) f LEFT JOIN tree_person s ON f.spouse_id = s.person_id AND ".actualClause('s');
		$data = $db->select($sql);
		//print_pre($data);

		foreach ($data as $spousefrom) {
			# We now have both sets of spouses in the same loop
			# We have to loop through them twice using if statements to separate them
			if ($spousefrom["person_id"] == $from_person) {
				$exists = false;
				foreach ($data as $spouseto) {
					if ($spouseto["person_id"] == $to_person) {
						//echo "comparing ". $spousefrom["spouse_id"] ." ". $spouseto["spouse_id"];
						# Compare the spouses of personfrom with spouses of personto
						if ($spousefrom["spouse_id"] == $spouseto["spouse_id"]) {
							#this person ($from_spouse) was married to the same person already
							#now we are merging their spouses' records, so we need to eliminate one of the marriages (later)
							$exists = true;
							$to_family = $spouseto['family_id'];
						} else {
							# We didn't have an exact match but maybe the names are still identical
							# If so, then add the spouses to the queue for later review
							if ( compareString($spousefrom["given_name"].$spousefrom["family_name"], $spouseto["given_name"].$spouseto["family_name"]) ) {
								addQueue($spouseto["spouse_id"], $spousefrom["spouse_id"]);
							}
						}
					}
				}
				$f = new Family();
				if ($exists) {
					include_class("Event");
					# Remove the marriage by transfering the children and expiring the records
					# NOTE!! We never actually move any other data like events and notes, could be bad
					# TODO: auto move events
					$f1 = new Family($to_family);
					$f->getFamily($spousefrom['family_id']);

					//print_pre($f1->data);
					//print_pre($f->data);
					if (is_array($f->data["e"])) {
					foreach($f->data["e"] as $event_type=>$eventdata) {
						# Loop through each event from the marriage that will be deleted
						foreach($eventdata as $efieldtype=>$efieldvalue) {
							# Loop through each event field and compare with the previous marriages data
							if ($f1->data["e"][$event_type][$efieldtype] > "") {
								# If the original marriage had data in this field, then use it
								$eventdata[$efieldtype] = $f1->data["e"][$event_type][$efieldtype];
							}
						}
						$eventdata["key_id"] = $f1->id;
						$eventdata["table_type"] = "F";
						Event::save($eventdata);
					}
					}
					//echo "<br>Family::moveChildren <br> ";
					Family::moveChildren($spousefrom['family_id'], $to_family);
					$f->delete($spousefrom['family_id']);
				} else {
					# Transfer this marriage by moving it to $to_person
					unset($save);
					$save["family_id"] = $spousefrom["family_id"];
					if ($spousefrom["spouse_order"] == 1) {
						$save["person2_id"] = $to_person;
						$save["person1_id"] = $spousefrom["spouse_id"];
					} else {
						$save["person1_id"] = $to_person;
						$save["person2_id"] = $spousefrom["spouse_id"];
					}
					$f->save($save);
				}
				unset($f);
			}
		}
		//die();
	}

	static public function restoreMarriages($person_id, $savedate) {
	 "<br>Family::restoreMarriages person $person_id date $savedate";
		global $db;
		if ( empty($person_id) ) return false;
		if ( empty($savedate) ) return false;

		$timeperiod = " AND actual_start_date <= ('$savedate' - INTERVAL 1 MINUTE) AND actual_end_date >= ('$savedate' - INTERVAL 1 MINUTE)";
		# get a list of families for person
		$sql = "SELECT family_id FROM tree_family WHERE person1_id = $person_id $timeperiod
				UNION
				SELECT family_id FROM tree_family WHERE person2_id = $person_id $timeperiod";
		//echo $sql;
		$data = $db->select($sql);
		$ids = array();
		foreach ($data as $fam) {
			Family::rollback($fam["family_id"], $savedate);
			$ids[] = $fam["family_id"];
		}

		if (count($ids) > 0) {
			# get a list of children in the above families
			$families = implode(",", $ids);
			$sql = "SELECT person_id FROM tree_person WHERE bio_family_id IN ($families) $timeperiod
					UNION
					SELECT person_id FROM tree_person WHERE bio_family_id IN ($families) $timeperiod";
			//echo $sql;
			$data = $db->select($sql);
			foreach ($data as $child) {
				//echo "<br>Person::unsave person ".$child["person_id"]." date $savedate";
				Person::unsave($child["person_id"], $savedate);
			}
		}
	}
}

?>
