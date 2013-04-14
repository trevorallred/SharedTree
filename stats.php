<?

require_once("config.php");
require_once("inc/main.php");

# Build the stats
# we should probably move this to another file eventually
if ($action=="descendant_count") {
	$sql = "UPDATE tree_family f, (
				SELECT bio_family_id, sum(pc.descendant_count) + count(pc.person_id) descendant_count 
				FROM tree_person p
				JOIN tree_person_calc pc ON p.person_id = pc.person_id
				WHERE ".actualClause("p")." GROUP BY bio_family_id) c
			SET f.descendant_count = c.descendant_count WHERE f.family_id = c.bio_family_id";
	$db->sql_query( $sql );

	$sql = "UPDATE tree_person_calc pc, (
				SELECT person_id, sum(descendant_count) descendant_count
				FROM (
					SELECT family_id, person1_id as person_id, descendant_count FROM tree_family WHERE ".actualClause()."
					UNION
					SELECT family_id, person2_id as person_id, descendant_count FROM tree_family WHERE ".actualClause()."
				) f group by person_id) m
			SET pc.descendant_count = m.descendant_count WHERE p.person_id = m.person_id";
	$db->sql_query( $sql );
}

###############################################################
# Show various types of statistics
# these are usually based on tree_person

$where = "";
$search = $_REQUEST["search"];
if ($search["familyname"]) {
	$var = fixTick($search["familyname"]);
	$where .= "AND family_name like '".$var."%' ";
}
if ($search["birthstart"]) {
	$var = (int)$search["birthstart"];
	$where .= "AND birth_year >= $var ";
}
if ($search["birthend"]) {
	$var = (int)$search["birthend"];
	$where .= "AND birth_year <= $var ";
}

$sql = "SELECT p.person_id, p.given_name, p.family_name, p.birth_year, pc.descendant_count, pc.page_views
		FROM tree_person p
		JOIN tree_person_calc pc ON p.person_id = pc.person_id
		WHERE p.public_flag=1 $where AND ".actualClause();
switch ($_REQUEST["type"]) {
	case "page_views":
		$T->assign("prompt", "Most Viewed Ancestors");
		$sql .= " AND page_views > 10 ORDER BY page_views DESC";
		break;
	case "descendant_count":
	default:
		$sql .= " AND descendant_count > 1 ORDER BY descendant_count DESC";
		$T->assign("prompt", "Ancestors with most Descendants");
		break;
}
$sql .= " LIMIT 0,100";

$people = $db->select( $sql );

$T->assign("search", $search);
$T->assign("type", $_REQUEST["type"]);
$T->assign("descendants", $people); // get rid of this once we move it over
$T->assign("people", $people);
$T->display("stats.tpl");
?>
