<?

class Messages
{
	private $db;
	public $id = 0;
	public $data = array();

	public function __construct($id=0)
	{
		global $db;
		$this->db = $db;
		if (!empty($id)) {
			$this->getMessage($id);
		}
	}

	/**
	 * Select this Message from the database
	 */
	public function getMessage($message_id)
	{
		if ($message_id > 0) $this->id = (int)$message_id;
		if (empty($this->id)) return false;

		$sql = "SELECT m.message_id, m.from_uid, m.to_uid, m.sent_date, m.status_code, m.subject, m.body
				FROM app_message m
				JOIN app_user uf ON m.from_uid = uf.user_id
				JOIN app_user ut ON m.to_uid = ut.user_id 
				WHERE m.message_id = $this->id";
		$data = $this->db->select( $sql );
		if (count($data) == 0) return false;
		$this->data = $data[0];

		return $this->data;
	}

	/**
	 * Update Message Status
	 */
	public function updateStatus($status_code)
	{
		if (empty($this->id)) return false;
		$status_code = substr($status_code, 0, 1);

		$sql = "UPDATE app_message SET status_code = '$status_code'
			WHERE message_id = $this->id";
		$this->db->sql_query( $sql );
	}

	/**
	 * Save the Group record
	 */
	static public function sendMessage($to_user, $subject, $body)
	{
		global $user, $db;
		if (empty($user->id)) return false;
		$to_user = (int)$to_user;
		if (empty($to_user)) return false;

		$toUser = new User($to_user);
		// SEND EMAIL HERE IF User settings permit it

		$sql = "INSERT INTO app_message SET 
		from_uid = $user->id,
		to_uid = $to_user,
		sent_date = Now(),
		subject = '".fixTick($subject)."',
		body = '".fixTick($body)."'";
		
		$db->sql_query( $sql.", status_code = 'N'" ); // New message for to user
		$db->sql_query( $sql.", status_code = 'O'" ); // Outbox for from user
		return true;
	}

	/**
	 * Delete the Group record
	 */
	public function delete() {
		if (empty($this->id)) return false;
		$sql = "DELETE FROM app_message WHERE message_id = '$this->id'";
		$this->db->sql_query($sql);
		return true;
	}

	/**
	 * Select page $page of message for this user
	 */
	static public function listMessages($user_id, $page=1, $status="NR", $sort="DESC")
	{
		global $db;
		$user_id = (int)$user_id;
		if (empty($user_id)) return false;

		$status_in = "'N','R'";
		$sql = "SELECT m.message_id, m.to_uid, m.sent_date, m.status_code, m.subject
		FROM app_message m 
		JOIN app_user ut ON m.to_uid = ut.user_id
		WHERE from_uid = $user_id AND status_code IN ($status_in)
		ORDER BY sent_date $sort";
		echo $sql;
		$val["data"] = $db->select( $sql );
		$val["pages"] = 1;
		return $val;
	}
}

?>
