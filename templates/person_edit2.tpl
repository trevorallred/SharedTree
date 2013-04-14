{include file="header.tpl" title=$title}

{literal}
<style>
table.tabber
{
	border-collapse: collapse;
	border: 0px;
	margin: 0px;
	padding: 0px;
}

td.tabbercontent
{
    BORDER-LEFT: 0px;
    BORDER-TOP: #aaa 1px solid;
	BORDER-RIGHT: #aaa 1px solid;
    BORDER-BOTTOM: #aaa 1px solid;
    PADDING: 5px;
    MARGIN: 5px;
	width:300px;
    BACKGROUND: #fff;
}
.tabcontent
{
    DISPLAY: none;
}
.tabbernav
{
    BORDER-RIGHT: #778 1px solid;
    PADDING: 0px;
    MARGIN: 0px;
    FONT: bold 12px Verdana, sans-serif;
}
.tabbernav li 
{
	position:relative;
	right:0px;
	width:100px;
    LIST-STYLE-TYPE: none;
    BORDER-TOP: #778 1px solid;
    BORDER-RIGHT: 0px;
    BORDER-LEFT: #778 1px solid;
    BORDER-BOTTOM: #778 1px solid;
    BACKGROUND: #dde;
    TEXT-ALIGN: center;
	cursor: pointer;
    PADDING: 3px;
	MARGIN-TOP: 3px;
	MARGIN-BOTTOM: 3px;
	MARGIN-LEFT: 0px;
	MARGIN-RIGHT: 0px;
}
li.tabactive {
    BACKGROUND: white;
    COLOR: #000;
    BORDER-RIGHT: white 1px solid;
	position: relative;
	right:-2px;
}

</style>
<SCRIPT LANGUAGE="JavaScript">
<!--

function tabDisplay(id, showtab) {
	var contentname = 'content'+id;
	var tabname = 'tab'+id;
	if (showtab) {
		var newclass = "tabactive";
		var newdisp = "block";
	} else {
		var newclass = "";
		var newdisp = "";
	}

	if (document.getElementById) {
		// this is the way the standards work
		var xcontent = document.getElementById(contentname).style;
		var xtab = document.getElementById(tabname);
	} else if (document.all) {
		// this is the way old msie versions work
		var xcontent = document.all[id].style;
		var xtab = document.all[id];
	} else if (document.layers) {
		// this is the way nn4 works
		var xcontent = document.layers[id].style;
		var xtab = document.layers[id];
	}
	xtab.className = newclass;
	xcontent.display = newdisp;
}

function chooseTab(id) {
{/literal}
{foreach item=gcode from=$gedcomcodes}
	{if ($show_lds==1 || $event.lds_flag==0) }
	tabDisplay('{$gcode.gedcom_code}', 0);
	{/if}
{/foreach}
{literal}
	tabDisplay(id, 1);
}

function toggleLayer(whichLayer) {
	if (document.getElementById) {
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = style2.display? "":"block";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = style2.display? "":"block";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = style2.display? "":"block";
	}
}
//-->
</script>
{/literal}

{if $person.person_id}
	<h2>{$title}</h2>
	<div>
	<a href="person.php?person_id={$person_id}"><img src="img/btn_individual.png" title="Individual Details" width="16" height="16" border="0" /></a>
	<a href="family.php?person_id={$person_id}"><img src="img/btn_family.png" title="Family Details" width="16" height="16" border="0" /></a>
	<a href="ped.php?person_id={$person_id}"><img src="img/btn_pedigree.png" title="Ancestor Pedigree" width="16" height="16" border="0" /></a>
	<a href="descendants.php?person_id={$person_id}"><img src="img/btn_descend.png" title="Descendants" width="16" height="16" border="0" /></a>
	</div>
{else}
	{if $results}
		<h2>Choose Existing Individual</h2>
		<table class="grid">
		<tr><th>Name:</th>
			<th>Gender:</th>
			<th>Birth:</th>
			<th>Location:</th>
			<th></th>
		</tr>
		{foreach item=result from=$results}
		<tr><td><a href="person.php?person_id={$result.person_id}">{$result.given_name} {$result.family_name}</a></td>
			<td>{$result.gender}</td>
			<td>{$result.event_date}</td>
			<td>{$result.location}</td>
			<td><a href="?action=save&person_id={$result.person_id}&person[parents_id]={$person.parents_id}&person[child_id]={$person.child_id}&person[spouse_id]={$person.spouse_id}&person[marriage_id]={$person.marriage_id}&return_to={$return_to}">Choose</a></td>
		</tr>
		{/foreach}
		</table>
		<h2>Create New Individual</h2>
	{else}
		<h2>Create New Individual</h2>
	{/if}
{/if}
{if $errors}
<ul class="errors">
	{foreach item=error from=$errors}
	<li>{$error}</li>
	{/foreach}
