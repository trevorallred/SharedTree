<?
class Watch
{
	static public function get($watch_id)
	{
		global $db;
		$watch_id = (int)$watch_id;
		if ( $watch_id > 0 ) {
			$sql = "SELECT * FROM app_watch WHERE watch_id = $watch_id";
			$data = $db->select( $sql );
			if (count($data) <> 1) return false;
			return $data[0];
		}
		return false;
	}

	/**
	 * Save this Watch into the database
	 */
	static public function save($data)
	{
		global $db, $user;
		if ( empty($user->id) ) return false;
		$watch_id = (int)$data["watch_id"];
		
		##################################################################
		$set = "SET user_id = $user->id";
		$person_id = (int)$data["person_id"];
		if ($person_id > 0) $set .= ", person_id = $person_id";
		if (isset($data["bookmark"])) {
			$set .= ", bookmark = ". (($data["bookmark"]==1)?"1":"0");
		}
		if (isset($data["show_watch"])) {
			$set .= ", show_watch = ". (($data["show_watch"]==1)?"1":"0");
		}

		if ($watch_id > 0) {
			//if (!$person_id > 0) return Watch::delete($watch_id);
			# UPDATE app_watch
			$sql = "UPDATE app_watch $set WHERE watch_id = $watch_id";
		} else {
			if (!$person_id > 0) return false;
			# ignore duplicates
			$sql = "INSERT IGNORE app_watch $set";
		}
		//echo $sql;
		return $db->sql_query( $sql );
	}

	static public function delete($watch_id)
	{
		global $db;
		$watch_id = (int)$watch_id;
		if ( $watch_id > 0 ) {
			$sql = "DELETE FROM app_watch WHERE watch_id = $watch_id";
			return $db->sql_query( $sql );
		}
		return false;
	}

	static public function watchPerson($person_id, $add=true)
	{
		global $db, $user;
		$person_id = (int)$person_id;
		if ( $person_id > 0 && $user->id > 0) {
			if ($add) {
				return Watch::save(array("person_id"=>$person_id));
			} else {
				$sql = "DELETE FROM app_watch WHERE person_id = '$person_id' AND user_id = '$user->id'";
				return $db->sql_query( $sql );
			}
		}
		return true;
	}

	static public function listUserBookmark($user_id=0)
	{
		global $db, $user;
		$user_id = (int)$user_id;
		if (empty($user_id)) $user_id = $user->id;

		$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year 
				FROM app_watch w
				JOIN tree_person p ON w.person_id = p.person_id AND ". actualClause("p") ."
				WHERE w.user_id = $user_id AND w.bookmark = 1
				ORDER BY p.family_name, p.given_name
				LIMIT 0, 20";
		return $db->select( $sql );
	}

	static public function listUserWatch($user_id)
	{
		global $db;
		$user_id = (int)$user_id;
		if ( $user_id > 0 ) {
			$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year, w.watch_id, w.bookmark, w.show_watch
					FROM app_watch w
					JOIN tree_person p ON w.person_id = p.person_id AND ". actualClause("p") ."
					WHERE w.user_id = $user_id
					ORDER BY w.bookmark DESC, p.family_name, p.given_name";
			return $db->select( $sql );
		}
		return false;
	}

	static public function listPersonWatch($person_id)
	{
		global $db;
		$person_id = (int)$person_id;
		if ( $person_id > 0 ) {
			$sql = "SELECT u.user_id, u.given_name, u.family_name
					FROM app_watch w JOIN app_user u ON user_id = w.user_id
					WHERE person_id = $person_id";
			return $db->select( $sql );
		}
		return false;
	}
}
?>
