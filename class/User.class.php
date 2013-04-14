<?
/**
 * @access public
 */
class User
{
	/**
	 * Lists various errors that occurred during the user authentication process
	 * @var array
	 */
	private $_permissions = array();
	private $db;
	public $id;
	public $data = array();

	/**
	 * @access public
	 *
	 */
	public function __construct($user_id=0)
	{
		global $db;
		$this->db = $db;
		if (!empty($user_id)) {
			$this->getUser($user_id);
		}
	}

	/**
	 * Select this User from the database
	 */
	public function getUser($id = 0, $email = '', $force = false)
	{
		$where = empty($id)?"u.email = '".fixTick($email)."'":"u.user_id = '$id'";

		$sql = "SELECT u.user_id, u.email, u.password, u.password_new, u.username, u.given_name, u.family_name, p.person_id, 
					u.address_line1, u.address_line2, u.city, u.state_code, u.country_id, u.postal_code,
					u.show_lds, p.given_name as p_given_name, p.family_name as p_family_name,
					u.visits, u.last_visit_date, u.line_update_date, u.description, u.permission_level, u.creation_date,
					u.weekly_email, u.email_confirmed, u.send_messages, u.facebook_id, u.restrict_access, u.language
				FROM app_user u 
				LEFT JOIN tree_person p ON p.person_id = u.person_id AND ".actualClause("p")."
			    WHERE $where";
		//echo $sql;
		$row = $this->db->select( $sql );

		if (!$row) return false;
		$this->data = $row[0];

		# Default to English if no lang is selected
		if ($this->data["language"] == "") $this->data["language"] = "english";
		$this->id = $this->data['user_id'];
		return $this->id;
	}

	static public function getUsers($list) {
		if (is_array($list)) {
			$list_str = "0";
			foreach($list as $key=>$val) $list_str .= ",".$key;
			$list = $list_str;
		}
		if (strlen($list) == 0) return array();

		global $db;
		$sql = "SELECT * FROM app_user WHERE user_id IN ($list) ORDER BY given_name, family_name";
		return $db->select($sql);
	}

	public function getFacebookUser($id)
	{
		$sql = "SELECT user_id FROM app_user WHERE facebook_id = $id";
		$data = $this->db->select($sql);
		if (count($data) == 0) return 0;
		return $this->getUser($data[0]["user_id"]);
	}

	/**
	 * Log the user into the RemedyMD network
	 * @param string $username
	 * @param string $password
	 * @return string Did any errors occur during login?
	 */
	public function login($username, $password)
	{
		if( empty($username) || empty($password) ) {
			return "You must enter a username and a password.";
		}
		$this->getUser(0, $username);

		if (empty($this->id)) {
			return "A user with this email address doesn't exist ";
		}
		if ($this->data['permission_level'] < 0) return "This account has been locked.";

		if( $this->data['password_new'] == $password) {
			// We had a password reset and they logged in correctly
			// Permanently reset the password
			$password = fixTick(md5($password));
			$sql = "UPDATE app_user SET password = '$password', password_new = NULL, update_date = Now(), update_process = 'User.class->login'
					WHERE user_id = $this->id ";
			$this->db->sql_query($sql);
			return $this->sessionLogin();
		}

		if( $this->data['password'] <> md5($password)) {
			// Passwords don't match
			return "Incorrect password for ". $username;
		}

		#################################################################
		# We have a valid user and password. Set the session data now
		#################################################################
		return $this->sessionLogin();
	}

	/**
	 * Actually log the user into the session
	 */
	public function sessionLogin($admin_id=0)
	{
		// Destroy the current session
		$_SESSION = array();
		
		$_SESSION['user_id']		= $this->id;
		$_SESSION['logged_on'] 		= true;
		$_SESSION['admin_id']		= $admin_id;
		$sql = "INSERT INTO app_user_login (user_id, login_date, ip_address, admin_id)
				VALUES ($this->id, NOW(), '".fixTick($_SERVER["REMOTE_ADDR"])."', '$admin_id')";
		$this->db->sql_query($sql);
		
		return "";
	}

	/**
	 * Log the current user out
	 */
	public function logout()
	{
		if (empty($_SESSION['admin_id'])) {
		    // Destroy Session
			session_unset();
		    session_destroy();
		    $_SESSION = array();
		} else {
			$_SESSION['user_id'] = $_SESSION['admin_id'];
			unset($_SESSION['admin_id']);
		}
	    return true;
	}

