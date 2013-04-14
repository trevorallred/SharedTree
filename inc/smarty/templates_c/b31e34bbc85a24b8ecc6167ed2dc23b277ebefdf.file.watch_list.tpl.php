<?php /* Smarty version Smarty-3.1.7, created on 2013-03-24 20:07:55
         compiled from "/var/www/sharedtree/templates/watch_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1275082097514fbf8b27f7e7-66956126%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b31e34bbc85a24b8ecc6167ed2dc23b277ebefdf' => 
    array (
      0 => '/var/www/sharedtree/templates/watch_list.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1275082097514fbf8b27f7e7-66956126',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'watchlist' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_514fbf8b3c1d9',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_514fbf8b3c1d9')) {function content_514fbf8b3c1d9($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Bookmarks &amp; Watch List",'includejs'=>1), 0);?>


<h1>Bookmarks &amp; Watch List</h1>

<table class="grid">
<tr><th>Individual</th>
	<th>Bookmark</th>
	<th>Delete</th>
</tr>
<?php if ($_smarty_tpl->tpl_vars['watchlist']->value){?>
<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['watchlist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
<tr><td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
, <?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
</a></td>
	<td><?php if ($_smarty_tpl->tpl_vars['person']->value['bookmark']){?>
			<a href="watch.php?action=save&data[watch_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['watch_id'];?>
&data[bookmark]=0">YES</a>
		<?php }else{ ?>
			<a href="watch.php?action=save&data[watch_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['watch_id'];?>
&data[bookmark]=1">NO</a>
		<?php }?></td>
	<td><a href="#" onclick="stConfirm('Are you sure you want to stop watching for changes to this person?','watch.php?action=unwatch&watch_id=<?php echo $_smarty_tpl->tpl_vars['person']->value['watch_id'];?>
');"><img src="img/btn_drop.png" width="16" height="16" alt="Unwatch <?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
" border="0"></a></td>
</tr>
<?php } ?>
</p>
<?php }else{ ?>
<tr><td colspan="3">You aren't watching any records yet.</td></tr>
<?php }?>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>