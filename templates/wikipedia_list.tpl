{include file="header.tpl" title="Wikipedia Biographies" help="Wikipedia Biographies"}

<h2>Wikipedia Biographies</h2>

<table class="table1" border="1">
<tr>
<th>Links</th>
<th>Person</th>
<th>Born</th>
<th>Wikipedia Article</th>
</tr>
{foreach item=person from=$people}
<tr>
<td>{include file="person_nav.tpl" nav_id=$person.person_id direction="flat"}</td>
<td><a href="person/{$person.person_id}">{$person.title} {$person.given_name} {$person.family_name}</a></td>
<td>{$person.birth_year}</td>
<td><a href="http://en.wikipedia.org/wiki/{$person.wikipedia}">{$person.wikipedia}</a></td>
</tr>
{/foreach}
</table>

{include file="footer.tpl"}
