<?
require_once("config.php");
require_once("inc/main.php");

require_login();
$T->assign('help', "How_To_Merge");
$T->assign('noindex',1); // don't index this page

include_class("Person");
include_class("Family");

$p1 = 0;
$p2 = 0;
if (isset($_REQUEST["p1"])) $p1 = (int)$_REQUEST["p1"];
if (isset($_REQUEST["p2"])) $p2 = (int)$_REQUEST["p2"];

$action = empty($_REQUEST['action']) ? "review" : $_REQUEST['action'];
$T->assign("returnto", $_REQUEST['returnto']);
$T->assign("ajax", $_REQUEST['ajax']);

if (isset($_REQUEST["undo"])) {
	unmerge($_REQUEST["undo"]);
	if ($_REQUEST['returnto'] > "") redirect($_REQUEST['returnto']);
	redirect("merge.php?action=list&matched=".$_REQUEST["undo"]);
}

if ($action == "match") {
	// select matches by either import batch or only your records or all
	$where = "";
	$import_id = (int)$_REQUEST["import_id"];
	if ($import_id > 0) $where .= " AND p.person_id IN (SELECT person_id FROM tree_person_gedcom WHERE import_id = $import_id)";
	if ($where=="" && !isset($_REQUEST["all"])) $where = " AND (created_by = $user->id) ";
	include_class("Match");
	Match::process($where);
	exit();
}

if ($action == "list") {
	$where = " AND (m.updated_by = '$user->id' OR p1.created_by = '$user->id' OR p2.created_by = '$user->id') ";
	$where .= " AND p1.created_by <> p2.created_by";

	if ($_REQUEST["show"] == "all") $where = "";

	$T->assign("show", $_REQUEST["show"]);

	$import_id = (int)$_REQUEST["import_id"];
	if ($import_id > 0) {
		$T->assign("import_id", $import_id);
		$where .= " AND (m.person_from_id IN (SELECT person_id FROM tree_person_gedcom WHERE import_id = '$import_id') OR m.person_from_id IN (SELECT person_id FROM tree_person_gedcom WHERE import_id = '$import_id'))";
	}

	if (isset($_REQUEST["next"])) {
		$sql = "SELECT m.person_from_id, m.person_to_id FROM app_merge m 
			JOIN tree_person p1 ON m.person_to_id = p1.person_id AND ".actualClause("p1")." AND (p1.public_flag = 1 OR p1.created_by = $user->id)
			JOIN tree_person p2 ON m.person_from_id = p2.person_id AND ".actualClause("p2")." AND (p2.public_flag = 1 OR p2.created_by = $user->id)
			WHERE m.status_code IN ('P','Q') and m.similarity_score > .5 and m.person_from_id <> m.person_to_id $where ORDER BY similarity_score DESC, m.update_date DESC LIMIT 0, 1";
		$data = $db->select($sql);
		if (count($data) > 0) {
			$action = "review";
			$p1 = (int)$data[0]["person_to_id"];
			$p2 = (int)$data[0]["person_from_id"];
		}
	}
}

