<?php
/**
 * Import Gedcom File
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
 *
 * Most of this file has been altered in OpenWillow from the original phpGedView
 */

// save at the beginning since it's easier
unset($data);
$data["status_code"] = "S"; // import started
$data["current_step"] = 3;
$gedimport->save($data);

//-- set the building index flag to tell the rest of the program that we are importing and so shouldn't
//-- perform some of the same checks
$BUILDING_INDEX = true;

include_class("Person");
include_class("Event");
include_class("DiscussWiki");

$data = $db->select("SELECT table_type, gedcom_code, lds_flag FROM ref_gedcom_codes g");
foreach($data as $row) {
	$event_types[] = $row["gedcom_code"];
}

require_once("functions_gedcom.php");
require_once("functions_name.php");
require_once("lang_settings_std.php");

$newfilename = getFilenameBackup($import_id);

if (!file_exists($newfilename)) errorMessage("Could not locate GEDCOM file at $newfilename");

if (isset($exectime)){
	$oldtime=time()-$exectime;
	$skip_table=0;
} else $oldtime=time();

$FILE_SIZE = filesize($newfilename);
$T->assign("file_size", $FILE_SIZE);

# don't let people come back to this page
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$T->display('import_process.tpl');

if (ob_get_level() == 0) {
   ob_start();
}

$record_count=0;
$i=$record_count;

$fpged = fopen($newfilename, "r");
$BLOCK_SIZE = 1024*4;	//-- 4k bytes per read
$fcontents = "";
$TOTAL_BYTES = 0;

#######################################################
# Read the sources
#######################################################
$sourcelist = array();
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
		$indirec = preg_replace("/\\\/", "/", $indirec);
		import_source(trim($indirec));
		$pos1 = $pos2;
	}
	$fcontents = substr($fcontents, $pos2);
}

//print_pre($sourcelist);
//fclose($fpged);
//die();

#######################################################
# Read the individual and families
#######################################################
$indilist = array();
$famlist = array();
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
		$indirec = preg_replace("/\\\/", "/", $indirec);
		if (preg_match("/1 BLOB/", $indirec)==0) import_record(trim($indirec));
		$pos1 = $pos2;
		if (!isset($show_type)){
			$show_type=$type;
			$i_start=1;
			$exectime_start=0;
			$type_BYTES=0;
		}
		$i++;
		if ($show_type!=$type) {
			$newtime = time();
			$exectime = $newtime - $oldtime;
			$show_exectime = $exectime - $exectime_start;
			$show_i=$i-$i_start;
			$type_BYTES=$TOTAL_BYTES-$type_BYTES;
			if (!isset($listtype[$show_type]["type"])) {
				$listtype[$show_type]["exectime"]=$show_exectime;
				$listtype[$show_type]["bytes"]=$type_BYTES;
				$listtype[$show_type]["i"]=$show_i;
				$listtype[$show_type]["type"]=$show_type;
			}
			else {
				$listtype[$show_type]["exectime"]+=$show_exectime;
				$listtype[$show_type]["bytes"]+=$type_BYTES;
				$listtype[$show_type]["i"]+=$show_i;
			}
			$show_type=$type;
			$i_start=$i;
			$exectime_start=$exectime;
			$type_BYTES=$TOTAL_BYTES;
		}
		if ($i%10==0) {
			$newtime = time();
			$exectime = $newtime - $oldtime;
			# This div will show loading percents
			print "\n<script type=\"text/javascript\">update_progress($TOTAL_BYTES, $exectime);</script>\n";
			flush();
			ob_flush();
		}
		else print " ";
		$show_gid=$gid;
	}
	$fcontents = substr($fcontents, $pos2);
}
fclose($fpged);

####################################################
#                Estimate Birth Years
####################################################

$BUILDING_INDEX = false; // we're done now and we need to recalculate these items now
echo "<br>estimating birth years<br>";
# birth year estimations now
$i = 0;
foreach ($indilist as $value) {
	$i++;
	//print_pre($value);
	$p = new Person($value);
	$p->estimateBirthYear();

	if ($i%10==0) {
		echo ".";
		flush();
		ob_flush();
	}
	unset($p);
}
# Set public/private flags
Person::resetPublicFlags();

###################################################
ob_end_flush();

unset($data);
$data["status_code"] = "C"; // import completed
$data["import_date"] = "NOW()";
$gedimport->save($data);

