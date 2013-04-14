{include file="header.tpl" includejs="1"}
{literal}
<script LANGUAGE="JavaScript">
<!-- Begin
function showPerson(p1, p2) {
	$("displayscreen").innerHTML = '<img src="/img/spinner_orange.gif">';
	$("displayscreen").show();
	new Effect.BlindUp($("pedigree"), {duration: .5});
        var pars = 'action=compare&p1='+p1+'&p2='+p2;
        new Ajax.Updater('displayscreen', 'merge_ped.php', {method: 'get', parameters: pars});
}
function showFamily(f1, f2) {
	$("displayscreen").innerHTML = '<img src="/img/spinner_orange.gif">';
	$("displayscreen").show();
	new Effect.BlindUp($("pedigree"), {duration: .5});
        var pars = 'action=compare_family&f1='+f1+'&f2='+f2;
        new Ajax.Updater('displayscreen', 'merge_fam.php', {method: 'get', parameters: pars});
}
function checkHistory(table, field) {
        var pars = 'action=ajax&person_id={/literal}{$p1.person_id}{literal}&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history1_'+table+field, 'ajax_fieldchanges.php', {method: 'get', parameters: pars});

        var pars = 'action=ajax&person_id={/literal}{$p2.person_id}{literal}&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history2_'+table+field, 'ajax_fieldchanges.php', {method: 'get', parameters: pars});
}
function chooseParent(id) {
	setBox(id, $('box'+id).checked);
}
function setBox(id, val) {
	//alert(id + ' ' + val);
	if ($('box'+id) != null) {
		$('box'+id).checked = val;
	}

	if (id == 1) {
		setBox(2, val);
		setBox(3, val);
	}
	if (id == 2) {
		setBox(4, val);
		setBox(5, val);
	}
	if (id == 3) {
		setBox(6, val);
		setBox(7, val);
	}
	if (id == 4) {
		setBox(8, val);
		setBox(9, val);
	}
	if (id == 5) {
		setBox(10, val);
		setBox(11, val);
	}
	if (id == 6) {
		setBox(12, val);
		setBox(13, val);
	}
	if (id == 7) {
		setBox(14, val);
		setBox(15, val);
	}
}
// End -->
</script>
<style>
.personframe {
	position: absolute;
	text-align: left;
}
.personbox {
	background: #EEE;
	border: solid 1px black;
	margin: 1px;
	width: 200px;
	height: 16px;
	font-size: 12px;
	padding: 2px;
	overflow: hidden;
	cursor: pointer;
}
.mergeselect {
	position: absolute;
	left:-18px;
	top:0px;
}
.familyselect {
	position: absolute;
	left:-18px;
	top:20px;
}

#pedigree {
	font-family: arial;
	background: #DDA;
	border: groove 3px #CC9;
	padding: 10px;
	margin: 5px;
	height: 420px;
	width: 763px;
	position: relative;
}
#pedigree select {
	font-size:8pt;
}
#pedigree input {
	font-size:8pt;
}
#gen1 {
	position: absolute;
	left:15px;
}
#gen2 {
	position: absolute;
	left:130px;
}
#gen3 {
	position: absolute;
	left:330px;
}
#gen4 {
	position: absolute;
	left:560px;
}

</style>
{/literal}

<h2>Merge Project for <a href="/person/{$b.person_id}">{$b.given_name} {$b.family_name} ({$b.person_id})</a></h2>

<div id="pedigree">
<div id="nav5">
{if $f.person_id > 0}
	<div style="position: absolute; top: 100px"><a href="ped.php?person_id={$f.person_id}">&gt;&gt;</a></div>
{/if}
{if $m.person_id > 0}
	<div style="position: absolute; top: 300px"><a href="ped.php?person_id={$m.person_id}">&gt;&gt;</a></div>
{/if}
</div>
<div id="gen4">
{include file="pedigree_piece.tpl" p1=$ammm p2=$bmmm ht=375 boxid=15}
{include file="pedigree_piece.tpl" p1=$ammf p2=$bmmf c1=$amm c2=$bmm ht=325 boxid=14}
{include file="pedigree_piece.tpl" p1=$amfm p2=$bmfm ht=275 boxid=13}
{include file="pedigree_piece.tpl" p1=$amff p2=$bmff c1=$amf c2=$bmf ht=225 boxid=12}
{include file="pedigree_piece.tpl" p1=$afmm p2=$bfmm ht=175 boxid=11}
{include file="pedigree_piece.tpl" p1=$afmf p2=$bfmf c1=$afm c2=$bfm ht=125 boxid=10}
{include file="pedigree_piece.tpl" p1=$affm p2=$bffm ht=75 boxid=9}
{include file="pedigree_piece.tpl" p1=$afff p2=$bfff c1=$aff c2=$bff ht=25 boxid=8}
</div>
<div id="gen3">
{include file="pedigree_piece.tpl" p1=$amm p2=$bmm ht=350 boxid=7}
{include file="pedigree_piece.tpl" p1=$amf p2=$bmf c1=$am c2=$bm ht=250 boxid=6}
{include file="pedigree_piece.tpl" p1=$afm p2=$bfm ht=150 boxid=5}
{include file="pedigree_piece.tpl" p1=$aff p2=$bff c1=$af c2=$bf ht=50 boxid=4}
</div>
<div id="gen2">
{include file="pedigree_piece.tpl" p1=$am p2=$bm ht=300 boxid=3}
{include file="pedigree_piece.tpl" p1=$af p2=$bf c1=$a c2=$b ht=100 boxid=2}
</div>
<div id="gen1">
{include file="pedigree_piece.tpl" p1=$a p2=$b ht=200 boxid=1}
</div>
</div>

<div id="displayscreen">
</div>
{include file="footer.tpl"}
