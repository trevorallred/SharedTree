{include file="import_nav.tpl" istep="1"}
<br>
<b><a href="import.php?action=validate&import_id={$import_id}">Continue to the Next Step &gt;&gt;</a></b>

<table class="portal">
<tr><td>
<label>Upload Date:</label> {$file.upload_date}<br>
<label>File Name:</label> {$file.filename}<br>
<label>Description:</label> {$file.description}<br>
<label>File Size:</label> {$file.file_size} bytes<br>
<label>Contents:</label><br>
<textarea rows="20" cols="80" nowrap>

{$fcontents|escape:'htmlall'}

</textarea>
<br>
<i>max 10000 bytes shown above</i>
<br><br>

<h3>Change File</h3>

{if $file.current_step > 3}
	<i>Cannot delete or modify a file that has already been imported</i>
{else}
	<form action="import.php" method="post" enctype="multipart/form-data">
	<i>not working yet</i><br>
	<input type="hidden" name="action" value="upload">
	<input type="hidden" name="import_id" value="{$file.import_id}">
	<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
	Upload file: <input type="file" name="importfile" size="30"> <b>&lt; 8 MB</b> <br>
	Optional description: <input type="text" name="description" size="30" value="{$file.description}"> 
	<input type="submit" value="Upload file" name="upload">
	</form>

	<a href="import.php?action=delete&import_id={$file.import_id}"><img src="img/btn_drop.png" height="16" width="16" border="0">Permanently Delete GEDCOM File</a>
{/if}


</td></tr>
</table>

{include file="footer.tpl"}
