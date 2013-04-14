<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:03:02
         compiled from "/var/www/sharedtree/templates/birth_year.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21386722695132bd66c3dd34-20149273%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c87320fa52027c84913684788d0a7b716f45d819' => 
    array (
      0 => '/var/www/sharedtree/templates/birth_year.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21386722695132bd66c3dd34-20149273',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'birth_year' => 0,
    'birth_date' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd66c6d1f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd66c6d1f')) {function content_5132bd66c6d1f($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['birth_year']->value!=0){?><?php if ($_smarty_tpl->tpl_vars['birth_date']->value>''){?><?php echo $_smarty_tpl->tpl_vars['birth_date']->value;?>
<?php if ($_smarty_tpl->tpl_vars['birth_year']->value<0){?> B.C.<?php }?><?php }else{ ?>~<?php echo $_smarty_tpl->tpl_vars['birth_year']->value;?>
<?php }?><?php }?><?php }} ?>