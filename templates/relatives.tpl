{include file="header.tpl" title="Relatives"}
{include file="util_header.tpl"}

<h2>Your Relatives on SharedTree</h2>
<a href="invite.php">Invite More</a>
<table class="grid">
<tr>
	<th>Last</th>
	<th>First</th>
	<th>Address</th>
	<th>Last visit</th>
</tr>
	{foreach item=user from=$users}
<tr><td><a href="profile.php?user_id={$user.user_id}">{$user.family_name}</a></td>
	<td><a href="profile.php?user_id={$user.user_id}">{$user.given_name}</a></td>
	<td>{if $user.address_line1}{$user.address_line1}<br>{/if}
		{if $user.address_line2}{$user.address_line2}<br>{/if}
		{$user.city}{if $user.city && $user.state_code}, {/if}{$user.state_code}</td>
	<td>{$user.last_visit_date|date_format}</td>
</tr>
	{/foreach}
</table>

This is a list of living people, registered on SharedTree, <br />
who are directly related to someone in your <a href="familytree.php">family tree index</a>.

{include file="footer.tpl"}
