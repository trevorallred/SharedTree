{include file="header.tpl" title=$title includejs=1 background="edit"}

<script type="text/javascript" src="/js/autocomplete.js"></script>
<script type="text/javascript">
{literal}
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
{/literal}
</script>

{if $person.person_id}
	<h2>{$title}</h2>
{else}
	{if $results}
		<h2>Choose Existing Individual</h2>
		<table class="grid">
		<tr><th>Name:</th>
			<th>Gender:</th>
			<th>Birth:</th>
			<th>Location:</th>
			<th>Parents:</th>
			<th></th>
		</tr>
		{foreach item=result from=$results}
		<tr><td><a href="/person/{$result.person_id}">{$result.given_name} {$result.family_name}</a></td>
			<td>{$result.gender}</td>
			<td>{$result.event_date}</td>
			<td>{$result.location}</td>
			<td><font size="1"><a href="family.php?person_id={$result.person1_id}" target="_BLANK">{$result.given_name1} {$result.family_name1}</a> &amp; <a href="family.php?person_id={$result.person2_id}" target="_BLANK">{$result.given_name2} {$result.family_name2}</a></font></td>
			<td><a href="?action=save&person_id={$result.person_id}&person[parents_id]={$person.parents_id}&person[child_id]={$person.child_id}&person[spouse_id]={$person.spouse_id}&person[marriage_id]={$person.marriage_id}&return_to={$return_to|escape:'url'}">Choose</a></td>
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
<table class="portal">
<tr><td align="center">
<label style="float: right"><input type="checkbox" name="watch" value="1" checked>Watch for changes to this individual</label>
<input type="submit" name="save" value="Save">
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
{if $person.attachuser}
	<input type="hidden" name="person[attachuser]" value="{$person.attachuser}">
{/if}
</td></tr>
<tr><td>
<h3>Recommended Information</h3>

<table class="editPerson">
<tr>
	<th>Family or Last Name:</th>
	<td><input type="text" class="textfield" name="person[family_name]" value="{$person.family_name|escape:'html'}"></td>
	<td width="150">The last name or the family name. Use only latin characters. See the <u>Original family name</u> below for Chinese, Hebrew, Arabic and other character sets.</td>
</tr>
<tr>
	<th>Given or First Name(s):</th>
	<td><input type="text" class="textfield" name="person[given_name]" value="{$person.given_name|escape:'html'}"></td>
	<td width="150">The first, middle, and any other given names</td>
</tr>
<tr>
	<th>Gender:</th>
	<td>{html_radios name="person[gender]" options=$gender_options selected=$person.gender}</td>
	<td width="150">The gender of the individual</td>
</tr>
</table>

{include file="event_edit.tpl" events=$person.e eventtype="person" defaultevent="1"}
</td></tr>
<tr><td align="center">
<input type="submit" name="save" value="Save">
</td></tr>
<tr><td>
<h3>Optional Information</h3>

<table class="editPerson">
<tr>
	<th>Child Order:</th>
	<td><input type="text" class="textfield" name="person[child_order]" value="{$person.child_order}" size="6"></td>
	<td width="150">The order of this child in the family. Only use this when birth dates are unknown.</td>
</tr>
<tr>
	<th>Prefix:</th>
	<td><input type="text" class="textfield" id="person_prefix" name="person[prefix]" value="{$person.prefix|escape:'html'}" size="6"></td>
	<td width="150">Person's name prefix if any.</td>
</tr>
<tr>
	<th>Suffix:</th>
	<td><input type="text" class="textfield" id="person_suffix" name="person[suffix]" value="{$person.suffix|escape:'html'}" size="6"></td>
	<td width="150">Person's suffix if any. Examples include Jr., Sr., I, II, III, etc.</td>
</tr>
<tr>
	<th>Nickname:</th>
	<td><input type="text" class="textfield" name="person[nickname]" value="{$person.nickname|escape:'html'}"></td>
	<td width="150">The person's preferred name.</td>
</tr>
<tr>
	<th>Original family name:</th>
	<td><input type="text" class="textfield" name="person[orig_family_name]" value="{$person.orig_family_name}"></td>
	<td width="150">The person's family name using their native alphabet such as Chinese, Japanese, Korean, Hebrew, Arabic, or Cyrillic. Leave blank if they used a latin alphabet like English or Spanish.</td>
</tr>
<tr>
	<th>Original given name:</th>
	<td><input type="text" class="textfield" name="person[orig_given_name]" value="{$person.orig_given_name}"></td>
	<td width="150">The person's given name(s) using their native alphabet.</td>
</tr>
<tr>
	<th>Title\Royalty:</th>
	<td><input type="text" class="textfield" id="person_title" name="person[title]" value="{$person.title|escape:'html'}"></td>
	<td width="150">Title or royalty status. Examples include King, Dr., and Sergent</td>
</tr>
<tr>
	<th>AFN:</th>
	<td><input type="text" class="textfield" name="person[afn]" value="{$person.afn|escape:'html'}"></td>
	<td width="150">Ancestral File Number</td>
</tr>
<tr>
	<th>SSN or National ID:</th>
	<td><input type="text" class="textfield" name="person[national_id]" value="{$person.national_id|escape:'html'}"></td>
	<td width="150">A social security number or other national identity number</td>
</tr>
<tr>
	<th>Nationality or Origin:</th>
	<td><input type="text" class="textfield" id="person_national_origin" name="person[national_origin]" value="{$person.national_origin|escape:'html'}"></td>
	<td width="150">The national or tribal origin</td>
</tr>
<tr>
	<th>Occupation:</th>
	<td><input type="text" class="textfield" id="person_occupation" name="person[occupation]" value="{$person.occupation|escape:'html'}"></td>
	<td width="150">The primary occupation of this person during life</td>
</tr>
<tr>
	<th>Wikipedia:</th>
	<td><input type="text" class="textfield" name="person[wikipedia]" value="{$person.wikipedia|escape:'html'}"></td>
	<td width="150">The English Wikipedia article featuring this individual.</td>
</tr>
</table>

{include file="event_edit.tpl" events=$person.e eventtype="person" defaultevent="0"}
</td></tr>
<tr><td align="center">
<input type="submit" name="save" value="Save">
</td></tr></table>
{literal}
<script type="text/javascript">
	new AutoComplete('person_prefix', '/suggest.php?field=prefix&value=',{threshold: 1});
	new AutoComplete('person_suffix', '/suggest.php?field=suffix&value=',{threshold: 1});
	new AutoComplete('person_title', '/suggest.php?field=title&value=',{threshold: 1});
	new AutoComplete('person_national_origin', '/suggest.php?field=national_origin&value=',{threshold: 1});
	new AutoComplete('person_occupation', '/suggest.php?field=occupation&value=',{threshold: 2});
</script>
{/literal}

</form>
{include file="footer.tpl"}
