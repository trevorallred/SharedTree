<?

require_once("config.php");
require_once("inc/main.php");

$T->assign('noindex',1); // don't index this page

$year = date("Y");
$birth_range = "AND p.birth_year BETWEEN ".($year-100)." AND ".($year-16);

$T->assign("help", "Invite Relatives");

if (is_array($_REQUEST["email"])) {
	$list = array();
	foreach($_REQUEST["email"] as $key=>$email) {
		if ($email > "") {
			$list[$key]["person_id"] = $key;
			$list[$key]["name"] = $_REQUEST["name"][$key];
			$list[$key]["email"] = $email;
		}
	}
}

if ($_REQUEST["send"]) {
	if (count($list) > 0) {
		$subject = $_REQUEST["subject"];
		$from_address = $user->data["given_name"]." ".$user->data["family_name"] . "<" . $user->data["email"] . ">";
		$headers = "From: $from_address";
		$message_orig = $_REQUEST["message"];
		foreach ($list as $person_id=>$person) {
			$secretkey = secret_key($person_id);
			$message = str_replace("SECRETKEY", $secretkey, $message_orig);
			$message = str_replace("PERSONS_NAME", $person["name"], $message);
			$message = str_replace("PERSON_ID", $person_id, $message);
			$to = $person["email"];
			//echo $to. " <br>";
			SendEmail($to, $from_address, $subject, $message );
		}
		$T->assign("sent", $list);
	}
}

if ($_REQUEST["invite"]) {
	if (count($list) > 0) {
		$T->assign("list", $list);
		$T->assign("user", $user->data);
		$T->display('invite.tpl');
		exit();
	}
}

$sql = "SELECT p.person_id, p.given_name, p.family_name, l.trace
		FROM app_user_line_person l
		JOIN tree_person p ON l.person_id = p.person_id AND ".actualClause("p")."
		LEFT JOIN app_user u ON p.person_id = u.person_id
		WHERE l.user_id = '$user->id' AND u.user_id IS NULL AND p.given_name > '' AND p.family_name > '' AND p.public_flag = 0 $birth_range
		ORDER BY distance LIMIT 0, 200";
$relatives = $db->select($sql);
$T->assign("relatives", $relatives);

$T->display('invite.tpl');
?>