if ($action == "list") {
	$limit = 50;
	$start = 0;
	if ($_REQUEST["page"] > 1) {
		$start = $limit * ((int)$_REQUEST["page"] - 1);
		$T->assign("page", $_REQUEST["page"]);
	} else {
		$T->assign("page", 1);
	}

	$sql = "SELECT count(*) total FROM app_merge m
			JOIN tree_person p1 ON m.person_to_id = p1.person_id AND ".actualClause("p1")." AND (p1.public_flag = 1 OR p1.created_by = $user->id)
			JOIN tree_person p2 ON m.person_from_id = p2.person_id AND ".actualClause("p2")." AND (p2.public_flag = 1 OR p2.created_by = $user->id)
			WHERE m.status_code IN ('P','Q') and m.similarity_score > .5 and m.person_from_id <> m.person_to_id $where";
	$data = $db->select($sql);
	$pages = ceil($data[0]["total"] / $limit);
	//echo "Page: ".$_REQUEST["page"]." Total: ".$data[0]["total"]." Pages: $pages";
	$T->assign("pages", $pages);

	$sql = "SELECT m.merge_id, round(m.similarity_score,2) as similarity_score, m.person_from_id, m.person_to_id,
			p1.given_name given_name1, p1.family_name family_name1, p1.birth_year birth_year1, p1.bio_family_id family_id1,
			p2.given_name given_name2, p2.family_name family_name2, p2.birth_year birth_year2, p2.bio_family_id family_id2
			FROM app_merge m
			JOIN tree_person p1 ON m.person_to_id = p1.person_id AND ".actualClause("p1")." AND (p1.public_flag = 1 OR p1.created_by = $user->id)
			JOIN tree_person p2 ON m.person_from_id = p2.person_id AND ".actualClause("p2")." AND (p2.public_flag = 1 OR p2.created_by = $user->id)
			WHERE m.status_code IN ('P','Q') and m.similarity_score > .5 and m.person_from_id <> m.person_to_id $where
			ORDER BY similarity_score DESC, m.update_date DESC LIMIT $start, $limit";
	$data = $db->select($sql);

	// We need to get the parents and the spouses but I want to simplify the queries a bit
	// I would be more inclined to get the parents in the query above if I could use views
	foreach($data as $row) {
		if ($row['family_id1'] > 0) $families[] = (int)$row['family_id1'];
		if ($row['family_id2'] > 0) $families[] = (int)$row['family_id2'];
		$people[] = (int)$row['person_from_id'];
		$people[] = (int)$row['person_to_id'];
	}

	$parents = Family::getParents($families);
	$spouses = Family::getSpouses($people);
	//print_pre($spouses);

	# Color the matches by score and add the parent and spouse data
	foreach ($data as $key=>$value) {
		$score = $value['similarity_score'];
		$color = "#FF0033";
		if ($score >= .5) $color = "#CC3333";
		if ($score >= .6) $color = "#996633";
		if ($score >= .7) $color = "#669933";
		if ($score >= .85) $color = "#33CC33";
		if ($score >= 1) $color = "#00FF33";
		if ($score >= 1.2) $color = "#33FF00";
		$data[$key]['color'] = $color;
		$data[$key]['parents_from'] = $parents[$value['family_id1']];
		$data[$key]['parents_to'] = $parents[$value['family_id2']];
		$data[$key]['spouses_from'] = $spouses[$value['person_from_id']];
		$data[$key]['spouses_to'] = $spouses[$value['person_to_id']];
	}

	$T->assign("matches", $data);
	$T->assign("matched", $_REQUEST["matched"]);
	$T->display('merge_list.tpl');
	exit();
}

# p1 should always be less than p2
# we always "keep" p1
if ($p1 > $p2) {
	$temp = $p1;
	$p1 = $p2;
	$p2 = $temp;
}

if (empty($p1)) errorMessage("Two people are required to merge", "", false);

if ($_REQUEST['message']) {
	include_class("DiscussPost");
	$data['person_id'] = $p1;
	$data['post_text'] = "<i>Merged previous person $p2 with the following message:</i>\n".$_REQUEST['message'];
	DiscussPost::save($data);
	//print_pre($data);
}

if ($_REQUEST['save']) {
	mergePeople($p1, $p2, $_REQUEST['merge'], $_REQUEST['merge_parents']);

	if (isset($_REQUEST["next"])) $next = $_REQUEST["next"];
	else $next = "list";

	if ($_REQUEST['returnto'] && $next == "return" ) {
		# Never return to the deleted person, always return to the person that is staying
		$newurl = str_replace($p2, $p1, $_REQUEST['returnto']);
		redirect($newurl);
	}
	if ($next == "next") redirect("merge.php?action=list&next=".$_REQUEST['next']."&matched=".$p1);
	if ($next == "show") redirect("family/".$p1);

	redirect("merge.php?action=list&matched=$p1");
}

