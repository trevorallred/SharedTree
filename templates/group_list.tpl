{include file="header.tpl" includejs=1}

<h2>Groups</h2>
<a href="list.php">Family Index</a> | <a href="locations.php">Browse Places</a><br>

<table class="grid">
<tr>
{if $person_id}<th>Choose</th>{/if}
<th align="left"><a href="?sort=group_name">Group Name</a></th>
<th><a href="?sort=member_count">Members</a></th>
<th><a href="?sort=start_year">Starting</a></th>
<th>Ending</th>
</tr>
{if $groups}
	{foreach item=grp from=$groups}
		<tr>
		{if $person_id}<td><a href="group.php?action=addmember&group_id={$grp.group_id}&person_id={$person_id}">Choose</a></td>{/if}
		<td align="left"><a href="group.php?group_id={$grp.group_id}">{$grp.group_name}</a></td>
		<td>{$grp.member_count}</td>
		<td>{$grp.start_year}</td>
		<td>{$grp.end_year}</td>
		</tr>
	{/foreach}
{else}
	<tr>
	<td colspan="{if $person_id}5{else}4{/if}">no groups found</td>
	</tr>
{/if}
	<tr>
	<td colspan="{if $person_id}5{else}4{/if}"><a href="group.php?action=edit">Add new group</a></td>
	</tr>
</table>


{include file="footer.tpl"}
