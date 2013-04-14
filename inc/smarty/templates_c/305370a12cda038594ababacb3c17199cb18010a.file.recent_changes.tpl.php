<?php /* Smarty version Smarty-3.1.7, created on 2013-03-08 08:57:20
         compiled from "/var/www/sharedtree/templates/recent_changes.tpl" */ ?>
<?php /*%%SmartyHeaderCode:309476955513a187032b376-63192720%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '305370a12cda038594ababacb3c17199cb18010a' => 
    array (
      0 => '/var/www/sharedtree/templates/recent_changes.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '309476955513a187032b376-63192720',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'changes' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513a187061dda',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513a187061dda')) {function content_513a187061dda($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Recent Changes"), 0);?>


<h1>New Additions to SharedTree</h1>

SharedTree is growing! Below are the 100 most recent additions to the database.

<table border="1" class="table1">
	<tr>
		<td class="label">Given name</td>
		<td class="label">Family name</td>
		<td class="label">Added</td>
		<td class="label">By</td>
	</tr>
<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['changes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
	<tr>
<?php if ($_smarty_tpl->tpl_vars['person']->value['public_flag']||$_smarty_tpl->tpl_vars['person']->value['description']){?>
		<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['title'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
</a></td>
		<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</a></td>
<?php }else{ ?>
		<td><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</td>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['description']){?>
		<td><?php echo $_smarty_tpl->tpl_vars['person']->value['description'];?>
</td>
<?php }?>
		<td><?php echo $_smarty_tpl->tpl_vars['person']->value['creation_date'];?>
</td>
		<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['person']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['user_name'];?>
</a></td>
	</tr>
<?php } ?>
</table>

<a href="recent_changes.php">All new entries</a> | <a href="recent_changes.php?action=mytree">Only additions to my tree</a>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>