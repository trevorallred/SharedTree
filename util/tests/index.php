<?
require_once("../config.php");
require_once("../inc/main.php");
require_login();

require_once 'PHPUnit2/Framework/TestCase.php';
require_once('PHPUnit2/Framework/TestResult.php');
require_once('PHPUnit2/Framework/TestSuite.php');
require_once('willow_framework.php');

$suite = new PHPUnit2_Framework_TestSuite( );

/*
    	if ($this->person_id > 0) {
	    	global $db;
	    	$sql = "DELETE FROM tree_event WHERE key_id = $this->person_id AND table_type = 'P' ";
	    	$db->sql_query();
	    	$sql = "DELETE FROM tree_person WHERE person_id = $this->person_id";
	    	$db->sql_query();
    	}
*/
#########################################
# Include all of the test objects here  #
include_once("Person.test.php");
$suite->addTestSuite( "Test_Person");

#########################################
# Run the Suite now                     #
$runner = new Willow_TestRunner( $suite );
$runner->addFormatter( new Willow_TestFormatter() );
$runner->run();

?>