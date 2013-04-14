<?

require_once("config.php");
require_once("inc/main.php");

$errors = array();
# Validate the key/person combo on save and on display
if (!empty($_REQUEST["key"])) {
	if (secret_key($_REQUEST["person_id"]) <> $_REQUEST["key"]) {
		unset($_REQUEST["key"]);
		unset($_REQUEST["person_id"]);
		$errors[] = "The secret key is not valid. Make sure you are copying the link correctly.";
	}
}

if ($_REQUEST['save']) {
	if (empty($_REQUEST['email'])) $errors[] = "Email address is required. You use this to login.";
	else {
		$temp_user = new User();
		$temp_user->getUser(0, $_REQUEST['email']);
		if ($temp_user->id > 0) {
			$errors[] = "This email address has already been used to register. <a href='login.php'>Click here</a> to login.";
		}
	}
	if (empty($_REQUEST['password'])) $errors[] = "Password is required. You use this to login.";
	if ($_REQUEST['password'] <> $_REQUEST['password2']) $errors[] = "Passwords do not match";
	if (empty($_REQUEST['given_name'])) $errors[] = "Given name is required";
	if (empty($_REQUEST['family_name'])) $errors[] = "Family name is required";

	if (count($errors) == 0) {
		#Save account
		$user_id = $user->save($_REQUEST);
		$user->attachperson($_REQUEST["person_id"]);
        $body = "Please click this link to confirm your new email address on SharedTree.

http://www.sharedtree.com/profile.php?confirm_email=".$user->data["email"]."&hash=".User::getConfirmationHash($user->data["email"]);
        SendEmail($user->data["email"], $from="SharedTree <noreply@sharedtree.com>", "Confirm Email Address", $body);
 
		$error = $user->login($_REQUEST['email'], $_REQUEST['password']);
		if (is_logged_on()) redirect("simple.php");
		else redirect("login.php");
	}
}
$T->assign('errors', $errors);
$T->assign('request', $_REQUEST);

$T->display('register.tpl');

?>
