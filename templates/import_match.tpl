{include file="import_nav.tpl" istep="4"}

<table class="portal" width="600">
<tr><td>

The final step in the import process is to see if there are any records in the global SharedTree that already match one or more of the records you just imported.
<br>

<a href="merge.php?action=match&import_id={$import_id}">Search for Matches</a>
<br><br>
Once you are finished searching for matches, you can review them below:<br>
<a href="merge.php?action=list&import_id={$import_id}">Review Matches</a>

<br><br>
<b>If no matches exist then you are done.<br>
Congratulations!!</b>

</td></tr>
</table>

{include file="footer.tpl"}
