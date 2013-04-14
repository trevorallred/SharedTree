<?php
include_class("Cpdf");

/**
 * draw circular pdf based on php array of genealogical data
 */
class PDFChartPedigree {
	private $font = "Helvetica"; # Default font
	public $margin = 2;
	public $gen_width = 130;
	private $cell_height;
	private $lastlinediff;
	private $fontheight2 = 8; # font height of the information (below name)

	private $lib;
	private $data = array(); # All of the people
	private $gen = 0;

	function __construct() {
	}

	public function drawPage($data, $gen) {
		$hname = 16; # for the height of the person's name
		$hfather = $this->fontheight2 * 6; #for the height of the father's information
		$hmother = $this->fontheight2 * 4; #for the height of the mother's information

		# space between the father and mother in the last generation
		$this->lastlinediff = ($hname + $hfather) / 2; # 40
		# total space occupied by a couple in the last generation
		$this->cell_height = $hname * 2 + $hfather + $hmother; #140

		if ($gen < 3) $gen = 3;
		$this->gen = $gen;
		$this->data = $data;

		$page_width = ($this->gen * $this->gen_width) + ($this->margin * 2);
		$page_height = (pow(2, $this->gen - 2) * $this->cell_height) + ($this->margin * 2);

		$this->lib = new Cpdf(array(0, 0, $page_width, $page_height));

		$this->lib->setLineStyle(1);
		# take the space at the bottom (last grandmother's info) - top (last grandfather's name) 
		# and split the difference in half along with the entire page middle 
		# to get the offset for the starting point
		$height = ($page_height/2) + ($hmother - $hname)/2;
		$this->lib->line($this->margin, $height, $this->margin + $this->gen_width, $height);

		$textstart = $this->margin;
		global $config;
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font."-Bold.afm");
		$this->lib->addText($this->margin, $page_height - $this->margin - 20, 20, "Pedigree Chart");
		$this->lib->addText($this->margin, $this->margin + 10, 16, "SharedTree.com");

