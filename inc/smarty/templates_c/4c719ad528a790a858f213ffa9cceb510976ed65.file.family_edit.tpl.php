<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 01:01:41
         compiled from "/var/www/sharedtree/templates/family_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11213726305133117588df63-98286551%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4c719ad528a790a858f213ffa9cceb510976ed65' => 
    array (
      0 => '/var/www/sharedtree/templates/family_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11213726305133117588df63-98286551',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'family_id' => 0,
    'family' => 0,
    'marriage_statuses' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51331175de59a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51331175de59a')) {function content_51331175de59a($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Edit Family",'background'=>"edit"), 0);?>

<script type="text/javascript">

function toggleLayer(whichLayer) {
	if (document.getElementById) {
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = style2.display? "":"block";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = style2.display? "":"block";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = style2.display? "":"block";
	}
}

function openChild(file,window) {
    childWindow=open(file,window,'resizable=yes,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=yes, width=400, height=500');
    if (childWindow.opener == null) childWindow.opener = self;
}

function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}

</script>

<h2>Edit Family</h2>
[<a href="marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
">Back to Marriage</a>]

<table class="portal" width="600">
<tr><td width="50%">
<label>Husband:</label><br />
<?php if ($_smarty_tpl->tpl_vars['family']->value['person1_id']){?>
	<a href="/family/<?php echo $_smarty_tpl->tpl_vars['family']->value['person1_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['family']->value['given_name1'];?>
 <?php echo $_smarty_tpl->tpl_vars['family']->value['family_name1'];?>
</a>
	<a href="#" onclick="confirmSubmit('Are you sure you want to remove <?php echo $_smarty_tpl->tpl_vars['family']->value['given_name1'];?>
 from this marriage?','marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family']->value['family_id'];?>
&removeparent=1');"><img src="img/btn_drop.png" title="Remove Husband" width="16" height="16" border="0" /></a>
<?php }else{ ?>
	<form action="/person_edit.php" method="get">
		<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['family']->value['family_id'];?>
">
		<input type="hidden" name="person[gender]" value="M">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td>
			<td><input type="text" name="person[given_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Family name:</td>
			<td><input type="text" name="person[family_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Birthdate:</td><td><input type="text" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
<?php }?>
</td>
<td width="50%">
<label>Wife:</label><br />
<?php if ($_smarty_tpl->tpl_vars['family']->value['person2_id']){?>
	<a href="/family/<?php echo $_smarty_tpl->tpl_vars['family']->value['person2_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['family']->value['given_name2'];?>
 <?php echo $_smarty_tpl->tpl_vars['family']->value['family_name2'];?>
</a>
	<a href="#" onclick="confirmSubmit('Are you sure you want to remove <?php echo $_smarty_tpl->tpl_vars['family']->value['given_name2'];?>
 from this marriage?','marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family']->value['family_id'];?>
&removeparent=2');"><img src="img/btn_drop.png" title="Remove Wife" width="16" height="16" border="0" /></a>
<?php }else{ ?>
	<form action="/person_edit.php" method="get">
		<input type="hidden" name="action" value="addparentchoose">
		<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['family']->value['family_id'];?>
">
		<input type="hidden" name="person[gender]" value="F">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td>
			<td><input type="text" name="person[given_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Family name:</td>
			<td><input type="text" name="person[family_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Birthdate:</td><td><input type="text" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
<?php }?>
</td></tr>

<form method="POST">
<input type="hidden" name="action" value="add_save">
<input type="hidden" name="family_id" value="<?php echo $_smarty_tpl->tpl_vars['family']->value['family_id'];?>
">
<tr><td colspan="2" style="text-align:center"><input type="submit" name="save" value="Save"></td></tr>
<tr>
<td colspan="2">
<label>Status:</label>
<?php echo smarty_function_html_radios(array('name'=>"family[status_code]",'options'=>$_smarty_tpl->tpl_vars['marriage_statuses']->value,'selected'=>$_smarty_tpl->tpl_vars['family']->value['status_code']),$_smarty_tpl);?>
<br>
<br>

<?php echo $_smarty_tpl->getSubTemplate ("event_edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('events'=>$_smarty_tpl->tpl_vars['family']->value['e'],'eventtype'=>"family",'defaultevent'=>"1"), 0);?>


</td></tr>
<tr><td colspan="2" style="text-align:center"><input type="submit" name="save" value="Save"></td></tr>
<tr><td colspan="2">
<h3>Other Data</h3>
<label>Marriage Notes:</label><br>
<textarea name="family[notes]"><?php echo $_smarty_tpl->tpl_vars['family']->value['notes'];?>
</textarea><br> <br>

<?php echo $_smarty_tpl->getSubTemplate ("event_edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('events'=>$_smarty_tpl->tpl_vars['family']->value['e'],'eventtype'=>"family",'defaultevent'=>"0"), 0);?>


</td></tr>
<tr><td colspan="2" style="text-align:center"><input type="submit" name="save" value="Save"></td></tr>
</table>
</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>