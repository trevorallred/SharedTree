<?
require_once("GenTable.class.php");

class Person extends GenTable
{
	public $restricted = false;
	public $superuser = false;

	public function __construct($id=0, $time=null)
	{
		parent::__construct();
		if (!empty($id)) {
			$this->getPerson($id, $time);
		}
	}

	/**
	 * Select this Person from the database
	 */
	public function getPerson($person_id = 0, $time=null)
	{
		global $user;

		if ($person_id > 0) $this->id = (int)$person_id;
		if (empty($this->id)) return false;

		if (empty($this->time)) $this->time = $time;
		if ($this->time) $time_sql = "'$time'";
		else $time_sql = "Now()";

		$sql = "SELECT p.person_id, p.gender, p.family_name, p.given_name, p.nickname, p.orig_family_name, p.orig_given_name, p.birth_year, p.public_flag, 
					p.title, prefix, suffix, rin, afn, national_id, f.family_id bio_family_id, p.adopt_family_id, p.merged_into, national_origin, occupation, child_order, wikipedia,
					l.trace, r.permission trace_permission, r.description trace_meaning, r.distance trace_distance, 
					merge_names(uc.given_name, uc.family_name) created_user, merge_names(uu.given_name, uu.family_name) updated_user, w.watch_id, 
					merge_names(ul.given_name, ul.family_name) closest_user, ul.restrict_access,
					pc.person_id calc_id, pc.age, pc.page_views, pc.closest_user_id, 
					pc.related_users, pc.descendant_count, pc.ancestor_count, pc.marriage_count, pc.photo_count, pc.biography_size, pc.forum_count, pc.merge_rank,
					p.created_by, p.creation_date, p.updated_by, p.update_date
				FROM tree_person p
				LEFT JOIN tree_person_calc pc ON p.person_id = pc.person_id
				LEFT JOIN tree_family f ON p.bio_family_id = f.family_id AND ".actualClause("f", $time_sql)."
				LEFT JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id = '$user->id'
				LEFT JOIN ref_relation r ON l.trace = r.trace
				LEFT JOIN app_watch w ON w.person_id = p.person_id AND w.user_id = '$user->id'
				LEFT JOIN app_user uc ON uc.user_id = p.created_by
				LEFT JOIN app_user uu ON uu.user_id = p.updated_by
				LEFT JOIN app_user ul ON ul.user_id = pc.closest_user_id
				WHERE p.person_id = '$this->id' AND ".actualClause("p", $time_sql);
		$data = $this->db->select( $sql );
		//echo $sql;

		if (!$data) return false;
		$this->data = $data[0];

		if ($this->data["calc_id"] == "") {
			# Add the person calc table now
			$sql2 = "INSERT INTO tree_person_calc (person_id) VALUES ($this->id)";
			$this->db->sql_query( $sql2 );
			$data = $this->db->select( $sql );
			$this->data = $data[0];
		}
		
		## Check to see if the person can view this record
		$view = false;
		if ($this->data["created_by"] == $user->id) $view = true;
		if ($this->data["trace"] > "") $view = true;
		if ($this->data["public_flag"] == 1) $view = true;
		if ($this->id == $user->data["person_id"]) $view = true;
		if ($this->superuser) $view = true;
		if ($_SERVER["PHP_SELF"] == '/merge.php') $view = true;
		if ($this->id == 45941) {
			echo $_SERVER["PHP_SELF"];
			phpinfo();
			$view = true;
			die();
		}
		if (!$view) {
			//$this->estimateBirthYear();
			//$this->resetPublicFlag();
			unset($this->data);
			$this->data["person_id"] = $this->id;
			$this->data["full_name"] = "LIVING";
			$this->data["given_name"] = "LIVING";
			$this->data["protected"] = 1;
			$this->restricted = true;
			return false;
		}

		$this->data["full_name"] =
			$this->data["title"] ." ".
			$this->data["prefix"] ." ".
			$this->data["given_name"] ." ".
			$this->data["family_name"] ." ".
			$this->data["suffix"];
		$this->data["full_name"] = trim(str_replace("  ", " ", $this->data["full_name"]));

		# Is this person new?
		$created = strtotime($this->data["creation_date"]);
		$new_date = strtotime("-1 month");
		if ($created > $new_date) $this->data["new"] = 1;
		else $this->data["new"] = 0;
		

		//$this->data["trace_meaning"] = traceMeaning($this->data["trace"]);
		$this->data["any_updated_by"] = $this->data["updated_by"];
		$this->data["any_update_date"] = $this->data["update_date"];
		$sql = "SELECT event_type, event_id, ".dateClause()." event_date, DATE_FORMAT(event_date, '%Y') event_year, event_date raw_date, ad, date_approx, age_at_event, date_text, e.status, location, location_id, e.temple_code, notes, source, e.updated_by, e.update_date, g.default_flag, g.lds_flag, g.avg_age, g.prompt, g.description
				FROM tree_event	e
				LEFT JOIN ref_gedcom_codes g ON e.event_type = g.gedcom_code
				WHERE key_id = '$this->id' AND e.table_type = 'P' AND ".actualClause("e", $time_sql)." ORDER BY g.seq asc, event_date";
		//echo $sql;
		$data = $this->db->select( $sql );

		foreach($data as $row) {
			$this->data["e"][$row["event_type"]] = $row;
			if (strtotime($row["update_date"]) > strtotime($this->data["any_update_date"])) {
				$this->data["any_updated_by"] = $row["updated_by"];
				$this->data["any_update_date"] = $row["update_date"];
			}
			if ($row["event_type"] == 'BIRT') {
				$this->data['e']['BIRT']['event_year'] = (int) $this->data['e']['BIRT']['event_year'];
				if ($this->data['e']['BIRT']['ad'] == 1) $this->data['birth_year_disp'] = $this->data['e']['BIRT']['event_year'];
				else $this->data['birth_year_disp'] = ($this->data['e']['BIRT']['event_year'] * -1) . " B.C.";
				if ($this->data['e']['BIRT']['event_year'] == 0) $this->data['birth_year_disp'] = "unknown";
			}
		}

		if ($this->data["any_updated_by"] > 0) {
			$sql = "SELECT given_name, family_name FROM app_user WHERE user_id = ".$this->data["any_updated_by"];
			$data = $this->db->select( $sql );
			$this->data["any_updated_name"] = trim($data[0]["given_name"]." ".$data[0]["family_name"]);
		}
		if ($this->data["merged_into"] > 0) {
			$sql = "SELECT merge_id FROM app_merge WHERE person_from_id = $this->id AND person_to_id = ".$this->data["merged_into"];
			$data = $this->db->select( $sql );
			$this->data["merge_id"] = $data[0]["merge_id"];
		}
		# Don't calculate the birth year if we are looking at history
		if ($time==null) $this->estimateBirthYear();

		# Make sure this user has a closest relative listed
		if (empty($this->data["closest_user_id"]) && $this->data["public_flag"] == 0) $this->userDescendants();

		//print_pre($this->data);
		return $this->id;
	}

