<?
//echo 7;
//phpinfo();
//die();

require_once("config.php");
require_once("inc/main.php");
include_class("Cpdf");

$lib = new Cpdf(array(0,0,100,30));
$lib->selectFont("c:/hosting/webhost4life/member/trevorallred/fonts/Times-Roman.afm");

$lib->addText(10, 10, 12, "Hello world", 0);

$lib->stream();
?>b


