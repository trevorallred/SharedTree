<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
require_once('inc/FSAPI/FamilySearchProxy.php');

//-- setup authentication credentials
$url = 'http://www.dev.usys.org';
$username = 'api-user-1115';
$password = '12fb';
$key = 'WCQY-7J1Q-GKVV-7DNM-SQ5M-9Q5H-JX3H-CMJK';

//-- ID of the person record to obtain
$id = 'KW3B-NW2,KW3B-KCK,KW3B-KCP,KW3B-KC5';

//-- extra parameters to constrain your request
$query = 'view=full&citations=false&notes=false';
$query = '';

//-- If there are errors coming back from the request, when
//--- $parseError set to false, the error will be in plain text,
//--- otherwise it will be in XML format.
$parseError = true;

//echo "querying Person from FamilySearch";

//--create a new object of FamilySearchProxy
$proxy = new FamilySearchProxy($url, $username, $password, $key);

//call the desire method
$response = $proxy->getPersonById($id, $query, $parseError);
echo $response;
?>
