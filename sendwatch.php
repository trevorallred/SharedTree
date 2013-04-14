<?
require_once("config.php");
require_once("inc/main.php");

include_class("Person");

############################################################################
# Set common variables and assign them to the smarty template
Person::mailWatch($_REQUEST['person_id']);
?>
