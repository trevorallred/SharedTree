{include file="header.tpl" title="Search"}
{literal}
<script type="text/javascript">
<!-- Begin
$i1 = null;
$i2 = null;
$lastid = null;
function mergePerson($id) {
	$thisbox = document.getElementById("check"+$id);

	// uncheck this box and set the value to null
	if (!$thisbox.checked) {
		if ($i1 == $id) $i1 = null;
		if ($i2 == $id) $i2 = null;
	} else {
		if ($i1 != $id) {
			$i2 = $i1;
			$i1 = $id;

			$list = document.getElementsByName("personlist");
			for (i = 0; i < $list.length; i++) {
				if ($list[i].value != $i1 && $list[i].value != $i2) {
					$list[i].checked = false;
				}
				//alert($list[i].value + $list[i].checked);
			}
		}
	}
	document.getElementById("merge1").value = $i1;
	document.getElementById("merge2").value = $i2;
}

//  End -->
</script>

<style>
span.datalabel {
	font-weight: bold;
	font-size: 10px;
	cursor: pointer;
}
</style>
{/literal}

<h1 align="center">Search for Individuals</h1>

<div class="errors">{$error}</div>
{if $request.search}
	Showing {$result_count} of {$total_records} records

{if $pages > 1}
	Page:
	{section name=page start=1 loop=$pages+1}
	{if $smarty.section.page.index != $request.page}
		<a href="search.php?search=1&page={$smarty.section.page.index}&family_name={$request.family_name|escape:'url'}&given_name={$request.given_name|escape:'url'}&gender={$request.gender}&birth_year={$request.birth_year}&birth_place={$request.birth_place|escape:'url'}&death_year={$request.death_year}&death_place={$request.death_place|escape:'url'}&adbc={$request.adbc}&range={$request.range}&context={$request.context}&created_by={$request.created_by}&sort={$request.sort}">{$smarty.section.page.index}</a>
	{else}
		{$smarty.section.page.index}
	{/if}
	{/section}
{/if}

<form action="merge.php" method="GET">
	<input type="hidden" name="returnto" value="{$smarty.server.REQUEST_URI}">
	<input type="hidden" name="p1" id="merge1" size="6">
	<input type="hidden" name="p2" id="merge2" size="6">
	<table border="1" class="table1">
		<tr>
			<td class="label"><input type="submit" value="Merge"></td>
			<td class="label">Links</td>
			<td class="label">Family name</td>
			<td class="label">Given name</td>
			<td class="label">Birth</td>
			<td class="label">Place</td>
			<td class="label">Sex</td>
			<td class="label">Data</td>
			<td class="label">Created</td>
		</tr>
	{if $results}
		{foreach item=result from=$results}
		<tr id="row{$result.person_id}">
			<td><input type=checkbox name="personlist" id="check{$result.person_id}" value="{$result.person_id}"  onClick="mergePerson({$result.person_id});"></td>
			<td>{include file="person_nav.tpl" nav_id=$result.person_id direction="flat"}</td>
			<td><a href="/person/{$result.person_id}">{$result.family_name}</a></td>
			<td><a href="/person/{$result.person_id}">{$result.given_name}</a></td>
			<td>{$result.birth_year}</td>
			<td>{$result.location}</td>
			<td>{$result.gender}</td>
			<td>
				{if $result.ancestor_count > 0}<span class="datalabel" title="{$result.ancestor_count} ancestor{if $result.ancestor_count > 1}s{/if}">A</span>{/if}
				{if $result.marriage_count > 0}<span class="datalabel" title="{$result.marriage_count} marriage{if $result.marriage_count > 1}s{/if}">M</span>{/if}
				{if $result.descendant_count > 0}<span class="datalabel" title="{$result.descendant_count} descendent{if $result.descendant_count > 1}s{/if}">D</span>{/if}
				{if $result.biography_size > 0}<span class="datalabel" title="{$result.biography_size} characters in biography">B</span>{/if}
				{if $result.forum_count > 0}<span class="datalabel" title="{$result.forum_count} forum post{if $result.forum_count > 1}s{/if}">F</span>{/if}
				{if $result.photo_count > 0}<span class="datalabel" title="{$result.photo_count} image{if $result.photo_count > 1}s{/if}">I</span>{/if}
			</td>
			<td><font size=1>{$result.creation_date|date_format} by <a href="profile.php?user_id={$result.created_by}">{$result.user_name}</a></font></td>
		</tr>
		{/foreach}
	{else}
		<tr>
			<td colspan="7" align="center">Sorry, no results were found</td>
		</tr>
	{/if}
	</table>
	</form>

	<p><a href="person_edit.php">Add a new individual</a></p>
{/if}

