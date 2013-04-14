{include file="header.tpl" title="Recent Changes"}

<h1>New Additions to SharedTree</h1>

SharedTree is growing! Below are the 100 most recent additions to the database.

<table border="1" class="table1">
	<tr>
		<td class="label">Given name</td>
		<td class="label">Family name</td>
		<td class="label">Added</td>
		<td class="label">By</td>
	</tr>
{foreach item=person from=$changes}
	<tr>
{if $person.public_flag || $person.description}
		<td><a href="/person/{$person.person_id}">{$person.title} {$person.given_name}</a></td>
		<td><a href="/person/{$person.person_id}">{$person.family_name}</a></td>
{else}
		<td>{$person.given_name}</td>
		<td>{$person.family_name}</td>
{/if}
{if $person.description}
		<td>{$person.description}</td>
{/if}
		<td>{$person.creation_date}</td>
		<td><a href="profile.php?user_id={$person.user_id}">{$person.user_name}</a></td>
	</tr>
{/foreach}
</table>

<a href="recent_changes.php">All new entries</a> | <a href="recent_changes.php?action=mytree">Only additions to my tree</a>
{include file="footer.tpl"}
