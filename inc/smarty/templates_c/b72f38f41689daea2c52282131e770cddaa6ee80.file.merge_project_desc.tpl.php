<?php /* Smarty version Smarty-3.1.7, created on 2013-03-26 14:11:27
         compiled from "/var/www/sharedtree/templates/merge_project_desc.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13195410751520eff39bfa3-20472617%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b72f38f41689daea2c52282131e770cddaa6ee80' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_project_desc.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13195410751520eff39bfa3-20472617',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'project' => 0,
    'tree' => 0,
    'fam' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51520eff445cf',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51520eff445cf')) {function content_51520eff445cf($_smarty_tpl) {?><ul id="tabnav">
	<li><a href="?action=main&project_id=<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
" class="active">Match</a></li>
	<li><a href="?action=main_merge&project_id=<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
">Merge</a></li>
</ul>
<div id="content">
	<div id="project_page_status"></div>
	<div id="tr_par<?php echo $_smarty_tpl->tpl_vars['tree']->value['parents']['family_id'];?>
">
		<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_parents.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pp2'=>$_smarty_tpl->tpl_vars['tree']->value['parents'],'pp1'=>$_smarty_tpl->tpl_vars['tree']->value['match']['parents']), 0);?>

	</div>

	<?php if ($_smarty_tpl->tpl_vars['tree']->value['marriages']){?>
		<b>Spouse(s):</b>
		<?php  $_smarty_tpl->tpl_vars['fam'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fam']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tree']->value['marriages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fam']->key => $_smarty_tpl->tpl_vars['fam']->value){
$_smarty_tpl->tpl_vars['fam']->_loop = true;
?>
			<div class="person" 
				id="tr_fam<?php echo $_smarty_tpl->tpl_vars['fam']->value['family_id'];?>
" 
				onmouseover="highlight('tr_fam<?php echo $_smarty_tpl->tpl_vars['fam']->value['family_id'];?>
', true)" 
				onmouseout="highlight('tr_fam<?php echo $_smarty_tpl->tpl_vars['fam']->value['family_id'];?>
', false)">
				<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc_spouse.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('f'=>$_smarty_tpl->tpl_vars['fam']->value,'f1s'=>$_smarty_tpl->tpl_vars['tree']->value['match']['marriages']), 0);?>

			</div>
		<?php } ?>
	<?php }else{ ?>
		There are no descendents to match
	<?php }?>
	<div style="float: left; margin: 2px; padding: 3px; border: 2px gray solid">
		<a href="?action=reset&project_id=<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
" style="text-decoration: none" title="Undo all matches and reset the project">Start Over</a>
	</div>
	<div style="float: right; margin: 2px; padding: 3px; border: 2px gray solid">
		<a href="?action=main_merge&project_id=<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
" style="text-decoration: none" title="Begin Merging">Begin Merging</a>
	</div>
</div>
<?php }} ?>