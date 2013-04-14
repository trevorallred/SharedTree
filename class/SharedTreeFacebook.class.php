<?

class SharedTreeFacebook
{

	/**
	 * Select this Group from the database
	 */
	static public function getAncestors($uid)
	{
		global $db;

		$uid = fixTick($uid);
		if (empty($uid)) return false;

		$sql = "SELECT f.watch_id, p.person_id, p.given_name, p.family_name, p.birth_year
				FROM app_facebook f 
				JOIN tree_person p ON f.person_id = p.person_id AND ".actualClause("p")."
				WHERE f.user_id = '$uid' ORDER BY p.family_name, p.given_name, p.birth_year";
		return $db->select( $sql );
	}

	/**
	 * Save the Group record
	 */
	static public function save($uid, $person_id, $relationship="")
	{
		global $db;

		if (empty($uid)) return false;
		if (empty($person_id)) return false;

		$fields = "user_id = $uid, person_id = $person_id, relationship = '".fixTick($relationship)."'";
		$sql = "REPLACE app_facebook SET $fields";
		$db->sql_query( $sql );
	}

	/**
	 * Delete the Group record
	 */
	static public function delete($uid, $person_id) {
		global $db;

		if (empty($uid)) return false;
		if (empty($person_id)) return false;
		$sql = "DELETE FROM app_facebook WHERE user_id = '$uid' AND person_id = '$person_id'";
		$db->sql_query($sql);
		return true;
	}

}

?>
