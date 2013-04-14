<?

require_once("config.php");
require_once("inc/main.php");

if (isset($_REQUEST['user_id'])) {
	# View a person's profile
	$uid = (int)$_REQUEST['user_id'];
	if (empty($uid)) {
		$T->display("header.tpl");
		errorMessage("You must supply a user_id","user.php",false);
	}
	$profile = new User($uid);
	if (empty($profile->id)) errorMessage("No user exists with that user_id");
	$T->assign("profile", $profile->data);

	$title = "Profile for ". trim($profile->data["given_name"] . " " . $profile->data["family_name"]);
	$T->assign("title", $title);

	$sql = "SELECT p.person_id, max(t.update_date) as update_date, p.family_name, p.given_name, p.birth_year, p.public_flag, l.trace
			FROM (
				(select p.person_id, p.update_date from tree_person p 
				 where ".actualClause()." and p.updated_by = $uid order by update_date DESC limit 0,50)
				union
				(select e.key_id as person_id, e.update_date from tree_event e 
				 where ".actualClause()." and e.updated_by = $uid and e.table_type = 'P' order by update_date DESC limit 0,100)
			) t
			JOIN tree_person p ON p.person_id = t.person_id
			LEFT JOIN app_user_line_person l ON p.person_id = l.person_id AND l.user_id = '$user->id'
			WHERE ".actualClause("p")." GROUP BY p.person_id ORDER BY update_date DESC LIMIT 0,25";
	$data = $db->select($sql);
	$T->assign("person_changes", $data);

	$sql = "SELECT count(*) total FROM tree_person WHERE ".actualClause()." and updated_by = $uid";
	$data = $db->select($sql);
	$T->assign("person_count", $data[0]["total"]);
	$sql = "SELECT count(*) total FROM tree_family WHERE ".actualClause()." and updated_by = $uid";
	$data = $db->select($sql);
	$T->assign("family_count", $data[0]["total"]);
	$sql = "SELECT count(*) total FROM tree_event WHERE ".actualClause()." and updated_by = $uid";
	$data = $db->select($sql);
	$T->assign("event_count", $data[0]["total"]);
	$sql = "SELECT count(*) total FROM app_user_line_person WHERE user_id = $uid";
	$data = $db->select($sql);
	$tree_size = $data[0]["total"];
	$T->assign("tree_size", $data[0]["total"]);

	if ($user->id > 0 && $tree_size > 0) {
		# how many people are the same in our family trees?
		$sql = "SELECT count(*) as total FROM app_user_line_person l1
				JOIN app_user_line_person l2 ON l1.person_id = l2.person_id
				WHERE l1.user_id = $user->id and l2.user_id = $uid";
		$data = $db->select($sql);
		$T->assign("tree_overlap", $data[0]["total"]);
		$T->assign("overlap_percent", round(100*$data[0]["total"] / $tree_size));

		$sql = "SELECT l2.user_id, l1.trace trace1, l2.trace trace2, (l1.distance + l2.distance) as total_dist, 
				r1.description r1_desc, r2.description r2_desc, 
				p.person_id, p.family_name, p.given_name
				FROM app_user_line_person l1
				JOIN app_user_line_person l2 ON l1.person_id = l2.person_id and l1.user_id <> l2.user_id
				LEFT JOIN ref_relation r1 ON r1.trace = l1.trace
				LEFT JOIN ref_relation r2 ON r2.trace = l2.trace
				JOIN app_user u ON u.user_id = l2.user_id
				JOIN tree_person p ON p.person_id = l1.person_id and p.actual_end_date > NOW()
				WHERE l1.user_id = $user->id and l2.user_id = $uid
				ORDER BY total_dist ASC LIMIT 0,5";
		$data = $db->select($sql);
		$low_trace = 999999999999999999;
		$data_size = count($data);
		//print_pre($data);
		for($i=0; $i < $data_size; $i++) {
			//echo "$i $low_trace";
			if ($low_trace >= $data[$i]["total_dist"]) {
				//echo "reset";
				$low_trace = $data[$i]["total_dist"];
			} else {
				//echo "unset $i";
				unset($data[$i]);
			}
			//echo "<br>";
		}
		$T->assign("common_relatives", $data);
	}

	# This is so trevor can do admin things to users
	if ($user->id == 1)	$T->assign("canlogin", true);
	$T->display('profile.tpl');
	exit();
}

if ($_REQUEST['savesecurity']) {
	print_pre($_REQUEST);
	die();
}

if ($_REQUEST['edit_security']) {
	# Get the list of family members
	include_class("Person");
	$list = Person::closestUser($user->id);
	$T->assign('list', $list);
//print_pre($list);
	
	# Get my relatives
	$relatives = $user->relatedToFamily();
	$T->assign('relatives', $relatives);

//print_pre($relatives);
	$T->assign("trust_options", array('P'=>'Pending', 'N'=>'No', 'Y'=>'Yes'));
	$T->display("profile_security.tpl");
	exit();
}

