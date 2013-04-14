<?
require_once("config.php");
require_once("inc/main.php");

$T->caching = 2; // lifetime is per cache
$T->cache_lifetime = 3600*24; //once per day is plenty

$by_letter = $_REQUEST['letter'];
# Determine if the letter is an actual letter or a potential hack attempt
if ($by_letter > '' && !is_string($by_letter)) errorMessage("If you pass a letter it must be a valid string");

if(!$T->is_cached('family_list.tpl', $by_letter)) {
	include_class("Person");
	$letters = array("A"); // TODO manually create the list rather than doing an expensive query
	$letters = Person::listPersonIndex(1);
	$T->assign('letters', $letters );
	if (strlen($by_letter) > 0 && strlen($by_letter) < 4) {
		$families = Person::listFamiliesByLetter($_REQUEST['letter']);
		for($i=0; $i < count($families); $i++) {
			$families[$i]["font"] = 1;
			if ($families[$i]["total"] >= 10) $families[$i]["font"] = 3;
			if ($families[$i]["total"] >= 100) $families[$i]["font"] = 5;
			if ($families[$i]["total"] >= 1000) $families[$i]["font"] = 6;
			if ($families[$i]["total"] >= 10000) $families[$i]["font"] = 7;
		}
		$T->assign('families',$families);
		$T->assign('letter', $_REQUEST['letter']);
	} else {
		$sql = "SELECT family_name, count(*) total FROM tree_person
			WHERE public_flag = 1 and ".actualClause()."
			and family_name is not null and family_name not in ('Unknown')
			GROUP BY family_name ORDER BY total DESC LIMIT 0,25";
		$data = $db->select($sql);
		$T->assign('topnames', $data);
	}
	$T->assign('help',"Family Index");
}

$T->display('family_list.tpl', $by_letter);
?>
