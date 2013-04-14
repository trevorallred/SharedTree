<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 03:22:33
         compiled from "/var/www/sharedtree/templates/location_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:64416843651333279264b03-10571165%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7ec3a1ddadc9b780221367f3564f969acdc8a4dd' => 
    array (
      0 => '/var/www/sharedtree/templates/location_list.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '64416843651333279264b03-10571165',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'nations' => 0,
    'location' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513332793ab33',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513332793ab33')) {function content_513332793ab33($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Locations: Listing"), 0);?>


<h2>Country Listings</h2>
<a href="list.php">Family Index</a> | <a href="group.php">Group Index</a><br>

<table class="portal">
<tr><td>
<ul>
<?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['nations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value){
$_smarty_tpl->tpl_vars['location']->_loop = true;
?>
	<li><a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['location']->value['location_name'];?>
</a></li>
<?php } ?>
	<li><a href="locations.php?action=add&location[location_type]=N">Add new country</a></li>
</ul>

<a href="locations.php?action=match">Match existing Event Locations</a>
</td></tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>