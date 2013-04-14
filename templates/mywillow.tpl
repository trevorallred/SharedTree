{include file="header.tpl" title="My Tree"}

<br clear="all" />
<table>
<tr valign="top"><td width="30%"><!-- left panel -->
<table class="portal" width="100%">
<tr><td width="100%">
	&gt;&gt; <a href="simple.php"><b>Click Here to Get Started</b></a> &lt;&lt;
	<br><br><br>

{if $user.person_id > 0}
	<h3>My Personal Genealogy Record</h3>
	<label>{$user.p_given_name} {$user.p_family_name}</label><br />
	{include file="person_nav.tpl" nav_id=$user.person_id direction="flat"}
	<br><br />
	{if $myline.person == 0}
		<span class="errors">You need to <a href="familytree.php">rebuild your family tree</a>!</span>
	{else}
		You currently have {$myline.person} individuals <br>
		in your <a href="familytree.php">family tree</a>
	{/if}
{/if}
</td></tr>
<tr><td>
	<div style="float:right"><a href="profile.php"><img src="img/btn_edit.png" title="Edit Profile" width="16" height="16" border="0"/>Edit</a></div>
	<h3>My Profile</h3>
	<span class="label">Name:</span> {$user.given_name} {$user.family_name}<br>
	<span class="label">Email:</span> {$user.email}<br>
	<span class="label">Address:</span>
	{if $user.address_line1 || $user.city}
		{if $user.address_line1}{$user.address_line1}{else}<a href="profile.php">No Street Address</a>{/if}
		{if $user.address_line2}{$user.address_line2}{/if}
		{$user.city}, {$user.state_code} {$user.postal_code}
	{else}
		<a href="profile.php"><i>Add your mailing address</i></a>
	{/if}
</td></tr>
<tr><td>
	<h3>My Bookmarks</h3>
	{if $bookmarks}
	<ul>
		{foreach item=bookmark from=$bookmarks}
		<li><a href="/person/{$bookmark.person_id}">{$bookmark.family_name}, {$bookmark.given_name}</a></li>
		{/foreach}
	</ul>
	{else}
		<i>No Bookmarks</i><br />
	{/if}
	<a href="watch.php">Manage Bookmarks &amp; Watchlist</a>
</td></tr>
{if $user.person_id > 0}
<tr><td>
	<h3>My Online Relatives</h3>
	<ul>
		{foreach item=relative from=$relatives}
		<li><a href="profile.php?user_id={$relative.user_id}" title="{$relative.trace}">{$relative.given_name} {$relative.family_name}</a> {$relative.last_visit_date|date_format:"%e %b %H:%M"}</li>
		{/foreach}
	</ul>
<a href="relatives.php">View all relatives</a> |
<a href="invite.php">Invite others</a>
</td></tr>
{/if}
{if $photos}
<tr><td>
        <h3>New Photos &amp; Source Documents</h3>
        <ul>
                {foreach item=photo from=$photos name=photos}
{if $smarty.foreach.photos.index == 0}
		<img src="image.php?image_id={$photo.image_id}&size=thumb" align="right" border="1" />
{/if}
                <li><a href="image.php?image_id={$photo.image_id}&action=summary">{$photo.given_name} {$photo.family_name} ({$photo.birth_year})</a><br />
		<font size="1">added by <a href="profile.php?user_id={$photo.updated_by}">{$photo.updated_name}</a> on {$photo.update_date|date_format:"%b %e"}</font></li>
                {/foreach}
        </ul>
<a href="relatives.php">View all relatives</a> |
<a href="invite.php">Invite others</a>
</td></tr>
{/if}
</table>
</td>
{if $user.person_id > 0}
<td width="40%"><!-- middle panel -->
<table class="portal" width="100%">
<tr><td width="100%">
<h3>Recent Changes</h3>

<b>Individuals</b>
{if $person_changes}
<table class="grid">
	{foreach item=change from=$person_changes}
<tr><td><a href="/person/{$change.person_id}" title="Relation: {$change.trace}">{$change.given_name} {$change.family_name}</a></td>
	<td>{$change.actual_start_date|date_format:"%e %b %H:%M"}</td>
	<td><a href="profile.php?user_id={$change.user_id}">{$change.user_name}</a></td>
</tr>
	{/foreach}
