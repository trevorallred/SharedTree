<?
require_once("config.php");
require_once("inc/main.php");

$T->assign('title',"Contact Us");
$T->display('header.tpl');

if ($_REQUEST["txtMessage"] > "") {
	if ($_REQUEST["txtName"] > "") $from_address = $_REQUEST["txtName"] . " <".$_REQUEST["txtEmail"].">";
	else $from_address = $_REQUEST["txtEmail"];
	$subject = $_REQUEST["txtSubject"];
	$headers = "From: $from_address \r\n";
	//if ($_REQUEST["sendcopy"] == "on") $headers .= "Bcc: ".$_REQUEST["txtEmail"]."\r\n";
	//print_pre($_REQUEST);
	//die($headers);
	$msghead = "From SharedTree\r\n-------------------------\r\n";
	mail("trevorallred@gmail.com", $subject, $msghead.str_replace("\'","'",$_REQUEST["txtMessage"]), $headers);

	echo "Thank you for sending us email. We will reply if necessary as soon as possible.";
}
?>
<h2>Contact us at SharedTree</h2>
<form method="POST">
<input type="hidden" name="to" value="<? echo $to ?>" />
<table class="portal">
<tr><td>
	Your name: <input name="txtName" class="textfield" type="text" /><br />
	Your email: <input name="txtEmail" class="textfield" type="text" /><br />
	Subject: <input name="txtSubject" class="textfield" type="text" /><br />
	<textarea name="txtMessage" rows="10" cols="20" class="textfield" style="width:400px;"></textarea><br />
	<input type="submit" value="Send Message" />
</form>
</td></tr></table>
<?
$T->display('footer.tpl');
?>
