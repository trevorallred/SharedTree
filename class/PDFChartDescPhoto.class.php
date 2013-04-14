<?php
//ini_set("display_errors", 1);
include_class("Cezpdf");
include_class("Image");

/**
 * draw circular pdf based on php array of genealogical data
 */
class PDFChartDescPhoto {
	public $font = "Times-Roman"; // Default font

	private $lib;
	private $data = array(); # All of the descendents
	private $temp_photo_name = "pdf_photo";
	public $cache_photos = false;

	function __construct() {
		$this->cache_photos = false;
	}

	public function drawPage($data, $gen=3) {

ini_set("display_errors", 1);
		$this->lib = new Cezpdf("LETTER", "landscape"); //792 X 612
		$this->lib->selectFont($config['BASE_DIR']."/fonts/".$this->font.".afm");
		$this->data = $data;
		$page_width = 792;
		$page_height = 612;
		$margin = 20;
		$centerX = $page_width / 2;

		$photo1_width = 150; // width of photos for gen 1 (grandparents)
		$photo1_spacing = $photo1_width * .1;
		$photo2_width = $photo1_width * .7; // width of photos for gen 2 (parents)
		$photo2_spacing = $photo2_width * .1;
		$photo3_width = $photo2_width * .7; // width of photos for gen 3 (children)
		$photo3_spacing = $photo3_width * .1;

		$y1 = $page_height - $margin - ($photo1_width * 1.2);
		$y2 = $y1 - ($photo2_width * 1.8);
		$y3 = $y2 - ($photo3_width * 1.8);
		$children_rows = floor(($y2 - $margin) / ($photo3_width * 1.8));

		$spouse_count = count($data["spouse"]);

		$grand_total_width = $page_width;
		$loops = 0;
		while ($grand_total_width >= $page_width && $loops < 10) {
			$loops++;


			$grand_total_width = 0;
			for($s=0; $s < $spouse_count; $s++) {
				$x = 0;
				if (isset($data["spouse"][$s]["child"])) {
					for($c=0; $c < count($data["spouse"][$s]["child"]); $c++) {
						$children_columns = ceil($data["spouse"][$s]["child"][$c]["child_count"] / $children_rows);
						$spouses = $data["spouse"][$s]["child"][$c]["spouse_count"];
						$parents_width = ((1 + $spouses) * $photo2_width) + ($photo2_spacing * $spouses);
						$children_width = ($children_columns * $photo3_width) + (($children_columns - 1)*$photo3_spacing);
						$total_width = ($parents_width > $children_width) ? $parents_width : $children_width;
						$grand_total_width = $grand_total_width + $total_width + $photo2_spacing;

						$data["spouse"][$s]["child"][$c]["parents_width"] = $parents_width;
						$data["spouse"][$s]["child"][$c]["children_width"] = $children_width;
						$data["spouse"][$s]["child"][$c]["total_width"] = $total_width;
					}
				}
			}
			if ($grand_total_width > $page_width) {
				$photo2_width = $photo2_width*.9;
				$photo2_spacing = $photo2_spacing*.9;
				$photo3_width = $photo3_width*.9;
				$photo3_spacing = $photo3_spacing*.9;
				//echo "$loops shrinking down $photo2_width $photo3_width<br>";
			}
			$y1 = $page_height - $margin - ($photo1_width * 1.2);
			$y2 = $y1 - ($photo2_width * 1.8);
			$y3 = $y2 - ($photo3_width * 1.8);
			$children_rows = floor(($y2 - $margin) / ($photo3_width * 1.8));
		}
		$x2 = ($page_width - $grand_total_width) / 2; // start on the left margin to center the parents & children

		// Add the grandparents

		$width_of_parents = (($spouse_count + 1) * $photo1_width) + ($photo1_spacing * $spouse_count);
		$x1 = $centerX - ($width_of_parents/2);
		$this->addImage($data, $x1, $y1, $photo1_width);
		foreach ($data["spouse"] as $spouse) {
			$x1 = $x1 + $photo1_width + $photo1_spacing;
			$this->addImage($spouse, $x1, $y1, $photo1_width);
			foreach ($spouse["child"] as $child) {
				$parentX = $x2;
				$childX = $x2;
				$offset = 0;
				if ($child["children_width"] < $child["parents_width"]) {
					$childX = $x2 + ($child["parents_width"] - $child["children_width"]) / 2;
				} else {
					$parentX = $x2 + ($child["children_width"] - $child["parents_width"]) / 2;
				}
				$this->addImage($child, $parentX, $y2, $photo2_width);
				if (isset($child["spouse"])) {
				foreach ($child["spouse"] as $inlaw) {
					$parentX = $parentX + $photo2_width + $photo2_spacing;
					$this->addImage($inlaw, $parentX, $y2, $photo2_width);
					if (isset($inlaw["child"])) {
					foreach ($inlaw["child"] as $key=>$gchild) {
						$this->addImage($gchild, $childX, $y3-$offset, $photo3_width);
						$offset = $offset + ($photo3_width * 1.6);
						if (($key+1)%$children_rows==0) {
							$offset = 0;
							$childX = $childX + $photo3_width + $photo3_spacing;
						}
					}
					}
				}
				}
				$x2 = $x2 + $child["total_width"] + $photo2_spacing;
			}
		}

		# Close page
		if (0) {
			echo "<a href='pdf_file.pdf' target='_BLANK'>View File</a> ";

			$a = $this->lib->output();
			$handle = fopen("pdf_file.pdf", "w");
			fwrite($handle, $a);
			fclose($handle);

//print_pre($data);
			//echo $a;
			return;
		}
		$this->lib->ezStream();
	}

	private function addImage($person, $x, $y, $size=50) {
		$size_original = $size;
		//echo "adding ".$person["name"]." $x $y $size<br>";
		$file_name = "/tmp/pdf-img".$person["id"];
		//$this->cache_photos = false;
		if (file_exists($file_name) && $this->cache_photos) {
			$image = @getimagesize($file_name);
		} else {
			$image = Image::showByPerson($person["id"], "med", true);
			if (!empty($image["image"])) {
				$fp = fopen($file_name,"w");
				fwrite($fp, $image["image"]);
				fclose($fp);
				$image = @getimagesize($file_name);
			}
		}
		if (isset($image[0])) {
			$w = $image[0];
			$h = $image[1];
			$maxRatio = 1.3;
			if ($h / $w > $maxRatio) {
				$size = $size * $maxRatio / ($h/$w);
				$x = $x + ($size_original - $size)/2;
				//echo "dropped photo width down to ".round($size,2)." for ".$person["name"]."<br>";
			}

			switch ($image["mime"]) {
				case "image/jpeg":
				$this->lib->addJpegFromFile($file_name, $x, $y, $size);
				break;
				case "image/png":
				$this->lib->addPngFromFile($file_name, $x, $y, $size);
				break;
			}
		}
		$fsize = $size_original/8;
		$text_width = 999;

		while ($size < $text_width && $fsize > 1) {
			$fsize--;
			$text_width = $this->lib->getTextWidth($fsize, $person["name"]);
			//echo "shrink fsize = $fsize ";
		}
		$this->lib->addText($x+($size-$text_width)/2,$y-$fsize,$fsize,$person["name"]);
	}
}

?>
