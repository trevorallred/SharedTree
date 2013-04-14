<?php /* Smarty version Smarty-3.1.7, created on 2013-04-01 18:28:45
         compiled from "/var/www/sharedtree/templates/location_add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1362599359515a344d6cb1e8-78331272%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'de3a904e62bb2e74b05c21de4f604933b6136f68' => 
    array (
      0 => '/var/www/sharedtree/templates/location_add.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1362599359515a344d6cb1e8-78331272',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'parent' => 0,
    'types' => 0,
    'location' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_515a344d95a6f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_515a344d95a6f')) {function content_515a344d95a6f($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Locations: Add"), 0);?>


<h2><a href="locations.php">All</a>: 
<?php if ($_smarty_tpl->tpl_vars['parent']->value['location_id']){?><a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['parent']->value['location_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['parent']->value['display_name'];?>
</a>: <?php }?>
Add New</h2>
<table class="portal">
<tr><td>
<form method="POST">
<input type="hidden" name="action" value="save">
<input type="hidden" name="location[parent_id]" value="<?php echo $_smarty_tpl->tpl_vars['parent']->value['location_id'];?>
">
<label>Location type:</label> <?php echo smarty_function_html_radios(array('name'=>"location[location_type]",'options'=>$_smarty_tpl->tpl_vars['types']->value,'selected'=>$_smarty_tpl->tpl_vars['location']->value['location_type']),$_smarty_tpl);?>
<br>
<label>Location name:</label> <input type="text" name="location[location_name]" value="<?php echo $_smarty_tpl->tpl_vars['location']->value['location_name'];?>
"><br>
<label>Display text:</label> <input type="text" name="location[display_name]" value="<?php echo $_smarty_tpl->tpl_vars['location']->value['display_name'];?>
" size="50" maxlength="255"><br>
<label>Description:</label> <input type="text" name="location[description]" value="<?php echo $_smarty_tpl->tpl_vars['location']->value['description'];?>
" size="50" maxlength="255"><br>
<br>

<input type="submit" name="save" value="Add"><br>
</form>
</td></tr></table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>