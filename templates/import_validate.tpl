<br>
<b><a href="import.php?action=import&import_id={$import_id}">Continue to the Next Step &gt;&gt;</a></b>
<table class="portal">
<tr><td>

<label>File size</label> {$file.file_size} bytes<br>
<label>Families in file</label> {$file.family_count}<br>
<label>Individuals in file</label> {$file.person_count}<br>
<label>Percent matched</label> {$file.person_matched}%<br>
<label>Possible Duplicates</label> {$file.possible_duplicates}<br>
{if $file.possible_duplicates > 100 && $file.person_matched > 25}
	<div class="errors">WARNING!! There are {$file.possible_duplicates} <b>possible</b> matches in your file. <br>You may be required to manually merge each one of these with SharedTree individuals. <br>You should reconsider importing this entire file and split it up instead.<br> Click Help for more information.</div>
{/if}
{if $file.person_matched > 50}
	<div class="errors">WARNING!! Over half the records have <b>possible</b> matches. <br>You should reconsider importing this entire file and split it up instead.<br> Click Help for more information.</div>
{/if}
<br><br>
<a href="?action=validate&import_id={$import_id}&revalidate=1">Re-validate file</a>
</td></tr>
</table>

{include file="footer.tpl"}
