{if $nav_id > 0}
{if $direction=="flat"}
<!-- show the navigation icons horizontally -->
<a href="/family/{$nav_id}{if $time}&time={$time}{/if}"><img src="/img/ico_family.gif" title="Family Details" width="16" height="16" border="0" /></a>&nbsp;<a href="/person/{$nav_id}{if $time}&time={$time}{/if}"><img src="/img/ico_indi.gif" title="Individual Details" width="16" height="16" border="0" /></a>&nbsp;<a href="/ped.php?person_id={$nav_id}"><img src="/img/btn_pedigree.png" title="Ancestor Pedigree" width="16" height="16" border="0" /></a>&nbsp;<a href="/descendants.php?person_id={$nav_id}"><img src="/img/btn_descend.png" title="Descendant Chart" width="16" height="16" border="0" /></a>{if $is_logged_on}&nbsp;<a href="/person_edit.php?person_id={$nav_id}{if $return_to}&return_to={$return_to|escape:'url'}{/if}"><img src="/img/btn_edit.png" title="Edit Person" width="16" height="16" border="0" /></a>{/if}
{else}
<!-- show the navigation icons vertically -->
<div style="float:right;">
<table border="0">
<tr><td><a href="/family/{$nav_id}{if $time}&time={$time}{/if}"><img src="/img/ico_family.gif" title="Family Details" width="16" height="16" border="0" /></a></td>
	<td><a href="/family/{$nav_id}{if $time}&time={$time}{/if}">Family</a></td></tr>
<tr><td><a href="/person/{$nav_id}{if $time}&time={$time}{/if}"><img src="/img/ico_indi.gif" title="Individual Details" width="16" height="16" border="0" /></a></td>
	<td><a href="/person/{$nav_id}{if $time}&time={$time}{/if}">Individual</a></td></tr>
<tr><td><a href="/ped.php?person_id={$nav_id}"><img src="/img/btn_pedigree.png" title="Ancestor Pedigree" width="16" height="16" border="0" /></a></td>
	<td><a href="/ped.php?person_id={$nav_id}">Pedigree</a></td></tr>
<tr><td><a href="/descendants.php?person_id={$nav_id}"><img src="/img/btn_descend.png" title="Descendant chart" width="16" height="16" border="0" /></a></td>
	<td><a href="/descendants.php?person_id={$nav_id}">Descendants</a></td></tr>
<tr><td><a href="/chart.php?person_id={$nav_id}"><img src="/img/btn_report.png" title="Print PDF Reports" width="16" height="16" border="0" /></a></td>
	<td><a href="/chart.php?person_id={$nav_id}">Reports</a></td></tr>
	{if $is_logged_on}
<tr><td><a href="/person_edit.php?person_id={$nav_id}{if $return_to}&return_to={$return_to|escape:'url'}{/if}"><img src="/img/btn_edit.png" title="Edit Person" width="16" height="16" border="0" /></a></td>
	<td><a href="/person_edit.php?person_id={$nav_id}{if $return_to}&return_to={$return_to|escape:'url'}{/if}">Edit</a></td></tr>
	{/if}
</table>
</div>
{/if}
{/if}
