<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 04:48:52
         compiled from "/var/www/sharedtree/templates/group_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:58812359513346b4c2aca9-62057823%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4e6a7c4ff5c241ea1d25e23bb17ee35d8d6ec7bd' => 
    array (
      0 => '/var/www/sharedtree/templates/group_view.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '58812359513346b4c2aca9-62057823',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'group' => 0,
    'group_id' => 0,
    'members' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513346b51a635',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513346b51a635')) {function content_513346b51a635($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('includejs'=>1), 0);?>


<h2><?php echo $_smarty_tpl->tpl_vars['group']->value['group_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['group']->value['initials'];?>
</h2>

<a href="group.php">Group List</a>
| <a href="group.php?action=edit&group_id=<?php echo $_smarty_tpl->tpl_vars['group_id']->value;?>
">Edit <?php echo $_smarty_tpl->tpl_vars['group']->value['group_name'];?>
</a>

<table class="portal">
<tr align="left">
<td width="350">
<label>From <?php echo $_smarty_tpl->tpl_vars['group']->value['start_year'];?>
 to <?php if ($_smarty_tpl->tpl_vars['group']->value['end_year']>''){?><?php echo $_smarty_tpl->tpl_vars['group']->value['end_year'];?>
<?php }else{ ?>Present<?php }?></label>
<br><br>
<?php echo $_smarty_tpl->tpl_vars['group']->value['description'];?>

</td>
</tr>
</table>

<table class="grid">
<tr>
<th>Name</th>
<th>Born</th>
<th>Remove</th>
</tr>
<?php if ($_smarty_tpl->tpl_vars['members']->value){?>
	<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['members']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
		<tr>
		<td align="left"><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
, <?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
</a></td>
		<td><?php echo $_smarty_tpl->tpl_vars['person']->value['birth_year'];?>
</td>
		<td><a href="#" onclick="stConfirm('Are you sure you want to remove <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 from this group?', 'group.php?action=deletemember&group_id=<?php echo $_smarty_tpl->tpl_vars['group_id']->value;?>
&person_id=<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
');"><img src="/img/btn_drop.png" title="Remove from group" width="16" height="16" border="0" /></a></td>
		</tr>
	<?php } ?>
<?php }else{ ?>
	<tr>
	<td colspan="3">no group members found</td>
	</tr>
<?php }?>
</table>


<p>
<font size="1" color="#999999">
<label>Created by:</label> <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['group']->value['created_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['group']->value['created_name'];?>
</a> on <?php echo $_smarty_tpl->tpl_vars['group']->value['creation_date'];?>
<br>
<label>Updated by:</label> <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['group']->value['updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['group']->value['updated_name'];?>
</a> on <?php echo $_smarty_tpl->tpl_vars['group']->value['update_date'];?>

</font>
</p>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>