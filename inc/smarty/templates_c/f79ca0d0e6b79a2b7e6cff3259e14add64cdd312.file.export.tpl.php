<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:09:23
         compiled from "/var/www/sharedtree/templates/export.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18245192555132bee308ad50-35062824%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f79ca0d0e6b79a2b7e6cff3259e14add64cdd312' => 
    array (
      0 => '/var/www/sharedtree/templates/export.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18245192555132bee308ad50-35062824',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'person' => 0,
    'person_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bee320919',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bee320919')) {function content_5132bee320919($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"GEDCOM Export"), 0);?>


<table class="portal">
<tr><td>
<h2>Export GEDCOM file</h2>

<b><?php echo $_smarty_tpl->tpl_vars['person']->value['full_name'];?>
</b><br /><br />

<form action="export.php" method="post">
<input type="hidden" name="person_id" value="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">
<label>Ancestors:</label>
<select name="gen_up">
	<option>0</option>
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
	<option>6</option>
	<option>7</option>
	<option>8</option>
	<option>9</option>
	<option>10</option>
</select> generations <br />
<label>Siblings:</label> <input type="checkbox" name="siblings" value="1"><br />

<label>Descendents:</label>
<select name="gen_down">
	<option>0</option>
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
</select> generations <br />
<input type="submit" value="Download" name="download">
</form>
</td></tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>