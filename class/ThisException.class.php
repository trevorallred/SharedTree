<?
class ThisException extends Exception 
{
	/**
	 * contains all page data used for debugging
	 *	@var array
	 */
	public $errorData;

	public $debug = true; // We should probably set this so only Trevor and Ryan see this
	
	public function __construct($error)
	{
		$error = empty($error) ? $GLOBALS['php_errormsg'] : $error;
		if(is_array($error)) $error = $error['message'];
		$this->errorData['trace'] 		= debug_backtrace();

		parent::__construct($error);
		global $User;
		if (isset($User)) $this->errorData['user'] = $User;
		
		$this->errorData['session'] 	= empty($_SESSION) ? false : $_SESSION;
		$this->errorData['server']		= $_SERVER;
		$this->errorData['request'] 	= $_REQUEST;

		try {
			global $db;
			//$db->sql_query("", END_TRANSACTION);
		} catch (Exception $e) {
			echo "Failed to do a ROLLBACK";
		}

		echo "<p><b>Sorry, a fatal unexpected error has occurred.</b></p><hr><pre>$error</pre>";
		if ($this->debug) {
			echo "<hr>System Details:<br>";
			print_pre($this->errorData);
		}
		exit();
	}
	
}

?>