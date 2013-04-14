<?
/**
 * @access public
 */
class MergeProject
{
	/**
	 * Lists various errors that occurred during the user authentication process
	 * @var array
	 */
	private $db;
	public $id;
	private $descendents = "";
	private $tree = "";
	public $data = array();
	
	// Used when traversing the tree
	public $current_matchfamilies = array();
	public $current_spouse = array();
	public $current_matchchildren = array();
	public $current_child = array();
	public $current_matchparents = array();
	public $current_parents = array();
	
	public $merge_ready = false;
	/**
	 * @access public
	 *
	 */
	public function __construct($id=0)
	{
		global $db;
		$this->db = $db;
		if (!empty($id)) {
			$this->getProject($id);
		}
	}

	/**
	 * Select this Project from the database
	 */
	public function getProject($id=0)
	{
		$id = (int)$id;
		if (empty($id) && $this->id > 0) $id =$this->id;
		if (empty($id)) return false;

		$sql = "SELECT p.project_id, p.update_date, p.project_status, p.tree_data, 
				p.person1_id, merge_names(p1.given_name, p1.given_name) person_name1, p1.birth_year byear1,
				p.person2_id, merge_names(p2.given_name, p2.given_name) person_name2, p1.birth_year byear2,
				p.updated_by
				FROM app_project p
				LEFT JOIN tree_person p1 ON p.person1_id = p1.person_id AND ".actualClause("p1")."
				LEFT JOIN tree_person p2 ON p.person2_id = p2.person_id AND ".actualClause("p2")."
			    WHERE p.project_id = $id";
		//echo $sql;
		$row = $this->db->select( $sql );

		if (!$row) return false;
		try {
			$tree_data = $row[0]['tree_data'];
			$this->tree = unserialize($tree_data);
			if (!is_array($this->tree) || count($this->tree["P"]) < 2)
				$this->tree = null;

		} catch (Exception $e) {
			$this->tree = null;
			echo "error" . $tree_data;
			print_pre($this->tree);
			print_pre($e);
			die();
		}
		unset($row[0]["tree_data"]);
		
		$this->data = $row[0];
		switch($this->data['project_status']) {
			case 'X': $meaning = "Matching"; break;
			case 'M': $meaning = "Merging"; break;
			case 'C': $meaning = "Complete"; break;
		}
		$this->data['status_meaning'] = $meaning;
		
		$this->id = $this->data['project_id'];
		return true;
	}
				
	public function setDescendents()
	{
		//print_pre($this->tree);
		$serialized = serialize($this->tree);
		//echo "\n".$serialized;
		//$new_tree = unserialize($serialized);
		//print_pre($new_tree);
		
		$serialized = fixTick($serialized);
		//echo $serialized."\n";
		$sql = "UPDATE app_project SET tree_data = '$serialized' WHERE project_id = '$this->id'";
		$this->db->sql_query( $sql );


		//$sql = "SELECT p.tree_data FROM app_project p WHERE p.project_id = $this->id";
		//echo $sql;
		//$row = $this->db->select( $sql );
		//$tree_data = $row[0]['tree_data'];
		//echo "\n".$tree_data."\n";
		//echo "equals".($serialized==$tree_data)."\n";
		//echo "equals".($serialized==$tree_data)."\n";
		//$queried_tree = unserialize($tree_data);
		//print_pre($queried_tree);
	}
	
	/**
	 * p[*][person_id]
	 *     [full_name]
	 *     [marriages][*]=>{f}
	 *     [parents]=>{f}
	 *     [match]{p}
	 * 
	 * f[*][family_id]
	 *     [father]=>{p}
	 *     [mother]=>{p}
	 *     [spouse]=>{p}
	 *     [children][*]=>{p}
	 *     [match]=>{f}
	 */
	public function getDescendents()
	{
		//$this->tree = array();
		$id = $this->data["person2_id"];
		$person2_id = $id; // for later use
		if (!is_array($this->tree) || count($this->tree)==0) {
			$this->readPerson($id);
			$this->addParents($this->tree["P"][$id]);
			$this->addSpouses($this->tree["P"][$id]);
			
			##############
			$id = $this->data["person1_id"];
			
			$this->readPerson($id);
			$this->addParents($this->tree["P"][$id]);
			$this->addSpouses($this->tree["P"][$id]);
			
			$this->tree["P"][$person2_id]["match"] =& $this->tree["P"][$id];
			$this->tree["P"][$person2_id]["match_action"] = "M";
			
			$this->compareParents($this->tree["P"][$person2_id]["person_id"]);
			$this->setDescendents();
		}
		$this->merge_ready = true;
		foreach($this->tree["P"] as $person) {
			//echo $person["person_id"]. $person["match_action"];
			if (empty($person["merge_action"])) {
				$this->merge_ready = false;
			}
		}
		return $this->tree["P"][$person2_id];
	}
	
