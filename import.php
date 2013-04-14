<?
require_once("config.php");
require_once("inc/main.php");

require_login();

# General setup used for all steps
$action = $_REQUEST['action'];
include_class("GEDImport");
$gedimport = new GEDImport($_REQUEST['import_id']);
$import_id = $gedimport->id;
$T->assign("import_id", $import_id );
$T->assign("file", $gedimport->data );
$T->assign("title", "Import GEDCOM");
$T->assign("help", "Importing_Data");
$_GLOBAL['update_process'] = "import.php";

# We should only do this when uploading or importing
$TIME_LIMIT = 60*60; //1 hour
set_time_limit ($TIME_LIMIT);
$T->assign("time_limit", $TIME_LIMIT);

################################################################
# Begin Importing Steps
#
# 1) Upload GEDCOM file
# 1a) Delete Import file
#
# 2) Validate data and match people
#
# 3) Import records
# 3a) Approve/lock file
# 3b) Delete imported records
#
# 4a) Choose self
# 4b) Merge records
#
# Present the steps in reverse order in this file
#
################################################################

if (empty($import_id) && $action != "upload") $action = "";

################################################################
# STEP 4 Choose Self / Merge Records
################################################################

if ($action == "match") {
	if ($gedimport->data["current_step"] == 3) {
		//require("inc/match.php");
		//exit();
	}
	$T->display('import_match.tpl');
	exit();
}

################################################################
# STEP 3 Import records
################################################################
if ($action == "clear") {
	$gedimport->deleteRecords($import_id);
	$gedimport->getImport();
	$action = "validate";
}
if ($gedimport->data["status_code"] == "S") {
	errorMessage("This file did not finish importing during your last attempt. Please delete the data and try again.", "import.php?action=clear&import_id=$import_id");
}

if ($action == "approve") {
	$data["import_id"] = $import_id;
	$data["status_code"] = "A";
	$gedimport->save($data);
	//$gedimport->getImport();
	$action = "";
}
if ($action == "unlock") {
	$data["import_id"] = $import_id;
	$data["status_code"] = "C";
	$gedimport->save($data);
	$gedimport->getImport();
	# now show the summary
	$action = "listpeople";
}
if ($action == "import") {
	if ($gedimport->data["current_step"] == 2) {
		require("inc/gedcomimport.php");
		exit();
	}
	$action = "listpeople";
}

if ($action == "listpeople") {
	# Set some paging variables
	$page = (int)$_REQUEST["page"];
	if ($page >= 1) $page--;
	$T->assign("page", $page);

	$gedimport->showPeople($page);

	# We need this so if the user doesn't have a person yet, then we'll prompt them to choose one
	$T->assign("user_person_id", $user->data["person_id"]);
	$T->assign("pages", $gedimport->pages);
	$T->assign("individuals", $gedimport->page_data);

	$T->display('import_listpeople.tpl');
	exit();
}

################################################################
# STEP 2 Validate data and match people
################################################################

// testing ansel conversion
//include_once("inc/functions_gedcom.php");
//echo convert_ansel_unicode("2 PLAC Frederikshavn, Hj²rring, Denmark");

if ($action == "validate" && $import_id > 0) {
	$T->assign("istep", 2);
	$T->display('import_nav.tpl');

	if ($gedimport->data["current_step"] <= 1 || $_REQUEST["revalidate"]) {
		require("inc/gedcomvalidate.php");
		$gedimport->getImport();
		$T->assign("file", $gedimport->data );
	}

	$T->display('import_validate.tpl');
	exit();
}

################################################################
# STEP 1 Upload file
################################################################

if ($action == "viewfile" && $import_id > 0) {
	$newfilename = getFilename($import_id);
	# show the content of the file
	
	$fpged = fopen($newfilename, "r");
	$fcontents = fread($fpged, 10000);
	$T->assign("fcontents", $fcontents);

	$T->display('import_viewfile.tpl');
	exit();
}

if ($action == "delete" && $import_id > 0) {
	$newfilename = getFilename($import_id);
	unlink($newfilename);
	$gedimport->delete($import_id);
	# now show the summary
}

if ($action == "upload") {
	# Uploading GEDCOM file
	# Save the upload into the app_import database table
	# Save the file into the temp directory
	$newfile = $_FILES['importfile'];

	switch ($newfile['error']) {
		case UPLOAD_ERR_INI_SIZE:
			errorMessage("File too large for server. Increase the upload_max_filesize directive (".ini_get("upload_max_filesize").") in php.ini.");
			break;
		case UPLOAD_ERR_FORM_SIZE:
			errorMessage("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.");
			break;
		case UPLOAD_ERR_PARTIAL:
			errorMessage("The uploaded file was only partially uploaded.");
			break;
		case UPLOAD_ERR_NO_FILE:
			errorMessage("No file was uploaded.");
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			errorMessage("Missing a temporary folder.");
			break;
		case UPLOAD_ERR_OK:
			continue;
			break;
		default:
			errorMessage("Unknown file error");
			break;
	}
	# No error messages, keep going
	# Check to see if it's valid and then move it to temp

	if (!is_uploaded_file($newfile['tmp_name'])) {
		errorMessage("Possible file upload attack: filename ". $newfile['tmp_name']);
	}
	
	#######################################################
	# The file seems to be OK, save the record into the DB and move the file over
	#######################################################
	$data['import_id'] = $_REQUEST['import_id'];
	$data['description'] = $_REQUEST['description'];
	$data['file_size'] = $newfile['size'];
	$data['filename'] = $newfile['name'];
	$data['current_step'] = 1;
	
	$file_id = $gedimport->save($data);
	
	# Move the File now
	$newfilename = getFilename($file_id);
	//$exists = file_exists($newfile['tmp_name']);
	//echo "exists=$exists ";
	if (!move_uploaded_file($newfile['tmp_name'], $newfilename)) {
		$gedimport->delete();
		//echo $newfile['tmp_name']."<br>";
		//echo $newfilename."<br>";
		errorMessage("Failed to move uploaded file into gedcom directory.");
	}
	//gzip($newfilename, 9);
	
	//print_pre($_FILES['importfile']);

	redirect("import.php?action=viewfile&import_id=$file_id");
}

#######################################################
# Default action = view list of files and prompt for new import
#######################################################

$data = GEDImport::getList($user->id, $_REQUEST["show"]);
$T->assign("files", $data);

$T->display('import_upload.tpl');

##########################################
function getFilename($import_id) {
	global $user, $config;
	if (!is_writable($config["gedcom_dir"])) errorMessage($config["gedcom_dir"]." is not writable");
	return $config["gedcom_dir"]."gedcom_".$import_id."_".$user->id.".ged";
}
function getFilenameBackup($import_id) {
	global $user, $config;
	return $config["gedcom_dir"]."gedcom_".$import_id."_".$user->id.".bak";
}

?>






