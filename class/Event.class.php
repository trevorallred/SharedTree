<?
include_class("DateParser");

class Event
{

	/**
	 * Save this Event into the database
	 */
	static public function save($data, $db_time=NULL)
	{
		# List of fields in the tree_event table we need to update
		# Manually build "event_date" & "ad"

		$data_fields = array("date_approx", "age_at_event", "status", "temple_code", "location", "location_id", "notes", "source");

		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Event.class.php";
		global $db, $user, $sendmail;
		if ( empty($user->id) ) return false;

		if (empty($db_time)) $db_time = "'".getServerTime()."'";
		##################################################################
		# Get the current row
		$row = array(); // Assume we don't query anything
		$event_id = 0;
		if ($data['key_id'] > 0 && $data['table_type'] > "" && $data['event_type'] > "") {
			# Read the current event to see what we need to do
			# We have to have atleast the 3 pieces of the event to save, event_id doesn't really matter

			$where_array[] = "key_id = '".$data['key_id']."'";
			$where_array[] = "table_type = '".$data['table_type']."'";
			$where_array[] = "event_type = '".$data['event_type']."'";
			$where = implode(" AND ", $where_array);
			$field_key = implode(", ", $where_array);

			# Query the current database record to do see if we need to do an insert, archive or a simple update
			$sql = "SELECT e.*, IFNULL(DATE_FORMAT(event_date, '%d %b %Y'), IFNULL(DATE_FORMAT(event_date, '%b %Y'), DATE_FORMAT(event_date, '%Y'))) formatted_date
					FROM tree_event e WHERE $where AND ". actualClause("", $db_time)." ORDER BY event_id";

			$row = $db->select($sql);

			if (count($row) > 1) {
				$sql = "DELETE FROM tree_event WHERE $where AND event_id > ".$row[0]['event_id']." AND ". actualClause("", $db_time);
				$db->sql_query($sql);
				echo "Warning: fixing overlapping duplicate event data";
			}

			$current_row = $row[0];
			if (count($current_row) > 0) {
				$event_id = $current_row['event_id'];
	
				$archive = TRUE;
				# If the same person is trying to edit again and the change was recent, then don't bother archiving
				$elapsed_time = time() - strtotime($current_row['actual_start_date']);
				if ($current_row['updated_by'] == $user->id && $elapsed_time < ARCHIVE_SECONDS) $archive = FALSE;
			} else $archive = FALSE;
		} else {
			# What do we do if we don't get have enough info to query?
			echo __FILE__ ." ". __LINE__ . " failed to get/save event because we didn't have enough information";
			print_pre($data);
			echo "<br>";
			return false;
		}

		##################################################################
		# Get the list of fields we need to save
		$fields = "";
		$datachanged = FALSE;
		# Manually build the event_date and ad fields
		if (!isset($data["event_date"]) && isset($data["yyyy"])) {
			# we are getting the date in [dd] [mon] [yyyy] format instead of [event_date]
			$data["event_date"] = trim($data["dd"]." ".$data["mon"]." ".$data["yyyy"]);
		}

		if (isset($data['event_date'])) {
			# always save the raw text of the data here
			# this is for debugging purposes since dates are so tricky
			$data['date_text'] = $data['event_date'];
			$fields .= "date_text = '".fixTick($data['event_date'])."', ";

			if (empty($data['event_date']) || $data['event_date'] == "0000") {
				# We don't seem to have a date so set everything to null
				$data['event_date'] = null;
				$data['ad'] = null;
				$fields .= "event_date = NULL, ad = NULL";
			} else {
				if (!isset($data['ad']) || $data['ad'] <> 0) $data['ad'] = 1;
				DateParser::getEstimate($data['event_date'], $data['date_approx']);
				$data['event_date'] = DateParser::parseDate($data['event_date']);
				if ($data['event_date'] == "") {
					# Oops, we couldn't format the date! :( don't save a bad date
					# we'll have to use the date_text to figure this out and make parseDateFormat better
					$data['event_date'] = null;
					$data['ad'] = null;
					$data['date_approx'] = null;
					$fields .= "event_date = NULL, ad = NULL";
				} else {
					# Great, we have a good date, let's try and save it now
					$fields .= "event_date = '".$data['event_date']."', ad = '".$data['ad']."'";
				}
			}
			if ($data['event_date'] <> $current_row['event_date']) $datachanged = TRUE;
			if ($data['ad'] <> $current_row['ad']) $datachanged = TRUE;
		}
		//echo $fields;

		foreach ($data as $key=>$value) {
			// For security reasons, Only save data in the data_fields array
			if (in_array($key, $data_fields)) {
				$value = fixTick($value);
				if ($key == "location") {
					// Convert Place,Place to Place, Place
					$value = str_replace(", ", ",", $value);
					$value = str_replace(",", ", ", $value);
				}
				if ($fields > "") $fields .= ", ";
				$fields .= "$key = '$value'";
				// See if SOME person data has changed, we need to do an update on person
				if ($value <> $current_row[$key]) $datachanged = TRUE;
			}
		}
		if (!$datachanged) return $event_id;
		# We don't need to continue like we do with Person.class


		##################################################################
		if ($event_id == 0) {
			# There are no records found for this event, just insert a new one now
			$sql = "INSERT INTO tree_event
					SET $field_key, $fields,
						update_date=$db_time, updated_by=$user->id, update_process='".$_GLOBAL['update_process']."',
						actual_start_date=$db_time, actual_end_date='". ARCHIVE_DATE ."' ";
			//echo $sql;
			$db->sql_query( $sql );
			$sendmail = true; // We've made a change so flag this as needing to send an email to watchers
			return true;
		}

		##################################################################
		# We have an existing record, update or archive it now
		if ($archive) {
			# Make a copy of the record for audit reasons
			$last_update_date = $current_row['actual_start_date'];

			if ($db->sql_affectedrows() <> 1) throw new ThisException("Failed to update 1 and only 1 row for query: $sql");

			# Get all the fields in the SELECT * above and reinsert them
			# except for the array list below which will have different values
			foreach ($current_row as $colname=>$temp) {
				if (!in_array($colname, array("updated_by", "update_process", "update_date", "actual_start_date", "actual_end_date", "formatted_date")) ) {
					$listarray[] = $colname;
				}
			}
			$field_list = implode(", ", $listarray);

			$sql = "INSERT INTO tree_event ($field_list, updated_by, update_process, update_date, actual_start_date, actual_end_date)
					SELECT $field_list, $user->id, '".$_GLOBAL['update_process']."', $db_time, $db_time, '". ARCHIVE_DATE ."'
					FROM tree_event WHERE event_id = $event_id AND actual_start_date = '$last_update_date' ";
			//echo $sql;
			$db->sql_query( $sql );
			if ($db->sql_affectedrows() <> 1) throw new ThisException("Failed to update 1 and only 1 row for query: $sql");

			# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
			$sql = "UPDATE tree_event SET actual_end_date = $db_time WHERE event_id = $event_id AND actual_start_date = '$last_update_date' ";
			//echo $sql;
			$db->sql_query( $sql );
			$sendmail = true; // We've made a change so flag this as needing to send an email to watchers
		}
		# Update the current record now
		$sql = "UPDATE tree_event SET $fields WHERE event_id = $event_id AND ".actualClause("", $db_time);
		//echo $sql;
		$db->sql_query( $sql );
		# We don't care about EventIDs so just skip it and move on
		return true;
	}