	public function reset()
	{
		$this->tree = array();
		$this->setDescendents();
	}

	
	
	###################################################
	public function matchChild($person_id, $match_id="U") {
		//echo "$person_id, $match_id";
		$person =& $this->tree["P"][$person_id];
		
		# If the person has parents then set the family_id
		if (is_array($person["parents"])) $family_id = $person["parents"]["family_id"];
		else $family_id = $person["parents"];
		$this->current_matchchildren = $this->tree["F"][$family_id]["matchchildren"];
		
		if ((int)$match_id > 0) {
			# Match this child and add spouses
			$match =& $this->tree["P"][$match_id];
			
			# get both sets of children
			$this->addSpouses($match);
			$this->addSpouses($person);
			
			$person["match_action"] = "M";
			$person["match"] =& $match;
		} else {
			unset($person["match"]);
			unset($person["marriages"]);
			$person["match_action"] = $match_id;
		}
		//print_pre($this->tree);
		$this->current_child = $person;
		$this->setDescendents();
	}
	
	/**
	 * family_id: the marriage to match to
	 * parent_id: the person to match to
	 * match_action: U = undo, R = reject, M = match
	 */
	public function matchParent($family_id, $parent_id, $match_action) {

		global $user;
		$debug = false;
		if ($user->id == 1)
			$debug = true;

		$parent =& $this->tree["P"][$parent_id]; //reset for shorthand reference
		if (!is_array($parent)) {
			echo("failed to find parent_id = $parent_id");
			print_pre($this->tree);
			die();
		}
		
		$family =& $this->tree["F"][$family_id]; //reset for shorthand reference
		$child_id = $family["parents_of"];
		
		if     ($family["person1"]["person_id"] == $parent_id) $which = "1";
		elseif ($family["person2"]["person_id"] == $parent_id) $which = "2";
		else die(__FILE__.__LINE__." parent_id $parent_id not found in family $family_id");
		$label = "person".$which;
	
		if ($match_action == "U") {
			$temp = $parent["parents"]["family_id"];
			unset($parent["parents"]);
			$parent["parents"] = $temp;
			
			unset($parent["match"]);
			unset($parent["match_action"]);
		}
		if ($match_action == "R") {
			$parent["match_action"] = $match_action;
			unset($parent["match"]);
		}
		if ($match_action == "M") {
			//echo $label;
			//print_pre($family);
			//print_pre($parent);
			//print_pre($this->tree["P"][$child_id]);
			$parent["match_action"] = $match_action;
			//echo "child_id = $child_id <br> label = $label <br>";
			$parent["match"] =& $this->tree["P"][$child_id]["match"]["parents"][$label];
			
			// Extend family tree to parent's parents
			$this->addParents($parent);
			$this->addParents($parent["match"]);
		}
		if ($parent["person_id"] == $this->tree["P"][$child_id]["match"]["parents"][$label]["person_id"]) {
			$parent["match_action"] = "C";
		}
		
		$done = array("M","C");
		if (in_array($family["person1"]["match_action"], $done)
		 && in_array($family["person2"]["match_action"], $done)) {
			# Both parents are now matched, so match the children
			$family["children"] = $this->readChildren($family["family_id"], $child_id);
			$match_family_id = $this->tree["P"][$child_id]["match"]["parents"]["family_id"];
			$this->tree["F"][$match_family_id]["children"] = $this->readChildren($match_family_id, $this->tree["P"][$child_id]["match"]["person_id"]);
		} else {
			unset($family["children"]);
		}
		$this->current_parents      = $family;
		$this->current_matchparents = $this->tree["P"][$child_id]["match"]["parents"];

		$this->setDescendents();
	}
	
	public function matchSpouse($family_id, $match_id="U") {
		$family =& $this->tree["F"][$family_id];
		
		if ((int)$match_id > 0) {
			# Match this spouse and add the children 
			# to the marriage and parents to the spouse
			$match =& $this->tree["F"][$match_id];
			
			# get both sets of children
			$family["match_action"] = "M";
			$family["spouse"]["match_action"] = "M";
			$family["spouse"]["match"] =& $match["spouse"];
			$family["children"] = $this->readChildren($family["family_id"]);
			$match["children"] = $this->readChildren($match["family_id"]);
			$family["matchchildren"] =& $match["children"];
			// TODO add parents of spouse too
			
			// Extend family tree to the spouse's parents
			$this->addParents($family["spouse"]);
			$this->addParents($family["spouse"]["match"]);
		} else {
			unset($family["spouse"]["parents"]);
			unset($family["children"]);
			unset($family["spouse"]["match"]);
			unset($family["spouse"]["match_action"]);
			$family["match_action"] = $match_id;
		}
		$this->current_spouse = $family;
		$this->current_matchfamilies = $this->tree["P"][$family["marriage_of"]]["match"]["marriages"];
		$this->setDescendents();
	}
	