</table>
{else}
	<p>There have been no changes to individuals yet</p>
{/if}
<a href="recent_changes.php">Recent Changes</a>

<p>
<b>Posts</b>
<table class="grid">
{foreach item=post from=$posts}
	<tr>
	<td>{$post.update_date|date_format:"%e %b %H:%M"} <a href="profile.php?user_id={$post.user_id}">{$post.ugiven_name}</a></td>
	<td><a href="/person/{$post.person_id}#post{$post.post_id}">{$post.given_name} {$post.family_name}</a> - {$post.post_text|strip_tags:false|truncate:50}</a></td>
	</tr>
{/foreach}
</table>

</p>

<p>
<b>Biographies</b>
<table class="grid">
{foreach item=post from=$bios}
	<tr>
	<td>{$post.update_date|date_format:"%b %e"} <a href="profile.php?user_id={$post.user_id}">{$post.ugiven_name}</a></td>
	<td><a href="/person/{$post.person_id}">{$post.given_name} {$post.family_name}</a> - {$post.post_text|strip_tags:false|truncate:50}</a></td>
	</tr>
{/foreach}
</table>

</p>

</td></tr>
</table>
</td>
{/if}
<td width="30%"><!-- right panel -->
<table class="portal" width="100%">
{if $announcements}
<tr><td width="100%">
<h3>Announcements</h3>
{foreach item=announce from=$announcements}
	<p><label>{$announce.start_date|date_format:"%b %e"}:</label> {$announce.announcement} <br /><i>Posted by: {$announce.user_name}</i></p>
{/foreach}
</td></tr>
{/if}
{if $recent_forum_posts}
<tr><td width="100%">
<h3>Recent Forum Posts</h3>
{foreach item=posts from=$recent_forum_posts}
	<label>{$posts.topic_last_post_time|date_format:"%b %e"}</label> <i>by {$posts.topic_last_poster_name}</i> <a href="forums/viewtopic.php?t={$posts.topic_id}">{$posts.topic_title}</a><br />
{/foreach}
</td></tr>
{/if}
<tr><td>
<h3>Recently Viewed</h3>
{if $viewed}
<table class="grid">
<tr>
	<th>Individual</th>
	<th>Last Viewed</th>
</tr>
{foreach item=person from=$viewed}
<tr>
	<td><a href="/person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></td>
	<td>{$person.last_update_date|date_format:"%e %b %H:%M"}</td>
</tr>
{/foreach}
</table>
{else}
	<p>You haven't seen any individuals yet</p>
{/if}
</td></tr>
{if $user.person_id > 0}
<tr><td>
<h3>Other Links</h3>
<ul class="sidelinks">
<li><a href="person_edit.php">Add Individual</a></li>
<li><a href="locations.php">Browse Places</a></li>
<li><a href="calendar.php">Calendar</a></li>
<li><a href="group.php">Groups</a></li>
{if $user.person_id > 0}
<li><a href="familytree.php">My Family Tree</a></li>
<li><a href="relatives.php">My Relatives</a></li>
{/if}
{if $user.show_lds > 0}
<li><a href="templework.php">Temple Work</a></li>
{/if}
<li><a href="orphans.php">Orphans</a></li>
<li><a href="stats.php">Stats</a></li>
<li><a href="list.php">Surname List</a></li>
<li><a href="wikipedia.php">Wikipedia Articles</a></li>
</ul>
</td></tr>
{/if}
{if $user.user_id == 1}
<tr><td>
<h3>Utilities for Trevor</h3>

<a href="util/merge_search.php">Merge Individuals</a><br>
<a href="http://phpmyadmin.mysite4now.com/">phpMyAdmin</a><br>
<a href="util/estimate_birth.php">Estimate Birth Years</a><br>
<a href="util/set_public_flag.php">Set Public Flags</a><br>
<a href="util/rebuild_trees.php">Build FTIs</a><br>
<a href="tests/">Unit Testing</a><br>
<a href="user.php">User List</a><br>
</td></tr>
{/if}
<tr><td width="100%">
<h3>Statistics</h3>
{$count_person} individuals
<br>{$count_family} marriages
<br>{$count_user} users
</td></tr>

</td>
</tr>
</table>
</td></tr>
</table>

{include file="footer.tpl"}
