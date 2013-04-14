<?
class GEDImport
{

	public $data = array();
	private $db;
	public $id = 0;
	public $time = null;
	public $pages = 0;

	public function __construct($import_id=0)
	{
		global $db;
		$this->db = $db;
		$this->getImport($import_id);
	}

	/**
	 * Select this import file's information from the database
	 */
	public function getImport($import_id=0)
	{
		if ($import_id > 0) $this->id = $import_id;
		if (empty($this->id)) return false;

		$sql = "SELECT *
				FROM app_import i WHERE import_id = '$this->id'";
		$data = $this->db->select( $sql );

		if (!$data) return false;
		$this->data = $data[0];
		$this->data["possible_duplicates"] = (int)$this->data["person_matched"] * $this->data["person_count"] / 100;
		$this->id = $this->data['import_id'];
		return $this->id;
	}

	/**
	 * Save the import file details
	 */
	public function save($data)
	{
		global $user;
		if (empty($data['import_id']) && $this->id > 0) {
			$data['import_id'] = $this->id;
		}

		foreach ($data as $key=>$value) {
			if ($key <> "import_id") {
				if ($value == "NOW()" || $value == "NULL") {
					$field[] = "$key = $value";
				} else {
					$field[] = "$key = '".fixTick($value)."'";
				}
			}
		}
		if ($data['import_id'] > 0) {
			$this->id = (int) $data['import_id'];
			# Update the family record
			$fields = implode(", ", $field);
			$sql = "UPDATE app_import SET $fields WHERE import_id = '$this->id' ";
			$this->db->sql_query( $sql );
		} else {
			if (empty($data['user_id'])) $field[] = "user_id = $user->id";
			$fields = implode(", ", $field);
			if (empty($data['upload_date'])) $fields .= ", upload_date = Now()";
			$sql = "INSERT INTO app_import SET $fields ";
			$this->db->sql_query( $sql );
			$this->id = $this->db->sql_nextid();
		}
		//echo $sql;
		return $this->id;
	}
	
	public function delete($import_id) {
		if (empty($this->id)) $this->id = $import_id;
		$import_id = (int) $import_id;
		if (empty($import_id)) return false;
		$sql = "DELETE FROM app_import WHERE import_id = $import_id";
		$this->db->sql_query( $sql );
		unset($this->data);
		$this->id = 0;
		return true;
	}
	
	public function showPeople($page=1) {
		# Set some paging variables
		$perpage = 500;
		$start = $page * $perpage;

		# Get the total page count
		$sql = "SELECT count(*) total
			FROM tree_person p
			JOIN tree_person_gedcom i ON p.person_id = i.person_id AND i.import_id = '$this->id'
			WHERE ".actualClause("p")." ORDER BY p.person_id LIMIT $start, 500";
		$temp = $this->db->select($sql);
		$total_recs = $temp[0]["total"];
		if ($total_recs == 0) {
			$this->page_data = array();
			return;
		}
		$pages = ceil($total_recs/$perpage);
		$this->pages = $pages;

		# Now get this page's actual data
		$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year, p.public_flag, p.update_date, p.updated_by, 
				pc.merge_rank, CONCAT(u.given_name, ' ', u.family_name) user_name, i.individual, i.gedcom_text
			FROM tree_person p
			JOIN tree_person_calc pc ON p.person_id = pc.person_id
			JOIN tree_person_gedcom i ON p.person_id = i.person_id AND i.import_id = '$this->id'
			LEFT JOIN app_user u ON p.updated_by = u.user_id
			WHERE ".actualClause("p")." ORDER BY p.person_id LIMIT $start, $perpage";
		//echo $sql;
		$this->page_data = $this->db->select($sql);
		//print_pre($this->page_data);

		# While we're here, let's see if any data has changed
		# If it has then we need to lock this import so it can't be deleted
		$sql = "SELECT *
			FROM tree_person p
			JOIN tree_person_gedcom i ON p.person_id = i.person_id AND i.import_id = '$this->id'
			LEFT JOIN app_user u ON p.updated_by = u.user_id
			WHERE ".actualClause("p");
		//echo $sql;
		//$data = $this->db->select($sql);
	}

