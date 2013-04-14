<?
require_once("config.php");
require_once("inc/main.php");

require_login();
$T->assign('noindex',1); // don't index this page

$T->assign('title',"Send Email");
$T->display('header.tpl');



$to = $_REQUEST['to'];
if (empty($to)) {
	errorMessage("You must specify a user to send an email to");
}
$touser = new User($to);

if ($_REQUEST["txtMessage"] > "") {
	$email_address = $touser->data["email"];
	$from_address = $user->data["given_name"]." ".$user->data["family_name"] . " <" . $user->data["email"] . ">";
	$subject = str_replace("\'","'",$_REQUEST["txtSubject"]);
	$headers = "From: $from_address \r\n";
	if ($_REQUEST["sendcopy"] == "on") $headers .= "Bcc: ".$user->data["email"]."\r\n";
	//print_pre($_REQUEST);
	//die($headers);
	$msghead = "The following email was sent from my SharedTree.com account:\n---------------------------------------------------\n\n";
	mail($email_address, $subject, $msghead.str_replace("\'","'",$_REQUEST["txtMessage"]), $headers);

	echo "<br><br><table class=\"portal\"><tr><td>Your Email was successfully sent <br><br><a href='profile.php?user_id=$to'>Return to Profile</a></td></tr></table>";
	$T->display('footer.tpl');
	exit();
}
?>
<h2>Send Email to <? echo $touser->data["given_name"]." ".$touser->data["family_name"]; ?></h2>
<form method="POST">
<input type="hidden" name="to" value="<? echo $to ?>" />
<table class="portal">
<tr><td>
	From: <? echo $user->data["given_name"]." ".$user->data["family_name"]." &lt;".$user->data["email"]."&gt;"; ?><br />
	<font size="1" color="#999999">Make sure your email address is <a href="profile.php">correct</a></font><br />
	Subject: <input name="txtSubject" type="text" class="textfield" size="30" />&nbsp;&nbsp;&nbsp;<input type="submit" value="Send Message" /><br />
	<input type="checkbox" name="sendcopy" checked/> Send me a copy of this email<br />
	<textarea name="txtMessage" rows="20" cols="20" class="textfield" style="width:500px;"></textarea><br />
	<input type="submit" value="Send Message" />
</form>
</td></tr></table>
<a href="contact.php">Contact SharedTree</a>
<?
$T->display('footer.tpl');
?>