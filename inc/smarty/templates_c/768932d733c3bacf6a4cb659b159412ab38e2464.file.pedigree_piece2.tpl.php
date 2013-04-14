<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:02:52
         compiled from "/var/www/sharedtree/templates/pedigree_piece2.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15099737315132bd5c466779-19397516%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '768932d733c3bacf6a4cb659b159412ab38e2464' => 
    array (
      0 => '/var/www/sharedtree/templates/pedigree_piece2.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15099737315132bd5c466779-19397516',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ht' => 0,
    'p' => 0,
    'boxid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd5c52dbe',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd5c52dbe')) {function content_5132bd5c52dbe($_smarty_tpl) {?><div class="personframe" style="top: <?php echo $_smarty_tpl->tpl_vars['ht']->value;?>
px">
<?php if ($_smarty_tpl->tpl_vars['p']->value['person_id']>0){?>
	<div class="personbox" onclick="showhide('info<?php echo $_smarty_tpl->tpl_vars['boxid']->value;?>
');"><?php echo $_smarty_tpl->tpl_vars['p']->value['full_name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['p']->value['birth_year'];?>
)</div>
	<div class="personinfo" id="info<?php echo $_smarty_tpl->tpl_vars['boxid']->value;?>
">
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['p']->value['person_id']), 0);?>

		<img src="image.php?person_id=<?php echo $_smarty_tpl->tpl_vars['p']->value['person_id'];?>
" align="left" height="80">
		<div title="birth date">B: <?php echo $_smarty_tpl->tpl_vars['p']->value['e']['BIRT']['event_date'];?>
</div>
		<div title="birth place">P: <?php echo $_smarty_tpl->tpl_vars['p']->value['e']['BIRT']['location'];?>
</div>
		<div title="death date">D: <?php echo $_smarty_tpl->tpl_vars['p']->value['e']['DEAT']['event_date'];?>
</div>
		<div title="death place">P: <?php echo $_smarty_tpl->tpl_vars['p']->value['e']['DEAT']['location'];?>
</div>
	</div>
<?php }else{ ?>
	<div class="personbox" style="cursor: default"></div>
<?php }?>
</div>
<?php }} ?>