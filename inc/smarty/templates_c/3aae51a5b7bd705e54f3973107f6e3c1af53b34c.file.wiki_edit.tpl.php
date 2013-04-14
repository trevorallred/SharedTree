<?php /* Smarty version Smarty-3.1.7, created on 2013-03-07 17:12:09
         compiled from "/var/www/sharedtree/templates/wiki_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:124437465351393ae9007dc9-75562263%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3aae51a5b7bd705e54f3973107f6e3c1af53b34c' => 
    array (
      0 => '/var/www/sharedtree/templates/wiki_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '124437465351393ae9007dc9-75562263',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'person' => 0,
    'update_date' => 0,
    'wiki' => 0,
    'history' => 0,
    'change' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51393ae934298',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51393ae934298')) {function content_51393ae934298($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Edit Biography",'background'=>"edit"), 0);?>


<h2>Edit Biography for <?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</h2>
<label>
Version: <?php if ($_smarty_tpl->tpl_vars['update_date']->value){?><?php echo $_smarty_tpl->tpl_vars['update_date']->value;?>
<?php }else{ ?>current<?php }?> by <a href="/profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['wiki']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['wiki']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['wiki']->value['family_name'];?>
</a>
</label>

<form method="POST">
<input type="hidden" name="action" value="wikiedit">
<input type="hidden" name="wiki[wiki_id]" value="<?php echo $_smarty_tpl->tpl_vars['wiki']->value['wiki_id'];?>
">
<input type="hidden" name="wiki[person_id]" value="<?php echo $_smarty_tpl->tpl_vars['wiki']->value['person_id'];?>
">

<input type="submit" name="save" value="Save">
<br>
<textarea name="wiki[wiki_text]" cols="80" rows="30"><?php echo $_smarty_tpl->tpl_vars['wiki']->value['wiki_text'];?>
</textarea><br>
<label><input type="checkbox" name="watch" value="1" checked>Watch for changes to this individual</label><br>
<input type="submit" name="save" value="Save">
</form>

<?php if ($_smarty_tpl->tpl_vars['history']->value){?>
<table class="portal">
<tr><td>
View previous edits to this biography:
<ul>
	<li><a href="/person/<?php echo $_smarty_tpl->tpl_vars['wiki']->value['person_id'];?>
&action=wikiedit">Current</a></li>
<?php  $_smarty_tpl->tpl_vars['change'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['change']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['history']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['change']->key => $_smarty_tpl->tpl_vars['change']->value){
$_smarty_tpl->tpl_vars['change']->_loop = true;
?>
	<li><a href="/person/<?php echo $_smarty_tpl->tpl_vars['wiki']->value['person_id'];?>
&action=wikiedit&update_date=<?php echo $_smarty_tpl->tpl_vars['change']->value['update_date'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['update_date'];?>
</a> by <a href="profile.php?person_id=<?php echo $_smarty_tpl->tpl_vars['change']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['change']->value['family_name'];?>
</a> - <?php echo $_smarty_tpl->tpl_vars['change']->value['text_length'];?>
 bytes</li>
<?php } ?>
</ul>
</td></tr></table>
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>