	/**
	 * Select this Person from the database
	 */
	public function getPersonDeleted($person_id = 0)
	{
		if ($person_id > 0) $this->id = (int)$person_id;
		if (empty($this->id)) return false;

		# Query this person 1 second before they were deleted
		$sql = "SELECT DATE_SUB(max(actual_end_date), INTERVAL 1 SECOND) delete_date FROM tree_person p WHERE p.person_id = '$this->id' ";
		$data = $this->db->select( $sql );
		$delete_date = $data[0]["delete_date"];
		if (empty($delete_date)) return null;

		$found_id = $this->getPerson($this->id, $delete_date);
		if ($found_id > 0) {
			$this->time = $delete_date;
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

		$sql = "SELECT actual_start_date update_date FROM tree_person WHERE person_id = $this->id
				UNION
				SELECT actual_start_date update_date FROM tree_event WHERE key_id = $this->id AND table_type = 'P'
				ORDER BY update_date DESC";
		// I'm not sure which makes more sense here
		$sql = "SELECT c.update_date, u.user_id, u.given_name, u.family_name
				FROM (
					SELECT update_date, updated_by FROM tree_person WHERE person_id = $this->id
					UNION
					SELECT update_date, updated_by FROM tree_event WHERE key_id = $this->id AND table_type = 'P') c
				LEFT JOIN app_user u ON c.updated_by = u.user_id
				ORDER BY update_date DESC";
		//echo $sql;
		$data = $this->db->select( $sql );

		if (!$data) return false;
		return $data;
	}


	/**
	 * Return an array of changes made to this person
	 */
	public function getGedcom()
	{
		if (empty($this->id)) return false;

		$sql = "SELECT import_id, individual, gedcom_text FROM tree_person_gedcom WHERE person_id = $this->id";
		$data = $this->db->select( $sql );
		return $data;
	}


	/**
	 * Select all marriages for this person
	 */
	public function getMarriages($include_children=false)
	{
		global $user;
		if ($this->time) $time_sql = "'$this->time'";
		else $time_sql = "Now()";

		$sql = "SELECT f.family_id, f.status_code, p.person_id, p.family_name, p.given_name, p.title, 
					p.prefix, p.suffix, p.gender, p.bio_family_id, 1 as position, p.created_by, l.trace, 
					p.public_flag, r.permission trace_permission, r.description trace_meaning, 
					r.distance trace_distance, p.birth_year, 
					sp_dateformat(em.event_date) m_date, em.ad m_ad, em.location m_location,
					sp_dateformat(eb.event_date) b_date, eb.ad b_ad, eb.location b_location,
					sp_dateformat(ed.event_date) d_date, ed.ad d_ad, ed.location d_location
				FROM (
					SELECT family_id, actual_start_date, actual_end_date, person1_id person_id, person2_id spouse_id, status_code, notes 
					FROM tree_family WHERE ".actualClause("", $time_sql)." AND person1_id = '$this->id'
					UNION
					SELECT family_id, actual_start_date, actual_end_date, person2_id person_id, person1_id spouse_id, status_code, notes 
					FROM tree_family WHERE ".actualClause("", $time_sql)." AND person2_id = '$this->id'
				) f
				LEFT JOIN tree_person p ON f.spouse_id = p.person_id AND ".actualClause("p", $time_sql)."
				LEFT JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id = '$user->id'
				LEFT JOIN ref_relation r ON l.trace = r.trace
				LEFT JOIN tree_event em ON f.family_id = em.key_id AND em.table_type = 'F' AND em.event_type = 'MARR' AND ".actualClause("em", $time_sql)."
				LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb", $time_sql)."
				LEFT JOIN tree_event ed ON p.person_id = ed.key_id AND ed.table_type = 'P' AND ed.event_type = 'DEAT' AND ".actualClause("ed", $time_sql)."
				ORDER BY em.event_date ASC, p.family_name, p.given_name
				";
		//echo $sql;
		$data = $this->db->select( $sql );
		if (!$data) return false;
		
		## Check to see if the person can view this record
		foreach ($data as $key=>$value) {
			//print_pre($value);
			$view = false;
			if ($value["created_by"] == $user->id && is_logged_on() ) $view = true;
			if ($value["trace"] > "") $view = true;
			if ($value["public_flag"] == 1) $view = true;
			if (!$view) {
				unset($data[$key]);
				$data[$key]["family_id"] = $value["family_id"];
				$data[$key]["person_id"] = $value["person_id"];
				$data[$key]["status_code"] = $value["status_code"];
				$data[$key]["gender"] = $value["gender"];
				if ($value["person_id"] > 0) {
					$data[$key]["given_name"] = "LIVING";
				}
				$data[$key]["protected"] = 1;
				//return $data;
			}

			$data[$key]["full_name"] =
				$data[$key]["title"] ." ".
				$data[$key]["prefix"] ." ".
				$data[$key]["given_name"] ." ".
				$data[$key]["family_name"] ." ".
				$data[$key]["suffix"];
			$data[$key]["full_name"] = trim(str_replace("  ", " ", $data[$key]["full_name"]));
		}

		if ($include_children) {
			include_class("Family");
			$descendant_count = 0;
			$fam = new Family();
			$fam->time = $this->time;
			foreach ($data as $key=>$value) {
				$data[$key]['children'] = $fam->getChildren($value['family_id']);
				foreach($data[$key]['children'] as $child)
					$descendant_count += 1 + (int)$child["descendant_count"];

				/*
				// While we are here, let's make sure we have either a spouse or children, otherwise the marriage is useless
				if (empty($data[$key]['person_id']) && count($data[$key]['children']) == 0) {
					$f = new Family();
					$f->delete($data[$key]);
					unset($data[$key]);
				}
				*/
			}
			//print_pre($data);
			$this->saveCalc("descendant_count", $descendant_count);
		}

		$this->saveCalc("marriage_count", count($data));
		return $data;
	}

	/**
	 * Save the person record
	 * See also Family->save and Event->save
	 */
	public function save($data)
	{
		global $BUILDING_INDEX;
		if (empty($data)) return true;

		$data_fields = array("gender", "family_name", "given_name", "bio_family_id", "adopt_family_id", "child_order", "prefix", "suffix", "nickname", "title", "rin", "afn", "national_id", "national_origin", "occupation", "orig_family_name", "orig_given_name", "wikipedia");

		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Person.class.php";
		global $user, $sendmail;
		if ( empty($user->id) ) return false;
		if ( empty($this->id) ) $this->id = $data['person_id'];
		$db_time = getServerTime();
		$db_secs = strtotime($db_time); // we can't use PHP time() because it can vary from MySQL's Now()
		$db_time = "'$db_time'";
		$sendmail = false; // Don't send the email about changes unless something actually does

		if ($this->id > 0) {
			# Query the current database record to do see if we need to do an archive or a simple update
			$sql = "SELECT * FROM tree_person WHERE person_id = $this->id AND ". actualClause("", $db_time)." ORDER BY person_id";
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
			$elapsed_time = $db_secs - strtotime($current_row['actual_start_date']);
			if ($current_row['updated_by'] == $user->id && $elapsed_time < ARCHIVE_SECONDS) $archive = FALSE;
			$last_update_date = $current_row['actual_start_date'];
			# Don't archive if the times are the same
			# I'm not sure why the above $elapsed_time < ARCHIVE_SECONDS doesn't catch this case ???
			//if ($last_update_date == $db_time) $archive = FALSE;
			//echo "last_update_date = $last_update_date <br> elapsed_time = $elapsed_time <br> db_time = $db_time";
			//die();
		}
		$fields = "";
		$datachanged = FALSE;
		foreach ($data as $key=>$value) {
			// For security reasons, Only save data in the data_fields array
			if (in_array($key, $data_fields)) {
				$value = fixTick($value);
				if ($fields > "") $fields .= ", ";
				$fields .= "$key = '$value'";
				// See if SOME person data has changed, we need to do an update on person
				if ($value <> $current_row[$key]) $datachanged = TRUE;
			}
		}

		if ($this->id > 0) {
			if ($datachanged) {
				# Update the person record
				if ($archive) {
					# Make a copy of the record for audit reasons

					if ($this->db->sql_affectedrows() <> 1) throw new ThisException("Failed to update 1 and only 1 row for query: $sql");

					# Get all the fields in the SELECT * above and reinsert them
					# except for the array list below which will have different values
					foreach ($current_row as $colname=>$temp) {
						if (!in_array($colname, array("updated_by", "update_process", "update_date", "actual_start_date", "actual_end_date")) ) {
							$listarray[] = $colname;
						}
					}
					$field_list = implode(", ", $listarray);
					$sql = "INSERT INTO tree_person ($field_list, updated_by, update_process, update_date, actual_start_date, actual_end_date)
							SELECT $field_list, $user->id, '".$_GLOBAL['update_process']."',$db_time,$db_time,'". ARCHIVE_DATE ."'
							FROM tree_person WHERE person_id = $this->id AND actual_start_date = '$last_update_date' ";
					//echo $sql;
					//die();
					$this->db->sql_query( $sql );
					if ($this->db->sql_affectedrows() <> 1) throw new ThisException("Failed to update 1 and only 1 row for query: $sql");

					# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
					$sql = "UPDATE tree_person SET actual_end_date = $db_time WHERE person_id = $this->id AND actual_start_date = '$last_update_date' ";
					$this->db->sql_query( $sql );
					$sendemail = true; // notify watchers
				}
				# Now update the current record, regardless if we archived or not
				# This non-arching occurs if the same user is updating again within the threshhold (1 day)
				$sql = "UPDATE tree_person SET $fields WHERE person_id = $this->id AND ".actualClause("", $db_time);
				$this->db->sql_query( $sql );
			}
		} else {
			$sql = "INSERT INTO tree_person SET $fields, creation_date = $db_time, created_by = $user->id, update_date=$db_time, updated_by=$user->id, update_process='".$_GLOBAL['update_process']."', actual_start_date=$db_time, actual_end_date='". ARCHIVE_DATE ."' ";
			//echo $sql;
			$this->db->sql_query( $sql );
			$this->id = $this->db->sql_nextid();
			
			# Add the person calc table too
			$sql2 = "INSERT INTO tree_person_calc (person_id) VALUES ($this->id)";
			$this->db->sql_query( $sql2 );
		}

		$sql = "SELECT event_type, event_id FROM tree_event e
				WHERE key_id = '$this->id' AND e.table_type = 'P' AND ".actualClause("e", $time_sql);
		$temp = $this->db->select( $sql );
		$existing_events = array();
		foreach($temp as $value) {
			if ($value["event_id"]) $existing_events[$value["event_type"]] = $value["event_id"];
		}

		include_class("Event");
		if (count($data[e])) {
			foreach($data[e] as $key=>$eventdata) {
				$change = 0;
				foreach($eventdata as $colname=>$value) {
					# we have something to add or modify (skip the ad field)
					if ($value > "" && $colname <> "ad") $change++;
				}
				if ($existing_events[$key] > 0 || $change > 0) {
					// We may have something to add, modify or delete
					$eventdata['key_id'] = $this->id;
					$eventdata['table_type'] = 'P';
					$eventdata['event_type'] = $key;
					//print_pre($eventdata);
					Event::save($eventdata, $db_time);
				}
			}
			//die();
		}
		if ($BUILDING_INDEX) return $this->id;

		# Don't do this stuff if we are doing an import
		# But we need to do it AFTER the import is done
		$this->getPerson();
		$this->estimateBirthYear();
		$this->resetPublicFlag();
		if ($sendemail) Person::mailWatch($this->id);
		return $this->id;
	}

	/**
	 * Undo a previous Save to a person (usually undoing a merge)
	 *
	 * This difficult function removes a time slice starting at the "savedate"
	 * It finds the previous timeslice and repairs the actual_end_date
	 * Warning!! You CANNOT undo an undo.
	 */
	static public function unsave($person_id, $savedate)
	{
		# Basic setup first
		global $db;
		if ( empty($person_id) ) return false;
		if ( empty($savedate) ) return false;

		###########################################################################
		# Undo the saves to any events or marriages associated with this person
		include_class("Event");
		Event::unsave("P", $person_id, $savedate);

		# Find the record associated with this save date. Find all records
		# saved within +/- 60 seconds of the save and then sort them to get the closest one.
		# The reason we search around the date is because we could be off by a few seconds due
		# to a large process like a merge not processing all at once, but we should probably
		# just change that use a single date the whole time.
		$sql = "SELECT actual_start_date, actual_end_date, ABS(UNIX_TIMESTAMP('$savedate')-UNIX_TIMESTAMP(actual_start_date)) diff
						FROM tree_person
						WHERE person_id = $person_id AND actual_start_date BETWEEN '$savedate' - INTERVAL 1 MINUTE AND '$savedate' + INTERVAL 1 MINUTE
						ORDER BY diff ASC LIMIT 1";
		$baddata = $db->select( $sql );
		if (count($baddata) == 0) return false; //couldn't find any data to delete!!
		# reset the $savedate to the actual date the bad data was saved
		$savedate = $baddata[0]["actual_start_date"];

		# Find the previous record that was saved right before the bad data
		$sql = "SELECT actual_start_date, actual_end_date FROM tree_person
						WHERE person_id = $person_id AND actual_start_date < '$savedate'
						ORDER BY actual_start_date DESC LIMIT 1";
		$gooddata = $db->select( $sql );
		if (count($gooddata) == 0) return false; //You can only unsave an update! Use Person::delete to revert an insert

		# Delete the bad timeslice (remember this cannot be undone!!)
		$sql = "DELETE FROM tree_person WHERE person_id = $person_id AND actual_start_date = '$savedate'";
		$db->sql_query( $sql );

		# Now fix the good timeslice
		$sql = "UPDATE tree_person SET actual_end_date = '".$baddata[0]["actual_end_date"]."'
				WHERE person_id = $person_id AND actual_start_date = '".$gooddata[0]["actual_start_date"]."'";
		$db->sql_query( $sql );

		return true;
	}

	public function saveCalc($fieldname, $value) {
		if ($this->data[$fieldname] == $value) return;
		$this->data[$fieldname] = $value;
		$value = fixTick($value);
		$sql = "UPDATE tree_person_calc SET $fieldname = '$value', update_date = NOW() WHERE person_id = '$this->id'";
		//echo $sql;
		$this->db->sql_query( $sql );
	}

	/**
	 * Update just the child_order field (no archiving needed)
	 */
	static public function updateChildOrder($person_id, $child_order)
	{
		global $db;
		# convert to integers just in case
		$child_order = (int)$child_order;
		$person_id = (int)$person_id;
		$sql = "UPDATE tree_person SET child_order = '$child_order' WHERE person_id = '$person_id'";
		//echo $sql;
		$db->sql_query($sql);
	}

	public function delete($merged_into=0) {
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Person.class.php";
		global $user;
		if ( empty($user->id) ) return false;

		$db_time = "'".getServerTime()."'";

		include_class("Event");
		Event::delete("P", $this->id, $db_time);

		# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
		$sql = "UPDATE tree_person SET actual_end_date=$db_time, update_date=$db_time, updated_by=$user->id, update_process='".$_GLOBAL['update_process']."' WHERE person_id = $this->id AND actual_end_date > $db_time ";
		$this->db->sql_query( $sql );

		if ($merged_into > 0) {
			$sql = "UPDATE tree_person SET merged_into = $merged_into WHERE person_id = $this->id";
			$this->db->sql_query( $sql );
			//die($sql);
		}
		return true;
	}

	static public function restore($person_id) {
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Person.class.php";

		global $db, $user;
		if ( empty($user->id) ) return false;

		$db_time = "'".getServerTime()."'";

		$sql = "SELECT max(actual_end_date) delete_date, DATEDIFF(max(actual_end_date), NOW()) days_diff FROM tree_person p WHERE p.person_id = '$person_id' ";
		$data = $db->select( $sql );
		$delete_date = $data[0]["delete_date"];
		//if ($data[0]["days_diff"]
		if (empty($delete_date)) return false;

		include_class("Event");
		Event::restore("P", $person_id, $delete_date);

		# Update the last timeslice to have an actual_end_date of EOT
		$sql = "UPDATE tree_person SET actual_end_date='".ARCHIVE_DATE."', update_date=Now(), updated_by=$user->id, update_process='".$_GLOBAL['update_process']."' WHERE person_id = $person_id AND actual_end_date = '$delete_date' ";
		$db->sql_query( $sql );

		$sql = "UPDATE tree_person SET merged_into = null WHERE person_id = $person_id";
		$db->sql_query( $sql );
		return true;
	}

	# We will continue to calculate this value until we enter our own Birthday
	# It's slower when saving people bday's that we don't know
	# but it makes things more accurate on a continual basis
	public function estimateBirthYear() {
		global $BUILDING_INDEX;
		if ($BUILDING_INDEX) return;
		
		//print_pre($this->data);
		//echo $new_year;
		//die();
		//echo $this->data['birth_year'];
		## Calculate first based on my own birth date
		if (!empty($this->data['e']['CHR']['event_year'])) {
			$new_year = $this->data['e']['CHR']['event_year'];
			if ($this->data['e']['CHR']['ad'] == 0) $new_year = $new_year * -1; //This date is BC
		}
		if (!empty($this->data['e']['BIRT']['event_year'])) {
			$new_year = $this->data['e']['BIRT']['event_year'];
			if ($this->data['e']['BIRT']['ad'] == 0) $new_year = $new_year * -1; //This date is BC
		}

		## Calculate next based on my children's birthdates
		if (empty($new_year)) {
			// This should be done if 1 query, but I couldn't get Mysql to process it the indexes correctly
			// It runs 100x faster in two queries...go figure. (trevor 1/22/06)
			$sql = "SELECT family_id FROM tree_family f
					WHERE ".actualClause("f")." AND f.person1_id = $this->id
					UNION
					SELECT family_id FROM tree_family f
					WHERE ".actualClause("f")." AND f.person2_id = $this->id";
			$temp = $this->db->select($sql);
			$temp_id = $temp[0]["family_id"];
			if ($temp_id > 0) {
				$sql = "SELECT ROUND(AVG(p.birth_year)) birth_year FROM tree_person p
						WHERE p.bio_family_id = $temp_id AND p.birth_year IS NOT NULL AND ".actualClause("p");
				$data2 = $this->db->select($sql);
				if (!empty($data2[0]['birth_year'])) {
					$new_year = $data2[0]['birth_year'] - 30; // Parents are usually born 30 years BEFORE their children
					if ($death <> 0) {
						//echo "$this->id $new_year $death <br>";
						# we know the death year, so the new birth year should between 20 and 60 years before the death date
						if ($new_year > $death - 20) $new_year = $death - 20;
						if ($new_year < $death - 70) $new_year = $death - 70;
					}
				}
			}
		}
		//echo $new_year .$sql."<p>";

		## Calculate based on my spouses' birthdates last resort
		if (empty($new_year)) {
			$sql = "SELECT ROUND(AVG(birth_year)) birth_year FROM (
					SELECT p.birth_year FROM tree_family f
					JOIN tree_person p ON p.person_id = f.person1_id
					WHERE f.person2_id = '$this->id' AND p.birth_year IS NOT NULL
					  AND ".actualClause("p")." AND ".actualClause("f")."
					UNION
					SELECT p.birth_year FROM tree_family f
					JOIN tree_person p ON p.person_id = f.person2_id
					WHERE f.person1_id = '$this->id' AND p.birth_year IS NOT NULL
					  AND ".actualClause("p")." AND ".actualClause("f")."
				) t";
			$data2 = $this->db->select($sql);
			if (!empty($data2[0]['birth_year'])) {
				$new_year = $data2[0]['birth_year']; // Spouses are "usually" around the same age
			}
		}

		## Calculate based on my parents' birthdates
		if (empty($new_year)) {
			$sql = "SELECT p1.birth_year f_year, p2.birth_year m_year, ((p1.birth_year + p2.birth_year) / 2) avg_year
					FROM tree_family f
					LEFT JOIN tree_person p1 ON p1.person_id = f.person1_id AND ".actualClause("p1")."
					LEFT JOIN tree_person p2 ON p2.person_id = f.person2_id AND ".actualClause("p2")."
					WHERE f.family_id = '".$this->data['bio_family_id']."' AND ".actualClause("f");
			$data2 = $this->db->select($sql);
			unset($b);
			$b = $data2[0];
			# Mothers are in general a better gauge of children's ages because they can't have children later in their life
			if (!empty($b['f_year'])) $new_year = $b['f_year'] + 30;
			if (!empty($b['m_year'])) $new_year = $b['m_year'] + 30;
			if (!empty($b['avg_year'])) $new_year = $b['avg_year'] + 30;
		}

		## Figure out the death date
		$death = 0;
		if (!empty($this->data['e']['DEAT']['event_year']) && empty($this->data['e']['BIRT']['event_year'])) {
			$death = $this->data['e']['DEAT']['event_year'];
			if ($this->data['e']['DEAT']['ad'] == 0) $death = $death * -1; //This date is BC
		}

		# If I found a birth year, check to see if it matches what's in the db. If not, then update it.
		if ($new_year <> $this->data['birth_year']) {
			//echo "updating birth year";
			# update ALL timeslices, not just the current one
			$sql = "UPDATE tree_person SET birth_year = '$new_year' WHERE person_id = $this->id";
			$this->db->sql_query( $sql );
			$this->data['birth_year'] = $new_year;
		}
		# Estimate year of death
		$bdate = $this->data['e']['BIRT']['raw_date'];
		if(empty($bdate)) $bdate = $this->data['e']['CHR']['raw_date'];
		if(empty($bdate)) $bdate = '00-00-'.$this->data['birth_year'];
		$byear = substr($bdate,0,4) + substr($bdate,5,2)/12 + substr($bdate,8,2)/365;
		$ddate = $this->data['e']['DEAT']['raw_date'];
		if(empty($ddate)) $ddate = $this->data['e']['BURI']['raw_date'];
		if(empty($ddate)) $ddate = ($this->data['birth_year']+99).'00-00';
		$dyear = substr($ddate,0,4) + substr($ddate,5,2)/12 + substr($ddate,8,2)/365;
		$now = date("Y") + date("z")/365;
		if ($dyear > $now) $dyear = $now;

		$new_age = floor($dyear - $byear);
		$this->saveCalc("age", $new_age);
		
		$this->resetPublicFlag();
	}

