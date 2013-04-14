<?

require_once("config.php");
require_once("inc/main.php");

include_class("Person");
include_class("Family");

############################################################################
# Set common variables and assign them to the smarty template
$person_id = $_REQUEST['person_id'];
$T->assign('person_id',$person_id);
$action = $_REQUEST['action'];
$T->assign('action',$action);
$T->assign('gender_options',array("M"=>"Male","F"=>"Female"));

############################################################################
# Add/save new individuals



if ($person_id > 0) {
	$person = new Person($person_id, $time);
	$T->assign('s',$person->data);
	getParentTree($person->data['bio_family_id'], "");
}
$T->display('pedigree_edit.tpl');

############################################################################
function getParentTree($family_id, $level, $depth=0) {
	global $T;
	$depth++;
	if ($depth > 2) return;

	$f_level = $level."f";
	$m_level = $level."m";
	$p = new Family($family_id);
	$father_id = $p->data['person1_id'];
	$mother_id = $p->data['person2_id'];
	if ($father_id > 0) {
		$f = new Person($father_id);
		$T->assign($f_level,$f->data);
		getParentTree($f->data['bio_family_id'], $f_level, $depth);
	}
	if ($mother_id > 0) {
		$m = new Person($mother_id);
		$T->assign($m_level,$m->data);
		getParentTree($m->data['bio_family_id'], $m_level, $depth);
	}
}

?>