$T->assign('listtype', $listtype);
$T->assign('total_records', $i);
$T->assign('exectime', $exectime);

$T->display('import_process_done.tpl');

# DONE
# RETURN




####################################################
#                IMPORT FUNCTIONS
####################################################

function import_record($indirec) {
	global $indilist, $famlist, $sourcelist, $event_types;
	global $db, $import_id;
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

	$indirec = cleanup_tags_y($indirec);
	$indi = parse_tags($indirec);
	$db_time = "'".getServerTime()."'";
	if ($type == "INDI") {
		#### Save the Person first
		//print_pre($indirec);
		//print_pre($indi);
		$person = new Person();
		unset($save);
		# Set the Surname and given names to the parsed name value first
		preg_match("/(.*)\/(.*)\//", $indi['NAME']['val'], $match);
		addField(&$save, 'family_name', $match[2]);
		addField(&$save, 'given_name', $match[1]);
		# If SURN or GIVN are passed in, then use those instead
		addField(&$save, 'family_name', $indi['NAME']['SURN']['val']);
		addField(&$save, 'given_name', $indi['NAME']['GIVN']['val']);
		addField(&$save, 'prefix', $indi['NAME']['NPFX']['val']);
		if (!empty($indi['NAME']['NICK']['val'])) {
			addField(&$save, 'nickname', $indi['NAME']['NICK']['val']);
		} else {
			# if we don't have NICK, then use ALIA
			addField(&$save, 'nickname', $indi['NAME']['ALIA']['val']);
		}
		addField(&$save, 'gender', $indi['SEX']['val']);
		//print_pre($note);
		//print_pre($save);
		//print_pre($event_types);
		//die();
		$person->save($save);
		$indilist[$gid] = $person->id;

		#### Save the Notes into the Biography
		//$note = $indi['NOTE']['val'];
		//foreach($indi['NOTE']['CONT'] as $note_continued) $note .= "$note_continued";
		//foreach($indi['NOTE']['CONC'] as $note_continued) $note .= "$note_continued";
		$note = $indi['NOTE'];
		if ($note > "") {
			unset($wiki);
			$wiki["person_id"] = $person->id;
			$wiki["wiki_text"] = $note;
			DiscussWiki::save($wiki);
		}

		#### Save the original gedcom data into tree_person_gedcom
		$fixedgid = fixTick($gid); // unlikely, but just incase, also, rename it so we don't get mixed up later
		$gedcom_text = fixTick($indirec);
		$sql = "INSERT INTO tree_person_gedcom (person_id, import_id, individual, gedcom_text) 
				VALUES ($person->id, $import_id, '$fixedgid', '$gedcom_text')";
		$db->sql_query($sql);

		#### Add the Events
		foreach ($indi as $key=>$value) {
			//echo ($key);
			if (in_array($key, $event_types)) {
				unset($event);
				$event['key_id'] = $person->id;
				$event['table_type'] = 'P';
				$event['event_type'] = $key;
				addField($event, 'event_date', $value['DATE']['val']);
				addField($event, 'location', $value['PLAC']['val']);
				addField($event, 'temple_code', $value['TEMP']['val']);
				addField($event, 'status', $value['STAT']['val']);
				if ($value['SOUR']['val']) {
					$source = $sourcelist[str_replace('@','',$value['SOUR']['val'])];
					$source .= $value['SOUR']['NOTE']['val'];
					addField($event, 'source', $source);
				}
				//addField($event, 'notes', $value['NOTE']['val']);
				addField($event, 'notes', $value['CAUS']['val']);
				//print_pre($event);
				Event::save($event, $db_time);
			}
		}
	}
	else if ($type == "FAM") {
		include_class("Family");
		include_class("Event");
		$family = new Family();
		unset($save);
		
		addField(&$save, 'person1_id', $indilist[$indi['HUSB']['val']]);
		addField(&$save, 'person2_id', $indilist[$indi['WIFE']['val']]);
		if ($indi['DIV']['val'] == "Y") {
			addField(&$save, 'status', 'D');
		} else {
			addField(&$save, 'status', 'M');
		}
		$children = array();
		if (is_array($indi['CHIL'])) {
			foreach ($indi['CHIL'] as $kid) {
				$save['children'][] = $indilist[$kid];
			}
		}
		//echo "<br><br> $gid save['children']<br>";
		//print_pre($save['children']);
		//echo "<br>indi['CHIL']<br>";
		//print_pre($indi['CHIL']);
		$save["import_id"] = $_REQUEST['import_id'];
		$family->save($save);
		if ($family->id > 0) {
			$famlist[$gid] = $family->id;
			//print_pre($save);

			$gid = fixTick($gid); // unlikely, but just incase
			$sql = "INSERT INTO tree_family_gedcom (family_id, import_id, family, gedcom_text) 
					VALUES ($family->id, $import_id, '$gid', '$gedcom_text')";
			$db->sql_query($sql);

			foreach ($indi as $key=>$value) {
				if (in_array($key, $event_types)) {
					unset($save);
					$event['key_id'] = $family->id;
					$event['table_type'] = 'F';
					$event['event_type'] = $key;
					addField($event, 'event_date', $value['DATE']['val']);
					addField($event, 'location', $value['PLAC']['val']);
					addField($event, 'temple_code', $value['TEMP']['val']);
					addField($event, 'status', $value['STAT']['val']);
					if ($value['SOUR']['val']) {
						$source = $sourcelist[str_replace('@','',$value['SOUR']['val'])];
						$source .= $value['SOUR']['NOTE']['val'];
						addField($event, 'source', $source);
					}
					addField($event, 'notes', $value['NOTE']['val']);
					// the source data is referenced and we don't support that yet
					//addField($event, 'source', $value['SOUR']['val']); 
					//print_pre($event);
					Event::save($event, $db_time);
				}
			}
			$famlist[] = $fam;
		} else {
			//echo "did not save family probably because no parents exist: <pre>$indirec</pre><br>";
		}
	}
}

