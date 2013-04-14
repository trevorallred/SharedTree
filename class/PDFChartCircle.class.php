<?php
include_class("Cpdf");

/**
 * draw circular pdf based on php array of genealogical data
 */
class PDFChartCircle {
	public $font = "Times-Roman"; // Default font
	public $margin = 20;
	public $linespacing = 1; //smaller means closer text
	public $center_circle = 100; // Radius of the circle in the middle
	public $cell_width = 110;

	private $lib;
	private $data = array(); # All of the descendents
	private $angle = 0;
	private $side = "right"; // or left
	private $gen = 0;

	function __construct() {
	}

	public function drawPage($data, $gen) {
		global $config;
		$this->gen = $gen;
		$this->data = $data;
		$width = $this->margin + $this->center_circle + (($this->gen-1) * $this->cell_width);
		$this->lib = new Cpdf(array(-$width,-$width,$width,$width));
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font.".afm");

		if (count($this->data["spouse"]) > 1) {
			$this->center_circle = 200;
		}

		$this->data["degrees"] = 360;
		$goodsize = false;
		$attempt = 0;
		while(!$goodsize && $attempt <= 20) {
			$degrees_needed = $this->countFamily($this->data);
			$this->calculateDegrees($this->data);
			$deg = round($this->data["mindegrees"],1);
			if ($deg > 260) {
				# We need to decrease font and increase circles
				$this->center_circle = $this->center_circle * 1.2;
				$this->cell_width = $this->cell_width * 1.2;
			} elseif ($deg < 30) {
				# We need to decrease font and increase circles
				$this->center_circle = $this->center_circle * .8;
				$this->cell_width = $this->cell_width * .9;
				if ($this->center_circle < 65) $this->center_circle = 65;
				if ($this->cell_width < 70) $this->cell_width = 70;
			} else {
				$goodsize = true;
			}
			$attempt++;
			//echo "Attempt: $attempt<br> Center: $this->center_circle<br>Cell: $this->cell_width<br>Spacing: $this->linespacing<br> Degrees: $degrees_needed<br><br>";
		}
		
