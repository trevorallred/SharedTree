{include file="header.tpl" title="LDS Temple Work To Submit"}

<h2>Temple Work Report</h2>
<b>Ancestors Still Requiring Temple Work</b>

<form action="export.php" method="POST">
	<input type="hidden" name="temple" value="1" />
	<input type="hidden" name="destination" value="TEMPLEREADY" />
	<input type="submit" value="Save File for Temple Ready">
</form>

{include file="page_nav.tpl"}
<table class="grid">
<tr>
<td class="label">Name</td>
<td class="label">Baptism</td>
<td class="label">Endowment</td>
<td class="label">Sealing to Parents</td>
<td class="label">Status</td>
<td class="label">Relationship</td>
</tr>
{if $results}
{foreach item=result from=$results}
	<tr>
	<td align="left"><a href="/person/{$result.person_id}">{$result.given_name} {$result.family_name}</a></td>
	<td>{$result.baptism_date} {$result.baptism_status}</td>
	<td>{$result.endow_date} {$result.endow_status}</td>
	<td>{$result.seal_date} {$result.seal_status}</td>
	<td>{$result.done}</td>
	<td>{$result.description}</td>
</tr>
{/foreach}
{else}
<tr>
	<td colspan="4" align="center">You don't have any individuals in your family tree needing temple work.</td>
</tr>
{/if}
</table>
{include file="page_nav.tpl"}

<br /><br />
<table class="portal">
<tr><td>
	<b>Notes about the Temple Work Report</b><br />
	<ul>
	<li>This is a report used by members of the LDS Church to assist them in submitting names to the temple.</li>
	<li>Children who die before 8 years old, should be listed as CHILD, INFANT, or BIC</li>
	<li>Once an individual has all three ordinances complete (not just submitted), then they will be marked as State = "DONE" and removed from the list the next time.</li>
	<li>Only individuals included in your family tree are included in the list.</li>
	<li>Individuals are listed in order of distance from you.</li>
	<br>
	<li><i>Incomplete marriage ordinances are not included in this report yet.</i></li>
	</ul>
</td></tr>
</table>
{include file="footer.tpl"}