if ($_REQUEST['confirm_email']) {
	$valid = User::confirmEmail($_REQUEST['confirm_email'], $_REQUEST['hash']);
	if (!$valid) errorMessage("Incorrect hash for email address ".$_REQUEST['confirm_email']);
	$T->display("header.tpl");
	echo "<h2>Successfully confirmed email address</h2>";
	if (is_logged_on() ) echo "<a href='index.php'>Continue to My Tree</a>";
	else echo "<a href='login.php'>Continue to Login</a>";
	echo "<br /><br /><br /><br /><br /><br />";
	$T->display("footer.tpl");
	die();
}

if ($_REQUEST['unsubscribe']) {
	$email = $_REQUEST['unsubscribe'];
	$valid = User::unsubscribe($email, $_REQUEST['hash']);
	if (!$valid) errorMessage("Incorrect hash for email address ".$email);
	$T->display("header.tpl");
	echo "Successfully unsubscribed <u>$email</u> from the SharedTree Weekly Digest. <br> If you would like to receive emails of changes on SharedTree in the future, please <a href=\"profile.php\">edit your profile</a>.";
	$T->display("footer.tpl");
	die();
}

# We didn't have a user_id, so assume we are wanting to view our own profile
require_login();

if ($_REQUEST['send_confirmation']) {
	$body = "Please click this link to confirm your new email address on SharedTree.

http://www.sharedtree.com/profile.php?confirm_email=".$user->data["email"]."&hash=".User::getConfirmationHash($user->data["email"]);
	SendEmail($user->data["email"], $from="SharedTree <noreply@sharedtree.com>", "Confirm Email Address", $body);
	echo "
<html>
<body>
<div align='center'>
<h3>Email Sent</h3>
to <i>".$user->data["email"]."</i><br><br>
Please check your email account now to complete the process.

<form>
<input type='button' value='Close' onclick='window.close()'>
</form>
</body>
</html>";
	die();
}

// Update user profile with the person_id
if ($_REQUEST['addperson']) {
	$user->attachperson($_REQUEST['addperson']);
	//$user->save(array('user_id'=>$user->id, 'person_id'=>$_REQUEST['addperson']));
	$T->assign('rebuild', 1);
}
// Update the user profile
if ($_REQUEST['save']) {
	if (empty($_REQUEST['email'])) $errors[] = "Email address is required. You use this to login.";
	if ($_REQUEST['oldpassword'] > "") {
		$oldpass = md5($_REQUEST['oldpassword']);
		if ($user->data['password'] <> $oldpass) $errors[] = "You entered an incorrect old password.";
		if (empty($_REQUEST['newpassword'])) $errors[] = "Your new password cannot be blank.";
		if ($_REQUEST['newpassword'] <> $_REQUEST['newpassword2']) $errors[] = "Your new passwords do not match.";
		$_REQUEST['password'] = $_REQUEST['newpassword'];
	}
	if (empty($_REQUEST['given_name'])) $errors[] = "Given name is required.";
	if (empty($_REQUEST['family_name'])) $errors[] = "Family name is required.";
	if ($_REQUEST['username'] > "") {
		$_REQUEST['username'] = strtolower($_REQUEST['username']);
		if (preg_match("/[^a-z0-9]+/", $_REQUEST['username'])) $errors[] = "Username can only contain letters A-Z and numbers 0-9.";
		else {
			$sql = "SELECT user_id FROM app_user WHERE username = '".fixTick($_REQUEST['username'])."'";
			$row = $db->select($sql);
			if (count($row) > 0) {
				if ($row[0]["user_id"] <> $user->id) {
					$errors[] = "The username '".$_REQUEST['username']."' has already been taken.";
				}
			}
		}
	}
	if (count($errors) == 0) {
		#Save account
		$_REQUEST['user_id'] = $user->id;
		$user_id = $user->save($_REQUEST);
		if ($user_id > 0) redirect("index.php");
	}
	$T->assign('errors', $errors);
	$T->assign('request', $_REQUEST);
} else {
	$T->assign('request', $user->data);
}

$T->assign("yesno_options", array(1=>'Yes', 0=>'No'));
$T->assign("send_messages_options", array('B'=>'Email & Private Message', 'E'=>'Email only', 'P'=>'Private Message only'));
$T->assign("restrict_access", array('0'=>'All relatives (Recommended)', '1'=>'I will choose'));
include_class("Locations");
$data = Locations::findLocationByParent(0, 'N');
$countries = array();
foreach($data as $row) {
	$countries[$row["location_id"]] = $row["display_name"];
}
$T->assign("countries", $countries);

include_once("inc/lang_settings_std.php");
$lang_options = array();
foreach($language_settings as $key=>$row) $lang_options[$key] = $row["pgv_lang"];
$T->assign("languages", $lang_options);

$T->display('profile_edit.tpl');
?>
