<b>Showing {$count} individual{if $count <> 1}s{/if} in your family tree {if $trace}filter by {$trace}{/if}.</b>

<table class="grid">
<tr><th>Name</th>
<th>Thru</th>
<th>Relation</th>
<th>Birthplace</th>
</tr>
{if $relatives}
{foreach item=person from=$relatives}
	<tr><td><a href="person/{$person.person_id}">{$person.given_name} {$person.family_name}</a></td>
	<td>{$person.trace}</td>
	<td>{$person.birth_year}</td>
	<td>{$person.location}</td>
	</tr>
{/foreach}
{else}
	<tr><td colspan="2"><br>You need to click the Rebuild link <br />on the right to build your tree<br><br></td></tr>
{/if}
</table>
