<?
class GenTable
{
	public $data = array();
	protected $db;
	public $id = 0;
	public $time = null;

	public function __construct()
	{
		global $db;
		$this->db = $db;
	}

	/**
	 * Save the table record
	 */
	protected function save($data)
	{

	}
}

?>