{if $pages > 1}
	Page:
	{section name=page start=1 loop=$pages+1}
	{if $smarty.section.page.index != $request.page}
		<a href="search.php?search=1&page={$smarty.section.page.index}&family_name={$request.family_name|escape:'url'}&given_name={$request.given_name|escape:'url'}&gender={$request.gender}&birth_year={$request.birth_year}&birth_place={$request.birth_place|escape:'url'}&death_year={$request.death_year}&death_place={$request.death_place|escape:'url'}&adbc={$request.adbc}&range={$request.range}&context={$request.context}&created_by={$request.created_by}&sort={$request.sort}">{$smarty.section.page.index}</a>
	{else}
		{$smarty.section.page.index}
	{/if}
	{/section}
{/if}

<form method="GET" action="search.php">
<table class="search">
<tr><td class="search" align="left">
<table border="0">
<tr><td>Last name:</td>
	<td colspan="2"><input type="text" name="family_name" value="{$request.family_name|escape}" class="textfield"></td>
<tr><td>Given name:</td>
	<td colspan="2"><input type="text" name="given_name" value="{$request.given_name|escape}" class="textfield"></td>
</tr>
<tr><td>Gender:</td>
	<td colspan="2">{html_radios name="gender" options=$gender_options selected=$request.gender}</td>
</tr>
<tr><td>Birth year:</td>
	<td colspan="2"><input type="text" name="birth_year" size="5" value="{$request.birth_year}" class="textfield"></td>
</tr>
<tr><td>Birth place:</td>
	<td colspan="2"><input type="text" name="birth_place" value="{$request.birth_place|escape}" class="textfield"></td>
</tr>
<tr><td>Death year:</td>
	<td colspan="2"><input type="text" name="death_year" size="5" value="{$request.death_year}" class="textfield"></td>
</tr>
<tr><td>Death place:</td>
	<td colspan="2"><input type="text" name="death_place" value="{$request.death_place|escape}" class="textfield"></td>
</tr>
<tr><td>Range:</td>
	<td><select name="range" class="textfield">
		<option value=0 {if $request.range==0}selected{/if}>Exact</option>
		<option value=1 {if $request.range==1}selected{/if}>+/- 1 year</option>
		<option value=2 {if $request.range==2}selected{/if}>+/- 2 years</option>
		<option value=5 selected>+/- 5 years</option>
		<option value=10 {if $request.range==10}selected{/if}>+/- 10 years</option>
		<option value=15 {if $request.range==15}selected{/if}>+/- 15 years</option>
		<option value=20 {if $request.range==20}selected{/if}>+/- 20 years</option>
		<option value=50 {if $request.range==50}selected{/if}>+/- 50 years</option>
		<option value=100 {if $request.range==100}selected{/if}>+/- 100 years</option>
		</select></td>
	<td>{html_radios name="adbc" options=$adbc selected=$request.adbc}</td>
</tr>
{if $is_logged_on}
<tr><td>Context:</td>
	<td colspan="2"><label><input type="radio" name="context" value="all" {if $request.context!="tree"}checked{/if} />Everyone</label>
<label><input type="radio" name="context" value="tree" {if $request.context=="tree"}checked{/if} />Your Family</label></td>
</tr>
{/if}
<tr><td>Submitter #:</td>
	<td colspan="2"><input type="text" name="created_by" size="7" value="{$request.created_by}" class="textfield"></td>
</tr>
<tr><td>Sort:</td>
	<td colspan="2"><label><input type="radio" name="sort" value="name" {if $request.sort!="date"}checked{/if} />Name</label>
<label><input type="radio" name="sort" value="birth" {if $request.sort=="birth"}checked{/if} />Birth</label>
<label><input type="radio" name="sort" value="creation" {if $request.sort=="creation"}checked{/if} />Submit Date</label></td>
</tr>
<tr><td>&nbsp;</td><td align="center" colspan="2"><input type="submit" name="search" value="Search"></td>
</tr>
</table>
</td></tr></table>
</form>
<label>Related Pages:</label>
<a href="list.php">Family Index</a> | 
<a href="group.php">Group Index</a> | 
<a href="locations.php">Browse Places</a> 
<br /><br />
{include file="footer.tpl"}
