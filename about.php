<?

require_once("config.php");
require_once("inc/main.php");

$page = $_REQUEST['page'];
if (empty($page)) $page = "about";

switch ($page) {
	case "about":
	case "privacy":
	case "paypal_thankyou":
	case "paypal_cancel":
	case "donations":
	case "license":
	case "genealogy_mess":
	case "free_registration":
	case "terms":
		$T->display("about_$page.tpl");
		break;
	default:
		errorMessage("No page found called $page");
		break;
}

?>