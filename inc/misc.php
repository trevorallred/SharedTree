<?

function include_class($class_name) {
	global $config;
	require_once($config["BASE_DIR"] . "/class/$class_name.class.php");
}

function fixTick($data) {
	$data = trim($data);
	$data = str_replace("'", "\'", $data);
	$data = str_replace("\\\\", "\\", $data);
	$data = str_replace("  ", " ", $data); // get rid of double spaces
	return $data;
}
function removeSlashes($data) {
	$data = str_replace("\\\\", "\\", $data);
	$data = str_replace("\\\\", "\\", $data);
	$data = str_replace("\\\\", "\\", $data);
	$data = str_replace("\\'", "'", $data);
	return $data;
}

function compareString($string1, $string2) {
	$string1 = str_replace(" ", "", trim($string1));
	$string2 = str_replace(" ", "", trim($string2));
	$string1 = strtoupper($string1);
	$string2 = strtoupper($string2);
	if ($string1 == $string2) return true;
	return false;
}

function print_pre($mixed) {
	echo "<pre style='text-align: left'>";
	print_r($mixed);
	echo "</pre>";
}

function redirect($url) {
	header("Location: $url");
	exit();
}

function addInsertClause(&$sql, $fieldname, $value, $default=null) {
	//if (empty($value) && empty($default)) return;

	if (empty($value)) $value = $default;
	$value = fixTick($value);

	if ($sql > "") $sql .= ", ";
	$sql .= "$fieldname = '$value'";
}

function actualClause($alias='', $time='') {
	$alias_sql = ($alias=='')?'':$alias.'.';
	$time_sql = ($time=='')?"Now()":$time;
	return "{$alias_sql}actual_start_date <= $time_sql AND {$alias_sql}actual_end_date > $time_sql";
}

function dateClause($alias='') {
	if ($alias > '') $alias .= ".";
	$sql = "
IF(DATE_FORMAT(".$alias."event_date, '%d') <> '00',
  DATE_FORMAT(".$alias."event_date, '%d %b %Y'), 
  IFNULL(
    DATE_FORMAT(".$alias."event_date, '%b %Y'), 
    DATE_FORMAT(".$alias."event_date, '%Y')
  )
)";
	return $sql;
}

function getServerTime() {
	global $db;
	$sql = "SELECT Now() as db_datetime";
	$row = $db->select( $sql );
	return $row[0]['db_datetime'];
}

function errorMessage($error, $url="", $send=true) {
	global $T, $db, $user, $config;

	$T->assign("error", $error);
	if (empty($url)) $url = $_SERVER["HTTP_REFERER"];
	$T->assign("url", $url);

	$temp = $db->sql_error();
	if ($temp["message"] > "") $error .= "\nDatabase: ".$temp["message"];
	$error .= "\n\nSQL: ".$db->last_sql;
	//echo $error;
	
	//print_pre($user);
	//die();
	$user_printed = "";
	if ($user->id > 0) {
		$udata = $user->data;
		unset($udata["password"]);
		unset($udata["password_new"]);
		unset($udata["description"]);
		$user_printed = var_export($udata, true);
	}

	$current_url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$refer = $_SERVER["HTTP_REFERER"];
	if ($refer > "") $refer = "From: $refer";
	$body = "$error
$dberror
$refer
URL: $current_url
user: $user_printed
";
	if ($config['AREA_TYPE'] == "prod") {
		if ($send)
			mail("trevorallred@gmail.com", "SharedTree Error", $body, "From: admin@sharedtree.com");
	} else {
		$T->assign("logging", $body);
	}
	$T->display('error.tpl');
	die();
}

function shrinkNames($name, $max=30) {
	return $name;
}

function gzip($src, $level = 5, $dst = false){
   if($dst == false){
       $dst = $src.".gz";
   }
   if(file_exists($src)){
       $filesize = filesize($src);
       $src_handle = fopen($src, "r");
       if(!file_exists($dst)){
           $dst_handle = gzopen($dst, "w$level");
           while(!feof($src_handle)){
               $chunk = fread($src_handle, 2048);
               gzwrite($dst_handle, $chunk);
           }
           fclose($src_handle);
           gzclose($dst_handle);
           return true;
       } else {
           error_log("$dst already exists");
       }
   } else {
       error_log("$src doesn't exist");
   }
   return false;
}