	/**
	 * Create a new password and email it to the primary account
	 */
	public function newpassword($email, $from_email="")
	{
		if (!validateEmail($email)) {
			return "Sorry, <b>$email</b> not a valid email address.";
		}
		$email = fixTick($email);
		$password = "";
		$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
		$i = 0;
		while ($i < 8) {
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			// we don't want this character if it's already in the password
			if (!strstr($password, $char)) { 
				$password .= $char;
				$i++;
			}
		}
		$sql = "UPDATE app_user SET password_new = '$password' WHERE email = '$email' ";
		$this->db->sql_query($sql);
		$rows = $this->db->sql_affectedrows();
		if ($rows == 0) {
			return "Sorry, <b>$email</b> does not exist in our system. Please create an new account.";
		}
		$from_address = "SharedTree <noreply@sharedtree.com>";
		if ($from_email > "") $from_address = $from_email;
		
		$subject = "Password Reset";
		$headers = "From: $from_address";
		$message = "You or someone pretending to be you has asked us to send you a new password. If you did not request this, you can ignore this message. If you did ask for the new password, you may access your account now with the following:\n\n$password\n\nYour old password will still work until you use this new one. Once you login, you may reset your password to something easier to remember.\n\nLogin: http://www.sharedtree.com/login.php?email=$email";
		SendEmail($email, $from_email, $subject, $message);
		//mail($email, $subject, $message, $headers);
		return "An email was sent to $email with your new password.<br> Your old password will continue to work until you reset it.";
	}

	/**
	 * Save a user
	 */
	public function save($data)
	{
		if (isset($data["email"]) && $data["email"] <> $this->data["email"]) {
			// Email changed or is new so force the user to reconfirm the email address
			$data["email_confirmed"] = 0;
		}
		$field = "";
		foreach ($data as $key=>$value) {
			if (in_array($key, array('email','password','given_name','username','family_name','address_line1','address_line2','city','state_code','country_id','postal_code','show_lds','description','send_messages','email_confirmed','weekly_email','facebook_id','restrict_access','language'))) {
				if ($key == "password") {
					$value = md5($value);
				}
				if (in_array($key, array('person_id','country_id','show_lds','email_confirmed','weekly_email','facebookd_id','restrict_access'))) {
					$value = (int)$value;
				} else {
					$value = fixTick($value);
				}
				if (strlen($value) == 0) $field[] = "$key = NULL";
				else $field[] = "$key = '$value'";
			}
		}
		//print_pre($field);
		$fields = implode(", ", $field);
		if ($data['user_id'] > 0) {
			# Update
			$this->id = (int) $data['user_id'];
			//echo $sql;
			$sql = "UPDATE app_user SET $fields, update_date = Now(), update_process = 'User.class.php' WHERE user_id = '$this->id' ";
			$this->db->sql_query($sql);
		} else {
			# Insert
			$sql = "INSERT INTO app_user SET $fields, update_date = Now(), update_process = 'User.class.php', creation_date = Now()";
			$this->db->sql_query($sql);
			$this->id = $this->db->sql_nextid();
			$this->sessionLogin();
		}
		if ($data['username'] > '') {
			#Add/Update the phpBB Users
			$regdate = strtotime($this->data['creation_date']);
			$sql = "INSERT IGNORE INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (2, $this->id, 0, 0)";
			$this->db->sql_query($sql);
			$sql = "INSERT IGNORE INTO phpbb_users SET 
				user_id=$this->id, user_type=3, username='".fixTick($data['username'])."', username_clean='".fixTick($data['username'])."', 
				user_email='".fixTick($data['email'])."', user_regdate=$regdate
				ON DUPLICATE KEY UPDATE username='".fixTick($data['username'])."', username_clean='".fixTick($data['username'])."', user_email='".fixTick($data['email'])."', user_regdate=$regdate";
			$this->db->sql_query($sql);

			#Add/Update the MediaWiki Users
			$sql = "INSERT INTO mw_user (user_id, user_name, user_touched, user_registration) 
			VALUES (".$data['user_id'].", '".fixTick($data['username'])."', NOW(), NOW()) 
			ON DUPLICATE KEY UPDATE user_name='".fixTick($data['username'])."', user_touched=NOW()";
			$this->db->sql_query($sql);
		} else {
			#Add/Update the MediaWiki and phpBB Users
			$sql = "DELETE FROM mw_user WHERE user_id = $this->id";
			$this->db->sql_query($sql);
		}
		return $this->id;
	}

	/**
	 * Attach (or detach) a person to a user
	 * and rebuild the family tree
	 */
	public function attachperson($person_id=0)
	{
		$person_id = (int) $person_id;
		$sql = "UPDATE app_user SET person_id = '$person_id', update_date = Now(), update_process = 'User.class.php->attachperson' WHERE user_id = '$this->id' ";
		$this->db->sql_query($sql);
		if ($person_id > 0) {
			# Rebuild the tree now we have a new person_id
			require_once("inc/buildline.php");
			buildFamilyTreeIndex($this->id);
		}
		# refresh the user profile again
		$this->getUser($this->id);
	}

