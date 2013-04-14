<?
require_once("misc.php");
include_class("ThisException");
include_class("GenTreeSmarty");
$T = new GenTreeSmarty();
$T->assign("domain_name", $config['BASE_URL']);
$T->assign("area_type", $config['AREA_TYPE']);

require_once("db.php");

/*
## Track Referrers
# we must track referrers before we check URL to redirect
# I CAN'T FIGURE THIS OUT!!!
if ($_REQUEST["person_id"] == 44365) {
	$url_ref = substr($_SERVER["HTTP_REFERER"], 0, 25);

	phpinfo();
	echo $url_ref;
	if ($_SERVER["HTTP_REFERER"] > "" && $url_ref <> "http://www.sharedtree.com") {
		//echo $url_ref;

		$sql = "DELETE FROM app_referrer WHERE last_click_date < DATE_SUB(CURDATE(),INTERVAL 7 DAY)";
		$db->sql_query($sql);

		$url_ref = fixTick($_SERVER["HTTP_REFERER"]);
		$url_dest = $_SERVER["PHP_SELF"];
		if ($_SERVER["QUERY_STRING"] > "") $url_dest .= "?".$_SERVER["QUERY_STRING"];
		$url_dest = fixTick($url_dest);
		$remote_address = fixTick($_SERVER["REMOTE_ADDR"]);
		$sql = "INSERT app_referrer (referring_url, dest_url, last_click_date, clicks, ip_address)
				VALUES ('$url_ref', '$url_dest', NOW(), 1, '$remote_address')
				ON duplicate KEY UPDATE last_click_date=NOW(), clicks=clicks+1, ip_address='$remote_address'";
		$db->sql_query($sql);
	}
}
*/

redirectURL();
# use a function so it doesn't affect global variables
function redirectURL() {
	global $mobile;
	$mobile = false;
	# don't redirect if the url is this
	if (!isset($_SERVER["SERVER_NAME"])) return;
	if ($_SERVER["SERVER_NAME"] == "www.openwillow.net") return;
	if ($_SERVER["SERVER_NAME"] == "openwillow.net") return;
	if ($_SERVER["SERVER_NAME"] == "localhost") return;

	$urls = explode(".", $_SERVER["SERVER_NAME"]);

	# Convert ?????.??? to www.?????.???
	if (count($urls) == 2) {
		$urls[2] = $urls[1];
		$urls[1] = $urls[0];
		$urls[0] = "www";
	}

	# Convert www.?????.??? to www.sharedtree.com
	if (count($urls) == 3) {
		$urls[1] = "sharedtree";
		$urls[2] = "com";
		if ($urls[0]=="mobile") $mobile = true;
	}

	$new_url = implode(".", $urls);
	# If the new url is already sharedtree, then just exit
	if ($new_url == $_SERVER["SERVER_NAME"]) return;

	# Put REQUEST_URI back on to the new_url
	# For some reason this paramater disappeared on this server??? WTF??
	# So I am building it from SCRIPT_NAME & QUERY_STRING instead
	$new_url .= $_SERVER["SCRIPT_NAME"];
	if ($_SERVER["QUERY_STRING"] > "") $new_url .= "?".$_SERVER["QUERY_STRING"];

	header("Location: http://$new_url");
	exit();
}

### Include the CacheLite
$cache_class = $config["cache_dir"].'Lite.php';
if (!file_exists($cache_class))
	die("The PEAR Cache_Lite package has not been installed. Please set config cache_dir to your pear cache lite directory. cache_dir=".$config["cache_dir"]);
require_once($config["cache_dir"].'Lite.php');

### Start the session
include_class("Session");
$session = new session();
session_name("a");   // Session ID var is called 'a'
# we may want to consider not starting sessions for bots
session_start();	 // Start the session - on every page
$session->user_id = $_SESSION['user_id'];

### Track Page Views
if (!isset($track_hit)) $track_hit = true;
//$track_hit = false; // turn this off for now (trying to solve performance issues
include_class("User");
$user = new User($_SESSION['user_id']);
$user->recordVisit();
$T->assign('user', $user->data);
$T->assign('is_logged_on', is_logged_on());

### Track Page Views
$track_hit = true;
//$track_hit = false; // turn this off for now (trying to solve performance issues
if ($is_bot) $track_hit = false;
if ($_SERVER["SCRIPT_NAME"] == "/image.php") $track_hit = false;
if ($track_hit) {
	// We'll do this until our page views gets too big ;)
	$sql = "UPDATE LOW_PRIORITY mw_site_stats SET ss_total_views = ss_total_views + 1 WHERE ss_row_id = 1";
	$db->sql_query($sql);
}

########################################################
# get page view count and build functions for display

//$sql = "SELECT ss_total_views FROM mw_site_stats WHERE ss_row_id = 1";
//$data = $db->select($sql);
//$page_views = number_format($data[0]["ss_total_views"], 0, "", ",");
//$T->assign("page_views", number_format($data[0]["ss_total_views"], 0, "", ","));
function smarty_page_views($params, &$smarty) {
	global $page_views;
	return $page_views;
	return $params['page_views'];
}
// Can't get this to work
//$T->registerPlugin('page_views', 'smarty_page_views', false, array('page_views'));

date_default_timezone_set('America/Los_Angeles');

?>