# function to import a source record into memory
function import_source($indirec) {
	# remove double @ signs
	$indirec = preg_replace("/@+/", "@", $indirec);
	# remove heading spaces
	$indirec = preg_replace("/\n(\s*)/", "\n", $indirec);

	$ct = preg_match("/0 @(.*)@ (.*)/", $indirec, $match);

	$gid = $match[1];
	$type = trim($match[2]);

	$indirec = cleanup_tags_y($indirec);
	$indi = parse_tags($indirec);
	if ($type == "SOUR") {
		global $sourcelist;
		//print_pre($indi);
		$source = "";
		if (isset($indi["TITL"]["val"])) $source = $indi["TITL"]["val"]."\n\n";
		if (isset($indi["AUTH"]["val"])) $source .= "Author: ".$indi["AUTH"]["val"]."\n";
		if (isset($indi["PUBL"]["val"])) $source .= "Publication: ".$indi["PUBL"]["val"]."\n";
		if (isset($indi["TEXT"]["val"])) $source .= $indi["TEXT"]["val"]."\n";
		if (isset($indi["NOTE"]["val"])) $source .= $indi["NOTE"]."\n";
		$sourcelist[$gid] = trim($source);
	}
}

function getTypeValue($level, $type, $haystack) {
	preg_match("/\n$level $type (.*)/", $haystack, $match);
	return $match[1];
}

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
			if (in_array($tag, array("HUSB", "WIFE", "CHIL", "FAMS"))) {
					$value = str_replace("@", "", $value);
			}
			switch ($tag) {
				case "CHIL":
				case "CONT":
				case "CONC":
				case "FAMS":
					# These don't have sublevels, and in some cases, they have multiples
					if ($value > '') $tags[$tag][] = $value;
					break;
				case "NOTE":
					//print_pre($match);
					$value = substr($substring, 8);
					//echo "NOTE: $value <br>";
					$value = str_replace("\n", "", $value);
					$value = str_replace("\r", "", $value);
					$value = str_replace("  ", " ", $value);
					$value = str_replace("2 CONC ", "", $value);
					$value = str_replace("2 CONT ", "\n\n", $value);
					if ($value > '') {
						//echo "Stripped: $value <br>";
						$tags["NOTE"] = $value;
					}
					break;
				default:
					if (strpos($substring, "\n$next_level")) {
						$tags[$tag] = parse_tags($substring, $next_level);
					}
					if ($value > '') $tags[$tag]['val'] = $value;
					break;
			}
		}
		$pos1 = $pos2;
	}
	//print_pre($tags);
	return $tags;
}

function addField(&$save, $key, $value) {
	$value = trim($value);
	if ($value > '' && $key > '') {
		$save[$key] = $value;
	}
}
?>