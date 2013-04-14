<?
$base = "/var/www/openwillow.net";
require_once("$base/config.php");
require_once("$base/inc/main.php");

$lastweek = date("Y-m-d", strtotime('-1 week'));

$sql = "SELECT user_id FROM app_user WHERE creation_date <= '$lastweek' ORDER BY user_id";
$data = $db->select($sql);

/*
print_pre($data);
die();
*/
foreach($data as $value) createEmail($value["user_id"], $lastweek);

function createEmail($user_id, $since_date) {
	global $T, $db;
	$T->assign('since_date', $since_date);
	$u = new User($user_id);
	$T->assign('user', $u->data);
	$sql = "SELECT l.trace, u.*
		FROM app_user_line_person l
		JOIN app_user u ON l.person_id = u.person_id AND u.user_id <> l.user_id
		WHERE l.user_id = '$user_id' AND creation_date >= '$since_date' ORDER BY creation_date DESC";
	//echo $sql;
	$relatives = $db->select($sql);
	$T->assign('relatives', $relatives);

	##################################################
	# get a list of recent changes to individuals
	$sql= "
	SELECT p.* FROM (
	SELECT person_id FROM tree_person WHERE update_date >= '$since_date' AND updated_by <> $user_id AND person_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = $user_id)
	UNION
	SELECT key_id as person_id FROM tree_event WHERE update_date >= '$since_date' AND updated_by <> $user_id AND table_type = 'P' AND key_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = $user_id)
	UNION
	SELECT person_id FROM discuss_post WHERE update_date >= '$since_date' AND updated_by <> $user_id AND person_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = $user_id)
	UNION
	SELECT person_id FROM discuss_wiki WHERE update_date >= '$since_date' AND updated_by <> $user_id AND person_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = $user_id)
	UNION
	SELECT person_id FROM tree_image WHERE update_date >= '$since_date' AND updated_by <> $user_id AND person_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = $user_id)
	) t
	JOIN tree_person p ON t.person_id = p.person_id AND actual_start_date <= NOW() AND actual_end_date > NOW()
	ORDER BY p.family_name, p.given_name";

	$people = $db->select($sql);
	$T->assign("people", $people);

	if (count($relatives) > 0 || count($people) > 0) {
		$mailto = $u->data["email"];
		//$mailto = "trevor.allred@xdti.com";
		$body = $T->fetch('mail.tpl');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: mailer@sharedtree.com' . "\r\n";
		$headers .= 'Bcc: mailer@sharedtree.com' . "\r\n";
		mail($mailto, "Your SharedTree updates from $since_date", $body, $headers);
		echo "sent mail to $user_id<br>\n";
	}
}

?>
