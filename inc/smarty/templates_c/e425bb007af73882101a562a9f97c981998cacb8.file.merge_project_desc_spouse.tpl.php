<?php /* Smarty version Smarty-3.1.7, created on 2013-03-26 14:11:27
         compiled from "/var/www/sharedtree/templates/merge_project_desc_spouse.tpl" */ ?>
<?php /*%%SmartyHeaderCode:85789735451520eff5255c3-89781597%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e425bb007af73882101a562a9f97c981998cacb8' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_project_desc_spouse.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85789735451520eff5255c3-89781597',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'f' => 0,
    'f1s' => 0,
    'f1' => 0,
    'child' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51520eff7a70a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51520eff7a70a')) {function content_51520eff7a70a($_smarty_tpl) {?><?php if (!is_callable('smarty_function_math')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.math.php';
?><?php if ($_smarty_tpl->tpl_vars['f']->value['match_action']=="C"||$_smarty_tpl->tpl_vars['f']->value['spouse']['person_id']==$_smarty_tpl->tpl_vars['f']->value['spouse']['match']['person_id']){?>
	<a href="person/<?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['person_id'];?>
" target="_BLANK"><strong><?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['full_name'];?>
</strong></a> 
	<font color="#336633"><b>ALREADY MERGED</b></font>
<?php }else{ ?>
	<table class="<?php if ($_smarty_tpl->tpl_vars['f']->value['match_action']==''){?>pending<?php }else{ ?>matched<?php }?>">
	<tr valign="top">
	<td><a href="person/<?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['person_id'];?>
" target="_BLANK"><strong><?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['full_name'];?>
</strong></a>
		<?php if ($_smarty_tpl->tpl_vars['f']->value['spouse']['b_date']||$_smarty_tpl->tpl_vars['f']->value['b_location']){?><br>Birth: <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['b_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['b_location'];?>
<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['f']->value['spouse']['d_date']||$_smarty_tpl->tpl_vars['f']->value['d_location']){?><br>Death: <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['d_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['d_location'];?>
<?php }?>
	</td>
	<td><form action="" id="spouses<?php echo $_smarty_tpl->tpl_vars['f']->value['family_id'];?>
">
	<?php if ($_smarty_tpl->tpl_vars['f']->value['match_action']=="ADD"){?>
			<input type="button" name="undo" value="Undo" onclick="call('select_spouse', 'tr_fam<?php echo $_smarty_tpl->tpl_vars['f']->value['family_id'];?>
', '&family2_id=<?php echo $_smarty_tpl->tpl_vars['f']->value['family_id'];?>
');">
			<br>Adding new marriage
	<?php }else{ ?>
		<?php if ($_smarty_tpl->tpl_vars['f']->value['match_action']=="M"){?>
			<input type="button" name="undo" value="Undo" onclick="call('select_spouse', 'tr_fam<?php echo $_smarty_tpl->tpl_vars['f']->value['family_id'];?>
', '&family2_id=<?php echo $_smarty_tpl->tpl_vars['f']->value['family_id'];?>
');">
		<?php }else{ ?>
			<input type="button" value="Select" onclick="selectSpouse(<?php echo $_smarty_tpl->tpl_vars['f']->value['family_id'];?>
);">
			<br />
			<select name="family_id" multiple="multiple" size="<?php echo smarty_function_math(array('equation'=>"1 + option_size",'option_size'=>count($_smarty_tpl->tpl_vars['f1s']->value)),$_smarty_tpl);?>
">
				<option value="ADD" style="font-style: italic">(+) add as new spouse</option>
				<?php  $_smarty_tpl->tpl_vars['f1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['f1s']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f1']->key => $_smarty_tpl->tpl_vars['f1']->value){
$_smarty_tpl->tpl_vars['f1']->_loop = true;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['f1']->value['family_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['f1']->value['spouse']['full_name'];?>

					<?php if ($_smarty_tpl->tpl_vars['f1']->value['spouse']['b_date']){?>b:<?php echo $_smarty_tpl->tpl_vars['f1']->value['spouse']['b_date'];?>
<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['f1']->value['spouse']['d_date']){?>d:<?php echo $_smarty_tpl->tpl_vars['f1']->value['spouse']['d_date'];?>
<?php }?>
					</option>
				<?php } ?>
			</select>
		<?php }?>
	<?php }?>
	</form>
	</td>
	<?php if ($_smarty_tpl->tpl_vars['f']->value['match_action']=="M"){?>
		<td><a href="person/<?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['match']['person_id'];?>
" target="_BLANK"><?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['match']['full_name'];?>
</a>
			<?php if ($_smarty_tpl->tpl_vars['f']->value['spouse']['match']['b_date']||$_smarty_tpl->tpl_vars['f']->value['spouse']['match']['b_location']){?><br>Birth: <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['match']['b_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['match']['b_location'];?>
<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['f']->value['spouse']['match']['d_date']||$_smarty_tpl->tpl_vars['f']->value['spouse']['match']['d_location']){?><br>Death: <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['match']['d_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['match']['d_location'];?>
<?php }?>
		</td>
	<?php }?>
	</tr>
	</table>
	<?php if ($_smarty_tpl->tpl_vars['f']->value['spouse']['match']['person_id']>0&&$_smarty_tpl->tpl_vars['f']->value['spouse']['parents']['family_id']>0){?>
		<div id="tr_par<?php echo $_smarty_tpl->tpl_vars['f']->value['spouse']['parents']['family_id'];?>
">
			<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_parents.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pp2'=>$_smarty_tpl->tpl_vars['f']->value['spouse']['parents'],'pp1'=>$_smarty_tpl->tpl_vars['f']->value['spouse']['match']['parents']), 0);?>

		</div>
	<?php }?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['f']->value['children']){?>
	<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['f']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
	<div class="person"
		id="tr_chil<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
"
		onmouseover="highlight('tr_chil<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
', true)" 
		onmouseout="highlight('tr_chil<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
', false)">
		<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_child.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['child']->value,'p1s'=>$_smarty_tpl->tpl_vars['f']->value['matchchildren']), 0);?>

	</div>
	<?php } ?>
<?php }?>
<?php }} ?>