if ($_REQUEST['reject']) {
	// TODO update/insert into app_merge for rejected records
	//($p1, $p2, $_REQUEST['merge']);
	$sql = "UPDATE app_merge SET status_code = 'R', updated_by = '$user->id', update_date = Now()
		WHERE (person_from_id = '$p1' AND person_to_id = '$p2')
		OR (person_from_id = '$p2' AND person_to_id = '$p1') ";
	$db->sql_query($sql);
	$rows_affected = $db->sql_affectedrows();
	if ($rows_affected == 0) {
		$sql = "INSERT INTO app_merge (person_from_id, person_to_id, status_code, updated_by, update_date) 
				VALUES ($p2, $p1, 'M', $user->id, Now())";
		$db->sql_query($sql);
	}

	if ($_REQUEST['returnto'] > "") redirect($_REQUEST['returnto']);
	redirect("merge.php?action=list");
}

########################################################
# Show the information to be merged
if ($p1 == $p2) errorMessage("For some reason you're trying to merge the same person");

$person1 = new Person($p1);
$person2 = new Person($p2);
if (empty($person2->id) || empty($person1->id)) errorMessage("Two people are required to merge");

if ($person2->restricted || $person1->restricted) errorMessage("One of these individuals is still presumed living, to which you do not have access to.");
$person1->updateMergeRank();
$person2->updateMergeRank();

include_class("Event");
$gedcomcodes = Event::GedcomCodes("P");
$T->assign("gedcomcodes", $gedcomcodes);

$families = array();
if ($person1->data["bio_family_id"] > 0) $families[] = $person1->data["bio_family_id"];
if ($person2->data["bio_family_id"] > 0) $families[] = $person2->data["bio_family_id"];
$parents = Family::getParents($families);
$spouses = Family::getSpouses(array($p1, $p2));

$person1->data["parents"] = $parents[$person1->data["bio_family_id"]];
$person2->data["parents"] = $parents[$person2->data["bio_family_id"]];
$person1->data["spouses"] = $spouses[$p1];
$person2->data["spouses"] = $spouses[$p2];

include_class("DiscussWiki");
$wiki = DiscussWiki::getWiki($person1->id);
$person1->data['wiki_text'] = substr(DiscussWiki::convertToHTML($wiki[0]['wiki_text']), 0,255);
$wiki = DiscussWiki::getWiki($person2->id);
$person2->data['wiki_text'] = substr(DiscussWiki::convertToHTML($wiki[0]['wiki_text']), 0,255);

include_class("DiscussPost");
$forumposts = DiscussPost::getPosts("p.person_id IN ($p1, $p2)");
$T->assign("forumposts", $forumposts);
//print_pre($forumposts);

//print_pre($person1->data);
//die();

$T->assign("p1", $person1->data);
$T->assign("p2", $person2->data);
$T->assign("return_to", "merge.php?p1=".$person1->id."&p2=".$person2->id);
$T->display('merge_view.tpl');

exit();

