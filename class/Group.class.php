<?

class Group
{
	private $db;
	public $id = 0;
	public $data = array();
	public $members = array();
	public $pages = 0;

	public function __construct($id=0, $page = 1)
	{
		global $db;
		$this->db = $db;
		if (!empty($id)) {
			$this->getGroup($id, $page);
		}
	}

	/**
	 * Select this Group from the database
	 */
	public function getGroup($group_id = 0, $page = 1)
	{
		if ($group_id > 0) $this->id = (int)$group_id;
		if (empty($this->id)) return false;

		$sql = "SELECT group_id, group_name, start_year, end_year, member_count, g.initials, g.description, g.created_by, merge_names(uc.given_name, uc.family_name) created_name, g.updated_by, merge_names(uu.given_name, uu.family_name) updated_name, g.creation_date, g.update_date
				FROM app_group g 
				LEFT JOIN app_user uc ON g.created_by = uc.user_id
				LEFT JOIN app_user uu ON g.updated_by = uu.user_id 
				WHERE g.group_id = '$this->id'";
		$data = $this->db->select( $sql );
		if (count($data) == 0) return false;
		$this->data = $data[0];

		$sql = "SELECT count(*) as total FROM tree_person_group g
				JOIN tree_person p ON g.person_id = p.person_id AND ".actualClause("p")."
				WHERE g.group_id = '$this->id' ";
		$total = $this->db->select( $sql );

		# Make sure the member_count still matches
		if ($total[0]["total"] <> $this->data["member_count"]) {
			$this->data["member_count"] = $total[0]["total"];
			$sql = "UPDATE app_group SET member_count = '".$total[0]["total"]."' WHERE group_id = '$this->id'";
			$this->db->select( $sql );
		}
		$perpage = 100;
		$this->pages = ceil($this->data["member_count"] / $perpage);

		if ($page > 0) {
			$limit = ($page - 1) * $perpage;

			$sql = "SELECT p.person_id, p.title, p.given_name, p.family_name, p.birth_year
					FROM tree_person_group g
					JOIN tree_person p ON g.person_id = p.person_id AND ".actualClause("p")."
					WHERE g.group_id = '$this->id' AND active_flag = 1 ORDER BY p.family_name, p.given_name LIMIT $limit, $perpage";
			//echo $sql;
			$this->members = $this->db->select( $sql );
		}

		return $this->data;
	}

	/**
	 * Select this Group from the database
	 */
	static public function personGroups($person_id)
	{
		global $db;
		$person_id = (int)$person_id;
		if (empty($person_id)) return false;

		$sql = "SELECT group_id, group_name FROM app_group g JOIN tree_person_group p USING (group_id) WHERE p.person_id = '$person_id' AND active_flag = 1 ORDER BY group_name";
		///echo $sql;
		return $db->select( $sql );
	}

	/**
	 * Save the Group record
	 */
	public function save($data)
	{
		global $user;
		if (empty($data)) return true;

		$fields = "";
		if (isset($data["group_name"]) && $data["group_name"] > "") {
			$fields .= "group_name = '".fixTick($data["group_name"])."', ";
		}
		if (isset($data["start_year"])) {
			$year = (int)$data["start_year"];
			if ($year <> 0) $fields .= "start_year = '$year', ";
			else $fields .= "start_year = NULL, ";
		}
		if (isset($data["end_year"])) {
			$year = (int)$data["end_year"];
			if ($year <> 0) $fields .= "end_year = '$year', ";
			else $fields .= "end_year = NULL, ";
		}
		if (isset($data["group_name"])) {
			$fields .= "description = '".fixTick($data["description"])."', ";
		}
		if (isset($data["initials"])) {
			$fields .= "initials = '".fixTick(substr($data["initials"],0,2))."', ";
		}
		if ($fields == "") return $this->id;

		if ($this->id > 0) {
			$sql = "UPDATE app_group SET $fields update_date=Now(), updated_by=$user->id, update_process='Group.class.php' WHERE group_id = $this->id ";
			//echo $sql;
			$this->db->sql_query( $sql );
		} else {
			$sql = "INSERT INTO app_group SET $fields creation_date = Now(), created_by = $user->id, update_date=Now(), updated_by=$user->id, update_process='Group.class.php' ";
			//echo $sql;
			$this->db->sql_query( $sql );
			$this->id = $this->db->sql_nextid();
		}
		return $this->id;
	}

	/**
	 * Delete the Group record
	 */
	public function delete() {
		if (empty($this->data["member_count"])) return false;
		if (empty($this->id)) return false;
		$sql = "DELETE FROM tree_person_group WHERE group_id = '$group_id' AND person_id = '$person_id'";
		$db->sql_query($sql);
		return true;
	}

	/**
	 * Add a member to the group
	 */
	static public function addMember($group_id, $person_id) {
		global $db, $user;
		$person_id = (int)$person_id;
		$group_id = (int)$group_id;
		if (empty($person_id)) return false;
		if (empty($group_id)) return false;
		$sql = "INSERT INTO tree_person_group (group_id, person_id, active_flag, creation_date, created_by, update_date, updated_by) VALUES ($group_id, $person_id, 1, NOW(), $user->id, NOW(), $user->id) ON DUPLICATE KEY UPDATE active_flag = 1, update_date = NOW(), updated_by = $user->id ";
		//echo $sql;
		//die();
		$db->sql_query($sql);
		return true;
	}

	/**
	 * Remove a member from the group
	 */
	static public function deleteMember($group_id, $person_id) {
		global $db, $user;
		$person_id = (int)$person_id;
		$group_id = (int)$group_id;
		$sql = "UPDATE tree_person_group SET active_flag = 0, update_date = NOW(), updated_by = $user->id WHERE group_id = '$group_id' AND person_id = '$person_id'";
		$db->sql_query($sql);
		return true;
	}

	/**
	 * List the groups
	 */
	static public function listGroups($byear=null, $dyear=null, $orderby="member_count DESC")
	{
		global $db;
		switch($orderby) {
			case "member_count DESC":
			case "member_count":
			case "group_name":
			case "start_year":
				break;
			default:
				$orderby = "member_count DESC";
		}
		$where = "1=1";
		$byear = (int)$byear; // birth year
		$dyear = (int)$dyear; // death year
		if ($byear <> 0 && $dyear == 0) $dyear = $byear + 100;
		if ($dyear <> 0 && $byear == 0) $byear = $dyear - 100;

		if ($byear <> 0) $where .= " AND (end_year >= $byear OR end_year IS NULL)";
		if ($dyear <> 0) $where .= " AND (start_year <= $dyear OR start_year IS NULL)";
		$sql = "SELECT * FROM app_group WHERE $where ORDER BY $orderby, group_name ASC";
		//echo $sql;
		$data = $db->select($sql);
		return $data;
	}

}

?>
