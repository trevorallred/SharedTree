<?php /* Smarty version Smarty-3.1.7, created on 2013-03-07 12:27:10
         compiled from "/var/www/sharedtree/templates/relatives.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17201917125138f81e45cd77-20863132%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '547daf93b9d67360abd95ee3025ef24aa036f0b6' => 
    array (
      0 => '/var/www/sharedtree/templates/relatives.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17201917125138f81e45cd77-20863132',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'users' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5138f81e754cb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5138f81e754cb')) {function content_5138f81e754cb($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Relatives"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("util_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<h2>Your Relatives on SharedTree</h2>
<a href="invite.php">Invite More</a>
<table class="grid">
<tr>
	<th>Last</th>
	<th>First</th>
	<th>Address</th>
	<th>Last visit</th>
</tr>
	<?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
?>
<tr><td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['user']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['family_name'];?>
</a></td>
	<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['user']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['given_name'];?>
</a></td>
	<td><?php if ($_smarty_tpl->tpl_vars['user']->value['address_line1']){?><?php echo $_smarty_tpl->tpl_vars['user']->value['address_line1'];?>
<br><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['user']->value['address_line2']){?><?php echo $_smarty_tpl->tpl_vars['user']->value['address_line2'];?>
<br><?php }?>
		<?php echo $_smarty_tpl->tpl_vars['user']->value['city'];?>
<?php if ($_smarty_tpl->tpl_vars['user']->value['city']&&$_smarty_tpl->tpl_vars['user']->value['state_code']){?>, <?php }?><?php echo $_smarty_tpl->tpl_vars['user']->value['state_code'];?>
</td>
	<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['user']->value['last_visit_date']);?>
</td>
</tr>
	<?php } ?>
</table>

This is a list of living people, registered on SharedTree, <br />
who are directly related to someone in your <a href="familytree.php">family tree index</a>.

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>