<?
/**
 * Check for valid Gedcom, Step 2
 *
 * Search for names to be imported
 *
 * Loop through the import file and count how many people
 * seem to have a possible match in SharedTree already
 * this is only a ROUGH estimate of duplicates
 *
 * parts of this file have been taken from phpGedView
 * Copyright (C) 2002 to 2005  John Finlay and Others
 */

include_once("inc/functions_gedcom.php");

# Validate the file to ensure it's a real gedcom file and count the records
$newfilename = getFilename($import_id);
$bakfilename = getFilenameBackup($import_id);

if (!file_exists($newfilename)) errorMessage("The file does not exist.");

if (ob_get_level() == 0) {
   ob_start();
}

##########################################################
# Clean up the GEDCOM file
##########################################################
$l_headcleanup = false;
$l_macfilecleanup = false;
$l_lineendingscleanup = false;
$l_placecleanup = false;
$l_datecleanup=false;
$l_isansi = false;
$count_fam = 0;
$count_ind = 0;
$count = 0;

$fp = fopen($newfilename, "rb");
$fw = fopen($bakfilename, "wb");
$FILE_SIZE = filesize($newfilename);
$BLOCK_SIZE = 1024*4;	//-- 4k bytes per read

print "<br>validating file for correct GEDCOM formatting<br>";

//-- read the gedcom and test it in 8KB chunks
while(!feof($fp)) {
	$fcontents = fread($fp, 1024*8);
	$lineend = "\n";

	if (!$l_headcleanup && need_head_cleanup()) $l_headcleanup = true;
	if (!$l_macfilecleanup && need_macfile_cleanup()) $l_macfilecleanup = true;
	if (need_macfile_cleanup()) {
		$l_macfilecleanup=true;
		$lineend = "\r";
	}
	
	if (!$l_lineendingscleanup && need_line_endings_cleanup()) $l_lineendingscleanup = true;
	if (!$l_placecleanup && ($placesample = need_place_cleanup()) !== false) $l_placecleanup = true;
	//if (!$l_datecleanup && ($datesample = need_date_cleanup()) !== false) $l_datecleanup = true;

	if (!$l_isansi && is_ansi()) $l_isansi = true;
	if ($l_isansi) $fcontents = convert_ansel_unicode($fcontents);
	//echo $fcontents;

	//-- read ahead until the next line break
	$byte = "";
	while((!feof($fp)) && ($byte!=$lineend)) {
		$byte = fread($fp, 1);
		$fcontents .= $byte;
	}
	
	if (!$l_headcleanup && need_head_cleanup()) {
		head_cleanup();
		$l_headcleanup = true;
	}

	if ($l_macfilecleanup) {
		macfile_cleanup();
	}

	if (isset($_POST["cleanup_places"]) && $_POST["cleanup_places"]=="YES") {
		if(($sample = need_place_cleanup()) !== false) {
				$l_placecleanup=true;
			place_cleanup();
		}
	}

	line_endings_cleanup();
	
	if ($_POST["utf8convert"]=="YES") {
		convert_ansi_utf8();
	}
	fwrite($fw, $fcontents);

	$count_ind += preg_match_all("/0 @.*@ INDI/", $fcontents, $matches);
	$count_fam += preg_match_all("/0 @.*@ FAM/", $fcontents, $matches);
	echo ". ";
	flush();
	ob_flush();
}
fclose($fp);
fclose($fw);

# import the bak file now
#copy($bakfilename, $newfilename);
#unlink($bakfilename);

##########################################################
# Search SharedTree for people that /might/ match the person in the GEDCOM
##########################################################

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

include_class("Person");

$record_count=0;
$found_count=0;
$sample_size=0;

$sample_factor = 10; // sample 10%
if ($count_ind < 1000) $sample_factor = 5; // do 20% (200)
if ($count_ind < 300) $sample_factor = 2; // do half (150)
if ($count_ind < 100) $sample_factor = 1; // do all (100)

$fpged = fopen($bakfilename, "r"); // we use the copied bak file to import now

print "<br>comparing GEDCOM with SharedTree for possible matches<br>";

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

		if ($type == "INDI" && $record_count%$sample_factor==0) {
			# Search for every fifth individual in the file
			$indi = parse_tags($indirec);

			# Set the Surname and given names to the parsed name value first
			preg_match("/(.*)\/(.*)\//", $indi['NAME']['val'], $match);
			$family_name = $match[2];
			$given_name = $match[1];
			# If SURN or GIVN are passed in, then use those instead
			if ($indi['NAME']['SURN']['val'] > "") $family_name = $indi['NAME']['SURN']['val'];
			if ($indi['NAME']['GIVN']['val'] > "") $family_name = $indi['NAME']['GIVN']['val'];
			if ($indi['SEX']['val'] > "") $gender = $indi['SEX']['val'];
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


###########################################
# Save and print results
###########################################

echo "
<h3>Validation and Matching Complete</h3>
Number of Families: $count_fam<br>
Number of Individuals: $count_ind<br>

Sample size: $sample_size <br>
People with possible matches: $found_count <br>
Percent of possible duplicates: $perc_found <br>
<br>";
if ($l_headcleanup) echo "Cleaned up character before file header<br>";
if ($l_macfilecleanup) echo "Cleaned up file with Macintosh formatting<br>";
if ($l_lineendingscleanup) echo "Fixed duplicate line endings<br>";
if ($l_placecleanup) echo "Fixed Places<br>";
if ($l_datecleanup) echo "Fixed Dates<br>";
if ($l_isansi) echo "Converted from Ansi or ANSEL to UNICODE<br>";

unset($data);
$data["import_id"] = $import_id;
$data["family_count"] = $count_fam;
$data["person_count"] = $count_ind;
$data["person_matched"] = $perc_found;
$data["current_step"] = 2; // validation done
$gedimport->save($data);

?>