	/**
	 * Undo a previous Save to a family (usually undoing a merge)
	 *
	 * This is almost identical to Person::unsave
	 * except this finds all possible events and deletes any of them
	 * that have changes (updates or insert) corresponding with this merge
	 *
	 * This is probably too liberal...we always save events with the exact same datetime as the person/family
	 * so we shouldn't need to search around the 1 min mark for all events
	 */
	static public function unsave($type, $id, $savedate)
	{
		# Basic setup first
		global $db;
		if ( empty($id) ) return false;
		if ( empty($savedate) ) return false;

		# Get a list of event_types that this entity has
		$sql = "SELECT DISTINCT event_type FROM tree_event WHERE table_type = '$type' AND key_id = $id";
		$events = $db->select( $sql );

		$origdate = $savedate;
		foreach ($events as $event) {
			# Combing all three key parts into a single statement (easier to reference later)
			$event_where = "table_type = '$type' AND key_id = $id AND event_type = '$event[event_type]'";

			# Find the record associated with this save date. Find all records
			# saved within +/- 60 seconds of the save and then sort them to get the closest one.
			# The reason we search around the date is because we could be off by a few seconds due
			# to a large process like a merge not processing all at once, but we should probably
			# just change that use a single date the whole time.
			$sql = "SELECT actual_start_date, actual_end_date, ABS(UNIX_TIMESTAMP('$savedate')-UNIX_TIMESTAMP(actual_start_date)) diff
							FROM tree_event
							WHERE $event_where AND actual_start_date BETWEEN '$savedate' - INTERVAL 1 MINUTE AND '$savedate' + INTERVAL 1 MINUTE
							ORDER BY diff ASC LIMIT 1";
			$baddata = $db->select( $sql );
			if (count($baddata) > 0) {
				# reset the $savedate to the actual date the bad data was saved
				$savedate = $baddata[0]["actual_start_date"];
				# Delete the bad timeslice (remember this cannot be undone!!)
				$sql = "DELETE FROM tree_event WHERE $event_where AND actual_start_date = '$savedate'";
				$db->sql_query( $sql );

				# Find the previous record that was saved right before the bad data
				$sql = "SELECT actual_start_date, actual_end_date FROM tree_event
								WHERE person_id = $person_id AND actual_start_date < '$savedate'
								ORDER BY actual_start_date DESC LIMIT 1";
				$gooddata = $db->select( $sql );
				if (isset($gooddata[0]["actual_start_date"])) {
					# Fix the good timeslice
					$sql = "UPDATE tree_event SET actual_end_date = '".$baddata[0]["actual_end_date"]."'
							WHERE $event_where AND actual_start_date = '".$gooddata[0]["actual_start_date"]."'";
					$db->sql_query( $sql );
				}
			}
		}
		return true;
	}

