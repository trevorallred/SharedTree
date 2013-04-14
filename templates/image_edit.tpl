{include file="header.tpl" title="Upload Image"}
<script type="text/javascript">
{literal}
function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}
{/literal}
</script>


<h1>Upload {if $data.event_id > 0}Source Document{else}Photo{/if}</h1>
<table class="portal">
<tr><td>
{if $image_id > 0}
	<a href="image.php?image_id={$image_id}&action=summary">&lt;&lt; Back to Image</a>
{/if}

<div class="errors">{$error}</div>

<form enctype="multipart/form-data" method="post">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="return_to" value="{$return_to}">
	<input type="hidden" name="image_id" value="{$image_id}">
	<input type="hidden" name="data[image_id]" value="{$data.image_id}">
	<input type="hidden" name="data[person_id]" value="{$data.person_id}">
	<input type="hidden" name="data[image_order]" value="{$data.image_order}">
	<input type="hidden" name="data[event_id]" value="{$data.event_id}">

	<img src="image.php?image_id={$image_id}&size=thumb" border="1" align="right">
	Type: 
	{if $data.event_id > 0}
		{$data.event_prompt} Source Document
		<br />Suggested file formats: <b>{if $data.event_id > 0}pdf, {/if}bmp, jpg, gif, or png</b>
		<br><br>
		<font color="#D27" size="-2"><i><b>Source documents</b> are scanned images of birth or death certificates, <br>photos of tombstones, scanned census pages, or other primary <br>genealogical sources. DO NOT abuse this feature by using it as a <br>scrapbooking service or you will be suspended and your files removed.</i></font>
	{else}
		{if $data.image_order == 1}Childhood{/if}
		{if $data.image_order == 2}Adulthood{/if}
		{if $data.image_order == 3}Later Years{/if}
		<br />Suggested file formats: <b>pdf, bmp, jpg, or png</b>
	{/if}
	<br />
	<br />
	<br clear="all" />
	File: <input name="userfile" class="textfield" type="file" /><br />
	{if $data.image_order > 0}
	Age taken: <input type="textbox" class="textfield" name="data[age_taken]" value="{$data.age_taken}" size="5" /><br />
	{/if}
	Description: <input type="textbox" class="textfield" name="data[description]" value="{$data.description}" size="50" />
	<br />
	<input type="submit" value="Submit" />
	<br />
</form>
<br><br><br>
{if $image_id}
<a href="#" onclick="confirmSubmit('Are you sure you want to permanently delete this file? This action cannot be undone!','image.php?image_id={$image_id}&action=delete');"><img src="img/btn_drop.png" width="16" height="16" border="0">Permanently Delete this File</a>
{/if}

</td></tr></table>
{include file="footer.tpl"}
