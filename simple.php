<?
require_once("config.php");
require_once("inc/main.php");

require_login();

include_class("Person");
include_class("Family");

############################################################################
# Set common variables and assign them to the smarty template
$person_id = $user->data["person_id"];
$T->assign('person_id',$person_id);

$T->assign("user", $user->data);

$T->assign("help", "Getting_Started");

$T->assign('gender_options',array("M"=>"Male","F"=>"Female"));
$T->assign('day31_options',array(""=>"", 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31));
$T->assign('month_options',array(""=>"", "JAN"=>"JAN","FEB"=>"FEB","MAR"=>"MAR","APR"=>"APR","MAY"=>"MAY","JUN"=>"JUN","JUL"=>"JUL","AUG"=>"AUG","SEP"=>"SEP","OCT"=>"OCT","NOV"=>"NOV","DEC"=>"DEC"));

############################################################################
# View the Individual and their Parents
$T->assign("title", "Getting Started");

$T->assign("message", "Welcome to SharedTree. Start your family tree by creating a genealogy record for yourself");
if ($person_id) {
	$T->assign("message", "Continue building your family tree by adding your parents");
	$person = new Person($person_id);
	$T->assign('individual',$person->data);

	$title = $person->data['full_name'];
	if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
	$T->assign("primary_person", $title);

	$parent_family = new Family($person->data['bio_family_id'] );

	if ($parent_family->data["person1_id"] > 0) {
		$father = new Person($parent_family->data["person1_id"]);
		$T->assign('father',$father->data);
		$fparent_family = new Family($father->data['bio_family_id'] );
		$ffather = new Person($fparent_family->data["person1_id"]);
		$T->assign('ffather',$ffather->data);
		$fmother = new Person($fparent_family->data["person2_id"]);
		$T->assign('fmother',$fmother->data);
	}

	if ($parent_family->data["person2_id"] > 0) {
		$mother = new Person($parent_family->data["person2_id"]);
		$T->assign('mother',$mother->data);
		$mparent_family = new Family($mother->data['bio_family_id'] );
		$mfather = new Person($mparent_family->data["person1_id"]);
		$T->assign('mfather',$mfather->data);
		$mmother = new Person($mparent_family->data["person2_id"]);
		$T->assign('mmother',$mmother->data);
	}
	if ($father->id > 0 && $mother->id > 0) {
		$T->assign("message", "Continue building your family tree by adding your grandparents");
	}
	if ($ffather->id > 0 && $fmother->id > 0 && $mfather->id > 0 && $mmother->id > 0) {
		$T->assign("message", "DONE");
	}
}

$T->display('simple.tpl');
?>