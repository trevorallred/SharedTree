{include file="header.tpl" includejs=1}

<h2>{$group.group_name} {$group.initials}</h2>

<a href="group.php">Group List</a>
| <a href="group.php?action=edit&group_id={$group_id}">Edit {$group.group_name}</a>

<table class="portal">
<tr align="left">
<td width="350">
<label>From {$group.start_year} to {if $group.end_year > ""}{$group.end_year}{else}Present{/if}</label>
<br><br>
{$group.description}
</td>
</tr>
</table>

<table class="grid">
<tr>
<th>Name</th>
<th>Born</th>
<th>Remove</th>
</tr>
{if $members}
	{foreach item=person from=$members}
		<tr>
		<td align="left"><a href="/person/{$person.person_id}">{$person.family_name}, {$person.given_name}</a></td>
		<td>{$person.birth_year}</td>
		<td><a href="#" onclick="stConfirm('Are you sure you want to remove {$person.given_name|escape:'html'} {$person.family_name|escape:'html'} from this group?', 'group.php?action=deletemember&group_id={$group_id}&person_id={$person.person_id}');"><img src="/img/btn_drop.png" title="Remove from group" width="16" height="16" border="0" /></a></td>
		</tr>
	{/foreach}
{else}
	<tr>
	<td colspan="3">no group members found</td>
	</tr>
{/if}
</table>


<p>
<font size="1" color="#999999">
<label>Created by:</label> <a href="profile.php?user_id={$group.created_by}">{$group.created_name}</a> on {$group.creation_date}<br>
<label>Updated by:</label> <a href="profile.php?user_id={$group.updated_by}">{$group.updated_name}</a> on {$group.update_date}
</font>
</p>
{include file="footer.tpl"}