	static public function delete($table_type, $key, $db_time="Now()")
	{
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Event.class.php";
		global $db, $user;

		//echo $sql;
		# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
		$sql = "UPDATE tree_event SET actual_end_date=$db_time, update_date=$db_time, updated_by=$user->id, update_process='".$_GLOBAL['update_process']."'
				WHERE key_id = $key AND table_type = '$table_type' AND actual_end_date > $db_time ";
		$db->sql_query( $sql );
		return true;
	}

	static public function deleteByEvent($event_id)
	{
		$event_id = (int)$event_id;
		if (empty($event_id)) return false;

		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Event.class.php";
		global $db, $user;

		# Update current timeslice(s) to have an archive_date = Now()
		$sql = "UPDATE tree_event SET actual_end_date=Now(), update_date=Now(), updated_by=$user->id, update_process='".$_GLOBAL['update_process']."' WHERE event_id = $event_id AND actual_end_date > Now() ";
		$db->sql_query( $sql );
		return true;
	}

	static public function restore($table_type, $key, $db_time)
	{
		if ( empty($_GLOBAL['update_process']) ) $_GLOBAL['update_process'] = "Event.class.php";
		global $db, $user;

		# Update the timeslices ending on the delete datetime to have an actual_end_date of EOT
		$sql = "UPDATE tree_event SET actual_end_date='".ARCHIVE_DATE."', update_date=Now(), updated_by=$user->id, update_process='".$_GLOBAL['update_process']."'
						WHERE key_id = $key AND table_type = '$table_type' AND actual_end_date = '$db_time' ";
		$db->sql_query( $sql );
		return true;
	}

	static public function GedcomCodes($table_type)
	{
		global $db;

		//echo $sql;
		# Update the previous timeslice to have an archive_date the same as the new time slice that starts right now (db_time)
		$sql = "SELECT gedcom_code, default_flag, lds_flag, avg_age, prompt, description
				FROM ref_gedcom_codes WHERE table_type = '$table_type' ORDER BY seq, default_flag DESC, prompt";
		$data = $db->select( $sql );
		return $data;
	}
}

?>
