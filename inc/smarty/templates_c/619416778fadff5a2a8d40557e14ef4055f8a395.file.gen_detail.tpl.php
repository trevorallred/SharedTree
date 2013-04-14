<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:03:11
         compiled from "/var/www/sharedtree/templates/gen_detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1339851435132bd6f60dd74-05400839%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '619416778fadff5a2a8d40557e14ef4055f8a395' => 
    array (
      0 => '/var/www/sharedtree/templates/gen_detail.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1339851435132bd6f60dd74-05400839',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'indi' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd6f6a16f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd6f6a16f')) {function content_5132bd6f6a16f($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['indi']->value['e']['BIRT']){?>
	<label>Birth: </label>
	<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['indi']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['indi']->value['e']['BIRT']['event_date']), 0);?>
<br />
	<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['indi']->value['e']['BIRT']['location'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><br />
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['indi']->value['e']['CHR']){?>
		<label>Christening: </label>
		<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['indi']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['indi']->value['e']['CHR']['event_date']), 0);?>
<br />
		<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['indi']->value['e']['CHR']['location'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><br />
	<?php }else{ ?>
		<label>Birth:</label> ~<?php echo $_smarty_tpl->tpl_vars['indi']->value['birth_year'];?>

	<?php }?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['indi']->value['e']['DEAT']){?>
	<label>Death:</label>
	<?php echo $_smarty_tpl->tpl_vars['indi']->value['e']['DEAT']['event_date'];?>
<?php if ($_smarty_tpl->tpl_vars['indi']->value['e']['DEAT']['ad']=='0'){?> B.C.<?php }?> <br />
	<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['indi']->value['e']['DEAT']['location'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><br />
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['indi']->value['e']['BURI']){?>
		<label>Burial: </label>
		<?php echo $_smarty_tpl->tpl_vars['indi']->value['e']['BURI']['event_date'];?>
<?php if ($_smarty_tpl->tpl_vars['indi']->value['e']['BURI']['ad']=='0'){?> B.C.<?php }?> <br />
		<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['indi']->value['e']['BURI']['location'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><br />
	<?php }?>
<?php }?>

<?php }} ?>