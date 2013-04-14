<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:02:51
         compiled from "/var/www/sharedtree/templates/person_nav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19669358535132bd5b3a6589-78274745%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c6070427e1e1f4832496175175876f77b3c3e1f6' => 
    array (
      0 => '/var/www/sharedtree/templates/person_nav.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19669358535132bd5b3a6589-78274745',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'nav_id' => 0,
    'direction' => 0,
    'time' => 0,
    'is_logged_on' => 0,
    'return_to' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd5b49f28',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd5b49f28')) {function content_5132bd5b49f28($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['nav_id']->value>0){?>
<?php if ($_smarty_tpl->tpl_vars['direction']->value=="flat"){?>
<!-- show the navigation icons horizontally -->
<a href="/family/<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>"><img src="/img/ico_family.gif" title="Family Details" width="16" height="16" border="0" /></a>&nbsp;<a href="/person/<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>"><img src="/img/ico_indi.gif" title="Individual Details" width="16" height="16" border="0" /></a>&nbsp;<a href="/ped.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
"><img src="/img/btn_pedigree.png" title="Ancestor Pedigree" width="16" height="16" border="0" /></a>&nbsp;<a href="/descendants.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
"><img src="/img/btn_descend.png" title="Descendant Chart" width="16" height="16" border="0" /></a><?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>&nbsp;<a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['return_to']->value){?>&return_to=<?php echo rawurlencode($_smarty_tpl->tpl_vars['return_to']->value);?>
<?php }?>"><img src="/img/btn_edit.png" title="Edit Person" width="16" height="16" border="0" /></a><?php }?>
<?php }else{ ?>
<!-- show the navigation icons vertically -->
<div style="float:right;">
<table border="0">
<tr><td><a href="/family/<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>"><img src="/img/ico_family.gif" title="Family Details" width="16" height="16" border="0" /></a></td>
	<td><a href="/family/<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>">Family</a></td></tr>
<tr><td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>"><img src="/img/ico_indi.gif" title="Individual Details" width="16" height="16" border="0" /></a></td>
	<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>">Individual</a></td></tr>
<tr><td><a href="/ped.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
"><img src="/img/btn_pedigree.png" title="Ancestor Pedigree" width="16" height="16" border="0" /></a></td>
	<td><a href="/ped.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
">Pedigree</a></td></tr>
<tr><td><a href="/descendants.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
"><img src="/img/btn_descend.png" title="Descendant chart" width="16" height="16" border="0" /></a></td>
	<td><a href="/descendants.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
">Descendants</a></td></tr>
<tr><td><a href="/chart.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
"><img src="/img/btn_report.png" title="Print PDF Reports" width="16" height="16" border="0" /></a></td>
	<td><a href="/chart.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
">Reports</a></td></tr>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<tr><td><a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['return_to']->value){?>&return_to=<?php echo rawurlencode($_smarty_tpl->tpl_vars['return_to']->value);?>
<?php }?>"><img src="/img/btn_edit.png" title="Edit Person" width="16" height="16" border="0" /></a></td>
	<td><a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['nav_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['return_to']->value){?>&return_to=<?php echo rawurlencode($_smarty_tpl->tpl_vars['return_to']->value);?>
<?php }?>">Edit</a></td></tr>
	<?php }?>
</table>
</div>
<?php }?>
<?php }?>
<?php }} ?>