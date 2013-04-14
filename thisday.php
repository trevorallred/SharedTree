<?
require_once("config.php");
require_once("inc/main.php");

if (isset($_REQUEST["d"])) $day = $_REQUEST["d"];
else $day = date("d");
if (isset($_REQUEST["m"])) $month = $_REQUEST["m"];
else $month = date("m");

if (preg_match("/[0-3][0-9]/", $day) == 0) errorMessage("Day must be 2 digits");
if (preg_match("/[0-1][0-9]/", $month) == 0) errorMessage("Month must be 2 digits");

$T->assign('m', $month);
$T->assign('d', $day);

for($i=1; $i <= 12; $i++) $months[str_pad($i, 2, "0", STR_PAD_LEFT)] = date("M", mktime(0,0,0,$i,1,2007));
for($i=1; $i <= 31; $i++) $days[str_pad($i, 2, "0", STR_PAD_LEFT)] = $i;
$T->assign('months', $months);
$T->assign('days', $days);

$today = mktime(0,0,0,$month,$day,date("Y"));
$oneday = 60 * 60 * 24;
$T->assign('tm', date("m", $today + $oneday));
$T->assign('td', date("d", $today + $oneday));
$T->assign('ym', date("m", $today - $oneday));
$T->assign('yd', date("d", $today - $oneday));
$T->assign('thisday', date("F jS", $today));

$sql = "SELECT p.person_id, p.given_name, p.family_name, YEAR(e1.event_date) event_year, e1.event_type
	FROM tree_person p
	JOIN tree_event e1 ON e1.event_type IN ('BIRT','DEAT') AND e1.actual_start_date <= NOW() AND e1.actual_end_date > NOW() AND e1.table_type = 'P' AND e1.key_id = p.person_id
	WHERE p.actual_start_date <= NOW() AND p.actual_end_date > NOW()
	AND RIGHT(e1.event_date,5) = '$month-$day'
	AND p.person_id IN (
		SELECT person_id FROM app_user_line_person l 
		JOIN ref_relation r ON l.trace = r.trace WHERE user_id = '$user->id'
	) ORDER BY YEAR(e1.event_date) DESC, e1.event_type DESC";

$data = $db->select( $sql );

$T->assign('events', $data);
//print_pre($data);

$T->display('calendar_thisday.tpl');
?>
