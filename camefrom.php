<?

require_once("config.php");
require_once("inc/main.php");

$person_id = $user->data['person_id'];
if (empty($person_id)) die("invalid URL");
$gen = $_REQUEST["gen"];

include_class("Person");
include_class("Family");
$ancestry = getAncestry($person_id, 1, 0);
#print_pre($ancestry);

$countries = array();
foreach ($ancestry as $person) {
	$countries[$person["location"]] = $countries[$person["location"]] + $person["percent"];
}
arsort($countries);

print_pre($countries);


function getAncestry($person_id, $level, $depth) {
	if (empty($person_id)) return array();
	$depth++;
	global $gen;
	if ($depth > $gen) return array();

	$p = new Person($person_id);
	$location = $p->data['e']['BIRT']['location'];
	$location = findCountry($location);
	#echo $location ."<br>";

	$f = new Family($p->data['bio_family_id']);
	$ancestry1 = getAncestry($f->data['person1_id'], $level/2, $depth);
	$ancestry2 = getAncestry($f->data['person2_id'], $level/2, $depth);
	if (count($ancestry1) == 0) {
		$ancestry1[0]["location"] = $location;
		$ancestry1[0]["percent"] = $level/2;
	}
	if (count($ancestry2) == 0) {
		$ancestry2[0]["location"] = $location;
		$ancestry2[0]["percent"] = $level/2;
	}
	return array_merge($ancestry1, $ancestry2);
}

function findCountry($location) {
	$places = split(",",$location);
	$len = count($places);
	for($i=1;$i <= $len; $i++) {
		$candidate = trim($places[$len - $i]);
		$candidate = str_replace(".", "", $candidate);
		$candidate = str_replace(">", "", $candidate);
		$skip = false;
		if (strtoupper($candidate) == "USA") $skip = true;
		if (!$skip) {
			switch(strtoupper($candidate)) {
				case "SC": return "South Carolina";
				case "NC": return "North Carolina";
				case "GA": return "Georgia";
				case "NY": return "New York";
				case "KY": return "Kentucky";
				case "MASS":
				case "MA": return "Massachusetts";
				case "ILL":
				case "IL": return "Pennsylvania";
				case "NH": return "New Hampshire";
				case "VT": return "Vermont";
				case "TENN":
				case "TN": return "Tennessee";
				case "PENN":
				case "PA": return "Pennsylvania";
				case "RI": return "Rhode Island";
				case "CONN":
				case "CT": return "Connecticut";
				case "VA": return "Virginia";
	
				case "ENG": return "England";
				case "IRE": return "Ireland";
				case "SWITZ": return "Switzerland";
			}
			if ($candidate > "") return $candidate;
		}
	}
	return trim($location);
}
?>
