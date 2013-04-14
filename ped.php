<?

require_once("config.php");
require_once("inc/main.php");

$person_id = $_REQUEST['person_id'];
if (empty($person_id)) {
	$T->display("header.tpl");
	errorMessage("You must supply a person_id to view a pedigree chart","search.php", false);
}

$T->assign('person_id',$person_id);

include_class("Person");
include_class("Family");

# s = self
# p = parents
# f = father
# m = mother

$s = new Person($person_id);
if ($s->restricted) privateRecord();

$s->getMarriages(true);

$T->assign('s',$s->data);
$T->assign('spouses',$s->getMarriages(true));
getParentTree($s->data['bio_family_id'], "");

$title = $s->data['full_name'];
if ($s->data['birth_year'] > 0) $title .= " (".$s->data['birth_year'].")";
if ($s->data['birth_year'] < 0) $title .= " (".-1*$s->data['birth_year']." B.C.)";

$T->assign("primary_person", $title);
$T->assign('title', $title." Pedigree");

$T->display('pedigree_view.tpl');

function getParentTree($family_id, $level, $depth=0) {
	global $T;
	$depth++;
	if ($depth > 4) return;

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
