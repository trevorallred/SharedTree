<?
require_once("config.php");
require_once("inc/main.php");

include_class("Family");

############################################################################
# Set common variables and assign them to the smarty template
$family_id = $_REQUEST['family_id'];
if (empty($family_id))
	$family_id = (int)substr($_SERVER['PATH_INFO'],1,16);
$T->assign('family_id',$family_id);
$action = $_REQUEST['action'];
$T->assign('action',$action);
$time = $_REQUEST['time'];
$T->assign('time',$time);
$T->assign("show_lds", $user->data['show_lds']);

if ($action == "delete") {
	require_login();
	$family = new Family($_REQUEST['family_id']);
	$_GLOBAL['update_process'] = __FILE__;
	$family->delete();
}

if ($action == "add_save") {
	require_login();
	$family = new Family();
	$_GLOBAL['update_process'] = __FILE__;
	//print_pre($_REQUEST['family']);
	$save = $_REQUEST['family'];
	$save["family_id"] = $_REQUEST['family_id'];
	$family_id = $family->save($save);
	if ($family_id > 0) {
		redirect("marriage.php?family_id=".$family_id);
		exit();
	} else {
		$family_id = $_REQUEST['family_id'];
	}
}
$T->assign("date_approx", array(""=>"", "ABT"=>"About", "CAL"=>"Calculated", "EST"=>"Estimated"));

include_class("Event");
$gedcomcodes = Event::GedcomCodes("F");
$T->assign("gedcomcodes", $gedcomcodes);

$marriage_statuses['M'] = "Married";
$marriage_statuses['D'] = "Divorced";
$marriage_statuses['S'] = "Separated";
$marriage_statuses['U'] = "Unknown";
$marriage_statuses['N'] = "Not Married";
$T->assign('marriage_statuses',$marriage_statuses);

if ($action == "add") {
	require_login();
	$add = $_REQUEST['add'];
	if ($add['person1_id'] > 0 OR $add['person2_id'] > 0) {
		$family = new Family();
		$family_id = $family->save($add);
		$action == "edit";
	} else {
		$T->assign('family',$_REQUEST['add']);
		$T->display('family_edit.tpl');
		exit;
	}
}
if (empty($family_id)) {
	die("family_id is required to view or edit");
}

$family = new Family($family_id, $time);
if (empty($family->data)) {
	# We found no active records, so let's try this again by searching for the last deleted record
	$time = $family->getDeleted($family_id);
	$T->assign('time',$time);
}

if (!empty($_REQUEST['removeparent'])) {
	if ($_REQUEST['removeparent'] == 1) $save['person1_id'] = null;
	if ($_REQUEST['removeparent'] == 2) $save['person2_id'] = null;
	if (count($save)) {
		$save['family_id'] = $family_id;
		$family->save($save);
		redirect("marriage.php?family_id=".$family_id."&action=edit");
		exit();
	}
}

if (!empty($_REQUEST['addparent'])) {
	$save['family_id'] = $family_id;
	if (empty($family->data['person1_id'])) $save['person1_id'] = $_REQUEST['addparent'];
	elseif (empty($family->data['person2_id'])) $save['person2_id'] = $_REQUEST['addparent'];
	if (count($save) > 1) {
		$save['family_id'] = $_REQUEST['family_id'];
		$family->save($save);
		redirect("family.php?family_id=".$family_id);
		exit();
	}
}
## View this marriage
//print_pre($family->data);
$T->assign('children',$family->getChildren());
//print_pre($family->children);

$T->assign('family',$family->data);
$T->assign('history',$family->getHistory());

if ($action == "edit") {
	require_login();
	$T->display('family_edit.tpl');
} else {
	$T->display('family_view.tpl');
}
?>
