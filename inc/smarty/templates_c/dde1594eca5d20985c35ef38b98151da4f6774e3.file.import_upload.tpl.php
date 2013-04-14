<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 19:32:30
         compiled from "/var/www/sharedtree/templates/import_upload.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2052517132513415ce9536d0-17283033%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dde1594eca5d20985c35ef38b98151da4f6774e3' => 
    array (
      0 => '/var/www/sharedtree/templates/import_upload.tpl',
      1 => 1325981309,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2052517132513415ce9536d0-17283033',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'files' => 0,
    'file' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513415ceb8f6f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513415ceb8f6f')) {function content_513415ceb8f6f($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


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
<?php if ($_smarty_tpl->tpl_vars['files']->value){?>
<?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value){
$_smarty_tpl->tpl_vars['file']->_loop = true;
?>
	<tr>
	<td><a href="?action=viewfile&import_id=<?php echo $_smarty_tpl->tpl_vars['file']->value['import_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['file']->value['filename'];?>
</a></td>
	<td><?php echo $_smarty_tpl->tpl_vars['file']->value['upload_date'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['file']->value['description'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['file']->value['file_size'];?>
</td>
	<td>
		<?php echo $_smarty_tpl->tpl_vars['file']->value['person_count'];?>
 individual(s)<br>
		<?php echo $_smarty_tpl->tpl_vars['file']->value['family_count'];?>
 marriage(s)
	</td>
	<td><?php echo $_smarty_tpl->tpl_vars['file']->value['import_date'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['file']->value['current_step'];?>
</td>
	</tr>
<?php } ?>
<?php }else{ ?>
	<tr>
	<td colspan="10">You do not have any files currently uploaded</td>
	</tr>
<?php }?>
</table>
<a href="import.php?show=all">Show All</a> - 
<a href="import.php">Filter Approved</a>

</td></tr>
</table>
-->

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>