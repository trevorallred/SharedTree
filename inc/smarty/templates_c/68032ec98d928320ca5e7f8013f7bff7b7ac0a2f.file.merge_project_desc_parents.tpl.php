<?php /* Smarty version Smarty-3.1.7, created on 2013-03-26 14:11:27
         compiled from "/var/www/sharedtree/templates/merge_project_desc_parents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31271132551520eff44b2f1-23423829%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '68032ec98d928320ca5e7f8013f7bff7b7ac0a2f' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_project_desc_parents.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31271132551520eff44b2f1-23423829',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pp1' => 0,
    'pp2' => 0,
    'child' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51520eff51e76',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51520eff51e76')) {function content_51520eff51e76($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['pp1']->value['person1']['person_id']>0&&$_smarty_tpl->tpl_vars['pp2']->value['person1']['person_id']>0){?>
	<div class="person" 
		id="fam_f<?php echo $_smarty_tpl->tpl_vars['pp2']->value['family_id'];?>
"
		onmouseover="highlight('fam_f<?php echo $_smarty_tpl->tpl_vars['pp2']->value['family_id'];?>
', true)" 
		onmouseout="highlight('fam_f<?php echo $_smarty_tpl->tpl_vars['pp2']->value['family_id'];?>
', false)">
	<b>Father:</b>
	<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_parents_parent.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p2'=>$_smarty_tpl->tpl_vars['pp2']->value['person1'],'p1'=>$_smarty_tpl->tpl_vars['pp1']->value['person1'],'which'=>"1"), 0);?>

	</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['pp1']->value['person2']['person_id']>0&&$_smarty_tpl->tpl_vars['pp2']->value['person2']['person_id']>0){?>
	<div class="person" 
		id="fam_m<?php echo $_smarty_tpl->tpl_vars['pp2']->value['family_id'];?>
"
		onmouseover="highlight('fam_m<?php echo $_smarty_tpl->tpl_vars['pp2']->value['family_id'];?>
', true)" 
		onmouseout="highlight('fam_m<?php echo $_smarty_tpl->tpl_vars['pp2']->value['family_id'];?>
', false)">
	<b>Mother:</b>
	<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_parents_parent.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p2'=>$_smarty_tpl->tpl_vars['pp2']->value['person2'],'p1'=>$_smarty_tpl->tpl_vars['pp1']->value['person2'],'which'=>"2"), 0);?>

	</div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['pp2']->value['children']){?>
	<b>Sibling(s):</b><br>
	<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pp2']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
			<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_child.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['child']->value,'p1s'=>$_smarty_tpl->tpl_vars['pp1']->value['children']), 0);?>

		</div>
	<?php } ?>
<?php }?>
<?php }} ?>