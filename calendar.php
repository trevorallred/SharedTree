<?
ini_set('display_errors', true);
require_once("config.php");
require_once("inc/main.php");

if (empty($_REQUEST["level"])) $level = 3;
else $level = (int)$_REQUEST["level"];

$sql = "SELECT p.person_id, p.given_name, p.nickname, p.family_name, pc.age, 
			RIGHT(e1.event_date,5) birthday, 
			(YEAR(CURDATE())-YEAR(e1.event_date)) - (RIGHT(CURDATE(),5) < RIGHT(e1.event_date,5)) new_age
		FROM tree_person p
		LEFT JOIN tree_person_calc pc ON p.person_id = pc.person_id
		LEFT JOIN tree_event e1 ON e1.event_type = 'BIRT' AND e1.actual_start_date <= NOW() AND e1.actual_end_date > NOW() AND e1.table_type = 'P' AND e1.key_id = p.person_id
		WHERE p.actual_start_date <= NOW() AND p.actual_end_date > NOW() AND p.public_flag = 0
		AND p.person_id IN (
			SELECT person_id FROM app_user_line_person l 
			JOIN ref_relation r ON l.trace = r.trace AND r.permission >= $level
			WHERE user_id = '$user->id'
		)
		ORDER BY RIGHT(e1.event_date,5)";

//echo $sql;
$data = $db->select( $sql );

for ($i=1;$i<=12;$i++) {
	$months[$i]["name"] = date("F", mktime(0,0,0,$i,1,date("Y")));
}

$need_birthdate = array();
foreach ($data as $row) {
	if ($row["new_age"] > '') {
		if ($row["age"] <> $row["new_age"]) {
			#This person has their work completed
			$sql = "UPDATE tree_person SET age = '".$row["new_age"]."' WHERE person_id = ".$row["person_id"];
			$db->sql_query($sql);
		}
		//echo $row["birthday"];
		$row["new_age"] = $row["new_age"] + 1;
		$row["age"] = $row["new_age"];
		$mon = (int)substr($row["birthday"],0,2);
		$day = (int)substr($row["birthday"],3,2);
		//echo "$mon / $day <br>";
		if ($mon > 0 && $day > 0) {
			$months[$mon][$day][] = $row;
		} else {
			$need_birthdate[] = $row;
		}
	} else {
		$need_birthdate[] = $row;
	}
}

$thismonth = (int)date("m");
for($i=1; $i<=12;$i++) {
	if ($thismonth <= $i) $months2[$i] = $months[$i];
}
for($i=1; $i<=12;$i++) {
	if ($thismonth > $i) $months2[$i] = $months[$i];
}
$T->assign('months', $months2);
$T->assign('need_birthdate', $need_birthdate);
//print_pre($months);
//print_pre($need_birthdate);
//die();

$T->display('calendar.tpl');
?>
