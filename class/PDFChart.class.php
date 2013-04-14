<?php
$debug = false;

include_class("Cpdf");

/**
 * base class to draw different types of PDF charts
 */
class PDFChart {
	protected $lib;
	public $anc = array(); # All of the ancestors
	public $desc = array(); # All of the descendents

	protected $font = "";
	public $fontsize = 11;
	public $margin = 20;
	public $linespacing = 1; //smaller means closer text

	function __construct() {
	}

	public function drawPage() {
		$this->lib->stream();
	}
}

?>