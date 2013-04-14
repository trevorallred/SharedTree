{include file="header.tpl"}

<div>
Sorry, but I am not currently supporting new GEDCOM entries at this time.
</div>

<!--
<table class="portal">
<tr><td>
<h2>Step 1 of 4: Upload a new GEDCOM file</h2>
<form action="import.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
Upload file: <input type="file" name="importfile" size="30"> <b>&lt; 8 MB</b> <br>
Optional description: <input type="text" name="description" size="30"> 
<input type="submit" value="Upload file" name="upload">
</form>
</td></tr>
<tr><td>
<h2>GEDCOM files</h2>
<table class="grid">
	<tr>
	<th>Filename</th>
	<th>Uploaded</th>
	<th>Description</th>
	<th>Size</th>
	<th>Records</th>
	<th>Imported</th>
	<th>Step</th>
	</tr>
{if $files}
{foreach item=file from=$files}
	<tr>
	<td><a href="?action=viewfile&import_id={$file.import_id}">{$file.filename}</a></td>
	<td>{$file.upload_date}</td>
	<td>{$file.description}</td>
	<td>{$file.file_size}</td>
	<td>
		{$file.person_count} individual(s)<br>
		{$file.family_count} marriage(s)
	</td>
	<td>{$file.import_date}</td>
	<td>{$file.current_step}</td>
	</tr>
{/foreach}
{else}
	<tr>
	<td colspan="10">You do not have any files currently uploaded</td>
	</tr>
{/if}
</table>
<a href="import.php?show=all">Show All</a> - 
<a href="import.php">Filter Approved</a>

</td></tr>
</table>
-->

{include file="footer.tpl"}
