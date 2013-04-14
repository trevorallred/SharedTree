<?

require_once("config.php");
require_once("inc/main.php");

include_class("Group");
$T->assign("help", "Groups");

############################################################################
# Set common variables and assign them to the smarty template
$group_id = $_REQUEST['group_id'];
$T->assign('group_id',$group_id);
$person_id = $_REQUEST['person_id'];
$T->assign('person_id',$person_id);
$action = $_REQUEST['action'];

############################################################################
# Add/save groups

if ($action == "save") {
	# Save a new or existing individual
	require_login();
	$group = new Group($group_id, 0);
	$group_id = $group->save($_REQUEST);
	redirect("group.php?group_id=$group_id");
	exit();
}

if ($action == "edit") {
	# Save a new or existing group
	require_login();
	$group = new Group($group_id, 0);
	$T->assign("group", $group->data);

	$T->assign("title", "Edit Group");
	$T->display('group_edit.tpl');
	exit();
}

############################################################################
# Add group members

if ($action == "addmember") {
	# Save a new or existing individual
	require_login();
	Group::addMember($group_id, $person_id);
	redirect("group.php?group_id=$group_id");
	exit();
}

if ($action == "deletemember") {
	# Save a new or existing individual
	require_login();
	Group::deleteMember($group_id, $person_id);
	redirect("/person/$person_id");
	exit();
}

############################################################################
# Show Groups

if ($group_id > 0) {
	# Show group
	$page = 1;
	if (isset($_REQUEST["page"])) $page = $_REQUEST["page"];

	$group = new Group($group_id, $page);
	$T->assign("group", $group->data);
	$T->assign("members", $group->members);
	$T->assign("pages", $group->pages);
	$T->assign("thispage", $_REQUEST["page"]);

	//print_pre($group->members);

	$T->assign("title", "Group - " . $group->data["group_name"]);
	$T->display('group_view.tpl');
	exit();
}
$sort = $_REQUEST["sort"];
if (empty($sort)) $sort = "member_count DESC";
$T->assign("groups", Group::listGroups($_REQUEST["byear"], $_REQUEST["dyear"], $sort) );

$T->assign("title", "Groups");
$T->display('group_list.tpl');
?>
