{include file="header.tpl" includejs="1"}
{literal}
<script LANGUAGE="JavaScript">
<!-- Begin
function selectSpouse(spouse) {
	var merge = $('spouse'+spouse).value;
{/literal}
	var pars = 'action=matchspouse&p1={$b.person_id}&p2={$a.person_id}&spouse='+spouse+'&merge='+merge;
{literal}
	alert(pars);
	var myAjax = new Ajax.Updater('div_spouse'+spouse, 'merge_desc.php', {method: 'get', parameters: pars});
}
function showPerson(p1, p2) {
	$("displayscreen").innerHTML = '<img src="/img/spinner_orange.gif">';
	$("displayscreen").show();
	new Effect.BlindUp($("pedigree"), {duration: .5});
        var pars = 'action=compare&p1='+p1+'&p2='+p2;
        new Ajax.Updater('displayscreen', 'merge_ped.php', {method: 'get', parameters: pars});
}
// End -->
</script>
<style>
</style>
{/literal}

<h2>Match Descendents of <a href="/person/{$b.person_id}">{$b.given_name} {$b.family_name} ({$b.person_id})</a></h2>
<table class="grid">
<tr><td>
{foreach item=spouse from=$b.spouses}
<div id="div_spouse{$spouse.person_id}">
<table>
<form>
	<tr>
	<td><label>Married:</label></td>
	<td><b>{$spouse.full_name}</b></td>
	<td rowspan="3">
	<select size="4" id="spouse{$spouse.person_id}">
		<option value="-1"><i>--&gt; Add {$spouse.full_name} as a new spouse</i></option>
		<option value="0"><i>&lt;-- Delete {$spouse.full_name}</i></option>
	{foreach item=spouse1 from=$a.spouses}
		<option value="{$spouse1.person_id}">{$spouse1.full_name} B: {$spouse1.b_date} {$spouse1.b_location} D: {$spouse1.d_date} {$spouse1.d_location}</option>
	{/foreach}
	</select></td>
	<td rowspan="3"><input type="button" onclick="selectSpouse({$spouse.person_id});" value="Select" /></td>
	</tr>
	<tr>
	<td><label>Birth:</label></td>
	<td>{$spouse.b_date} {$spouse.b_location}</td>
	</tr>
	<tr>
	<td><label>Death:</label></td>
	<td>{$spouse.d_date} {$spouse.d_location}</td>
	</tr>
</form>
</table>
</div>
{/foreach}
</td></tr>
</table>
{include file="footer.tpl"}
