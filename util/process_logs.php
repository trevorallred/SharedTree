<?
$base = "/var/www/html";
require_once("$base/config.php");
require_once("$base/inc/main.php");

set_time_limit (3600);

include_class("Log");
Log::load();
?>
