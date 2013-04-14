{include file="header.tpl" title="Orphaned Individual Records"}

<h1>Orphaned Individual Records</h1>

The following list of individuals have no parents, spouses, or children. The should either be merged or deleted.

<table border="1" class="table1">
	<tr>
		<td class="label">Family name</td>
		<td class="label">Given name</td>
		<td class="label">Birth</td>
		<td class="label">Created</td>
		<td class="label">By User</td>
	</tr>
{if $orphans}
{foreach item=result from=$orphans}
	<tr>
		<td><a href="/person/{$result.person_id}">{$result.family_name}</a></td>
		<td><a href="/person/{$result.person_id}">{$result.given_name}</a></td>
		<td>{$result.birth_year}</td>
		<td>{$result.creation_date}</td>
		<td><a href="profile.php?user_id={$result.created_by}">{$result.user_name}</a></td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="5" align="center">Good! There are no orphaned records!</td>
	</tr>
{/if}
</table>

{include file="footer.tpl"}
