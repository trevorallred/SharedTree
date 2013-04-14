{include file="header.tpl" title="Bookmarks &amp; Watch List" includejs=1}

<h1>Bookmarks &amp; Watch List</h1>

<table class="grid">
<tr><th>Individual</th>
	<th>Bookmark</th>
	<th>Delete</th>
</tr>
{if $watchlist}
{foreach item=person from=$watchlist}
<tr><td><a href="/person/{$person.person_id}">{$person.family_name}, {$person.given_name}</a></td>
	<td>{if $person.bookmark}
			<a href="watch.php?action=save&data[watch_id]={$person.watch_id}&data[bookmark]=0">YES</a>
		{else}
			<a href="watch.php?action=save&data[watch_id]={$person.watch_id}&data[bookmark]=1">NO</a>
		{/if}</td>
	<td><a href="#" onclick="stConfirm('Are you sure you want to stop watching for changes to this person?','watch.php?action=unwatch&watch_id={$person.watch_id}');"><img src="img/btn_drop.png" width="16" height="16" alt="Unwatch {$person.given_name} {$person.family_name}" border="0"></a></td>
</tr>
{/foreach}
</p>
{else}
<tr><td colspan="3">You aren't watching any records yet.</td></tr>
{/if}
</table>

{include file="footer.tpl"}
