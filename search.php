<?
require_once("config.php");
require_once("inc/main.php");

$T->assign("adbc", array(1=>"A.D.", 0=>"B.C."));
$T->assign('gender_options',array("M"=>"Male","F"=>"Female",""=>"Both"));

if (isset($_REQUEST['search'])) {
	# Perform the search here
	# I moved this from the Person class because the extra layer was causing unneeded abstraction
	$search_data = $_REQUEST;
	$where = "";
	if ( !empty($search_data['person_id']) ) {
		$where .= " AND p.person_id = '" . (int)$search_data['person_id'] . "'";
	}
	if ( !empty($search_data['family_name']) ) {
		$where .= " AND p.family_name like '" . fixTick($search_data['family_name']) . "%'";
	}
	if ( !empty($search_data['given_name']) ) {
		$where .= " AND p.given_name like '%" . fixTick($search_data['given_name']) . "%'";
	}
	if ( !empty($search_data['created_by']) ) {
		$by = (int)$search_data['created_by'];
		$where .= " AND p.created_by = $by";
	}
	if ( !empty($search_data['birth_year']) ) {
		(int) $search_data['birth_year'];
		(int) $search_data['range'];
			
		if ($search_data['adbc'] == 0) $search_data['birth_year'] = $search_data['birth_year'] * -1;
		$start_yr = $search_data['birth_year'] - $search_data['range'];
		//$start_yr = ($start_yr >= 1) ? $start_yr : 1;
		$end_yr = $search_data['birth_year'] + $search_data['range'] + 1;
		//$where .= " AND eb.event_date >= STR_TO_DATE('{$start_yr}', '%Y')";
		//$where .= " AND eb.event_date <= STR_TO_DATE('{$end_yr}', '%Y')";
		$where .= " AND p.birth_year BETWEEN $start_yr and $end_yr";
		//die($where);
	}
	if ( !empty($search_data['death_year']) ) {
		(int) $search_data['death_year'];
		(int) $search_data['range'];
		$start_yr = $search_data['death_year'] - $search_data['range'];
		$end_yr = $search_data['death_year'] + $search_data['range'] + 1;
		$where .= " AND ed.event_date >= STR_TO_DATE('{$start_yr}', '%Y')";
		$where .= " AND ed.event_date <= STR_TO_DATE('{$end_yr}', '%Y')";
		if ($search_data['adbc'] == 0) $where .= " AND ed.ad = 0";
		else $where .= " AND ed.ad = 1";
	}
	if ( !empty($search_data['birth_place']) ) {
		$where .= " AND eb.location like '%".fixTick($search_data['birth_place'])."%'";
	}
	if ( !empty($search_data['death_place']) ) {
		$where .= " AND ed.location like '%".fixTick($search_data['death_place'])."%'";
	}
	if ( isset($search_data['gender']) ) {
		if ($search_data['gender'] == 'M') $where .= " AND p.gender = 'M'";
		if ($search_data['gender'] == 'F') $where .= " AND p.gender = 'F'";
	}
	if (!empty($where)) {
		if ($user->id > 0) $perm_sql = "OR p.created_by = $user->id 
										OR p.person_id IN (SELECT person_id FROM app_user_line_person WHERE user_id = '$user->id')";
		$page = $_REQUEST['page'] > 1 ? $_REQUEST['page']: 1;
		$max_records = 50;
		$start = $max_records * ($page - 1);

		$orderby = "p.family_name, p.given_name, p.birth_year";
		if ($search_data['sort'] == "birth") $orderby = "p.birth_year, p.family_name, p.given_name";
		if ($search_data['sort'] == "creation") $orderby = "p.creation_date DESC";

		$sql = "SELECT p.*, eb.*, u.given_name user_name, pc.*
				FROM tree_person p
				LEFT JOIN tree_person_calc pc ON p.person_id = pc.person_id
				LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb")."
				LEFT JOIN tree_event ed ON p.person_id = ed.key_id AND ed.table_type = 'P' AND ed.event_type = 'DEAT' AND ".actualClause("ed")."
				LEFT JOIN app_user u ON p.created_by = u.user_id
				WHERE ".actualClause("p")." $where
				AND (p.public_flag = 1 $perm_sql )
				ORDER BY $orderby
				LIMIT $start, $max_records";
		//if ($user->id == 1) echo $sql;
		$data = $db->select( $sql );
		$T->assign('results',$data);
		$T->assign('result_count',count($data));

		$sql = "SELECT count(*) total
				FROM tree_person p
				LEFT JOIN tree_event eb ON p.person_id = eb.key_id AND eb.table_type = 'P' AND eb.event_type = 'BIRT' AND ".actualClause("eb")."
				LEFT JOIN tree_event ed ON p.person_id = ed.key_id AND ed.table_type = 'P' AND ed.event_type = 'DEAT' AND ".actualClause("ed")."
				WHERE ".actualClause("p")." $where
				AND (p.public_flag = 1 $perm_sql )";
		//if ($user->id) echo $sql;
		$data = $db->select( $sql );
		$total_records = $data[0]["total"];
		$T->assign('total_records',$total_records);
		$T->assign('pages', ceil($total_records/$max_records) );
	}
}

foreach ($_REQUEST as $key=>$value) {
	$_REQUEST[$key] = removeSlashes($value);
}
if (!isset($_REQUEST["page"])) $_REQUEST["page"] = 1;
if (!isset($_REQUEST["adbc"])) $_REQUEST["adbc"] = 1;
$T->assign('request',$_REQUEST);
$T->assign('help',"Searching");

switch($_REQUEST["template"]) {
	case "mobile_search":
	case "fb_search":
		$template_name = $_REQUEST["template"].".tpl";
		$T->display($template_name);
		die();
}

if ($mobile) {
	$T->display('mobile_search.tpl');
	exit();
}
$T->display('search.tpl');

?>
