<?
require_once("config.php");
require_once("inc/main.php");

if ($_REQUEST['reset']) {
	$error = $user->newpassword($_REQUEST['email'], "SharedTree <noreply@sharedtree.com>");
}
if ($_REQUEST['logout']) {
	$user->logout();
	redirect("/index.php");
}
if ($_REQUEST['login']) {
	$error = $user->login($_REQUEST['email'], $_REQUEST['password']);
	if (is_logged_on()) {
		if (empty($_REQUEST['fromurl'])) {
			redirect("/index.php");
		} else {
			redirect($_REQUEST['fromurl']);
		}
	}
}
if ($_REQUEST['user_id']) {
	# If the current user is (super admin) then you can login as anyone
	if ($user->getPermissionLevel() >= 3) {
		# Login as a test user
		$newuser = new User($_REQUEST['user_id']);
		$newuser->sessionLogin($user->id);
		redirect("/index.php");
	} else {
		$T->display('header.tpl');
		errorMessage("You don't have permission to do that");
	}
}

$T->assign('error', $error);

$T->assign('email', $_REQUEST['email']);
$T->assign('fromurl', $_REQUEST['fromurl']);
$T->display('login.tpl');

?>