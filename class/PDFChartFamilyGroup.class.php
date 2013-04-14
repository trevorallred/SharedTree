<?php
include_class("Cpdf");

/**
 * draw circular pdf based on php array of genealogical data
 */
class PDFChartFamilyGroup {
	public $font = "Times-Roman"; // Default font

	private $lib;
	private $marr = array(); # All of the descendents
	private $children = array(); # All of the descendents

	function __construct() {
	}

	public function drawPage($marr, $children) {
		$this->marr = $marr;
		$this->children = $children;
		
		$dpi = 72;
		$this->lib = new Cpdf(array(0,0,$dpi*8.5,$dpi*11));
		global $config;
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font.".afm");

		$this->lib->addText(20, 20, 20, "Family Group Sheet");

		# Print the Parents
		$this->lib->addText(100, 100, 40, "UNDER CONSTRUCTION", -45);
		//print_pre($this->marr);
		//print_pre($this->children);
		//die();

		# Close page
		$this->lib->stream();
	}

	##############################################################################
	# Helper functions to Draw Page
	##############################################################################

}

?>