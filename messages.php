<?

require_once("config.php");
require_once("inc/main.php");

include_class("Messages");

require_login();

if (isset($_REQUEST["action"])) $action = $_REQUEST["action"];
else $action = "list";

if (isset($_REQUEST["folder"])) $folder = $_REQUEST["folder"];
else $folder = "INBOX";

if ($action == "send") {
	$result = Messages::sendMessage($_POST["to_user"], $_POST["subject"], $_POST["body"]);
	if ($result) $status[] = "Successfully sent message";
	else $status[] = "Failed to deliver message";

	$action = "list";
	$folder = "INBOX";
}

if ($action == "new") {
	$T->display("message_new.tpl");
	die();
}

if ($action == "read" && isset($_REQUEST["msg_id"]) ) {
	$message = new Messages($_REQUEST["msg_id"]);
	$T->assign("message", $message->getMessage($_REQUEST["msg_id"]));
	$T->display("message_read.tpl");
	die();
}

### The default is to show the list of messages
$pagesize = 50;
$page = (int)$_REQUEST["page"];
if ($page < 1) $page = 1;

$start = ($page - 1) * $pagesize;
switch ($_REQUEST["sortby"]) {
	case "sent_date":
	default:
		$sortby = "sent_date";
		break;
}

$data = Messages::listMessages($user->id, $page, "NR", $sort);

$T->assign("messages", $data["data"]);
$T->assign("pages", $data["pages"]);

$T->display('message_list.tpl');
?>
