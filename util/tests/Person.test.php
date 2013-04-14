<?
include_class("Person");

class Test_Person extends PHPUnit2_Framework_TestCase {
	private $person;
	private $person_id;
	public $debug;
	
	public function __construct($name) {
		parent::__construct($name);
	}
	
	protected function setUp() {
	}
	
    protected function tearDown() {
//    	unset($this->person);
    }
	
	public function testAdd() {
		global $db;
		$criteria['family_name'] = "Foo";
		$criteria['given_name'] = "Bar";
		$criteria['birth_year'] = 1969;
		$data = Person::search($criteria);
		if (count($data) > 0) {
			foreach ($data as $value) {
				$idarray[] = $value['person_id'];
			}
			$ids = implode(",", $idarray);
	    	$sql = "DELETE FROM tree_event WHERE key_id IN ($ids) AND table_type = 'P' ";
	    	$db->sql_query($sql);
	    	$sql = "DELETE FROM tree_person WHERE person_id IN ($ids)";
	    	$db->sql_query($sql);
	    	//echo "removed old entries";
		}
		$this->person = new Person();

		
		$save['family_name'] = "Foo";
		$save['given_name'] = "Bar";
		$save['nickname'] = "Bar";
		$save['title'] = "Bar";
		$save['b_date'] = "01 Jul 1969";
		$save['b_location'] = "Provo, Utah, Utah";
		$save['d_date'] = "31 Dec 2001";
		$save['d_location'] = "Provo, Utah, Utah";
		$this->person_id = $this->person->save($save);
		
		$this->person->getPerson();
		$data = $this->person->data;
		foreach ($save as $key=>$value) {
			$this->assertTrue( $data[$key] == $value, "Saved $key as '".$data[$key]."' instead of '$value'");
		}
		
		$criteria['birth_year'] = 1970;
		$criteria['range'] = 2;
		$data = Person::search($criteria);
		if ($debug) print_r($data);
		$this->assertTrue( count($data) >= 1, 'Could not find individual named Foo Bar born around 1970');
		$this->assertFalse( count($data) >= 3, 'Found too many individuals named Foo Bar born around 1970');
	}
}
?>