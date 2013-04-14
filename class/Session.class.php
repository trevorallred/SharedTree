<?

class session
{ 
	/* Set our constants */
	const SESSION_INACTIVITY_TIMEOUT = '-2880 minutes'; // any format will work - sessions expire this long ago

	private $db;
	private $isKnownSession;
	
	public function __construct()
	{
		global $db;
		$this->db = $db;
		$this->isKnownSession = false;
	
		// we must have this ini setting 'session.gc_maxlifetime'
		// or when the user closes the brower without
		// logging out the session will remain till the 
		// SESSION_INACTIVITY_TIMEOUT time has passed
		// which is a major security hole
		ini_set('session.gc_maxlifetime', 172800 );
		ini_set('session.save_handler','user');
		
		// tell php how to store it's session information
		session_set_save_handler(array($this, '_open'), 
	    	                      array($this, '_close'), 
	    	                      array($this, '_read'), 
	    	                      array($this, '_write'), 
	    	                      array($this, '_destroy'), 
	    	                      array($this, '_gc'));		
	}
	
	/* Open session, if you have your own db connection 
	   code, put it in here! */ 
	public function _open($path, $name) 
	{ 
		return true; 
	}

	/* Close session */ 
	public function _close()
	{
		// not much needs to be done here
		return true; 
	}

	/* Read session data from database */ 
	public function _read($session_id) 
	{
		$sql = "SELECT user_id, session_text FROM app_session WHERE session_id = '".$session_id."'"; 
		$row = $this->db->select($sql);
		
		if(count($row) == 0) return '';

		$this->isKnownSession = true;
		$this->user_id = $row[0]["user_id"];
		return stripslashes($row[0]["session_text"]);
	}

	/* Write new data to database */ 
	public function _write($session_id, $data) 
	{
		// is this a known session?
		// if it is, we update it
		// else we write a new one to the database

		$session = "session_id = '$session_id'";
		$uid = ($_SESSION['user_id'] > 0) ? $_SESSION['user_id'] : 0;

		$fields = "user_id = $uid, ";
		$fields .= "host_addr = '".$_SERVER['REMOTE_ADDR']."', ";
		$fields .= "session_text = '".fixTick($data)."', ";
		$fields .= "ses_time = ".time();

		if($this->isKnownSession == true) {
			// this is a known session
			$sql = "UPDATE app_session SET $fields WHERE $session ";
		} else {
			// create a new session
			$sql = "INSERT INTO app_session SET $fields, $session, ses_start = ".time();
		}
		$this->db->sql_query($sql);
		return true;
	}

	/* Destroy session record in database */ 
	public function _destroy($session_id) 
	{ 
		$sql = "DELETE FROM app_session WHERE session_id = '".$session_id."'"; 
		$this->db->sql_query($sql);
		return true; 
	} 
	
	private function _strToTimeFix($timeString)
	{
		date_default_timezone_set('America/Los_Angeles');
	   return time() + (strtotime($timeString) - strtotime('now'));
	}
	
	/* Garbage collection, deletes expired sessions */ 
	public function _gc($life) 
	{
		$sql = "DELETE FROM app_session WHERE ses_time <= ".$this->_strToTimeFix(self :: SESSION_INACTIVITY_TIMEOUT);
		$this->db->sql_query($sql);
		return true;
	}
}

?>
