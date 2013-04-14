<?

class Locations {
	private $db;
	public $data = array();
	public $spellings = array();
	public $children = array();
	public $id;

	/**
	 * @access public
	 *
	 */
	public function __construct($id=0)
	{
		global $db;
		$this->db = $db;
		$this->getLocation($id);
	}
	public function getLocation($id) {
		if ($id > 0) $this->id = $id;
		if (empty($id)) return false;
		$sql = "SELECT l.*, p.display_name parent_name 
				FROM ref_location l
				LEFT JOIN ref_location p ON l.parent_id = p.location_id 
				WHERE l.location_id = $id";
		$data = $this->db->select($sql);
		$this->id = $data[0]['location_id'];
		$this->data = $data[0];
		return $this->id;
	}
	
	public function save($data) {
		$this->id = $data['location_id'];
		$field = "";
		//print_pre($data);
		if (isset($data['display_name']) && empty($data['display_name'])) $data['display_name'] = $this->data['location_name'];
		//print_pre($data);
		foreach ($data as $key=>$value) {
			if (in_array($key, array('location_name','description','parent_id','valid_start','valid_end','location_type','display_name'))) {
				$field[] = "$key = '$value'";
			}
		}
		$fields = implode(", ", $field);
		if ($this->id > 0) {
			// Update
			$next_id = (int) $data['location_id'];
			$sql = "UPDATE ref_location SET $fields WHERE location_id = $this->id ";
			$this->db->sql_query($sql);
			//die($sql);
		} else {
			// Insert
			$sql = "INSERT INTO ref_location SET $fields";
			$this->db->sql_query($sql);
			$this->id = $this->db->sql_nextid();
			$this->saveSpelling($data['location_name']);
		}
		return $this->id;
	}
	public function delete() {
		if (empty($this->id)) return false;
		$this->getChildren();
		if (count($this->children) > 0) return false;
		$sql = "DELETE FROM ref_location_spellings WHERE location_id = $this->id ";
		$this->db->sql_query($sql);
		$sql = "DELETE FROM ref_location WHERE location_id = $this->id ";
		$this->db->sql_query($sql);
		unset($this->spellings);
		unset($this->data);
		unset($this->id);
		return true;
	}

	
	public function getSpellings() {
		if (empty($this->id)) return false;
		$sql = "SELECT * FROM ref_location_spellings WHERE location_id = $this->id ORDER BY alt_spelling";
		$data = $this->db->select($sql);
		$primary_exists = false;
		//print_pre($data);
		foreach ($data as $value) {
			if ($value['alt_spelling'] == $this->data['location_name']) $primary_exists = true;
			$this->spellings[] = $value['alt_spelling'];
		}
		if (!$primary_exists) $this->saveSpelling($this->data['location_name']);
		return true;
	}
	public function saveSpelling($alt_spelling) {
		if (empty($this->id)) return false;
		if (empty($alt_spelling)) return false;
		//print_pre($this->spellings);
		if (in_array($alt_spelling, $this->spellings)) return true;
		
		$sql = "INSERT INTO ref_location_spellings SET location_id = $this->id, alt_spelling = '".fixTick($alt_spelling)."'";
		$this->db->sql_query($sql);
		$this->spellings[] = $alt_spelling;
		return true;
	}
	public function deleteSpelling($alt_spelling) {
		if (empty($this->id)) return false;

		$sql = "DELETE FROM ref_location_spellings WHERE location_id = $this->id AND alt_spelling = '".fixTick($alt_spelling)."'";
		return $this->db->sql_query($sql);
	}

	public function getChildren() {
		if (empty($this->id)) return false;
		$sql = "SELECT location_id, location_name, c.meaning type_meaning FROM ref_location l
				LEFT JOIN ref_codes c ON l.location_type = c.ref_code AND c.ref_type = 'LocationTypes'
				WHERE parent_id = $this->id ORDER BY location_name";
		$this->children = $this->db->select($sql);
		return true;
	}

	public function getSimilar() {
		if (empty($this->data['location_name'])) return array();
		$sql = "SELECT DISTINCT l.location_id, l.location_name, l.display_name, c.meaning type_meaning
				FROM ref_location_spellings s
				JOIN ref_location l ON s.location_id = l.location_id
				LEFT JOIN ref_codes c ON l.location_type = c.ref_code AND c.ref_type = 'LocationTypes'
				WHERE s.alt_spelling IN (SELECT alt_spelling FROM ref_location_spellings WHERE location_id = $this->id)
				AND l.location_id <> $this->id ORDER BY display_name";
		$data = $this->db->select($sql);
		if (is_array($data)) return $data;
		else return array();
	}

	static public function getTypes()
	{
		$type['N'] = "Country";
		$type['S'] = "State";
		$type['P'] = "Province";
		$type['C'] = "County";
		$type['T'] = "City or Town";
		return $type;
	}

	static public function findLocationByName($location)
	{
		global $db;
		if (empty($location)) return false;
		$sql = "SELECT l.location_id, l.location_name, l.display_name, c.meaning type_meaning
				FROM ref_location l 
				LEFT JOIN ref_codes c ON l.location_type = c.ref_code AND c.ref_type = 'LocationTypes'
				WHERE location_name > '' ORDER BY c.seq, l.parent_id, l.location_name";
		return $db->select($sql);
	}

	/**
	 * Select this Person from the database
	 */
	static public function findLocationByParent($parent=0, $type='', $year='')
	{
		global $db;
		$where = "1=1 ";
		if ($parent > 0) $where .= "AND parent_id = $parent ";
		if ($type > '') $where .= "AND location_type = '$type' ";
		if ($year > '') {
			$year = (int)$year;
		} else {
			$year = date("Y");
		}
		$where .= "AND valid_start < $year AND valid_end > $year ";

		$sql = "SELECT l.location_id, l.location_name, l.display_name, l.parent_id, l.modern_id FROM ref_location l WHERE $where ORDER BY l.location_name";
		$data = $db->select($sql);
		//print_pre($data);
		if ($data) return $data;
		else return array();
	}
}

?>