	#####################################################
	# Helper functions that extend the tree when needed #
	
	/**
	 * Take a tree or a branch and add parents to it
	 */
	private function addParents(&$tree) {
		if (is_array($tree["parents"])) return;
		$id = (int)$tree["parents"];
		if (empty($id)) return;

		$this->readFamily($id);
		$tree["parents"] =& $this->tree["F"][$id];
		$this->tree["F"][$id]["parents_of"] = $tree["person_id"];
	}
	
	/**
	 * Take a tree or a branch and add spouses to it
	 */
	private function addSpouses(&$tree) {
		unset($tree["marriages"]);

		$spouses = $this->readMarriages($tree["person_id"]);
		foreach($spouses as $id) {
			if ($id > 0) {
				$this->readFamily($id, $tree["person_id"]);
				$tree["marriages"][] =& $this->tree["F"][$id];
				$this->tree["F"][$id]["marriage_of"] = $tree["person_id"];
			}
		}
	}
	
	private function compareParents($id) {
		$id2 = $this->tree["P"][$id]["match"]["person_id"];
		if ($this->tree["P"][$id]["parents"]["person1"]["person_id"] == 
			$this->tree["P"][$id2]["parents"]["person1"]["person_id"]) {
			$this->tree["P"][$id]["parents"]["person1"]["match_action"] = "C";
		}
		if ($this->tree["P"][$id]["parents"]["person2"]["person_id"] == 
			$this->tree["P"][$id2]["parents"]["person2"]["person_id"]) {
			$this->tree["P"][$id]["parents"]["person2"]["match_action"] = "C";
		}
	}
	
	############################################
	# read Families and Children from database #
	############################################
	public function readMarriages($id, $ignore_spouse=0)
	{
		$spouses = array();
		# Get Person1's family to match against
		$p = new Person($id);
		if (empty($p->id)) return $spouses;
		
		$temp = $p->getMarriages();
		if (is_array($temp)) {
			foreach($temp as $family) {
				if ($ignore_spouse != $family["person_id"]) {
					$spouses[] = $family["family_id"]; //Add the family to the tree
				}
			}
		}
		return $spouses;
	}
	
	public function readFamily($id, $spouse_id=0)
	{
		$family = array();
		$f = new Family($id);
		if (empty($f->id)) {
			unset($this->tree["F"][$id]);
			return;
		}
		
		@$this->tree["F"][$id]["family_id"] = $f->id;
		
		$id2 = $f->data["person1_id"];
		if ($id2 > 0 && $id2 <> $spouse_id) {
			$this->readPerson($id2);
			if ($spouse_id > 0)
				$this->tree["F"][$id]["spouse"] =& $this->tree["P"][$id2];
			else 
				$this->tree["F"][$id]["person1"] =& $this->tree["P"][$id2];
		}
		$id2 = $f->data["person2_id"];
		if ($id2 > 0 && $id2 <> $spouse_id) {
			$this->readPerson($id2);
			if ($spouse_id > 0)
				$this->tree["F"][$id]["spouse"] =& $this->tree["P"][$id2];
			else
				$this->tree["F"][$id]["person2"] =& $this->tree["P"][$id2];
		}
	}
	
	private function readPerson($id) {
		//echo "starting readPerson($id)<br>";
		//print_pre($this->tree);

		if (is_array($this->tree["P"][$id])) return;
		//echo " reading from DB";
		$person = array();
		$p = new Person($id);
		if (empty($p->id)) {
			unset($this->tree["P"][$id]);
			return;
		}
		$person["person_id"] = $p->id;
		$person["full_name"] = $p->data["full_name"];
		$person["b_date"] = $p->data["e"]["BIRT"]["event_date"];
		$person["b_location"] = $p->data["e"]["BIRT"]["location"];
		$person["d_date"] = $p->data["e"]["DEAT"]["event_date"];
		$person["d_location"] = $p->data["e"]["DEAT"]["location"];
		$person["parents"] = $p->data["bio_family_id"];
		$this->tree["P"][$p->id] = $person;
	}
	
	public function readChildren($id, $ignore_child=0)
	{
		$children = array();
		$f = new Family($id);
		if (empty($f->id)) return $children;
		
		$temp = $f->getChildren();
		if (count($temp)) {
			foreach($temp as $kid) {
				if ($kid["person_id"] <> $ignore_child) {
					$this->readPerson($kid["person_id"]);
					$children[] =& $this->tree["P"][$kid["person_id"]];
				}
			}
		}
		return $children;
	}
	
	####################################################################
	
	public function getSummary()
	{
		$data2 = array();
		return $data2;
	}

