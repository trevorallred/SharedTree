{include file="header.tpl" help="Users"}

<h2>Profile for {$profile.given_name} {$profile.family_name}</h2>
<table class="portal">
<tr><td>
<img src="image.php?person_id={$profile.person_id}" align="right">
<h3>Profile Details</h3>
{if $profile.person_id}
<label>Linked Individual:</label> <a href="/person/{$profile.person_id}">{$profile.p_given_name} {$profile.p_family_name}</a> <i>(must be related)</i><br>
{/if}
<label>From:</label> {if $profile.city || $profile.state_code}
{$profile.city}, {$profile.state_code}
{else}
<i>unknown location</i>
{/if}<br>
<label>Member since:</label> {$profile.creation_date|date_format:"%e %b %Y"}<br>
<label>Last visit:</label> {$profile.last_visit_date|date_format:"%e %b %Y"}<br>
{if $tree_size}<label>Family Tree Size:</label> {$tree_size}<br>{/if}
<br>
{if $is_logged_on}
<br />
{if $tree_overlap}<label>Relatives in common with you:</label> {$tree_overlap} (<i>{$overlap_percent}% of {$profile.given_name}'s tree</i>)<br>{/if}
<h3>Common Relative(s)</h3>
<ul>
	{foreach item=change from=$common_relatives}
	<li><a href="/person/{$change.person_id}">{$change.given_name} {$change.family_name}</a><br />
	{$profile.given_name}'s {$change.r2_desc}<br />
	your {$change.r1_desc}</li>
	{/foreach}
</ul>
{/if}

<p>{$profile.description}</p>
<hr />
<p align="center"><a href="email.php?to={$profile.user_id}">Send Email</a></p>

</td>
<td>
<h3>All Changes to SharedTree</h3>
{if $person_count}<label>To Individuals:</label> {$person_count}<br>{/if}
{if $family_count}<label>To Marriages:</label> {$family_count}<br>{/if}
{if $event_count}<label>To Events:</label> {$event_count}<br>{/if}

<h3>Recent changes by {$profile.given_name}</h3>
{if $person_changes}
<table class="grid">
<tr><td>Name</td>
	<td>Birth</td>
	<td>Change Date</td>
</tr>
	{foreach item=change from=$person_changes}
<tr><td><a href="/person/{$change.person_id}">{$change.given_name}&nbsp;{$change.family_name}</a></td>
	<td>{if $change.public_flag}{$change.birth_year}{else}private{/if}</td>
	<td>{$change.update_date}</td>
</tr>
	{/foreach}
</table>
{else}
	This user has not made any modifications yet.
{/if}
<a href="search.php?created_by={$profile.user_id}&sort=creation&search=Search">Search for individuals added by {$profile.given_name}</a>
</td></tr></table>

{if $canlogin}
Admin: <a href="login.php?user_id={$profile.user_id}">Login as {$profile.given_name}</a> | <a href="user.php">User List</a> |
{/if}
{if $is_logged_on}
<a href="relatives.php">Your Relatives</a> | 
<a href="invite.php">Invite More</a>
{/if}

{include file="footer.tpl"}
