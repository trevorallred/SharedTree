{include file="header.tpl" title="Calendar"}

<h2>Calendar</h2>

<table class="portal">
<tr><td colspan="2">
<label>Show:</label>
<a href="?level=3">Immediate Family</a> |
<a href="?level=2">Extended Family</a> |
<a href="?level=1">All Relatives</a>
</td></tr>
<tr><td>
{foreach item=month from=$months}
{foreach key=day item=people from=$month}
	{if $day == "name"}
		<h3>{$month.name}</h3>
	{else}
		<b>{$day}</b>
		{foreach from=$people item=person name=person}
			<a href="/person/{$person.person_id}">{$person.given_name} {$person.family_name}</a> {$person.new_age}{if not $smarty.foreach.person.last},{/if}
		{/foreach}
		<br />
	{/if}
{/foreach}
{/foreach}
</td>
<td>
<h3>Relatives Missing Birthdates</h3>
<ul>
{foreach item=person from=$need_birthdate}
	<li><a href="person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></li>
{/foreach}
</ul>
</td>
</table>

<a href="thisday.php">See This Day in Family History</a>

{include file="footer.tpl"}
