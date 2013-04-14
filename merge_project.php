<?
// TODO Fix when spouses are matched identical

require_once("config.php");
require_once("inc/main.php");

require_login();
$T->assign('help', "How_To_Merge");
$T->assign('noindex',1); // don't index this page

include_class("MergeProject");

$action = $_REQUEST['action'];
if ($action=="") $action = "list";

if ($action == "list") {
	$data = MergeProject::listProjects();
	$T->assign("projects", $data);
	$T->display("merge_project_list.tpl");
	exit();
}

include_class("Person");
include_class("Family");
$project = new MergeProject($_REQUEST['project_id']);

if ($action == "reset") {
	$project->reset();
	redirect("merge_project.php?action=main&project_id=".$project->id);
	die();
}
$T->assign("action", $action);

if ($action == "saveproject") {
	$p1 = (int)$_REQUEST['p1'];
	$p2 = (int)$_REQUEST['p2'];
	if (empty($p1) || empty($p2)) {
		$T->display("header.tpl");
		errorMessage("You must supply two individuals to merge", "", false);
	}
	if ($p1 == $p2) errorMessage("For some reason you're trying to merge the same person");
	
	$data = array();
	$data['person1_id'] = $p1;
	$data['person2_id'] = $p2;

	$project->save($data);
	if (empty($project->id)) errorMessage("Failed to save Merge Project");

	redirect("merge_project.php?action=main&project_id=".$project->id);
}

###
$project->getProject();
$T->assign("project", $project->data);
###

if ($action == "select_child") {
	$project->matchChild($_REQUEST["person2_id"], $_REQUEST["person1_id"]);
	$T->assign("p1s", $project->current_matchchildren);
	$T->assign("p", $project->current_child);
	$T->display("merge_project_desc_child.tpl");
	exit();
}

if ($action == "select_spouse") {
	$project->matchSpouse($_REQUEST["family2_id"], $_REQUEST["family1_id"]);
	$T->assign("f1s", $project->current_matchfamilies);
	$T->assign("f", $project->current_spouse);
	$T->display("merge_project_desc_spouse.tpl");
	exit();
}

if ($action == "match_parent") {
	$project->matchParent($_REQUEST["family_id"], $_REQUEST["person_id"], $_REQUEST["match_action"]);
	$T->assign("pp2", $project->current_parents);
	$T->assign("pp1", $project->current_matchparents);
	$T->display("merge_project_desc_parents.tpl");
	exit();
}

if ($action == "project_summary") {
	$T->assign("summary", $project->getSummary());
	$T->display("merge_project_summary.tpl");
	exit();
}

if ($action == "main" || $action == "main_merge") {
	$p2 = new Person($project->data["person2_id"]);
	$T->assign("title", "Matching Family of ".$p2->data["full_name"]);
	$T->assign('tree', $project->getDescendents());
	
	if ($action == "main") {
		$T->assign('tree', $project->getDescendents());
	} else {
		# $action == "main_merge"
		$merge_list = $project->getMergeList();
		$T->assign('list', $merge_list);
		if (count($merge_list) > 0) {
			$T->assign('next_item', $merge_list[0]);
		}
	}
	$T->display("merge_project.tpl");
	if (false && $action == "main") {
		echo(__FILE__." line:".__LINE__);
		print_pre($project->getDescendents());	
	}
	exit();
}

die("no valid action specified");

?>
