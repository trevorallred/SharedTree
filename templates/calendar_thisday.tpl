{include file="header.tpl" title="Calendar"}

<h2>{$thisday} in Your Family History</h2>

<table class="portal">
<tr><td>
<table border=0>
<tr>
<td style="width: 100px; border: none; align: left; vertical-align: middle;"><a href="?m={$ym}&d={$yd}">&lt;&lt;&nbsp;Back</a></td>
<td style="border: none">
<form style="margin: 0px; padding: 0px; text-align: center;">
	Month: {html_options name="m" options=$months selected=$m}
	Day: {html_options name="d" options=$days selected=$d}
	<input type="submit" value="Go" />
</form>
</td>
<td style="width: 100px; border: none; text-align: right; vertical-align: middle;"><a href="?m={$tm}&d={$td}">Forward&nbsp;&gt;&gt;</a></td>
</tr>
</table>
</td></tr>
<tr><td>
<h2>Births</h2>
	<ul>
	{foreach item=person from=$events}
		{if $person.event_type == "BIRT"}
		<li>{$person.event_year} <a href="person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></li>
		{/if}
	{/foreach}
	</ul>

<h2>Marriages</h2>
	<ul><i>coming soon</i>
	{foreach item=person from=$events}
		{if $person.event_type == "MARR"}
		<li>{$person.event_year} <a href="person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></li>
		{/if}
	{/foreach}
	</ul>

<h2>Deaths</h2>
	<ul>
	{foreach item=person from=$events}
		{if $person.event_type == "DEAT"}
		<li>{$person.event_year} <a href="person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></li>
		{/if}
	{/foreach}
	</ul>

</td></tr>
</table>

{include file="footer.tpl"}
