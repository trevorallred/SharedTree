<?php /* Smarty version Smarty-3.1.7, created on 2013-04-11 00:27:33
         compiled from "/var/www/sharedtree/templates/group_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1255342309516665e5d90961-34940606%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3792d6dc16ba26970bb380d05170ab098d16c31a' => 
    array (
      0 => '/var/www/sharedtree/templates/group_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1255342309516665e5d90961-34940606',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'group_id' => 0,
    'group' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_516665e619a0f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_516665e619a0f')) {function content_516665e619a0f($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<h2><?php if ($_smarty_tpl->tpl_vars['group_id']->value){?>Edit<?php }else{ ?>Create<?php }?> Group</h2>

<a href="group.php">Return to Group List</a>
<?php if ($_smarty_tpl->tpl_vars['group_id']->value){?> | <a href="group.php?group_id=<?php echo $_smarty_tpl->tpl_vars['group_id']->value;?>
">Show <?php echo $_smarty_tpl->tpl_vars['group']->value['group_name'];?>
</a><?php }?>
<table class="portal">
<tr align="left">
<td width="350">


<form method="POST" action="group.php">
<input type="hidden" name="action" value="save">
<input type="hidden" name="group_id" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['group_id'];?>
">
<label>Group name:</label> <input type="text" class="textfield" name="group_name" size="30" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['group_name'];?>
"><br>
<label>Initials:</label>   <input type="text" class="textfield" name="initials" size="3" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['initials'];?>
"><br>
<label>Start year:</label> <input type="text" class="textfield" name="start_year" size="5" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['start_year'];?>
"><br>
<label>End year:</label>   <input type="text" class="textfield" name="end_year" size="5" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['end_year'];?>
"><br>
<label>Description:</label><br>
<textarea name="description" cols="50" rows="10"><?php echo $_smarty_tpl->tpl_vars['group']->value['description'];?>
</textarea>
<input type="submit" value="Save"></td>

</tr>
</table>

</td></tr></table>
</form>

<i>* Please read the <a href="http://www.sharedtree.com/w/Group_Guidelines">Group Guidelines</a> before adding a new group <br />or making changes to an existing group.</i>


</td>
</tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>