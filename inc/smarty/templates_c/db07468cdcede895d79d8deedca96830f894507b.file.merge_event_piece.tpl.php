<?php /* Smarty version Smarty-3.1.7, created on 2013-03-08 19:01:46
         compiled from "/var/www/sharedtree/templates/merge_event_piece.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1807118988513aa61aed4560-50516620%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db07468cdcede895d79d8deedca96830f894507b' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_event_piece.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1807118988513aa61aed4560-50516620',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'var' => 0,
    'e1' => 0,
    'e2' => 0,
    'i' => 0,
    'prompt' => 0,
    'p1' => 0,
    'p2' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513aa61b0b6b7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513aa61b0b6b7')) {function content_513aa61b0b6b7($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['e1']->value[$_smarty_tpl->tpl_vars['var']->value]||$_smarty_tpl->tpl_vars['e2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>
	<tr onmouseover="$('history1_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').show(); $('history2_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').show();" onmouseout="$('history1_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').hide(); $('history2_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').hide();">
	<td><?php echo $_smarty_tpl->tpl_vars['prompt']->value;?>
</td>
	<td><input type="radio" id="merge<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
1" name="merge[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
]" value="1" <?php if ($_smarty_tpl->tpl_vars['e1']->value[$_smarty_tpl->tpl_vars['var']->value]){?>checked<?php }?>></td>
	<td><label for="merge<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
1"><?php echo $_smarty_tpl->tpl_vars['e1']->value[$_smarty_tpl->tpl_vars['var']->value];?>
</label> <a name="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
" /><div id="history1_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
"></div></td>
	<td>
<?php if ($_smarty_tpl->tpl_vars['e1']->value[$_smarty_tpl->tpl_vars['var']->value]==$_smarty_tpl->tpl_vars['e2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>
	-
<?php }else{ ?>
<input type="radio" id="merge<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
2" name="merge[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
]" value="2"
<?php if ($_smarty_tpl->tpl_vars['var']->value=="date_approx"){?>
	<?php if ($_smarty_tpl->tpl_vars['e1']->value['event_date']<=$_smarty_tpl->tpl_vars['e2']->value['event_date']&&$_smarty_tpl->tpl_vars['e1']->value[$_smarty_tpl->tpl_vars['var']->value]==''&&$_smarty_tpl->tpl_vars['e2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>checked<?php }?>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['e1']->value[$_smarty_tpl->tpl_vars['var']->value]==''&&$_smarty_tpl->tpl_vars['e2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>checked<?php }?>
<?php }?>
>
<?php }?></td>
	<td><label for="merge<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
2"><?php echo $_smarty_tpl->tpl_vars['e2']->value[$_smarty_tpl->tpl_vars['var']->value];?>
</label> <div id="history2_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
"></div></td>
	<td>
		<a href="#<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
" title="Show past history of changes to <?php echo $_smarty_tpl->tpl_vars['var']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['prompt']->value;?>
 listed by the number of contributers for each value" onclick="checkHistory(<?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
, <?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
, '<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
'); return false;">Show</a></td>
	</tr>
<?php }?>
<?php }} ?>