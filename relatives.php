<?

require_once("config.php");
require_once("inc/main.php");

require_login();

$sortby = "l.distance";
$sortby = "u.family_name, u.given_name";
$sortby = "creation_date DESC";
$sortby = "last_visit_date DESC";

$sql = "SELECT DISTINCT u.user_id, u.given_name, u.family_name, u.email, u.city, u.state_code, u.country_id, creation_date, last_visit_date, visits, line_update_date
		FROM app_user_line_person l1
		JOIN app_user_line_person l2 ON l1.person_id = l2.person_id and l1.user_id <> l2.user_id
		JOIN app_user u ON u.user_id = l2.user_id
		WHERE l1.user_id = '$user->id'
		ORDER BY $sortby";
$data = $db->select( $sql );
$T->assign('users', $data);

$T->display('relatives.tpl');
?>
