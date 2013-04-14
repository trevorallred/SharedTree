{include file="header.tpl"}
{literal}
<script language=javascript type='text/javascript'>
function showhide(id) {
	if (document.getElementById(id).style.display == 'block') {
		document.getElementById(id).style.display = 'none';
	} else {
		document.getElementById(id).style.display = 'block';
	}
}
</script>
<style>
.personframe {
	position: absolute;
	text-align: left;
}
.personbox {
	background: #EEE;
	border: solid 1px black;
	width: 200px;
	height: 16px;
	font-size: 12px;
	padding: 2px;
	margin: 0px;
	cursor: pointer;
}
.personinfo {
	display: none;
	border: solid 3px #ddd;
	position: absolute;
	background: #669;
	color: #FEE;
	font-size: 11px;
	margin: 2px;
	padding: 2px;
	height: 90px;
	width: 300px;
	z-index: 99;
 }
.personinfo table {
	padding: 0px;
}
.personinfo td {
	padding: 0px;
	font-size: 10px;
}
.personinfo a {
	color: #FFF;
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

#ped_title {
	position: absolute;
	left:5px;
	top:5px;
	font-size: 20px;
}
#gen1 {
	position: absolute;
	left:10px;
}
#gen2 {
	position: absolute;
	left:80px;
}
#gen3 {
	position: absolute;
	left:190px;
}
#gen4 {
	position: absolute;
	left:335px;
}
#gen5 {
	position: absolute;
	left:550px;
}
#nav5 {
	position: absolute;
	left:760px;
}
#nav5 a {
	text-decoration: none;
	color: #111;
}

</style>
{/literal}

<br clear="all" /><br />

<div id="pedigree">
{if $spouses}
	<div style="top: 400px; left: 10px; position: absolute;">
	<form method="get" name="frmChoose" action="ped.php">
	<select name="person_id" onchange="frmChoose.submit()">
	<option value="{$person_id}">-- Spouse &amp; Children --</option>
	{foreach item=spouse from=$spouses}
		<option value="{$spouse.person_id}">{$spouse.given_name} {$spouse.family_name} ({$spouse.b_date})</option>
		{foreach item=child from=$spouse.children}
			<option value="{$child.person_id}"><font size=1>&nbsp;&nbsp;&nbsp;{$child.given_name} {$child.family_name} ({$child.b_date})</font></option>
		{/foreach}
	{/foreach}
	</select>
	<input type="submit" value="Go" />
	</form>
	</div>
{/if}

<div id="ped_title">
	PEDIGREE for {$s.given_name} {$s.family_name}
</div>
<div id="nav5">
{if $f.person_id > 0}
	<div style="position: absolute; top: 100px"><a href="ped.php?person_id={$f.person_id}">&gt;&gt;</a></div>
{/if}
{if $m.person_id > 0}
	<div style="position: absolute; top: 300px"><a href="ped.php?person_id={$m.person_id}">&gt;&gt;</a></div>
{/if}
</div>
<div id="gen5">
{include file="pedigree_piece2.tpl" p=$mmmm ht=387.5 boxid=31}
{include file="pedigree_piece2.tpl" p=$mmmf ht=362.5 boxid=30}
{include file="pedigree_piece2.tpl" p=$mmfm ht=337.5 boxid=29}
{include file="pedigree_piece2.tpl" p=$mmff ht=312.5 boxid=28}
{include file="pedigree_piece2.tpl" p=$mfmm ht=287.5 boxid=27}
{include file="pedigree_piece2.tpl" p=$mfmf ht=262.5 boxid=26}
{include file="pedigree_piece2.tpl" p=$mffm ht=237.5 boxid=25}
{include file="pedigree_piece2.tpl" p=$mfff ht=212.5 boxid=24}
{include file="pedigree_piece2.tpl" p=$fmmm ht=187.5 boxid=23}
{include file="pedigree_piece2.tpl" p=$fmmf ht=162.5 boxid=22}
{include file="pedigree_piece2.tpl" p=$fmfm ht=137.5 boxid=21}
{include file="pedigree_piece2.tpl" p=$fmff ht=112.5 boxid=20}
{include file="pedigree_piece2.tpl" p=$ffmm ht=87.5 boxid=19}
{include file="pedigree_piece2.tpl" p=$ffmf ht=62.5 boxid=18}
{include file="pedigree_piece2.tpl" p=$fffm ht=37.5 boxid=17}
{include file="pedigree_piece2.tpl" p=$ffff ht=12.5 boxid=16}
</div>
<div id="gen4">
{include file="pedigree_piece2.tpl" p=$mmm ht=375 boxid=15}
{include file="pedigree_piece2.tpl" p=$mmf ht=325 boxid=14}
{include file="pedigree_piece2.tpl" p=$mfm ht=275 boxid=13}
{include file="pedigree_piece2.tpl" p=$mff ht=225 boxid=12}
{include file="pedigree_piece2.tpl" p=$fmm ht=175 boxid=11}
{include file="pedigree_piece2.tpl" p=$fmf ht=125 boxid=10}
{include file="pedigree_piece2.tpl" p=$ffm ht=75 boxid=9}
{include file="pedigree_piece2.tpl" p=$fff ht=25 boxid=8}
</div>
<div id="gen3">
{include file="pedigree_piece2.tpl" p=$mm ht=350 boxid=7}
{include file="pedigree_piece2.tpl" p=$mf ht=250 boxid=6}
{include file="pedigree_piece2.tpl" p=$fm ht=150 boxid=5}
{include file="pedigree_piece2.tpl" p=$ff ht=50 boxid=4}
</div>
<div id="gen2">
{include file="pedigree_piece2.tpl" p=$m ht=300 boxid=3}
{include file="pedigree_piece2.tpl" p=$f ht=100 boxid=2}
</div>
<div id="gen1">
{include file="pedigree_piece2.tpl" p=$s ht=200 boxid=1}
</div>
</div>

<br><br>

<form method="GET" action="chart.php" target="_BLANK">
	<input type="hidden" name="person_id" value="{$s.person_id}" />
	<input type="hidden" name="chart" value="pedigree" />
	Generations:
	<select name="gen">
		<option>3</option>
		<option>4</option>
		<option selected="selected">5</option>
		<option>6</option>
		<option>7</option>
		<option>8</option>
	</select>
	<input type="submit" value="Create Printable PDF">
</form>

{include file="footer.tpl"}
