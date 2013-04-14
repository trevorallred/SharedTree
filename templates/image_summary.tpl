{include file="header.tpl" includejs=1}
{literal}
<script type="text/javascript" language="javascript">
var imgX, imgY;
var moving = false;

function selectThumb(e)
{
	var pos = Position.cumulativeOffset($("origImg"));
	if (imgX == null)
	{
		$("output").innerHTML = 'First point selected successfully. Now click on another point on the image to view your thumbnail.';

		imgX = Event.pointerX(e) - pos[0];
		imgY = Event.pointerY(e) - pos[1];
		// Make it not visible in window
		$("myThumb").setStyle({display: 'none'});
	}
	else
	{
		imgX2 = Event.pointerX(e) - pos[0];
		imgY2 = Event.pointerY(e) - pos[1];
		displayThumb(imgX2, imgY2);
		$("output").innerHTML = 'Please verify your thumbnail and click [Save Thumbnail] or click on the image above to try again.';
	}
}
function setMax()
{
	imgX = 0;
	imgY = 0;
	y = getDimension($("origImg"), "height");
	x = getDimension($("origImg"), "width");

	displayThumb(x, y);
}
function displayThumb(imgX2, imgY2)
{
	origHeight = getDimension($("origImg"), "height");
	origWidth = getDimension($("origImg"), "width");
	divWidth = getDimension($("myDiv"), "width");

	// find the diff between the 2 points
	xDiff = imgX - imgX2;
	if (xDiff < 0) xDiff = xDiff * -1;
	yDiff = imgY - imgY2;
	if (yDiff < 0) yDiff = yDiff * -1;
	// set the width to the max of the 2 diffs
	imgW = xDiff;
	if (yDiff > imgW) imgW = yDiff;
	if (imgW == 0) imgW = 10; // never make the image with = 0 
	// make sure width isn't bigger than height or width...we don't want blank space on sides or top
	if (imgW > origHeight) imgW = origHeight;
	if (imgW > origWidth) imgW = origWidth;
	// Find the center of thumbnail
	xAvg = (imgX + imgX2) / 2;
	yAvg = (imgY + imgY2) / 2;
	// Make sure thumb isn't too close to edges because we'll get whitespace
	w2 = imgW/2;
	if (xAvg < w2) xAvg = w2;
	if (yAvg < w2) yAvg = w2;
	if ((origWidth - xAvg) < w2) xAvg = origWidth - w2;
	if ((origHeight - yAvg) < w2) yAvg = origHeight - w2;
	// Calculate the size of new image
	factor = divWidth / imgW;
	imgW = origWidth * factor;
	imgX = ((xAvg * factor) - (divWidth / 2));
	imgY = ((yAvg * factor) - (divWidth / 2));
	// Done with calculating
	$("formX").value = imgX;
	$("formY").value = imgY;
	$("formW").value = imgW;
	imgX = imgX * -1;
	imgY = imgY * -1;
	$("myThumb").src = "image.php?image_id={/literal}{$image_id}{literal}";
	$("myThumb").setStyle({top: imgY+'px', left: imgX+'px', width: imgW+'px', display: 'block'});
	$("myThumb").visible = true;
	imgX = imgY = null;
}
function getDimension(obj, val)
{
	x = obj.getStyle(val);
	if (x == null)
	{
		if (val == "width") return obj.width;
		if (val == "height") return obj.height;
		alert("getDimension: Failed to find "+val);
		return 0;
	}
	x = x.replace('px','');
	return x/1;
}
</script>
{/literal}

<h1>{$title}</h1>
<table class="portal" width="600">
<tr><td>
{if $data.image_type=="application/pdf"}
	<a href="image.php?image_id={$image_id}&size=full" target="_BLANK"><img src="img/pdf.gif" width="20" height="20" border="0" /></a>
	<br />
	<a href="image.php?image_id={$image_id}&size=full" target="_BLANK">Open PDF</a>
	<br />
	<br />
{else}
<img id="origImg" src="image.php?image_id={$image_id}" border=1>
<br>
<a href="image.php?image_id={$image_id}&size=full">View Full Version</a><br />
<div id="output">To edit the thumbnail below, click on the image above.</div>

<div id="myDiv"
	style="
	border: 1px black solid;
	width: 100px;
	height: 100px;
	overflow: hidden;
	background: #999;
	position: relative;
	float: left;
">
<img id="myThumb"
	style=" position: relative; "
	src="image.php?image_id={$image_id}&size=thumb"
	>
</div>
<br /><br />
<form action="image.php" method="POST">
<input type="hidden" name="action" value="save_thumb">
<input type="hidden" name="image_id" value="{$image_id}">
<input type="hidden" id="formX" name="X" value="0">
<input type="hidden" id="formY" name="Y" value="0">
<input type="hidden" id="formW" name="W" value="200">
<input type="submit" value="Save Thumbnail">
</form>
{/if}
<label>Person:</label> <a href="/person/{$data.person_id}">{$data.given_name} {$data.family_name}</a><br />
<label>Type:</label> {$image_type} <br />
{if $data.event_date}<label>Event date:</label> {$data.event_date} <br />{/if}
{if $data.age_taken}<label>Age taken:</label> {$data.age_taken}<br />{/if}
{if $data.description}<label>Description:</label> {$data.description}<br />{/if}
<label>Updated on:</label> {$data.update_date}<br />
<label>Updated by:</label> <a href="profile.php?user_id={$data.updated_by}">{$data.ugiven_name} {$data.ufamily_name}</a><br />
<br clear="all" />
<a href="image.php?action=edit&image_id={$image_id}"><img src="img/btn_edit.png" width="16" height="16" border="0" />Edit</a> | 
<a href="image.php?action=list">Photo Album</a>

</td></tr></table>
<script type="text/javascript" language="javascript">
Event.observe($("origImg"), "mousedown", selectThumb, false);
</script>

{include file="footer.tpl"}