		$width = $this->margin + $this->center_circle + (($this->gen-1) * $this->cell_width);
		$this->lib = new Cpdf(array(-$width,-$width,$width,$width));
		global $config;
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font.".afm");

		if ($goodsize) {
			$deg = round($this->data["mindegrees"],1);
			$text = "Degrees:$deg Attempt:$attempt";
			//$this->lib->addText(10-$width, 10-$width, 10, $text);
		}

		
		# Draw each of the circles for the generations
		for($i=0; $i < $this->gen; $i++) {
			//$r=$this->center_circle; $r < $width;
			if ($i == 0 || $i == ($this->gen - 1)) {
				$this->lib->setLineStyle(2);
			} else {
				$this->lib->setLineStyle(1);
			}
			$r = $this->center_circle + ($i * $this->cell_width);
			$this->lib->ellipse(0, 0, $r);
		}
		$p1 = $this->rotatePoint(array($start, 0), $this->angle);
		$p2 = $this->rotatePoint(array($start + $this->cell_width, 0), $this->angle);
		$this->lib->setLineStyle(2);
		$this->lib->line(0, $this->center_circle, 0, $this->center_circle + ($this->cell_width * ($this->gen-1)) );
		$this->lib->setLineStyle(1);


		# Add individual cells
		# first, we have to find the font, we are using
		
		$f = $this->getFontHeight(0); // rename the variable to make it easier to reference below
		$f2 = $this->getFontHeight(0, true);
		$pers = $this->data; // also to make it easier
		
		$down = $f * 1.8; //Increase to move the text in the middle up
		$this->centerMText($pers["name"], $down, 0, $f);
		$down -= $f2 * 1.1; // Increase line spacing
		$this->centerMText("B: ".$pers["bdate"] . " in ".$pers["bplace"], $down, 0, $f2);
		if ($pers["ddate"] . $pers["dplace"]) {
			$down -= $f2 * 1.1; // Increase line spacing
			$this->centerMText("D: ".$pers["ddate"] . " in ".$pers["dplace"], $down, 0, $f2);
		}
		
		$down -= $f * 1.4; // Increase line spacing
		$this->centerMText("~ married ~", $down, 0, $f);
		
		if (is_array($this->data["spouse"])) {
			foreach($this->data["spouse"] as $pers) {
				$down -= $f * 1.4; // Increase line spacing
				$this->centerMText($pers["name"], $down, 0, $f);
				$down -= $f2 * 1.1; // Increase line spacing
				$this->centerMText("B: ".$pers["bdate"] . " in ".$pers["bplace"], $down, 0, $f2);
				if ($pers["ddate"] . $pers["dplace"]) {
					$down -= $f2 * 1.1; // Increase line spacing
					$this->centerMText("D: ".$pers["ddate"] . " in ".$pers["dplace"], $down, 0, $f2);
				}
			}
		}
		// WARNING!! $pers is now the spouse not the person!!!
		
		/*		
		if ($deg > 360) {
			$down -= $f * 2; // Increase line spacing
			$this->centerMText("WARNING: Increase cell_width", $down, 0, $f2);
			$down -= $f2; // Increase line spacing
			$this->centerMText("$deg degrees is over 360", $down, 0, $f2);
		}
		if ($deg < 300 && $this->cell_width > 80) {
			$down -= $f * 2; // Increase line spacing
			$this->centerMText("WARNING: Decrease cell_width", $down, 0, $f2);
			$down -= $f2; // Increase line spacing
			$this->centerMText("$deg degrees is too low", $down, 0, $f2);
		}
		if ($deg < 300 && $this->center_circle > 80) {
			$down -= $f; // Increase line spacing
			$this->centerMText("WARNING: Decrease center_circle", $down, 0, $f2);
			$down -= $f2; // Increase line spacing
			$this->centerMText("$deg degrees is too low", $down, 0, $f2);
		}
		*/

		if ($this->gen > 1) {
			// Print children
			$this->lib->saveState();
			$this->angle = 90;
			$this->printRow0($this->data, 1);
			$this->lib->restoreState();
		}

		if ($this->gen > 2) {
			// Print grandchildren
			$this->lib->saveState();
			$this->angle = 90;
			$this->printRow1($this->data["spouse"], 2);
			$this->lib->restoreState();
		}

		if ($this->gen > 3) {
			// Print great-grandchildren
			$this->lib->saveState();
			$this->angle = 90;
			$this->printRow2($this->data["spouse"], 3);
			$this->lib->restoreState();
		}

		if ($this->gen > 4) {
			// Print great-great-grandchildren
			$this->lib->saveState();
			$this->angle = 90;
			$this->printRow3($this->data["spouse"], 4);
			$this->lib->restoreState();
		}

		if ($this->gen > 5) {
			// Print great-great-great-grandchildren
			$this->lib->saveState();
			$this->angle = 90;
			$this->printRow4($this->data["spouse"], 5);
			$this->lib->restoreState();
		}

		# Close page
		$this->lib->stream();
	}

	##############################################################################
	# Helper functions to Draw Page
	##############################################################################

	private function printRow4($spouses, $row) {
		global $debug;
		$printed = false;
		if (is_array($spouses)) {
			foreach($spouses as $spouse) {
				if (is_array($spouse["child"])) {
					foreach($spouse["child"] as $child) {
						$this_printed = $this->printRow3($child["spouse"], $row);
						if (!$this_printed) $this->printBlankCell($child, $row);
						$printed = true;
					}
				}
			}
		}
		return $printed;
	}

	private function printRow3($spouses, $row) {
		global $debug;
		$printed = false;
		if (is_array($spouses)) {
			foreach($spouses as $spouse) {
				if (is_array($spouse["child"])) {
					foreach($spouse["child"] as $child) {
						$this_printed = $this->printRow2($child["spouse"], $row);
						if (!$this_printed) $this->printBlankCell($child, $row);
						$printed = true;
					}
				}
			}
		}
		return $printed;
	}

	private function printRow2($spouses, $row) {
		global $debug;
		$printed = false;
		if (is_array($spouses)) {
			foreach($spouses as $spouse) {
				if (is_array($spouse["child"])) {
					foreach($spouse["child"] as $child) {
						$this_printed = $this->printRow1($child["spouse"], $row);
						if (!$this_printed) $this->printBlankCell($child, $row);
						$printed = true;
					}
				}
			}
		}
		return $printed;
	}

	private function printRow1($spouses, $row) {
		global $debug;
		$printed = false;
		if (is_array($spouses)) {
			foreach($spouses as $spouse) {
				if (is_array($spouse["child"])) {
					foreach($spouse["child"] as $child) {
						$this_printed = $this->printRow0($child, $row);
						if (!$this_printed) $this->printBlankCell($child, $row);
						$printed = true;
					}
				}
			}
		}
		return $printed;
	}

	private function printRow0($parent, $row) {
		global $debug;

		$printed = false;
		if (is_array($parent["spouse"])) {
			foreach($parent["spouse"] as $spouse) {
				if (is_array($spouse["child"])) {
					foreach ($spouse["child"] as $child) {
						$this->printCell($child, $row);
						$printed = true;
					}
				}
			}
		}
		return $printed;
	}

	private function printBlankCell($child, $row) {
		global $debug;
		$blank["degrees"] = $child["degrees"];
		$blank["name"] = "";
		$this->printCell($blank, $row);
		if ($debug) echo "<br>printBlankCell $this->angle ".$child["name"]. $child["degrees"];
	}

	private function printCell($child, $generation) {
		// $this->lib->setColor(0,0,1);
		$startangle = $this->angle;

		$start = (($generation - 1) * $this->cell_width) + $this->center_circle; // the inner circle
		$starttext = $start + $this->cell_width/2; // middle of the cell for this generation

		$this->rotateAngle(-$child["degrees"]/2);

		// we use this to convert pixels into degrees (degrees = pixels / $pixelsbydegree)
		$pixelsbydegree = (pi() * 2 * $starttext)/(360 * $this->linespacing);
		
		$namefontsize = $this->getFontSize($generation);
		$smallfontsize = $this->getFontSize($generation, true);
		$h = $this->getFontHeight($generation);
		$h4 = $this->getFontHeight($generation, true);

		$totalh = $h * -0.6;
		if ($child["bdate"] || $child["bplace"]) {
			$totalh += $h4;
		}
		if ($child["ddate"] || $child["dplace"]) {
			$totalh += $h4;
		}
		if (is_array($child["spouse"])) {
			foreach ($child["spouse"] as $spouse) {
				$totalh += $h;
				if ($spouse["status_code"] == "M") {
					if ($spouse["bdate"] || $spouse["bplace"]) {
						$totalh += $h4;
					}
					if ($spouse["ddate"] || $spouse["dplace"]) {
						$totalh += $h4;
					}
				}
			}
		}
		// Print Main Name
		$this->rotateAngle(($totalh/2) / $pixelsbydegree, false, $this->side);
		$this->printText($child["name"], $start, $this->angle, $namefontsize);
		
		// Print Main Birth Date/Plase
		if ($child["bdate"] || $child["bplace"]) {
			$text = "B: ".$child["bdate"];
			if ($child["bdate"] > '' && $child["bplace"] > '') {
				$text .= " in ";
			}
			$text .= $child["bplace"];
			$this->rotateAngle(-$h4 / $pixelsbydegree, false, $this->side);
			$this->printText($text, $start, $this->angle, $smallfontsize);
		}
		// Print Main Death Date/Plase
		if ($child["ddate"] || $child["dplace"]) {
			$text = "D: ".$child["ddate"];
			if ($child["ddate"] > '' && $child["dplace"] > '') {
				$text .= " in ";
			}
			$text .= $child["dplace"];
			$this->rotateAngle(-$h4 / $pixelsbydegree, false, $this->side);
			$this->printText($text, $start, $this->angle, $smallfontsize);
		}
		if (is_array($child["spouse"])) {
			foreach ($child["spouse"] as $spouse) {
				// Print Spouse
				// Print Spouse Birth Place/Date
				if ($spouse["status_code"] == "M") {
					$this->rotateAngle(-$h / $pixelsbydegree, false, $this->side);
					$this->printText($spouse["name"], $start, $this->angle, $namefontsize);
					if ($spouse["bdate"] || $spouse["bplace"]) {
						$this->rotateAngle(-$h4 / $pixelsbydegree, false, $this->side);
						$this->printText("B: ".$spouse["bdate"] . " in ".$spouse["bplace"], $start, $this->angle, $smallfontsize);
					}
					// Print Spouse Death Place/Date
					if ($spouse["ddate"] || $spouse["dplace"]) {
						$this->rotateAngle(-$h4 / $pixelsbydegree, false, $this->side);
						$this->printText("D: ".$spouse["ddate"] . " in ".$spouse[0]["dplace"], $start, $this->angle, $smallfontsize);
					}
				} else {
					$this->rotateAngle(-$h / $pixelsbydegree, false, $this->side);
					$this->printText("<i>".$spouse["name"]."</i>", $start, $this->angle, $namefontsize);
				}
			}
		}
		$this->rotateAngle($startangle - $child["degrees"], true);

		$p1 = $this->rotatePoint(array($start, 0), $this->angle);
		$p2 = $this->rotatePoint(array($start + $this->cell_width, 0), $this->angle);
		$this->lib->line($p1[0], $p1[1], $p2[0], $p2[1]);
	}

	private function rotateAngle($degrees, $reset=false, $side="") {
		if ($reset) {
			$this->angle = $degrees;
		} else {
			if (empty($side)) {
				$this->angle = $this->angle + $degrees;
			} else {
				if ($side=="right") $this->angle = $this->angle + $degrees;
				else $this->angle = $this->angle - $degrees;
			}
		}

		// make sure the degrees are within range of 0-359
		if ($this->angle >= 360) $this->angle = $this->angle - 360;
		if ($this->angle < 0) $this->angle = $this->angle + 360;

		if (empty($side)) {
			if ($this->angle > 90 && $this->angle <= 270) {
				$this->side = "left";
			} else {
				$this->side = "right";
			}
		}
	}

	private function rotatePoint ($point, $degrees) {
		$rad = deg2rad($degrees);
		$dist = ($point[0]^2 + $point[1]^2)^.5;
		$x = round(cos($rad) * $dist);
		$y = round(sin($rad) * $dist);
		return array($x, $y);
	}

	private function centerMText($text, $y=0, $x=0, $size=0) {
		# Center the text in the center circle
		$width = $this->lib->getTextWidth($size, $text);
		$maxwidth = 2 * $this->center_circle * .8;
		while ($maxwidth < $width) {
			$size = $size - 1;
			$width = $this->lib->getTextWidth($size, $text);
		}
		$this->lib->addText($x - ($width/2), $y, $size, $text, $this->angle);
	}

	private function printText($text, $radius1, $degrees, $size=0) {
		$radius2 = $radius1 + $this->cell_width;
		$x = ($radius2 + $radius1) / 2;

		// Make sure the text isn't too wide
		if (empty($size)) $size = $this->fontsize;
		$width = $this->lib->getTextWidth($size, $text);
		$maxwidth = ($radius2 - $radius1) * .9; // leave 5% on each side
		while ($maxwidth < $width) {
			$size = $size - 1;
			$width = $this->lib->getTextWidth($size, $text);
		}

		if ($this->side == "left") {
			// Left side
			$x = $x + $width/2;
			$p1 = $this->rotatePoint(array($x, $y), $degrees);
			$this->lib->addText($p1[0], $p1[1], $size, $text, 180-$degrees);
		} else {
			// Right side
			$x = $x - $width/2;
			$p1 = $this->rotatePoint(array($x, $y), $degrees);
			$this->lib->addText($p1[0], $p1[1], $size, $text, -$degrees);
		}
	}

	##############################################################################
	# Functions to properly count and size the family tree for a circle chart
	##############################################################################
	private function countFamily(&$person) {
		if ($person["gen"] > $this->gen) $this->gen = $person["gen"];
		
		// rename variables for ease of use
		$r = $this->getGenRadius($person["gen"]); // radius of the circle at this generation
		$f1 = $this->getFontHeight($person["gen"]); // this generation's name font
		$f2 = $this->getFontHeight($person["gen"], true); // this generation's small font
		
		// Calculate the degrees needed for this person and their spouse
		$degrees = 0;
		$degrees += heightDegrees($f1, $r); // add fontsize for person
		if ($person["bdate"] > '' || $person["bplace"] > '') $degrees += heightDegrees($f2, $r);
		if ($person["ddate"] > '' || $person["dplace"] > '') $degrees += heightDegrees($f2, $r);
		if (is_array($person["spouse"])) {
			foreach($person["spouse"] as $spouse) {
				$degrees += heightDegrees($f1, $r); // add fontsize for each spouse
				if ($spouse["bdate"] > '' || $spouse["bplace"] > '') $degrees += heightDegrees($f2, $r);
				if ($spouse["ddate"] > '' || $spouse["dplace"] > '') $degrees += heightDegrees($f2, $r);
			}
		}
		
		// Get the children's degrees
		$spousecount = count($person["spouse"]);
		$cdegrees = 0;
		for($x=0; $x < $spousecount; $x++) {
			$childcount = count($person["spouse"][$x]["child"]);
			for($y=0; $y < $childcount; $y++) {
				$cdegrees += $this->countFamily($person["spouse"][$x]["child"][$y]);
			}
		}
		
		if ($cdegrees > $degrees) $person["mindegrees"] = $cdegrees;
		else $person["mindegrees"] = $degrees;
		
		// Combine both counts now
		$person["count"] = $person["mindegrees"];

		return $person["mindegrees"];
	}

	private function calculateDegrees(&$person) {
		$thiscount = 0;

		$spousecount = count($person["spouse"]);
		for($x=0; $x < $spousecount; $x++) {
			$childcount = count($person["spouse"][$x]["child"]);
			for($y=0; $y < $childcount; $y++) {
				$thiscount = $thiscount + $person["spouse"][$x]["child"][$y]["count"];
				//echo $person["name"] ."-" .$person["spouse"][$x]["child"][$y]["name"] . " ". $thiscount ."<br>";
			}
		}

		for($x=0; $x < $spousecount; $x++) {
			$childcount = count($person["spouse"][$x]["child"]);
			
			// Now actually calculate the degrees for the child
			for($y=0; $y < $childcount; $y++) {
				$temp = &$person["spouse"][$x]["child"][$y]; // so it's easier to reference
				//echo $person["name"] . " ". $thiscount ."<br><br>";
				$person["spouse"][$x]["child"][$y]["degrees"] = $person["degrees"] * $person["spouse"][$x]["child"][$y]["count"] / $thiscount;
				$this->calculateDegrees($person["spouse"][$x]["child"][$y]);
			}
		}
		return $count;
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

function heightDegrees($height, $radius) {
	$circum = $radius * 2 * pi(); // PI * Diameter = circumference in pixels
	$degrees = 360 * $height / $circum; // minimum number of degrees needed to display children without modification
	
	return $degrees; // minimum number of degrees needed to display children without modification
}

?>
