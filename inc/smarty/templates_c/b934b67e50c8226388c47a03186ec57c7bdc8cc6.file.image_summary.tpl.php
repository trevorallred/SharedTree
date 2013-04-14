<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:30:22
         compiled from "/var/www/sharedtree/templates/image_summary.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19257384775132c3ce6b0523-73880867%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b934b67e50c8226388c47a03186ec57c7bdc8cc6' => 
    array (
      0 => '/var/www/sharedtree/templates/image_summary.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19257384775132c3ce6b0523-73880867',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'image_id' => 0,
    'title' => 0,
    'data' => 0,
    'image_type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c3cea898a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c3cea898a')) {function content_5132c3cea898a($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('includejs'=>1), 0);?>


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
	$("myThumb").src = "image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
";
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


<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<table class="portal" width="600">
<tr><td>
<?php if ($_smarty_tpl->tpl_vars['data']->value['image_type']=="application/pdf"){?>
	<a href="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&size=full" target="_BLANK"><img src="img/pdf.gif" width="20" height="20" border="0" /></a>
	<br />
	<a href="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&size=full" target="_BLANK">Open PDF</a>
	<br />
	<br />
<?php }else{ ?>
<img id="origImg" src="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
" border=1>
<br>
<a href="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&size=full">View Full Version</a><br />
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
	src="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&size=thumb"
	>
</div>
<br /><br />
<form action="image.php" method="POST">
<input type="hidden" name="action" value="save_thumb">
<input type="hidden" name="image_id" value="<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
">
<input type="hidden" id="formX" name="X" value="0">
<input type="hidden" id="formY" name="Y" value="0">
<input type="hidden" id="formW" name="W" value="200">
<input type="submit" value="Save Thumbnail">
</form>
<?php }?>
<label>Person:</label> <a href="/person/<?php echo $_smarty_tpl->tpl_vars['data']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['family_name'];?>
</a><br />
<label>Type:</label> <?php echo $_smarty_tpl->tpl_vars['image_type']->value;?>
 <br />
<?php if ($_smarty_tpl->tpl_vars['data']->value['event_date']){?><label>Event date:</label> <?php echo $_smarty_tpl->tpl_vars['data']->value['event_date'];?>
 <br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['data']->value['age_taken']){?><label>Age taken:</label> <?php echo $_smarty_tpl->tpl_vars['data']->value['age_taken'];?>
<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['data']->value['description']){?><label>Description:</label> <?php echo $_smarty_tpl->tpl_vars['data']->value['description'];?>
<br /><?php }?>
<label>Updated on:</label> <?php echo $_smarty_tpl->tpl_vars['data']->value['update_date'];?>
<br />
<label>Updated by:</label> <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['data']->value['updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['ugiven_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['ufamily_name'];?>
</a><br />
<br clear="all" />
<a href="image.php?action=edit&image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
"><img src="img/btn_edit.png" width="16" height="16" border="0" />Edit</a> | 
<a href="image.php?action=list">Photo Album</a>

</td></tr></table>
<script type="text/javascript" language="javascript">
Event.observe($("origImg"), "mousedown", selectThumb, false);
</script>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>