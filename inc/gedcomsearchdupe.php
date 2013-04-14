<?php
/**
 * Search for names to be imported
 *
 * Loop through the import file and count how many people
 * seem to have a possible match in SharedTree already
 * this is only a ROUGH estimate of duplicates
 *
 * parts of this file have been taken from phpGedView
 * Copyright (C) 2002 to 2005  John Finlay and Others
 */

include_class("Person");

#require_once("functions_gedcom.php");
#require_once("functions_name.php");
#require_once("lang_settings_std.php");

$newfilename = getFilename($_REQUEST['import_id']);
if (!file_exists($newfilename)) errorMessage("Could not locate GEDCOM file at $newfilename");

$record_count=0;
$found_count=0;
$sample_size=0;

if (ob_get_level() == 0) {
   ob_start();
}

$fpged = fopen($newfilename, "r");
$BLOCK_SIZE = 1024*4;	//-- 4k bytes per read

#######################################################
# Read the individuals
$indilist = array();
$fcontents = "";
$TOTAL_BYTES = 0;
fseek($fpged, $TOTAL_BYTES);
while(!feof($fpged)) {
	$fcontents .= fread($fpged, $BLOCK_SIZE);
	$TOTAL_BYTES += $BLOCK_SIZE;
	$pos1 = 0;
	$listtype= array();
	while($pos1!==false) {
		$pos2 = strpos($fcontents, "\n0", $pos1+1);
		while((!$pos2)&&(!feof($fpged))) {
			$fcontents .= fread($fpged, $BLOCK_SIZE);
			$TOTAL_BYTES += $BLOCK_SIZE;
			$pos2 = strpos($fcontents, "\n0", $pos1+1);
		}
		if ($pos2) $indirec = substr($fcontents, $pos1, $pos2-$pos1);
		else $indirec = substr($fcontents, $pos1);

		$indirec = trim($indirec);

		$indirec = preg_replace("/\\\/", "/", $indirec);
		//-- remove double @ signs
		$indirec = preg_replace("/@+/", "@", $indirec);
		// Remove heading spaces
		$indirec = preg_replace("/\n(\s*)/", "\n", $indirec);

		$ct = preg_match("/0 @(.*)@ (.*)/", $indirec, $match);

		if ($ct > 0) {
			$gid = $match[1];
			$type = trim($match[2]);
		}
		else {
			$ct = preg_match("/0 (.*)/", $indirec, $match);
			$gid = trim($match[1]);
			$type = trim($match[1]);
		}

		if ($type == "INDI" && $record_count%5==0) {
			# Search for every fifth individual in the file
			$indi = parse_tags($indirec);

			# Set the Surname and given names to the parsed name value first
			preg_match("/(.*)\/(.*)\//", $indi['NAME']['val'], $match);
			$family_name = $match[2];
			$given_name = $match[1];
			# If SURN or GIVN are passed in, then use those instead
			$family_name = $indi['NAME']['SURN']['val'];
			$given_name = $indi['NAME']['GIVN']['val'];
			$gender = $indi['SEX']['val'];
			//echo "search for: $gender $family_name, $given_name";

			#################################################
			# Search for this person now

			$where = " AND family_name like '".fixTick($family_name)."'";
			$where .= " AND given_name like '".fixTick($given_name)."'";
			$where .= " AND gender = '".fixTick($gender)."'";

			$sql = "SELECT p.public_flag, count(*) FROM tree_person p 
					WHERE ".actualClause("p")." $where GROUP BY p.public_flag";
			//echo $sql;
			$data = $db->select( $sql );

			$sample_size++;
			if (count($data) > 0) $found_count++;
			//print_pre($data);

			if ($record_count%10==0) {
				flush();
				ob_flush();
			}
			print ". ";
		}
		$record_count++;
		$show_gid=$gid;
		$pos1 = $pos2;
	}
	$fcontents = substr($fcontents, $pos2);
}
fclose($fpged);
ob_end_flush();

if ($record_count > 0) $perc_found = round(100*$found_count/$sample_size);

echo "<br><br><br>
Total individuals: $record_count <br>
Sample size: $sample_size <br>
Number with possible matches: $found_count <br>
Percent of possible duplicates: $perc_found <br>
";

unset($data);
$data["import_id"] = $import_id;
$data["percent_found"] = 0;
#don't save while testing
#$gedimport->save($data);
exit();

function parse_tags($haystack, $level=1) {
	$tags = array();
	$next_level = $level + 1;
	$pos1 = 0;
	while($pos1!==false) {
		$pos2 = strpos($haystack, "\n$level ", $pos1+1);
		if ($pos2) $substring = substr($haystack, $pos1, $pos2-$pos1);
		else $substring = substr($haystack, $pos1);
		preg_match("/\n$level ([_A-Z]+)(.*)/", $substring, $match);
		$tag = $match[1];
		if ($tag > '') {
			$value = trim($match[2]);
			if (strpos($substring, "\n$next_level")) {
				$tags[$tag] = parse_tags($substring, $next_level);
			}
			if ($value > '') $tags[$tag]['val'] = $value;
		}
		$pos1 = $pos2;
	}
	return $tags;
}
?>