	/**
	 * Save a Project 
	 *
	 */
	public function save($data)
	{
		global $user;

		$field = "";
		foreach ($data as $key=>$value) {
			if (in_array($key, array('project_id','person1_id','person2_id','project_status'))) {
				if (in_array($key, array('project_id','person1_id','person2_id'))) {
					$value = (int)$value;
					if ($key == 'project_id') $this->id = $value;
				} else {
					$value = fixTick($value);
				}
				if (strlen($value) == 0) $field[] = "$key = NULL";
				else $field[] = "$key = '$value'";
			}
		}
		//print_pre($data);
		//print_pre($field);
		$fields = implode(", ", $field);
		if ($this->id > 0) {
			# Update
			//echo $sql;
			$sql = "UPDATE app_project SET $fields, update_date = Now(), updated_by = '$user->id' WHERE project_id = '$this->id' ";
			$this->db->sql_query($sql);
		} else {
			# Insert
			$sql = "SELECT project_id FROM app_project 
					WHERE person1_id = '".(int)$data["person1_id"]."' 
					  AND person2_id = '".(int)$data["person2_id"]."'";
			$temp = $this->db->select($sql);
			if (!empty($temp[0]["project_id"])) {
				$this->id = $temp[0]["project_id"];
				return $this->id;
			}
			
			$sql = "INSERT INTO app_project SET $fields, project_status = 'M', update_date = Now(), updated_by = '$user->id' ";
			$this->db->sql_query($sql);
			$this->id = $this->db->sql_nextid();
		}
		return $this->id;
	}

	static public function listProjects()
	{
		global $user, $db;

		$sql = "SELECT p.project_id, 
				p1.person_id person1_id, merge_names(p1.given_name, p1.family_name) person_name1, p1.birth_year byear1,
				p2.person_id person2_id, merge_names(p2.given_name, p2.family_name) person_name2, p1.birth_year byear2,
				p.project_status
				FROM app_project p 
				LEFT JOIN tree_person p1 ON p.person1_id = p1.person_id AND ".actualClause("p1")."
				LEFT JOIN tree_person p2 ON p.person2_id = p2.person_id AND ".actualClause("p2")."
			    WHERE p.project_status IN ('X','M') AND p.updated_by = $user->id";
		$data = $db->select($sql);
		$requery = false;
		foreach($data as $row) {
			if (empty($row["person1_id"]) || empty($row["person2_id"])) {
				$project = new MergeProject($row["project_id"]);
				unset($row);
				$row["project_status"] = "C";
				//print_pre($row);
				$project->save($row);
				$requery = true;
			}
		}
		if ($requery) $data = $db->select($sql);
		
		//print_pre($data);
		return $data;
	}

	public function getMergeList()
	{
		global $db;
		
		# Find out which tree["P"]'s are still there
		# If a pending merge is missing either party member, then mark it Complete(C)
		$ids = array();
		foreach($this->tree["P"] as $key=>$person) {
			$ids[$key] = $key;
			# Assume that each record has been deleted, until we can confirm later on
			$this->tree["P"][$key]["deleted"] = 1;
		}
		$id_string = implode(",", $ids);

		$sql = "SELECT person_id FROM tree_person WHERE person_id IN ($id_string) AND ".actualClause();
		//echo $sql;
		$data = $db->select($sql);
		$existing = array();
		foreach($data as $person) {
			$this->tree["P"][$person["person_id"]]["deleted"] = 0;
		}
		//print_pre($data);
		
		$save = false;
		foreach($this->tree["P"] as $key=>$person) {
			//print_pre($this->tree["P"][$key]);
			if ($person["match_action"] == "M") {
				# This person has been matched, make sure both people are still there
				if ($person["deleted"] == 1 || $person["match"]["deleted"] == 1) {
					$this->tree["P"][$key]["match_action"] = "C";
					$save = true;
				}
				//print_pre($this->tree["P"][$key]);
			}
		}
		if ($save) $this->setDescendents();
		
		#####################
		$pending = 0;
		$data = array();
		foreach($this->tree["P"] as $person) {
			$person["match_id"] = $person["match"]["person_id"];
			if ($person["match_action"]=="M"
				&& $person["person_id"] > 0
				&& $person["match_id"] > 0
				&& ($person["person_id"] <> $person["match_id"])
				) {
				unset($person["match"]);
				unset($person["parents"]);
				unset($person["marriages"]);
				//print_pre($person);
				$data[] = $person;
			} elseif($person["match_action"] == "P") {
				// TODO match_action never gets set to P
				// We need be able to differentiate people needing merging and those just waiting to be merged against
				$pending++;
				print_pre($person);
			}
		}
		if (count($data)) {
			$data[0]["pending"] = $pending;
		}
		//print_pre($data);
		//die();
		return array_reverse($data, false);
	}
}
?>