function genderColor($gender){
	$color = "#AAAAAA";
	if ($gender == "M") $color = "#BDC5F9";
	if ($gender == "F") $color = "#FCE4EF";
	return $color;
}

function validateEmail($email) {
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) return true;
	return false;
}

function SendEmail($to, $from="", $subject, $body, $html=false) {
	global $config;
	if ( empty($from) && isset($config["admin_email"]) ) $from = "SharedTree <".$config["admin_email"].">";
	$headers = "From: $from";
	mail($to, $subject, $body, $headers);
}

// traceMeaning is obsolete. remove it after checking for usage
function traceMeaning($t) {
	$i = 0;
	while ($i < strlen($t)) {
		$means = "";

		$up = 5; // Assume we are going to match on 3 letters
		switch (substr($t, $i, $up)) {
			case "PPPPP": $means = "great-great-great-grandparent"; break;
			case "SPPPP": $means = "spouse's great-great-grandparent"; break;
			default:
		$up = 4; // Assume we are going to match on 3 letters
		switch (substr($t, $i, $up)) {
			case "PPPP": $means = "great-great-grandparent"; break;
			case "PPCC": $means = "cousin"; break;
			case "PPPC": $means = "great-uncle"; break;
			case "SPPP": $means = "spouse's great-grandparent"; break;
			default:
			$up = 3; // Assume we are going to match on 3 letters
			switch (substr($t, $i, $up)) {
				case "PPP": $means = "great-grandparent"; break;
				case "PPC": $means = "aunt/uncle"; break;
				case "PCC": $means = "niece/nephew"; break;
				case "PCC": $means = "great-grandchild"; break;
				case "PSC": $means = "step sibling"; break;
				case "PCS": $means = "brother/sister-in-law"; break;
				case "SPC": $means = "brother/sister-in-law"; break;
				case "SPP": $means = "spouse's grandparent"; break;
				case "CCC": $means = "great-grandchild"; break;
				default:
				$up = 2; // we didn't match 3, let's try 2
				switch (substr($t, $i, $up)) {
					case "PP": $means = "grandparent"; break;
					case "PC": $means = "sibling"; break;
					case "PS": $means = "step-parent"; break;
					case "SP": $means = "father/mother-in-law"; break;
					case "SC": $means = "step child"; break;
					case "CS": $means = "son/daughter-in-law"; break;
					case "CC": $means = "grandchild"; break;
					default:
					$up = 1;
					switch (substr($t, $i, $up)) {
						case "S": $means = "spouse"; break;
						case "P": $means = "parent"; break;
						case "C": $means = "child"; break;
						case "X": $means = "self"; break;
						default: $means = "???";
					}
				}
			}
		}
		}
		if ($i > 0) $meaning .= "'s ";
		$meaning .= $means;
		$i = $i + $up;
	}
	return $meaning;
}