		$this->lib->addText($textstart, $height+2, 11, $this->shortenText($data["name"], 11));

		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font."-Oblique.afm");
		$this->lib->addText($this->margin, $this->margin, 10, "Created:" . date("j M Y") );

		$fontsize = 8;
		$fh = $this->fontheight2; # alias for easier reference
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font.".afm");
		$this->lib->addText($textstart, $height - $fh*1, $fontsize, "B: ".$data["bdate"]);
		$this->lib->addText($textstart, $height - $fh*2, $fontsize, "P: ".$data["bplace"]);
		$this->lib->addText($textstart, $height - $fh*3, $fontsize, "D: ".$data["ddate"]);
		$this->lib->addText($textstart, $height - $fh*4, $fontsize, "P: ".$data["dplace"]);

		$this->drawParents($this->data, $height);
		//die();

		# Close page
		$this->lib->stream();
	}
	
	private function drawParents($data, $height, $gen=1) {
		if ($this->gen <= $gen) return;
		$start = $this->margin + ($this->gen_width * $gen);

		$cells = pow(2, ($this->gen - $gen) - 2);
		$distance = $this->cell_height * $cells / 2;
		if ($distance < $this->lastlinediff) {
			# This is the last generation, so the offset is a little different
			$distance = $this->lastlinediff;
		}
		//$debug = "Gen: $gen Cells: $cells Dist: $distance";
		//$this->lib->addText($start - $this->gen_width, $height + 20, 10, $debug);

		$fheight = $height + $distance;
		$mheight = $height - $distance;
		$this->lib->line($start, $fheight, $start + $this->gen_width, $fheight );
		$this->lib->line($start, $mheight, $start + $this->gen_width, $mheight );
		$this->lib->line($start, $fheight, $start, $mheight );

		$textstart = $start + 1;
		# Draw Parent's names (in bold)
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font."-Bold.afm");
		$this->lib->addText($textstart, $fheight+2, 10, $this->shortenText($data["father"]["name"], 10));
		$this->lib->addText($textstart, $mheight+2, 10, $this->shortenText($data["mother"]["name"], 10));

		# Add photos for parents
		// Can't seem to get this to work...need more time
		//$handle = fopen("http://openwillow.org/image.php?image_id=9&size=thumb", "r");
		//$im = @imagecreatefromjpeg("http://openwillow.org/image.php?image_id=9&size=thumb");
		//$this->lib->addImage($im, 200, 200, 100, 100);
		//$this->lib->addJpegFromFile("image.php?image_id=9&size=thumb", 100, 100, 100, 100);
		//die();

		# Draw Parent's info in smaller regular font
		$fontsize = 8;
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font.".afm");
		$fh = $this->fontheight2; # alias for easier reference
		$this->lib->addText($textstart, $fheight - $fh*1, $fontsize, "B: ".$data["father"]["bdate"]);
		$this->lib->addText($textstart, $fheight - $fh*2, $fontsize, "P: ".$this->shortenText($data["father"]["bplace"],$fontsize));
		$this->lib->addText($textstart, $fheight - $fh*3, $fontsize, "M: ".$data["father"]["mdate"]);
		$this->lib->addText($textstart, $fheight - $fh*4, $fontsize, "P: ".$this->shortenText($data["father"]["mplace"],$fontsize));
		$this->lib->addText($textstart, $fheight - $fh*5, $fontsize, "D: ".$data["father"]["ddate"]);
		$this->lib->addText($textstart, $fheight - $fh*6, $fontsize, "P: ".$this->shortenText($data["father"]["dplace"],$fontsize));

		$this->lib->addText($textstart, $mheight - $fh*1, $fontsize, "B: ".$data["mother"]["bdate"]);
		$this->lib->addText($textstart, $mheight - $fh*2, $fontsize, "P: ".$this->shortenText($data["mother"]["bplace"],$fontsize));
		$this->lib->addText($textstart, $mheight - $fh*3, $fontsize, "D: ".$data["mother"]["ddate"]);
		$this->lib->addText($textstart, $mheight - $fh*4, $fontsize, "P: ".$this->shortenText($data["mother"]["dplace"],$fontsize));

		# Draw father's parents
		$this->drawParents($data["father"], $fheight, $gen + 1);

		# Draw mother's parents
		$this->drawParents($data["mother"], $mheight, $gen + 1);
	}

	private function checkTextSize(&$text, $size) {
		$max = $this->gen_width - 3;
		
		$text = trim($text);
		$width = $this->lib->getTextWidth($size, $text);
		return $width < $max;
	}
	/**
	 * This is the function to shorten names of people and places down so they fit in a chart
	 *
	 * TODO This function should probably be rewritten later since it only handles a few scenarios
	 */
	private function shortenText($text, $size) {
		if ($this->checkTextSize($text, $size)) return $text;
		
		# Too long, remove middle piece if any
		$pieces = explode(" ", $text);
		for($i=0; $i < count($pieces); $i++) {
			if ($this->checkTextSize($text, $size)) return $text;
			$pieces = explode(" ", $text);
			$trimmed = false;
			$text = "";
			foreach($pieces as $key=>$piece) {
				if (!$trimmed // only trim once per loop
					&& strlen($piece) > 1 // only trim text if more than one character
					&& $key > 0 // don't trim the first word (yet)
					&& $key < (count($pieces)-1)) // don't trim the last word
				{
					$trimmed = true;
					$text .= substr($piece,0,1)." "; // trim the word
				} else {
					$text .= $piece." "; // add in the other words as they were
				}
			}
		}
		return "~".$text;
		
		# Trim the first word
		$pieces = explode(" ", $text);
		foreach($pieces as $key=>$piece) {
			if ($key == 0) {
				$text = substr($piece,0,1)." "; // trim the word
			} else {
				$text .= $piece." "; // add in the other words as they were
			}
		}
		if ($this->checkTextSize($text, $size)) return $text;
		
		$chars = string_len($text);
		while($chars > 1) {
			$chars--;
			$text = substring($text, 0, $chars);
			if ($this->checkTextSize($text, $size)) return $text;
		}
		return $text;
	}
	private function getGenRadius($i) {
		if ($i == 0) return $this->center_circle / 2;
		return $this->center_circle + ($i * $this->cell_width) - ($this->cell_width / 2);
	}
	private function getFontSize($i, $small=false) {
		$f = (($this->gen - $i) * 1.5) + 6;
		if ($small) $f = $f * .65;
		return $f;
	}
	private function getFontHeight($i, $small=false) {
		return $this->lib->getFontHeight($this->getFontSize($i, $small));
	}
}

?>