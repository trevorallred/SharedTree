{include file="header.tpl" title="Edit Biography" background="edit"}

<h2>Edit Biography for {$person.given_name} {$person.family_name}</h2>
<label>
Version: {if $update_date}{$update_date}{else}current{/if} by <a href="/profile.php?user_id={$wiki.user_id}">{$wiki.given_name} {$wiki.family_name}</a>
</label>

<form method="POST">
<input type="hidden" name="action" value="wikiedit">
<input type="hidden" name="wiki[wiki_id]" value="{$wiki.wiki_id}">
<input type="hidden" name="wiki[person_id]" value="{$wiki.person_id}">

<input type="submit" name="save" value="Save">
<br>
<textarea name="wiki[wiki_text]" cols="80" rows="30">{$wiki.wiki_text}</textarea><br>
<label><input type="checkbox" name="watch" value="1" checked>Watch for changes to this individual</label><br>
<input type="submit" name="save" value="Save">
</form>

{if $history}
<table class="portal">
<tr><td>
View previous edits to this biography:
<ul>
	<li><a href="/person/{$wiki.person_id}&action=wikiedit">Current</a></li>
{foreach item=change from=$history}
	<li><a href="/person/{$wiki.person_id}&action=wikiedit&update_date={$change.update_date}">{$change.update_date}</a> by <a href="profile.php?person_id={$change.user_id}">{$change.given_name} {$change.family_name}</a> - {$change.text_length} bytes</li>
{/foreach}
</ul>
</td></tr></table>
{/if}

{include file="footer.tpl"}