function cleanPlace($p) {
	// TODO use the ref_location table to look these conversions up
	$parray = split(",", $p);
	if (count($parray) < 2) return $p;

	$countrystate = $parray[count($parray) - 1];
	$states["ALABAMA"] = "AL";
	$states["ALASKA"] = "AK";
	$states["ARIZONA"] = "AZ";
	$states["ARKANSAS"] = "AR";
	$states["CALIFORNIA"] = "CA";
	$states["COLORADO"] = "CO";
	$states["CONNECTICUT"] = "CT";
	$states["DELAWARE"] = "DE";
	$states["FLORIDA"] = "FL";
	$states["GEORGIA"] = "GA";
	$states["GUAM"] = "GU";
	$states["HAWAII"] = "HI";
	$states["IDAHO"] = "ID";
	$states["ILLINOIS"] = "IL";
	$states["INDIANA"] = "IN";
	$states["IOWA"] = "IA";
	$states["KANSAS"] = "KS";
	$states["KENTUCKY"] = "KY";
	$states["LOUISIANA"] = "LA";
	$states["MAINE"] = "ME";
	$states["MARYLAND"] = "MD";
	$states["MASSACHUSETTS"] = "MA";
	$states["MICHIGAN"] = "MI";
	$states["MINNESOTA"] = "MN";
	$states["MISSISSIPPI"] = "MS";
	$states["MISSOURI"] = "MO";
	$states["MONTANA"] = "MT";
	$states["NEBRASKA"] = "NE";
	$states["NEVADA"] = "NV";
	$states["NEW HAMPSHIRE"] = "NH";
	$states["NEW JERSEY"] = "NJ";
	$states["NEW MEXICO"] = "NM";
	$states["NEW YORK"] = "NY";
	$states["NORTH CAROLINA"] = "NC";
	$states["NORTH DAKOTA"] = "ND";
	$states["OHIO"] = "OH";
	$states["OKLAHOMA"] = "OK";
	$states["OREGON"] = "OR";
	$states["PALAU"] = "PW";
	$states["PENNSYLVANIA"] = "PA";
	$states["PUERTO RICO"] = "PR";
	$states["RHODE ISLAND"] = "RI";
	$states["SOUTH CAROLINA"] = "SC";
	$states["SOUTH DAKOTA"] = "SD";
	$states["TENNESSEE"] = "TN";
	$states["TEXAS"] = "TX";
	$states["UTAH"] = "UT";
	$states["VERMONT"] = "VT";
	$states["VIRGINIA"] = "VA";
	$states["WASHINGTON"] = "WA";
	$states["WEST VIRGINIA"] = "WV";
	$states["WISCONSIN"] = "WI";
	$states["WYOMING"] = "WY";

	$states["Mexico"] = "Mex.";
	foreach($states as $find=>$replace) {
		$state_codes[] = $replace;
		$state_names[] = $find;
	}

	$countrystate = str_ireplace($state_names, $state_codes, $countrystate);
	$p = $parray[0] . ", " . $countrystate;
	$p = str_replace("  ", " ", trim($p));
	return $p;
}


# TODO: we may want to create a class for this instead
function addQueue($p1, $p2) {
	global $db, $user;

	# Make sure we have integers
	$p1 = (int)$p1;
	$p2 = (int)$p2;
	if (empty($p1)) return;
	if (empty($p2)) return;
	if ($p1==$p2) return;
	if ($p1 > $p2) {
		$temp = $p1;
		$p1 = $p2;
		$p2 = $temp;
	}

	# We have different people, so add them to the queue so we can review them later
	$sql = "INSERT INTO app_merge SET status_code = 'Q', updated_by = '$user->id', update_date = Now(), similarity_score = 2, 
					person_to_id = $p1, person_from_id = $p2
			ON DUPLICATE KEY UPDATE status_code = 'Q', updated_by = '$user->id', update_date = Now(), similarity_score = 2";
	$db->sql_query($sql);
}

function privateRecord() {
	global $T;
	require_login();
	$T->assign("person_id", NULL);
	$T->assign("title", "Private Record");
	$T->display("header.tpl");
	echo "<br><br><table class='table1'><tr><td><b>Sorry</b>, but the record you tried to access is a living individual, to which you do not (yet) have access. You should only have access to relatives that are close to you in your family tree. The <a href=\"/w/Family_Tree_Index\">Family Tree Index</a> controls this security. If you feel you're seeing this in error, then you should consider <a href=\"/familytree.php\">rebuilding your Family Tree Index</a>.</td></tr></table><br><br><br>";
	$T->display("footer.tpl");
	exit();
}

function returnMIMEType($filename) {
	preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
	
	switch(strtolower($fileSuffix[1])) {
		case "js" :
			return "application/x-javascript";
		case "jpg" :
		case "jpeg" :
		case "jpe" :
			return "image/jpg";

		case "png" :
		case "gif" :
		case "bmp" :
		case "tiff" :
			return "image/".strtolower($fileSuffix[1]);

		case "css" :
			return "text/css";

		case "xml" :
			return "application/xml";

		case "doc" :
			return "application/msword";

		case "rtf" :
			return "application/pdf";

		case "html" :
		case "htm" :
			return "text/html";

		case "txt" :
			return "text/plain";

		case "mpeg" :
		case "mpg" :
		case "mpe" :
			return "video/mpeg";

		case "avi" :
			return "video/msvideo";

		default :
			if(function_exists("mime_content_type"))
				return mime_content_type($filename);

	}
	return "unknown/" . trim($fileSuffix[0], ".");
}

?>
