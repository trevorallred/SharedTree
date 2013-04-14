<?php /* Smarty version Smarty-3.1.7, created on 2013-03-23 13:33:57
         compiled from "/var/www/sharedtree/templates/stats.tpl" */ ?>
<?php /*%%SmartyHeaderCode:602917042514e11b5b51754-86509202%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '60ee3d54e3392da3bc330838f6738a9ba11399b9' => 
    array (
      0 => '/var/www/sharedtree/templates/stats.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '602917042514e11b5b51754-86509202',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'prompt' => 0,
    'type' => 0,
    'search' => 0,
    'people' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_514e11b5da61c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_514e11b5da61c')) {function content_514e11b5da61c($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<h2><?php echo $_smarty_tpl->tpl_vars['prompt']->value;?>
</h2>

<table class="portal">
<tr><td>
<form>
<?php if ($_smarty_tpl->tpl_vars['type']->value){?>
<input type="hidden" name="type" value="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
">
<?php }?>
<label>Lastname:</label> <input type="textbox" name="search[familyname]" size="10" value="<?php echo $_smarty_tpl->tpl_vars['search']->value['familyname'];?>
">
<label>Birthyear:</label>
between <input type="textbox" name="search[birthstart]" size="5" value="<?php echo $_smarty_tpl->tpl_vars['search']->value['birthstart'];?>
">
    and <input type="textbox" name="search[birthend]" size="5" value="<?php echo $_smarty_tpl->tpl_vars['search']->value['birthend'];?>
">
<input type="submit" value="Search">
</form>
</td></tr>
</table>

<table class="grid">
<tr><td>Name</td>
	<td>Birth</td>
	<td><a href="stats.php?type=page_views<?php if ($_smarty_tpl->tpl_vars['search']->value['familyname']){?>&search[familyname]=<?php echo $_smarty_tpl->tpl_vars['search']->value['familyname'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['search']->value['birthstart']){?>&search[birthstart]=<?php echo $_smarty_tpl->tpl_vars['search']->value['birthstart'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['search']->value['birthstart']){?>&search[birthend]=<?php echo $_smarty_tpl->tpl_vars['search']->value['birthend'];?>
<?php }?>">Page Views</a></td>
	<td><a href="stats.php?type=descendant_count<?php if ($_smarty_tpl->tpl_vars['search']->value['familyname']){?>&search[familyname]=<?php echo $_smarty_tpl->tpl_vars['search']->value['familyname'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['search']->value['birthstart']){?>&search[birthstart]=<?php echo $_smarty_tpl->tpl_vars['search']->value['birthstart'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['search']->value['birthstart']){?>&search[birthend]=<?php echo $_smarty_tpl->tpl_vars['search']->value['birthend'];?>
<?php }?>">Descendants</a></td>
	<td></td>
</tr>
<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['people']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
<tr><td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</a></td>
	<td><?php echo $_smarty_tpl->tpl_vars['person']->value['birth_year'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['person']->value['page_views'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['person']->value['descendant_count'];?>
</td>
</tr>
<?php } ?>
</table>

<a href="?action=descendant_count">Count Descendants</a> (admin)

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>