########################################################
function mergePeople($p1, $p2, $merge) {
	global $db, $user;
	if ($p1 == $p2) return false;
	$person1 = new Person($p1);
	$person2 = new Person($p2);
	if (empty($person1->id) || empty($person2->id)) errorMessage("Two people are required to merge");

	# If parents aren't the same, then add them to the queue for merge
	if ($person1->data["bio_family_id"] > 0 AND $person2->data["bio_family_id"] > 0 AND $person1->data["bio_family_id"] <> $person2->data["bio_family_id"]) {
		# The parents are both set and different
		$family1 = new Family($person1->data["bio_family_id"]);
		$family2 = new Family($person2->data["bio_family_id"]);

		# Add parents to the queue for later review
		addQueue($family1->data["person1_id"], $family2->data["person1_id"]); // add fathers
		addQueue($family1->data["person2_id"], $family2->data["person2_id"]); // add mothers
	}
	//$newdata = $person1->data;
	# Figure out what data to move from p1 to p2
	foreach ($merge as $key=>$value) {
		if ($key == "e") {
			# Event data
			foreach ($value as $event_type=>$event) {
				foreach ($event as $field=>$value2) {
					if ($value2 == 2 && $person1->data["e"][$event_type][$field] <> $person2->data["e"][$event_type][$field]) {
						$newdata["e"][$event_type][$field] = $person2->data["e"][$event_type][$field];
					}
				}
			}
		} else {
			# Person data
			if ($value == 2 && $person1->data[$key] <> $person2->data[$key]) {
				$newdata[$key] = $person2->data[$key];
			}
		}
	}

	//print_pre($merge);
	# Merge the biography
	if ($merge["wiki_text"] == 2) {
		# Move wiki over replacing the old one
		include_class("DiscussWiki");

		$wiki2 = DiscussWiki::getWiki($person2->id);

		$wiki1 = DiscussWiki::getWiki($person1->id);
		$save["person_id"] = $person1->id; // just in case we didn't return anything
		$save["wiki_id"] = $wiki1[0]["wiki_id"]; // don't insert a new one if one already exists
		$save["page_name"] = $wiki2[0]["page_name"];
		$save["wiki_text"] = $wiki2[0]["wiki_text"];

		//print_pre($save);
		DiscussWiki::save($save);
	}

	$person1->save($newdata);
	$person2->transfer($person1->id);
	$person2->delete($person1->id);

	$sql = "UPDATE app_merge SET status_code = 'M', updated_by = '$user->id', update_date = Now()
		WHERE (person_from_id = '$p1' AND person_to_id = '$p2')
		OR (person_from_id = '$p2' AND person_to_id = '$p1') ";
	$db->sql_query($sql);
	$rows_affected = $db->sql_affectedrows();
	if ($rows_affected == 0) {
		$sql = "INSERT INTO app_merge (person_from_id, person_to_id, status_code, updated_by, update_date) 
				VALUES ($p2, $p1, 'M', $user->id, Now())";
		$db->sql_query($sql);
	}
}

function unmerge($merge_id) {
	global $db, $user;

	$merge_id = (int)$merge_id;

	$sql = "SELECT m.*, p1.person_id p1_id, p2.person_id p2_id FROM app_merge m 
			LEFT JOIN tree_person p1 ON p1.person_id = m.person_to_id AND ".actualClause("p1")."
			LEFT JOIN tree_person p2 ON p2.person_id = m.person_from_id AND ".actualClause("p2")."
			WHERE merge_id = '$merge_id'";
	$data = $db->select($sql);
	# Double check that these people actually need unmerging
	$p1 = $data[0]["p1_id"];
	$p2 = $data[0]["p2_id"];
	if ($p1 > 0 && $p2 > 0) return false; // both people are already restored
	if ($p1 == '' && $p2 == '') return false; // both people have already been deleted

	if ($p1 > 0) {
		$p1 = $data[0]["person_to_id"];
		$p2 = $data[0]["person_from_id"];
	} else {
		$p1 = $data[0]["person_from_id"];
		$p2 = $data[0]["person_to_id"];
	}
	$savedate = $data[0]["update_date"];

	# undo the merge in reverse order
	Person::restore($p2); // undelete the person we merged
	Family::restoreMarriages($p2, $savedate); // restore their marriages
	Person::unsave($p1, $savedate); // revert changes to the person we merged into
	Family::restoreMarriages($p1, $savedate); // restore their marriages

	# Update messages from $this->id to $to_user
	$sql = "UPDATE discuss_post SET person_id = $p2 WHERE merged_from = $p2";
	//$this->db->sql_query( $sql );

	# Reset the merge
	$sql = "UPDATE app_merge SET status_code = 'Q', updated_by = '$user->id', update_date = Now() WHERE merge_id = '$merge_id'";
	$db->sql_query( $sql );

	# Find all merge records addeded to queue by this user during this time and see if we should remove them
	// TODO
}
?>