	static public function getConfirmationHash($email) {
		return md5($email."jf892hjfv8fcm");
	}

	static public function confirmEmail($email, $hash) {
		if ($hash == User::getConfirmationHash($email)) {
			// Update user
			global $db;
			$sql = "UPDATE app_user SET email_confirmed = 1 WHERE email='".fixTick($email)."'";
			$db->sql_query($sql);
			return true;
		}
		return false;
	}

	static public function unsubscribe($email, $hash) {
		if ($hash == User::getConfirmationHash($email)) {
			// Update user
			global $db;
			$sql = "UPDATE app_user SET weekly_email = 0 WHERE email='".fixTick($email)."'";
			$db->sql_query($sql);
			return true;
		}
		return false;
	}

	public function recordVisit() {
		global $track_hit;
		if (!$track_hit) return;
		if (empty($this->id)) return;
		$sql = "UPDATE app_user SET visits = '".($this->data['visits']+1)."', last_visit_date = NOW() WHERE user_id = $this->id";
		$this->db->sql_query($sql);
	}

	/*
	 * Get a list of users that are related to me (overlapping family trees)
	 */
	public function relatedToFamily() {
		$sql = "SELECT u.user_id, u.given_name, u.family_name, t.status
				FROM app_user u
				LEFT JOIN app_user_trust t ON t.user2_id = u.user_id AND t.user1_id = '$this->id'
				WHERE u.user_id IN (
					SELECT l.user_id
					FROM app_user_line_person l
					JOIN tree_person_calc pc ON l.person_id = p.person_id
					WHERE pc.closest_user_id = '$this->id'
				) ORDER BY t.status, u.family_name, u.given_name";
		return $this->db->select($sql);
	}

	/*
	 * Get a list of users that are related to me (overlapping family trees)
	 */
	public function relatives($limit=10) {
		$sql = "SELECT l.trace, u.*
				FROM app_user_line_person l
				JOIN app_user u ON l.person_id = u.person_id AND u.user_id <> l.user_id
				WHERE l.user_id = '$this->id' ORDER BY distance";
		//echo $sql;
		return $this->db->select($sql);
	}

	public function recentViewed($limit=25)
	{
		$sql = "SELECT p.person_id, p.family_name, p.given_name, v.last_update_date
				FROM app_recent_view v JOIN tree_person p ON v.person_id = p.person_id AND ".actualClause("p")."
				WHERE v.user_id='$this->id' ORDER BY v.last_update_date DESC LIMIT 0 , $limit";
		//echo $sql;
		return $this->db->select($sql);
	}

	public function countLine() {
		if (empty($this->id)) return array();
		$sql = "SELECT count(*) total FROM app_user_line_person WHERE user_id = $this->id";
		$data = $this->db->select($sql);
		$result['person'] = $data[0]['total'];

		/*
		$sql = "SELECT count(*) total FROM app_user_line_family WHERE user_id = $this->id";
		$data = $this->db->select($sql);
		$result['family'] = $data[0]['total'];
		*/
		return $result;
	}
	
	public function sendMessage($subject, $body) {
		global $user;

		if (empty($this->id)) return;
		if ($this->data["email_confirmed"] && ($this->data["send_messages"] == "B" || $this->data["send_messages"] == "E") ) {
			$body .= "
-----------------------------------------------------------------------
To unsubscribe from these emails in the future, follow this link:
http://www.sharedtree.com/user.php?unsubscribe=1";
			SendEmail($this->data["email"], $user->data["email"], $subject, $body);
			//echo "send to ".$this->data["email"];
		}
		if ($this->data["send_messages"] == "B" || $this->data["send_messages"] == "P") {
			# TODO Send private message
		}
	}
	public function getPermissionLevel() {
		return $this->data["permission_level"];
	}
	
}

function is_logged_on() {
	global $user;
	# Sometimes the user gets destroyed but the session stays in tact
	# It this occurs just log them out
	if (empty($user->id) && $_SESSION['logged_on']) $user->logout();
	return $_SESSION['logged_on'];
}

function require_login() {
	if (is_logged_on()) return true;
	redirect("/login.php?fromurl=". urlencode($_SERVER['REQUEST_URI']));
}

function secret_key($person_id) {
	return md5($person_id."kFj#h4tg28!".$person_id.date("Y"));
}
?>
