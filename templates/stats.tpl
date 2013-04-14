{include file="header.tpl"}

<h2>{$prompt}</h2>

<table class="portal">
<tr><td>
<form>
{if $type}
<input type="hidden" name="type" value="{$type}">
{/if}
<label>Lastname:</label> <input type="textbox" name="search[familyname]" size="10" value="{$search.familyname}">
<label>Birthyear:</label>
between <input type="textbox" name="search[birthstart]" size="5" value="{$search.birthstart}">
    and <input type="textbox" name="search[birthend]" size="5" value="{$search.birthend}">
<input type="submit" value="Search">
</form>
</td></tr>
</table>

<table class="grid">
<tr><td>Name</td>
	<td>Birth</td>
	<td><a href="stats.php?type=page_views{if $search.familyname}&search[familyname]={$search.familyname}{/if}{if $search.birthstart}&search[birthstart]={$search.birthstart}{/if}{if $search.birthstart}&search[birthend]={$search.birthend}{/if}">Page Views</a></td>
	<td><a href="stats.php?type=descendant_count{if $search.familyname}&search[familyname]={$search.familyname}{/if}{if $search.birthstart}&search[birthstart]={$search.birthstart}{/if}{if $search.birthstart}&search[birthend]={$search.birthend}{/if}">Descendants</a></td>
	<td></td>
</tr>
{foreach item=person from=$people}
<tr><td><a href="/person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></td>
	<td>{$person.birth_year}</td>
	<td>{$person.page_views}</td>
	<td>{$person.descendant_count}</td>
</tr>
{/foreach}
</table>

<a href="?action=descendant_count">Count Descendants</a> (admin)

{include file="footer.tpl"}
