<?php /* Smarty version Smarty-3.1.7, created on 2013-03-04 12:46:14
         compiled from "/var/www/sharedtree/templates/familytree_partial.tpl" */ ?>
<?php /*%%SmartyHeaderCode:387176263513508164f3962-17357778%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e205bae68c614ca8554df834c848208b7064079' => 
    array (
      0 => '/var/www/sharedtree/templates/familytree_partial.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '387176263513508164f3962-17357778',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'count' => 0,
    'trace' => 0,
    'relatives' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513508165695a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513508165695a')) {function content_513508165695a($_smarty_tpl) {?><b>Showing <?php echo $_smarty_tpl->tpl_vars['count']->value;?>
 individual<?php if ($_smarty_tpl->tpl_vars['count']->value!=1){?>s<?php }?> in your family tree <?php if ($_smarty_tpl->tpl_vars['trace']->value){?>filter by <?php echo $_smarty_tpl->tpl_vars['trace']->value;?>
<?php }?>.</b>

<table class="grid">
<tr><th>Name</th>
<th>Thru</th>
<th>Relation</th>
<th>Birthplace</th>
</tr>
<?php if ($_smarty_tpl->tpl_vars['relatives']->value){?>
<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['relatives']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
	<tr><td><a href="person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</a></td>
	<td><?php echo $_smarty_tpl->tpl_vars['person']->value['trace'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['person']->value['birth_year'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['person']->value['location'];?>
</td>
	</tr>
<?php } ?>
<?php }else{ ?>
	<tr><td colspan="2"><br>You need to click the Rebuild link <br />on the right to build your tree<br><br></td></tr>
<?php }?>
</table>
<?php }} ?>