<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 03:22:02
         compiled from "/var/www/sharedtree/templates/group_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16346126285133325a7d3984-87082204%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a8f799a3d957ae9e85f710fef4157233ab63d51' => 
    array (
      0 => '/var/www/sharedtree/templates/group_list.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16346126285133325a7d3984-87082204',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'person_id' => 0,
    'groups' => 0,
    'grp' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5133325ac13d0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5133325ac13d0')) {function content_5133325ac13d0($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('includejs'=>1), 0);?>


<h2>Groups</h2>
<a href="list.php">Family Index</a> | <a href="locations.php">Browse Places</a><br>

<table class="grid">
<tr>
<?php if ($_smarty_tpl->tpl_vars['person_id']->value){?><th>Choose</th><?php }?>
<th align="left"><a href="?sort=group_name">Group Name</a></th>
<th><a href="?sort=member_count">Members</a></th>
<th><a href="?sort=start_year">Starting</a></th>
<th>Ending</th>
</tr>
<?php if ($_smarty_tpl->tpl_vars['groups']->value){?>
	<?php  $_smarty_tpl->tpl_vars['grp'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['grp']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['grp']->key => $_smarty_tpl->tpl_vars['grp']->value){
$_smarty_tpl->tpl_vars['grp']->_loop = true;
?>
		<tr>
		<?php if ($_smarty_tpl->tpl_vars['person_id']->value){?><td><a href="group.php?action=addmember&group_id=<?php echo $_smarty_tpl->tpl_vars['grp']->value['group_id'];?>
&person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">Choose</a></td><?php }?>
		<td align="left"><a href="group.php?group_id=<?php echo $_smarty_tpl->tpl_vars['grp']->value['group_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['grp']->value['group_name'];?>
</a></td>
		<td><?php echo $_smarty_tpl->tpl_vars['grp']->value['member_count'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['grp']->value['start_year'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['grp']->value['end_year'];?>
</td>
		</tr>
	<?php } ?>
<?php }else{ ?>
	<tr>
	<td colspan="<?php if ($_smarty_tpl->tpl_vars['person_id']->value){?>5<?php }else{ ?>4<?php }?>">no groups found</td>
	</tr>
<?php }?>
	<tr>
	<td colspan="<?php if ($_smarty_tpl->tpl_vars['person_id']->value){?>5<?php }else{ ?>4<?php }?>"><a href="group.php?action=edit">Add new group</a></td>
	</tr>
</table>


<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>