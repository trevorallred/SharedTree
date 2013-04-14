<?
/**
 * Various Test classes for the Willow web application
 *
 */

class Willow_TestRunner {
    private $suite;

    function __construct( PHPUnit2_Framework_TestSuite $suite ) {
        $this->suite = $suite;
    }

    function addFormatter( PHPUnit2_Framework_TestListener $formatter ) {
        $this->formatter = $formatter;
    }
    
    function run() {
        $result = new PHPUnit2_Framework_TestResult();
        $result->addListener( $this->formatter );
        $this->suite->run( $result );
    }
}

class Willow_TestFormatter implements  PHPUnit2_Framework_TestListener {
    private $previousFailure=false;

    public function addIncompleteTest(  PHPUnit2_Framework_Test $test,
                                        Exception $e) {
    }

    public function startTestSuite( PHPUnit2_Framework_TestSuite $suite) {
        print "<pre>starting suite: {$suite->getName()}\n";
    }

    public function startTest(PHPUnit2_Framework_Test $test) {
        if ( $test instanceof PHPUnit2_Framework_TestCase ) {
            print "\tstarting test: {$test->tostring()}\n";
        }
    }

    public function addFailure(
                    PHPUnit2_Framework_Test $test,
                    PHPUnit2_Framework_AssertionFailedError $e) {
        print "\t\tfailed: {$test->getName()}: {$e->getMessage()}\n";
        $this->previousFailure=true;
    }

    function addError(PHPUnit2_Framework_Test $test, Exception $e) {
        print "\t\tfailed: {$test->getName()}: {$e->getMessage()}\n";
        $this->previousFailure=true;
    }

    public function endTest( PHPUnit2_Framework_Test $test) {
        if ( ! $this->previousFailure ) {
            print "\t\tsucceeded!\n";
        }
        print "\tending test: ";
        if ( $test instanceof PHPUnit2_Framework_TestCase ) {
            print "{$test->tostring()}\n";
        }
        $this->previousFailure=false;
    }

    public function endTestSuite( PHPUnit2_Framework_TestSuite $suite) {
        print "ending suite: {$suite->getName()}\n</pre>\n";
    }
}
?>