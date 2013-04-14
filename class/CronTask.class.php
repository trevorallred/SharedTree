<?

class CronTask
{
	public $task_id = 0;
	public $minutes_between = 0;
	private $current_run_id = 0;
	
	public function __construct()
	{
	}

	public function setByResultSet($row)
	{
		$this->task_id = $row["task_id"];
		$this->minutes_between = $row["minutes_between"];
	}

	public function start()
	{
		if (empty($this->task_id)) return false;
		
		global $db;
		$sql = "INSERT INTO app_cron_run (task_id, start_datetime, status)
				VALUES ($this->task_id, NOW(), 'R')";
		$db->sql_query($sql);
		$this->current_run_id = $db->sql_nextid();
		
		$sql = "UPDATE app_cron_task 
				SET last_run_time = NOW(), 
					next_run_time = NOW() + INTERVAL $this->minutes_between MINUTE,
					last_status = 'R'
				WHERE task_id = $this->task_id";
		$db->sql_query($sql);
	}

	public function complete($success, $output)
	{
		if (empty($this->task_id)) return false;
		if (empty($this->current_run_id)) return false;
		
		global $db;
		if ($success) $status = "C"; //Complete
		else $status = "F"; //Failed
		$sql = "UPDATE app_cron_run 
				SET end_datetime = NOW(), 
					status = '$status', 
					output = '".fixTick($output)."'
				WHERE run_id = $this->current_run_id";
		$db->sql_query($sql);
		
		$sql = "UPDATE app_cron_task 
				SET last_status = '$status'
				WHERE task_id = $this->task_id";
		$db->sql_query($sql);
	}
	
	public function getRuns()
	{
		global $db;
		if (empty($this->task_id)) return false;
		
		$sql = "SELECT run_id, task_id, start_datetime, end_datetime, status, output
				FROM app_cron_run
				WHERE task_id = $this->task_id
				ORDER BY start_datetime DESC LIMIT 100";
		$data = $db->select($sql);
		return $this->id;
	}
	
	/**
	 * Select this Family from the database
	 */
	static public function getPendingList()
	{
		global $db;
		$sql = "SELECT task_id, file_name, minutes_between FROM app_cron_task
				WHERE next_run_time <= NOW()
				ORDER BY priority DESC, next_run_time DESC";
		$data = $db->select($sql);
		return $data;
	}
}

?>
