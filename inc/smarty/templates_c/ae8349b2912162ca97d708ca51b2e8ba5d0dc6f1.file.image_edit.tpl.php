<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 10:16:55
         compiled from "/var/www/sharedtree/templates/image_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7656251075133939740ce00-00452675%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ae8349b2912162ca97d708ca51b2e8ba5d0dc6f1' => 
    array (
      0 => '/var/www/sharedtree/templates/image_edit.tpl',
      1 => 1222142603,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7656251075133939740ce00-00452675',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'image_id' => 0,
    'error' => 0,
    'return_to' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513393976c735',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513393976c735')) {function content_513393976c735($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Upload Image"), 0);?>

<script type="text/javascript">

function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}

</script>


<h1>Upload <?php if ($_smarty_tpl->tpl_vars['data']->value['event_id']>0){?>Source Document<?php }else{ ?>Photo<?php }?></h1>
<table class="portal">
<tr><td>
<?php if ($_smarty_tpl->tpl_vars['image_id']->value>0){?>
	<a href="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&action=summary">&lt;&lt; Back to Image</a>
<?php }?>

<div class="errors"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</div>

<form enctype="multipart/form-data" method="post">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="return_to" value="<?php echo $_smarty_tpl->tpl_vars['return_to']->value;?>
">
	<input type="hidden" name="image_id" value="<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
">
	<input type="hidden" name="data[image_id]" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['image_id'];?>
">
	<input type="hidden" name="data[person_id]" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['person_id'];?>
">
	<input type="hidden" name="data[image_order]" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['image_order'];?>
">
	<input type="hidden" name="data[event_id]" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['event_id'];?>
">

	<img src="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&size=thumb" border="1" align="right">
	Type: 
	<?php if ($_smarty_tpl->tpl_vars['data']->value['event_id']>0){?>
		<?php echo $_smarty_tpl->tpl_vars['data']->value['event_prompt'];?>
 Source Document
		<br />Suggested file formats: <b><?php if ($_smarty_tpl->tpl_vars['data']->value['event_id']>0){?>pdf, <?php }?>bmp, jpg, gif, or png</b>
		<br><br>
		<font color="#D27" size="-2"><i><b>Source documents</b> are scanned images of birth or death certificates, <br>photos of tombstones, scanned census pages, or other primary <br>genealogical sources. DO NOT abuse this feature by using it as a <br>scrapbooking service or you will be suspended and your files removed.</i></font>
	<?php }else{ ?>
		<?php if ($_smarty_tpl->tpl_vars['data']->value['image_order']==1){?>Childhood<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['data']->value['image_order']==2){?>Adulthood<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['data']->value['image_order']==3){?>Later Years<?php }?>
		<br />Suggested file formats: <b>pdf, bmp, jpg, or png</b>
	<?php }?>
	<br />
	<br />
	<br clear="all" />
	File: <input name="userfile" class="textfield" type="file" /><br />
	<?php if ($_smarty_tpl->tpl_vars['data']->value['image_order']>0){?>
	Age taken: <input type="textbox" class="textfield" name="data[age_taken]" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['age_taken'];?>
" size="5" /><br />
	<?php }?>
	Description: <input type="textbox" class="textfield" name="data[description]" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['description'];?>
" size="50" />
	<br />
	<input type="submit" value="Submit" />
	<br />
</form>
<br><br><br>
<?php if ($_smarty_tpl->tpl_vars['image_id']->value){?>
<a href="#" onclick="confirmSubmit('Are you sure you want to permanently delete this file? This action cannot be undone!','image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['image_id']->value;?>
&action=delete');"><img src="img/btn_drop.png" width="16" height="16" border="0">Permanently Delete this File</a>
<?php }?>

</td></tr></table>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>