<?

require_once("config.php");
require_once("inc/main.php");

require_login();

$pagesize = 50;
$page = (int)$_REQUEST["page"];
if ($page < 1) $page = 1;

$start = ($page - 1) * $pagesize;
switch ($_REQUEST["sortby"]) {
	case "family_name":
		$sortby = "u.family_name, u.given_name";
		break;
	case "given_name":
		$sortby = "u.given_name, u.family_name";
		break;
	case "creation_date":
	default:
		$sortby = "creation_date DESC";
		break;
}

$where = "";
if (!empty($_REQUEST["family_name"])) $where .= " AND u.family_name LIKE '".fixTick($_REQUEST["family_name"])."%' ";
if (!empty($_REQUEST["given_name"])) $where .= " AND u.given_name LIKE '%".fixTick($_REQUEST["given_name"])."%' ";
if (!empty($_REQUEST["username"])) $where .= " AND u.username LIKE '%".fixTick($_REQUEST["username"])."%' ";

$sql = "SELECT u.user_id, u.given_name, u.family_name, u.username, u.city, u.state_code, u.country_id, creation_date, show_lds, last_visit_date, visits, line_update_date
		FROM app_user u WHERE 1=1 $where ORDER BY $sortby LIMIT $start, $pagesize";
$data = $db->select( $sql );
$T->assign('users', $data);

$sql = "SELECT count(*)	as total FROM app_user u WHERE 1=1 $where";
$data = $db->select( $sql );
$T->assign("page", $page);

$total_results = $data[0]["total"];
if ($total_results > 0) {
	$T->assign("pages", ceil($total_results/$pagesize));
	//echo ceil($total_results/$pagesize);
	//die();
} else $T->assign("pages", 1);
$T->assign("request", $_REQUEST);

$T->display('user_list.tpl');
?>
