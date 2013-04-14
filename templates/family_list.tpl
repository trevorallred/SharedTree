{include file="header.tpl" title="Family Index"}

<h2>Family Index</h2>
<a href="group.php">Group Index</a> | <a href="locations.php">Browse Places</a>

<table class="portal">
<tr><td>
<a href="list.php">Top</a>
{foreach item=lid from=$letters}
 - <a href="list.php?letter={$lid.value}" title="{$lid.total}">{$lid.value}</a>
{/foreach}
</td></tr>
</table>

{if $families}
<table class="portal">
<tr><td>
{foreach item=family from=$families}
	<a href="search.php?search=1&family_name={$family.family_name}"><font size="{$family.font}">{$family.family_name}</font></a>({$family.total})
{/foreach}
</td></tr>
</table>
{else}
	<table class="portal">
	<tr><td>
	Most common family names on SharedTree
	<ol>
	{foreach item=family from=$topnames}
		<li><a href="search.php?search=1&family_name={$family.family_name}">{$family.family_name} ({$family.total})</a></li>
	{/foreach}
	</ol>
	</td></tr>
	</table>
{/if}

{include file="footer.tpl"}