	public function resetPublicFlag() {
		global $BUILDING_INDEX;
		if ($BUILDING_INDEX) return;
		$flag = 0;
		if (!empty($this->data['e']['DEAT']['event_date'])) $flag = 1; // They are dead
		if ($this->data['birth_year'] < 1906) $flag = 1; // They are old (possibly dead)
		if ($this->data['public_flag'] <> $flag) {
			# The flag doesn't represent what's in the database
			$sql = "UPDATE tree_person SET public_flag = '$flag' WHERE person_id = $this->id";
			$this->db->sql_query( $sql );
			$this->data['public_flag'] = $flag;
		}
	}
	
	public function updateMergeRank() {
		global $BUILDING_INDEX;
		if ($BUILDING_INDEX) return;
		if ( empty($this->id) ) return false;


		$sql = "SELECT count(*) rank FROM (
				SELECT updated_by FROM tree_person WHERE person_id = $this->id
				UNION
				SELECT updated_by FROM tree_event WHERE table_type = 'P' AND key_id = $this->id
				UNION
				SELECT updated_by FROM tree_person WHERE merged_into = $this->id
				UNION
				SELECT created_by FROM tree_person WHERE merged_into = $this->id
			) c";
		//echo $sql;
		$data = $this->db->select( $sql );
		$rank = $data[0]['rank'] - 1;
		if ($rank <> $this->data["merge_rank"])
			$this->saveCalc("merge_rank", $rank);
	}

	public function transfer($to_person) {
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Person.class.php";
		global $user;
		if ( empty($user->id) ) return false;

		# Update marriages from $this->id to $to_user
		include_class("Family");
		Family::mergeMarriages($this->id, $to_person);

		# Update messages from $this->id to $to_user
		// TODO, add discuss_post.merged_from
		$sql = "UPDATE discuss_post SET merged_from = $this->id, person_id = $to_person WHERE person_id = $this->id";
		$sql = "UPDATE discuss_post SET person_id = $to_person WHERE person_id = $this->id";
		$this->db->sql_query( $sql );

		# If this person has a user_id pointing to them be sure to move them
		$sql = "UPDATE app_user SET person_id = $to_person WHERE person_id = $this->id";
		$this->db->sql_query( $sql );

		# Move the view log (ignore dupes)
		$sql = "UPDATE IGNORE app_recent_view SET person_id = $to_person WHERE person_id = $this->id";
		$this->db->sql_query( $sql );

		# Copy watches/bookmarks (ignore dupes)
		$sql = "UPDATE IGNORE app_watch SET person_id = $to_person WHERE person_id = $this->id";
		$sql = "INSERT INTO app_watch (person_id, user_id)
					SELECT $to_person, user_id FROM app_watch
					WHERE person_id = $this->id AND user_id NOT IN (SELECT user_id FROM app_watch WHERE person_id = $to_person)";
		$this->db->sql_query( $sql );

		# Don't MOVE !!
		# * tree_person_gedcom (they can use history to view other people's gedcom records)
		# * app_user_line_person (easier to just recalculate all at once or ad hoc)
		return true;
	}

	public function extendTree() {
		# each statement pair could be combined, however, MySQL seems to run too slowly
		# spliting these into 2 increases the performance dramatically

		$new = 0;
		# Add spouses from spouses
		$new += $this->extendTreeSQL("S", $this->id, "JOIN tree_family f ON l.person_id = f.person1_id AND f.person2_id = $this->id AND ".actualClause());
		$new += $this->extendTreeSQL("S", $this->id, "JOIN tree_family f ON l.person_id = f.person2_id AND f.person1_id = $this->id AND ".actualClause());
		# Add children from parents
		$family_id = $this->data["bio_family_id"];
		if ($family_id > 0) {
			$new += $this->extendTreeSQL("C", $family_id, "JOIN tree_family f ON l.person_id = f.person2_id AND f.family_id = $family_id AND ".actualClause());
			$new += $this->extendTreeSQL("C", $family_id, "JOIN tree_family f ON l.person_id = f.person1_id AND f.family_id = $family_id AND ".actualClause());
		}
		# Add parents from children
		$person_sql = "JOIN tree_person p ON l.person_id = p.person_id AND ".actualClause("p")."
				JOIN tree_family f ON p.bio_family_id = f.family_id AND ".actualClause("f");
		$new += $this->extendTreeSQL("P", $this->id, "$person_sql AND f.person1_id = $this->id");
		$new += $this->extendTreeSQL("P", $this->id, "$person_sql AND f.person2_id = $this->id");
		return $new;
	}
	private function extendTreeSQL($trace, $thru_id, $join) {
		// We should figure out a way to calculate the distance in PHP using traceDistance function
		$sql = "INSERT INTO app_user_line_person (user_id, person_id, thru_id, trace, distance, view_flag) ";
		$sql .= "SELECT DISTINCT l.user_id, $this->id, $thru_id, r.trace, 9999999999999, 1
			FROM app_user_line_person l
			$join
			JOIN ref_relation r ON r.trace = CONCAT(l.trace,'$trace')
			WHERE l.user_id NOT IN (SELECT user_id FROM app_user_line_person WHERE person_id = $this->id)";
		//echo $sql;
		//$data = $this->db->select($sql);
		//print_pre($data);
		$this->db->sql_query( $sql );
		return $this->db->sql_affectedrows();
	}

	/**
	 * Record the fact that someone viewed this person's record
	 */
	public function recordView()
	{
		global $user, $track_hit;
		if (!$track_hit) return; // hit tracking is turned off
		$uid = ($user->id > 0) ? $user->id : 3; // Record who viewed this person or say they were anonymous (id=3)
		$sql = "INSERT INTO app_recent_view (user_id, person_id, last_update_date) VALUES ($uid, $this->id, Now() )	 ON DUPLICATE KEY UPDATE last_update_date=Now() ";
		$this->db->sql_query( $sql );

		# Increase the page views for this person
		$sql = "UPDATE tree_person_calc SET page_views = page_views + 1
				WHERE person_id = $this->id";
		$this->db->sql_query( $sql );
	}
	/**
	 * Get the list of all people who have seen this record
	 */
	public function listViews()
	{
		$sql = "SELECT user_id, u.given_name, u.family_name, v.last_update_date FROM app_recent_view v
				JOIN app_user u USING(user_id) WHERE v.person_id = '$this->id' ORDER BY v.last_update_date DESC LIMIT 0, 10";
		//echo $sql;
		return $this->db->select( $sql );
	}

	/**
	 * List the PUBLIC family names by total
	 */
	static public function listFamiliesByLetter($letter)
	{
		global $db;
		$sql = "SELECT family_name, count(*) total
				FROM tree_person p
				WHERE p.public_flag = 1 AND p.update_date <= Now() AND ".actualClause("p")." AND family_name like '$letter%'
				GROUP BY family_name HAVING count(*) >= 3";
		//echo $sql;
		$data = $db->select( $sql );

		if (!$data) return false;
		return $data;
	}

	/**
	 * Create an index of last names for PUBLIC people
	 * We eventually will want to just list the alphabet I think
	 */
	static public function listPersonIndex($width=1)
	{
		global $db;
		$sql = "SELECT SUBSTRING(family_name,1,$width) as value, count(*) as total
				FROM tree_person p WHERE p.public_flag = 1 AND family_name like '_%' AND ".actualClause("p")."
				GROUP BY value HAVING count(*) > 15";
		$data = $db->select( $sql );

		if (!$data) return false;
		return $data;
	}

	static public function countPeople($where="")
	{
		global $db;
		$sql = "SELECT count(DISTINCT person_id) as total FROM tree_person WHERE 1=1 $where";
		$data = $db->select($sql);
		return $data[0]['total'];
	}

	public function listContributors() {
		if (empty($this->id)) return array();
		$sql = "SELECT created_by FROM tree_person WHERE person_id = $this->id
			UNION
			SELECT updated_by FROM tree_person WHERE person_id = $this->id
			UNION
			SELECT updated_by FROM tree_event WHERE table_type = 'P' AND key_id = $this->id
			UNION
			SELECT created_by FROM tree_image WHERE person_id = $this->id
			UNION
			SELECT updated_by FROM tree_image WHERE person_id = $this->id
			UNION
			SELECT updated_by FROM discuss_wiki WHERE person_id = $this->id
			UNION
			SELECT updated_by FROM discuss_post WHERE person_id = $this->id
			UNION
			SELECT user_id FROM app_watch WHERE person_id = $this->id
			";
		//echo $sql;
		$data = $this->db->select($sql);
		$to = array();
		foreach($data as $row) $to[$row["created_by"]] = 1;
		return $to;
	}

	public function findMatches() {
		$sql = "SELECT p.person_id, p.family_name, p.given_name, p.birth_year FROM tree_person p
				JOIN app_merge m ON p.person_id = m.person_to_id
				WHERE m.person_from_id = $this->id AND status_code IN ('P','Q') AND ".actualClause("p")."
				UNION
				SELECT p.person_id, p.family_name, p.given_name, p.birth_year FROM tree_person p
				JOIN app_merge m ON p.person_id = m.person_from_id
				WHERE m.person_to_id = $this->id AND status_code IN ('P','Q') AND ".actualClause("p");
		//echo $sql;
		$data = $this->db->select($sql);
		$match = $data;
		return $match;
	}

	static public function recentViewed($limit=25)
	{
		global $db, $user;
		if (empty($user->id)) return array();
		$sql = "SELECT p.person_id, p.family_name, p.given_name, v.last_update_date
				FROM app_recent_view v JOIN tree_person p ON v.person_id = p.person_id AND ".actualClause("p")."
				WHERE v.user_id='$user->id' ORDER BY v.last_update_date DESC LIMIT 0 , $limit";
		return $db->select($sql);
	}

	static public function recentChange($limit=25)
	{
		global $db, $user;
		if (empty($user->id)) return array();

		$sql = "SELECT p.person_id, p.family_name, p.given_name, p.actual_start_date, u.user_id, CONCAT(u.given_name, ' ', u.family_name) user_name, l.trace
				FROM tree_person p
				JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id = '$user->id'
				LEFT JOIN app_user u ON p.updated_by = u.user_id
				WHERE ".actualClause("p")." ORDER BY actual_start_date DESC LIMIT 0 , $limit";
		//echo $sql;
		return $db->select($sql);
	}

	static public function relatives($person_id, $trace="") {
		global $db, $user;
		$person_id = (int)$person_id;
		if (empty($person_id)) return array();
		if (empty($user->id)) return array();
		$trace = fixTick($trace);
		if ($trace > "") $where = "AND trace like '$trace%'";
		$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year, l.trace, l.thru_id, eb.location
				FROM app_user_line_person l
				JOIN tree_person p ON l.person_id = p.person_id AND ".actualClause("p")."
				LEFT JOIN tree_event eb ON eb.key_id = p.person_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb")."
				WHERE l.user_id = '$user->id' $where ORDER BY distance LIMIT 0, 500";
		return $db->select($sql);
	}

	static public function closestUser($user_id) {
		global $db;

		$id = (int)$user_id;
		$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year
			FROM tree_person p
			JOIN tree_person_calc pc ON p.person_id = pc.person_id
			WHERE pc.closest_user_id = '$id' AND ".actualClause()." ORDER BY p.family_name, p.given_name";
		$data = $db->select($sql);
		return $data;
	}

	public function userDescendants() {
		if (empty($this->id)) return false;
		$sql = "SELECT u.user_id, u.given_name, u.family_name, r.reverse trace, r.trace trace_code
				FROM app_user_line_person l
				JOIN app_user u ON l.user_id = u.user_id
				LEFT JOIN ref_relation r ON l.trace = r.trace
				WHERE l.person_id = '$this->id' ORDER BY r.distance, last_visit_date DESC LIMIT 0, 25";
		$data = $this->db->select($sql);
		if ($this->data["public_flag"] == 0) {
			$this->saveCalc('closest_user_id', $data[0]["user_id"]);
		}
		return $data;
	}

	static public function getDescendants($people, $depth=-1) {
		if (empty($people) || $depth == 0) return $people;

		global $db;
		$depth = $depth - 1;
		$people_sql = implode(",", $people);
		$sql = "SELECT person_id, given_name, family_name
				FROM tree_person p
				WHERE person_id NOT IN ($people_sql)
				AND ".actualClause("p")." AND bio_family_id IN (
					SELECT family_id FROM tree_family f
					WHERE ".actualClause("f")."
					AND ( person1_id IN ($people_sql) OR person2_id IN ($people_sql) )
				)";
		$data = $db->select($sql);
		$newpeople = array();
		foreach ($data as $value) {
			$newpeople[] = $value['person_id'];
		}
		$descendants = Person::getDescendants($newpeople, $depth);
		foreach ($descendants as $value) {
			$people[] = $value;
		}
		return $people;
	}

	static public function resetPublicFlags() {
		global $db;
		$output = "";
		
		# Make sure all people have a person_calc table
		$sql = "INSERT INTO tree_person_calc (person_id)
				SELECT DISTINCT person_id FROM tree_person
				WHERE person_id NOT IN (SELECT person_id FROM tree_person_calc)";
		$db->sql_query($sql);
		
		# Make sure everything is set to 0 or 1
		$sql = "UPDATE tree_person p SET public_flag = 0
				WHERE public_flag IS NULL OR public_flag NOT IN (0,1)";
		$db->sql_query($sql);
		$count = $db->sql_affectedrows();
		$output .= "Set $count flags to 0 that were not set to 0 or 1 already";
		
		# Place all living people born after 1906 into the LIVING domain
		$sql = "UPDATE tree_person p SET public_flag = 0
				WHERE public_flag = 1 AND p.birth_year >= 1906 AND p.person_id NOT IN (
					SELECT key_id FROM tree_event
					WHERE table_type = 'P' AND event_type = 'DEAT'
					AND event_date IS NOT NULL AND ". actualClause()."
				)";
		$db->sql_query($sql);
		$count = $db->sql_affectedrows();
		$output .= "\nSet $count people born after 1906 into the LIVING domain";
		
		# Place all people born before 1906 into public domain
		$sql = "UPDATE tree_person p SET public_flag = 1
				WHERE public_flag = 0 AND p.birth_year < 1906";
		$db->sql_query($sql);
		$count = $db->sql_affectedrows();
		$output .= "\nSet $count people born before 1906 into the public domain";
		
		# Place all dead people into the public domain
		$sql = "UPDATE tree_person p SET public_flag = 1
				WHERE public_flag = 0 AND p.person_id IN (
					SELECT key_id FROM tree_event
					WHERE table_type = 'P' AND event_type = 'DEAT'
					AND event_date IS NOT NULL AND ". actualClause()."
				)";
		$db->sql_query($sql);
		$count = $db->sql_affectedrows();
		$output .= "\nSet $count dead people into the public domain";
		
		return $output;
	}

	/**
	 * Update all birth_year fields that haven't already been set
	 */
	static public function updateBirthYears() {
		global $db;
		$sql = "UPDATE tree_person p, tree_event e
				SET p.birth_year = YEAR(e.event_date) * -1
				WHERE (p.birth_year IS NULL OR p.birth_year = 0) AND YEAR(e.event_date) IS NOT NULL
				AND p.person_id = e.key_id AND e.table_type = 'P' AND e.event_type = 'BIRT' AND e.ad = 0
				AND ".actualClause("e");
		$db->sql_query($sql);
		//echo "$sql<p>set year based on valid birth event</p>";

		$sql = "UPDATE tree_person p, tree_event e
				SET p.birth_year = YEAR(e.event_date)
				WHERE (p.birth_year IS NULL OR p.birth_year = 0) AND YEAR(e.event_date) IS NOT NULL
				AND p.person_id = e.key_id AND e.table_type = 'P' AND e.event_type = 'BIRT' AND e.ad = 1
				AND ".actualClause("e");
		$db->sql_query($sql);
		//echo "$sql<p>set year based on valid birth event</p>";
	}

	static public function mailWatch($person_id) {
		global $db, $user;
		$person_id = (int)$person_id;

		$sql = "SELECT w.watch_id, p.person_id, p.family_name, p.given_name, p.title, p.birth_year, u.email, merge_names(u.given_name, u.family_name) username
			FROM app_watch w
			JOIN tree_person p ON w.person_id = p.person_id AND ".actualClause()."
			JOIN app_user u ON w.user_id = u.user_id
			WHERE w.person_id = '$person_id' AND w.user_id <> '$user->id'";
		$data = $db->select($sql);
		if (count($data) > 0) {
			$row = $data[0];
			$full_name = $row["title"]." ".$row["given_name"]." ".$row["family_name"]." (".$row["birth_year"].")";
			$subject = "$full_name has been modified";
			$bodytemplate = "The following individual, $full_name has recently been modified on SharedTree by ".$user->data["given_name"]." ".$user->data["family_name"].". You have asked to be notified whenever this genealogy record has been modified. You can view more details about this change by going to http://www.sharedtree.com/person/".$row["person_id"]."

If you would like to stop receiving these notifications, then login and remove this person from your SharedTree Watchlist by clicking http://www.sharedtree.com/watch.php?action=unwatch&watch_id=WATCH_ID";
			foreach($data as $row) {
				$body = str_replace("WATCH_ID", $row["watch_id"], $bodytemplate);
				//echo "sending email to ".$row['email'];
				SendEmail($row["email"], "SharedTree <noreply@server.sharedtree.com>", $subject, $body);
			}
		}
	}
}
?>
