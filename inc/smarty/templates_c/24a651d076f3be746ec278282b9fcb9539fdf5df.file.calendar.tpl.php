<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 17:14:34
         compiled from "/var/www/sharedtree/templates/calendar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2020204965133f57a746b49-93980009%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24a651d076f3be746ec278282b9fcb9539fdf5df' => 
    array (
      0 => '/var/www/sharedtree/templates/calendar.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2020204965133f57a746b49-93980009',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'months' => 0,
    'month' => 0,
    'day' => 0,
    'people' => 0,
    'person' => 0,
    'need_birthdate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5133f57aa1559',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5133f57aa1559')) {function content_5133f57aa1559($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Calendar"), 0);?>


<h2>Calendar</h2>

<table class="portal">
<tr><td colspan="2">
<label>Show:</label>
<a href="?level=3">Immediate Family</a> |
<a href="?level=2">Extended Family</a> |
<a href="?level=1">All Relatives</a>
</td></tr>
<tr><td>
<?php  $_smarty_tpl->tpl_vars['month'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['month']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['months']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['month']->key => $_smarty_tpl->tpl_vars['month']->value){
$_smarty_tpl->tpl_vars['month']->_loop = true;
?>
<?php  $_smarty_tpl->tpl_vars['people'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['people']->_loop = false;
 $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['month']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['people']->key => $_smarty_tpl->tpl_vars['people']->value){
$_smarty_tpl->tpl_vars['people']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->value = $_smarty_tpl->tpl_vars['people']->key;
?>
	<?php if ($_smarty_tpl->tpl_vars['day']->value=="name"){?>
		<h3><?php echo $_smarty_tpl->tpl_vars['month']->value['name'];?>
</h3>
	<?php }else{ ?>
		<b><?php echo $_smarty_tpl->tpl_vars['day']->value;?>
</b>
		<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['people']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['person']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['person']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
 $_smarty_tpl->tpl_vars['person']->iteration++;
 $_smarty_tpl->tpl_vars['person']->last = $_smarty_tpl->tpl_vars['person']->iteration === $_smarty_tpl->tpl_vars['person']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['person']['last'] = $_smarty_tpl->tpl_vars['person']->last;
?>
			<a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</a> <?php echo $_smarty_tpl->tpl_vars['person']->value['new_age'];?>
<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['person']['last']){?>,<?php }?>
		<?php } ?>
		<br />
	<?php }?>
<?php } ?>
<?php } ?>
</td>
<td>
<h3>Relatives Missing Birthdates</h3>
<ul>
<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['need_birthdate']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
	<li><a href="person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</a></li>
<?php } ?>
</ul>
</td>
</table>

<a href="thisday.php">See This Day in Family History</a>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>