</ul>
{/if}
<form method="POST">
<table class="portal" width="700px">
<tr><td align="center">
<input type="submit" name="save" value="Save"><br>
<input type="hidden" name="action" value="save">
<input type="hidden" name="return_to" value="{$return_to}">
<input type="hidden" name="person[person_id]" value="{$person.person_id}">
{if $person.parents_id}
	<input type="hidden" name="person[parents_id]" value="{$person.parents_id}">
{/if}
{if $person.marriage_id}
	<input type="hidden" name="person[marriage_id]" value="{$person.marriage_id}">
{/if}
{if $person.spouse_id}
	<input type="hidden" name="person[spouse_id]" value="{$person.spouse_id}">
{/if}
{if $person.child_id}
	<input type="hidden" name="person[child_id]" value="{$person.child_id}">
{/if}
</td></tr>
<tr><td>
<h3>Recommended Information</h3>

<table class="editPerson">
<tr>
	<th>Last Name:</th>
	<td><input type="text" name="person[family_name]" value="{$person.family_name}"></td>
	<td width="150">The last name or the family name</td>
</tr>
<tr>
	<th>Given Name(s):</th>
	<td><input type="text" name="person[given_name]" value="{$person.given_name}"></td>
	<td width="150">The first, middle, and any other given names</td>
</tr>
<tr>
	<th>Gender:</th>
	<td>{html_radios name="person[gender]" options=$gender_options selected=$person.gender}</td>
	<td width="150">The gender of the individual</td>
</tr>
</table>
<br>
</td></tr>
</table>

{include file="event_edit.tpl" events=$person.e eventtype="person" defaultevent="1"}

<table class="portal" width="700px">
<tr><td align="center">
<input type="submit" name="save" value="Save">
</td></tr>
<tr><td>
<h3>Optional Information</h3>

<table class="editPerson">
<tr>
	<th>Child Order:</th>
	<td><input type="text" name="person[child_order]" value="{$person.child_order}"></td>
	<td width="150">The order of this child in the family. Only use this when birth dates are unknown.</td>
</tr>
<tr>
	<th>Prefix:</th>
	<td><input type="text" name="person[prefix]" value="{$person.prefix}"></td>
	<td width="150">Person's name prefix if any</td>
</tr>
<tr>
	<th>Suffix:</th>
	<td><input type="text" name="person[suffix]" value="{$person.suffix}"></td>
	<td width="150">Person's suffix if any</td>
</tr>
<tr>
	<th>Nickname:</th>
	<td><input type="text" name="person[nickname]" value="{$person.nickname}"></td>
	<td width="150">The person's preferred name</td>
</tr>
<tr>
	<th>Title\Royalty:</th>
	<td><input type="text" name="person[title]" value="{$person.title}"></td>
	<td width="150">Title or royalty status</td>
</tr>
<tr>
	<th>RIN:</th>
	<td><input type="text" name="person[rin]" value="{$person.rin}"></td>
	<td width="150"></td>
</tr>
<tr>
	<th>AFN:</th>
	<td><input type="text" name="person[afn]" value="{$person.afn}"></td>
	<td width="150">Ancestral File Number</td>
</tr>
<tr>
	<th>SSN or National ID:</th>
	<td><input type="text" name="person[national_id]" value="{$person.national_id}"></td>
	<td width="150">A social security number or other national identity number</td>
</tr>
<tr>
	<th>Nationality or Origin:</th>
	<td><input type="text" name="person[national_origin]" value="{$person.national_origin}"></td>
	<td width="150">The national or tribal origin</td>
</tr>
<tr>
	<th>Occupation:</th>
	<td><input type="text" name="person[occupation]" value="{$person.occupation}"></td>
	<td width="150">The primary occupation of this person during life</td>
</tr>
</table>

</td></tr>
</table>
{include file="event_edit.tpl" events=$person.e eventtype="person" defaultevent="0"}

<table class="portal" width="700px">
<tr><td align="center">
<input type="submit" name="save" value="Save">
</td></tr>
</table>
</form>

{include file="footer.tpl"}