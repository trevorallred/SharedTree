<?php /* Smarty version Smarty-3.1.7, created on 2013-03-08 19:01:46
         compiled from "/var/www/sharedtree/templates/merge_piece.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1718096525513aa61ada3077-07011440%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'af651728d8bd76807a0bca0dfd166b81ad8592a1' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_piece.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1718096525513aa61ada3077-07011440',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'var' => 0,
    'p1' => 0,
    'p2' => 0,
    'prompt' => 0,
    'info' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513aa61aec412',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513aa61aec412')) {function content_513aa61aec412($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['p1']->value[$_smarty_tpl->tpl_vars['var']->value]||$_smarty_tpl->tpl_vars['p2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>
	<tr onmouseover="$('history1_<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').show(); $('history2_<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').show();" onmouseout="$('history1_<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').hide(); $('history2_<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
').hide();">
	<td><?php echo $_smarty_tpl->tpl_vars['prompt']->value;?>
</td>
	<td><?php if ($_smarty_tpl->tpl_vars['info']->value!="true"){?><input type="radio" id="merge<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
1" name="merge[<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
]" value="1" <?php if ($_smarty_tpl->tpl_vars['p1']->value[$_smarty_tpl->tpl_vars['var']->value]){?>checked<?php }?>><?php }?></td>
	<td><label for="merge<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
1"><?php echo $_smarty_tpl->tpl_vars['p1']->value[$_smarty_tpl->tpl_vars['var']->value];?>

		<?php if ($_smarty_tpl->tpl_vars['var']->value=="bio_family_id"){?> <a href="marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['p1']->value[$_smarty_tpl->tpl_vars['var']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['p1']->value['parents'];?>
</a><?php }?></label>
		<div id="history1_<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
"></div>
	</td>
	<td><?php if ($_smarty_tpl->tpl_vars['info']->value!="true"){?><?php if ($_smarty_tpl->tpl_vars['p1']->value[$_smarty_tpl->tpl_vars['var']->value]==$_smarty_tpl->tpl_vars['p2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>-<?php }else{ ?><input type="radio" id="merge<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
2" name="merge[<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
]" value="2"  <?php if ($_smarty_tpl->tpl_vars['p1']->value[$_smarty_tpl->tpl_vars['var']->value]==''&&$_smarty_tpl->tpl_vars['p2']->value[$_smarty_tpl->tpl_vars['var']->value]){?>checked<?php }?>><?php }?><?php }?></td>
	<td><label for="merge<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
2"><?php echo $_smarty_tpl->tpl_vars['p2']->value[$_smarty_tpl->tpl_vars['var']->value];?>

		<?php if ($_smarty_tpl->tpl_vars['var']->value=="bio_family_id"){?> <a href="marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['p2']->value[$_smarty_tpl->tpl_vars['var']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['p2']->value['parents'];?>
</a><?php }?></label>
		<div id="history2_<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
"></div>
	</td>
	<td>
		<?php if ($_smarty_tpl->tpl_vars['info']->value!="true"&&$_smarty_tpl->tpl_vars['var']->value!="wiki_text"){?>
		<a href="#" title="Show past history of changes to <?php echo $_smarty_tpl->tpl_vars['prompt']->value;?>
 listed by the number of contributers for each value" onclick="checkHistory(<?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
, <?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
, '', '<?php echo $_smarty_tpl->tpl_vars['var']->value;?>
'); return false;">Show</a>
		<?php }?>
	</tr>
<?php }?>
<?php }} ?>