<?php
include_class("Person");
include_class("Family");

/**
 * Create a tree that can be printed
 */
class FamilyTreeDB {
	public static function getAncestors($person_id, $gen, $current_gen=1) {
		$p = array();
		if ($gen == 0) return null;
		if ($gen < $current_gen) return null;
		if ($person_id == 0) return null;

		$pobj = new Person($person_id);

		$p["id"] = $pobj->id;
		$p["name"] = $pobj->data["given_name"] ." ". $pobj->data["family_name"];
		$p["full_name"] = $pobj->data["full_name"];
		$p["trace_meaning"] = $pobj->data["trace_meaning"];
		$p["birth_year"] = $pobj->data["birth_year"];
		$p["bdate"] = $pobj->data["e"]["BIRT"]["event_date"];
		$p["bplace"] = cleanPlace($pobj->data["e"]["BIRT"]["location"]);
		$p["ddate"] = $pobj->data["e"]["DEAT"]["event_date"];
		$p["dplace"] = cleanPlace($pobj->data["e"]["DEAT"]["location"]);
		$p["gen"] = $current_gen;
		$p["ancestor_count"] = $pobj->data["ancestor_count"];
		
		$fobj = new Family($pobj->data["bio_family_id"]);
		if ($fobj->id > 0) {
			$marriage = $fobj->data["e"]["MARR"];
			$father = FamilyTreeDB::getAncestors($fobj->data['person1_id'], $gen, $current_gen + 1);
			$mother = FamilyTreeDB::getAncestors($fobj->data['person2_id'], $gen, $current_gen + 1);
			if (is_array($father)) {
				$p["ancestor_count"] += 1 + $father["ancestor_count"];
				$father["mdate"] = $marriage["event_date"];
				$father["mplace"] = $marriage["location"];
				$p["father"] = $father;
			}
			if (is_array($mother)) {
				$p["ancestor_count"] += 1 + $mother["ancestor_count"];
				$p["mother"] = $mother;
			}
		}
		$pobj->saveCalc("ancestor_count", $p["ancestor_count"]);
		return $p;
	}

	public static function getDescendants($person_id, $gen, $current_gen=1) {
		$p = array();
		if ($gen == 0) return null;
		if ($person_id == 0) return null;

		$pobj = new Person($person_id);

		$p["id"] = $pobj->id;
		$p["gen"] = $current_gen;
		$p["name"] = $pobj->data["given_name"] ." ". $pobj->data["family_name"];
		$p["full_name"] = $pobj->data["full_name"];
		$p["restricted"] = $pobj->restricted;
		if ($pobj->restricted) return $p;

		$p["trace"] = $pobj->data["trace_meaning"];
		$p["birth_year"] = $pobj->data["birth_year"];
		$p["bdate"] = $pobj->data["e"]["BIRT"]["event_date"];
		$p["bplace"] = cleanPlace($pobj->data["e"]["BIRT"]["location"]);
		$p["ddate"] = $pobj->data["e"]["DEAT"]["event_date"];
		$p["dplace"] = cleanPlace($pobj->data["e"]["DEAT"]["location"]);

		$p["spouse_count"] = 0;
		$p["child_count"] = 0;

		// If this is the last generation, then skip the spouse and children
		// We may reconsider getting the spouses on the last generation
		if ($gen == 1) return $p;
		$spouses = $pobj->getMarriages(true);
		if (is_array($spouses)) {
			foreach ($spouses as $spouse) {
				unset($s);
				//print_pre($spouse);
				//die();
				$s["id"] = $spouse["person_id"];
				$s["name"] = $spouse["given_name"] ." ". $spouse["family_name"];
				$s["full_name"] = $spouse["given_name"] ." ". $spouse["family_name"];
				//$s["restricted"] = $pobj->restricted;
				$s["bdate"] = $spouse["b_date"];
				$s["bplace"] = cleanPlace($spouse["b_location"]);
				$s["ddate"] = $spouse["d_date"];
				$s["dplace"] = cleanPlace($spouse["d_location"]);
				$s["trace"] = $spouse["trace_meaning"];
				$s["status_code"] = $spouse["status_code"];
				$s["child_count"] = 0;

				if (is_array($spouse['children'])) {
					foreach ($spouse['children'] as $child) {
						$c = FamilyTreeDB::getDescendants($child["person_id"], $gen - 1, $current_gen + 1);
						if (is_array($c)) $s["child"][] = $c;
					}
					$s["child_count"] = count($s["child"]);
					$p["child_count"] = $p["child_count"] + $s["child_count"];
				}
				$p["spouse"][] = $s;
			}
			$p["spouse_count"] = count($p["spouse"]);
		}
		return $p;
	}
}

?>
