<?
require_once("config.php");
require_once("inc/main.php");

require_login();
$T->assign('noindex',1); // don't index this page

include_class("Watch");

$action = $_REQUEST['action'];

if ($action == "unwatch") {
	$data = Watch::get($_REQUEST['watch_id']);
	if ($data !== false) {
		if ($data["user_id"] <> $user->id) errorMessage("You don't have permission to delete this watch");
		Watch::delete($_REQUEST['watch_id']);
		redirect("/person/".$data["person_id"]);
	}
}
if ($action == "save") {
	Watch::save($_REQUEST["data"]);
	redirect("watch.php");
}

$watchlist = Watch::listUserWatch($user->id);

$T->assign('watchlist', $watchlist);
$T->display('watch_list.tpl');

?>
