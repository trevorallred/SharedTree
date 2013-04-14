<img src="http://www.sharedtree.com/img/spinner_orange.gif" id="spinner" style="display:none;"/>
<div id="search_results2">
	<div class="errors">{$error}</div>
{if $total_records > $result_count}
	<i>Found {$total_records} but only displaying first {$result_count} records.</i>
{/if}

<table>
	<tr>
		<td>Add</td>
		<td>Family name</td>
		<td>Given name</td>
		<td>Birth</td>
		<td>Place</td>
		<td>Gender</td>
	</tr>
{if $results}
	{foreach item=result from=$results}
	<tr>
		<td><a href="?add={$result.person_id}">Add</a>
		<td><a href="http://www.sharedtree.com/person/{$result.person_id}" target="_BLANK">{$result.family_name}</a></td>
		<td><a href="http://www.sharedtree.com/person/{$result.person_id}" target="_BLANK">{$result.given_name}</a></td>
		<td>{$result.birth_year}</td>
		<td>{$result.location}</td>
		<td>{$result.gender}</td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="7" align="center">Sorry, no results were found</td>
	</tr>
{/if}
</table>
</div>
