<?
/**
 * This file contains the most basic, standard defaults for the application to run
 * do not include any even semi-complex functionality like connecting to a DB or instantiating large classes
 */
##########################################
# Default website settings               #
$config['BASE_DIR'] = dirname(__FILE__);
$config['SMARTY_LIB'] = '/usr/share/pear/Smarty/';

$config['BASE_URL'] = "http://www.sharedtree.com/";
$config['AREA_TYPE'] = "prod";

$config["cache_dir"] = "/usr/local/php/lib/php/Cache/";

$config["database_server"] = "";
$config["database_username"] = "sharedtree";
$config["database_password"] = "sharedtree";
$config["database_schema"] = "sharedtree";

$config['MAP_KEY'] = "INSERT_YOUR_GOOGLE_MAP_KEY_HERE";	// Google maps API key

$config["admin_email"] = "noreply@sharedtree.com";
$config["errors_email"] = "noreply@sharedtree.com";

error_reporting(E_ERROR|E_WARNING|E_PARSE);

##########################################
# Custom website settings
@include("config_local.php");

$time_start = microtime(true);

// The "end of time" date that we use
define('ARCHIVE_DATE', "4000-01-01");

// Number of seconds that must pass before the same person editing a file will create a new history slice
// This is to avoid MANY MANY timeslices because the same person just keeps clicking save over and over
define('ARCHIVE_SECONDS', 60*60);

$WIKI_URL = $config['BASE_URL'] . "wiki/index.php?title=";

// TODO move this to the misc page
### See if this is a bot
$is_bot = false;
$botlist = array(
	"Teoma",
	"alexa",
	"froogle",
	"inktomi",
	"looksmart",
	"URL_Spider_SQL",
	"Firefly",
	"NationalDirectory",
	"Ask Jeeves",
	"TECNOSEEK",
	"InfoSeek",
	"WebFindBot",
	"girafabot",
	"crawler",
	"www.galaxy.com",
	"Googlebot",
	"Scooter",
	"Slurp",
	"appie",
	"FAST",
	"WebBug",
	"Spade",
	"ZyBorg",
	"rabaz");

if (isset($HTTP_USER_AGENT)) foreach($botlist as $bot) if(ereg($bot, $HTTP_USER_AGENT)) $is_bot = true;

?>