	/**
	 * Delete all people and families imported by a file
	 * This is VERY dangerous and cannot be rolled back!!!
	 */
	public function deleteRecords($import_id) {

		if (empty($this->id)) $this->id = $import_id;
		$import_id = (int) $import_id;
		if (empty($import_id)) return false;
		
		$diff = strtotime("now") - strtotime($this->data["import_date"]);
		$days = $diff / (24 * 60 * 60);
		if (!empty($_SESSION["admin_id"])) $days = 0; // if we're logged in as an admin, override the date check

		if ($days > 30 && $this->data["import_date"] > "") errorMessage("You can't delete family trees that were imported more than 30 days ago","import.php");

		$psql = "SELECT person_id FROM tree_person_gedcom WHERE import_id = $import_id";
		$pdata = $this->db->select($psql);
		if (count($pdata) < 1000) {
			$psql = "0";
			foreach($pdata as $row) $psql .= ",".$row["person_id"];
		}
		$fsql = "SELECT family_id FROM tree_family_gedcom WHERE import_id = $import_id";
		$fdata = $this->db->select($fsql);
		if (count($fdata) < 1000) {
			$fsql = "0";
			foreach($fdata as $row) $fsql .= ",".$row["family_id"];
		}

		$sql = "DELETE FROM tree_residence WHERE family_id IN ($fsql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_image WHERE person_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM discuss_wiki WHERE person_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM discuss_post WHERE person_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_event WHERE table_type = 'P' AND key_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_event WHERE table_type = 'F' AND key_id IN ($fsql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_family WHERE person1_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_family WHERE person2_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_family WHERE family_id IN ($fsql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_person WHERE person_id IN ($psql)";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_person_gedcom WHERE import_id = $import_id";
		$this->db->sql_query( $sql );
		$sql = "DELETE FROM tree_family_gedcom WHERE import_id = $import_id";
		$this->db->sql_query( $sql );

		unset($data);
		$data["status_code"] = "P"; // import pending
		$data["current_step"] = 2; // validated
		$data["import_date"] = "NULL";
		$this->save($data);

		return true;
	}

	static public function getList($user_id, $show="") {
		global $db;
		$where = "AND status_code <> 'A'"; // don't show archived imports
		if ($show=="all") $where = "";

/*
		$sql = "SELECT i.import_id, i.filename, i.upload_date, i.import_date, i.description, i.file_size, i.family_count, i.person_count, count(DISTINCT p.person_id) pers_count, count(DISTINCT f.family_id) fam_count, status_code
				FROM app_import i 
				LEFT JOIN tree_person_gedcom p ON p.import_id = i.import_id
				LEFT JOIN tree_family_gedcom f ON f.import_id = i.import_id
				WHERE i.user_id = '$user->id' $where
				GROUP BY i.import_id, i.filename, i.upload_date, i.import_date, i.description, i.file_size, i.family_count, i.person_count
				ORDER BY upload_date";
*/

		$sql = "SELECT *
				FROM app_import i 
				WHERE i.user_id = '$user_id' $where
				ORDER BY current_step, upload_date";
		//echo $sql;
		$data = $db->select( $sql );
		for($i=0; $i < count($data); $i++) {
			switch($data[$i]["status_code"]) {
				case "P":
					$data[$i]["status_meaning"] = "Pending";
					break;
				case "A":
					$data[$i]["status_meaning"] = "Approved";
					break;
				case "I":
					$data[$i]["status_meaning"] = "Imported";
					break;
				default:
					$data[$i]["status_meaning"] = $data[$i]["status_code"];
			}
		}
		return $data;
	}
}

?>
