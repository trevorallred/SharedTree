<?
require_once("db.mysql4.php");

include_class("ThisException");

class tree_db extends sql_db
{
	public function sql_query($query = "", $transaction = FALSE) {
		if (empty($query) && $transaction == FALSE) return FALSE;
		//echo "<p>Running:<br>$query<br>$transaction $this->in_transaction</p>";
		$result = parent::sql_query($query, $transaction);
		if ($result == FALSE) {
			global $user;
			
			echo $query;
			$error = "SQL Failed";
			$temp = $this->sql_error();
			if ($temp["message"] > "") $error .= "\nDatabase: ".$temp["message"];
			$error .= "\n\nSQL: ".$query;
			echo $error;
			/*
			 */
			
			errorMessage("Critical Database Error - the administrator has been notified");

			//throw new ThisException(parent::sql_error());
		}
		return $result;
	}

	public function select($query = "", $transaction = FALSE)
	{
		$query_id = $this->sql_query($query, $transaction);
		return $this->sql_fetchrowset($query_id);
	}

	public function rollback()
	{
		echo "rolling back all uncommitted changes";
		print_pre($this->db_connect_id);
		mysql_query("ROLLBACK", $this->db_connect_id);
		return false;
	}
}

### Make the connection to MySQL ###

$db = new tree_db($config["database_server"], $config["database_username"], $config["database_password"], $config["database_schema"]);
// For security purposes, we may want to unset these config parameters

if (empty($db->db_connect_id)) die("failed to connect to database");
?>
