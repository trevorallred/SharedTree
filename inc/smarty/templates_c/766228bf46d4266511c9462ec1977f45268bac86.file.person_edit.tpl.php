<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:46:13
         compiled from "/var/www/sharedtree/templates/person_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8072204615132c785861551-73106685%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '766228bf46d4266511c9462ec1977f45268bac86' => 
    array (
      0 => '/var/www/sharedtree/templates/person_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8072204615132c785861551-73106685',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'person' => 0,
    'results' => 0,
    'result' => 0,
    'return_to' => 0,
    'errors' => 0,
    'error' => 0,
    'gender_options' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c785c0ca3',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c785c0ca3')) {function content_5132c785c0ca3($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value,'includejs'=>1,'background'=>"edit"), 0);?>


<script type="text/javascript" src="/js/autocomplete.js"></script>
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

</script>

<?php if ($_smarty_tpl->tpl_vars['person']->value['person_id']){?>
	<h2><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['results']->value){?>
		<h2>Choose Existing Individual</h2>
		<table class="grid">
		<tr><th>Name:</th>
			<th>Gender:</th>
			<th>Birth:</th>
			<th>Location:</th>
			<th>Parents:</th>
			<th></th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['results']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
$_smarty_tpl->tpl_vars['result']->_loop = true;
?>
		<tr><td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value['family_name'];?>
</a></td>
			<td><?php echo $_smarty_tpl->tpl_vars['result']->value['gender'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['result']->value['event_date'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['result']->value['location'];?>
</td>
			<td><font size="1"><a href="family.php?person_id=<?php echo $_smarty_tpl->tpl_vars['result']->value['person1_id'];?>
" target="_BLANK"><?php echo $_smarty_tpl->tpl_vars['result']->value['given_name1'];?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value['family_name1'];?>
</a> &amp; <a href="family.php?person_id=<?php echo $_smarty_tpl->tpl_vars['result']->value['person2_id'];?>
" target="_BLANK"><?php echo $_smarty_tpl->tpl_vars['result']->value['given_name2'];?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value['family_name2'];?>
</a></font></td>
			<td><a href="?action=save&person_id=<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
&person[parents_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['parents_id'];?>
&person[child_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['child_id'];?>
&person[spouse_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['spouse_id'];?>
&person[marriage_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['marriage_id'];?>
&return_to=<?php echo rawurlencode($_smarty_tpl->tpl_vars['return_to']->value);?>
">Choose</a></td>
		</tr>
		<?php } ?>
		</table>
		<h2>Create New Individual</h2>
	<?php }else{ ?>
		<h2>Create New Individual</h2>
	<?php }?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['errors']->value){?>
<ul class="errors">
	<?php  $_smarty_tpl->tpl_vars['error'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['error']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['errors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['error']->key => $_smarty_tpl->tpl_vars['error']->value){
$_smarty_tpl->tpl_vars['error']->_loop = true;
?>
	<li><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</li>
	<?php } ?>
</ul>
<?php }?>
<form method="POST">
<table class="portal">
<tr><td align="center">
<label style="float: right"><input type="checkbox" name="watch" value="1" checked>Watch for changes to this individual</label>
<input type="submit" name="save" value="Save">
<input type="hidden" name="action" value="save">
<input type="hidden" name="return_to" value="<?php echo $_smarty_tpl->tpl_vars['return_to']->value;?>
">
<input type="hidden" name="person[person_id]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
">
<?php if ($_smarty_tpl->tpl_vars['person']->value['parents_id']){?>
	<input type="hidden" name="person[parents_id]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['parents_id'];?>
">
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['marriage_id']){?>
	<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['marriage_id'];?>
">
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['spouse_id']){?>
	<input type="hidden" name="person[spouse_id]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['spouse_id'];?>
">
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['child_id']){?>
	<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['child_id'];?>
">
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['attachuser']){?>
	<input type="hidden" name="person[attachuser]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['attachuser'];?>
">
<?php }?>
</td></tr>
<tr><td>
<h3>Recommended Information</h3>

<table class="editPerson">
<tr>
	<th>Family or Last Name:</th>
	<td><input type="text" class="textfield" name="person[family_name]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">The last name or the family name. Use only latin characters. See the <u>Original family name</u> below for Chinese, Hebrew, Arabic and other character sets.</td>
</tr>
<tr>
	<th>Given or First Name(s):</th>
	<td><input type="text" class="textfield" name="person[given_name]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">The first, middle, and any other given names</td>
</tr>
<tr>
	<th>Gender:</th>
	<td><?php echo smarty_function_html_radios(array('name'=>"person[gender]",'options'=>$_smarty_tpl->tpl_vars['gender_options']->value,'selected'=>$_smarty_tpl->tpl_vars['person']->value['gender']),$_smarty_tpl);?>
</td>
	<td width="150">The gender of the individual</td>
</tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("event_edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('events'=>$_smarty_tpl->tpl_vars['person']->value['e'],'eventtype'=>"person",'defaultevent'=>"1"), 0);?>

</td></tr>
<tr><td align="center">
<input type="submit" name="save" value="Save">
</td></tr>
<tr><td>
<h3>Optional Information</h3>

<table class="editPerson">
<tr>
	<th>Child Order:</th>
	<td><input type="text" class="textfield" name="person[child_order]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['child_order'];?>
" size="6"></td>
	<td width="150">The order of this child in the family. Only use this when birth dates are unknown.</td>
</tr>
<tr>
	<th>Prefix:</th>
	<td><input type="text" class="textfield" id="person_prefix" name="person[prefix]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['prefix'], ENT_QUOTES, 'ISO-8859-1', true);?>
" size="6"></td>
	<td width="150">Person's name prefix if any.</td>
</tr>
<tr>
	<th>Suffix:</th>
	<td><input type="text" class="textfield" id="person_suffix" name="person[suffix]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['suffix'], ENT_QUOTES, 'ISO-8859-1', true);?>
" size="6"></td>
	<td width="150">Person's suffix if any. Examples include Jr., Sr., I, II, III, etc.</td>
</tr>
<tr>
	<th>Nickname:</th>
	<td><input type="text" class="textfield" name="person[nickname]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['nickname'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">The person's preferred name.</td>
</tr>
<tr>
	<th>Original family name:</th>
	<td><input type="text" class="textfield" name="person[orig_family_name]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['orig_family_name'];?>
"></td>
	<td width="150">The person's family name using their native alphabet such as Chinese, Japanese, Korean, Hebrew, Arabic, or Cyrillic. Leave blank if they used a latin alphabet like English or Spanish.</td>
</tr>
<tr>
	<th>Original given name:</th>
	<td><input type="text" class="textfield" name="person[orig_given_name]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['orig_given_name'];?>
"></td>
	<td width="150">The person's given name(s) using their native alphabet.</td>
</tr>
<tr>
	<th>Title\Royalty:</th>
	<td><input type="text" class="textfield" id="person_title" name="person[title]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['title'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">Title or royalty status. Examples include King, Dr., and Sergent</td>
</tr>
<tr>
	<th>AFN:</th>
	<td><input type="text" class="textfield" name="person[afn]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['afn'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">Ancestral File Number</td>
</tr>
<tr>
	<th>SSN or National ID:</th>
	<td><input type="text" class="textfield" name="person[national_id]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['national_id'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">A social security number or other national identity number</td>
</tr>
<tr>
	<th>Nationality or Origin:</th>
	<td><input type="text" class="textfield" id="person_national_origin" name="person[national_origin]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['national_origin'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">The national or tribal origin</td>
</tr>
<tr>
	<th>Occupation:</th>
	<td><input type="text" class="textfield" id="person_occupation" name="person[occupation]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['occupation'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">The primary occupation of this person during life</td>
</tr>
<tr>
	<th>Wikipedia:</th>
	<td><input type="text" class="textfield" name="person[wikipedia]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['wikipedia'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
	<td width="150">The English Wikipedia article featuring this individual.</td>
</tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("event_edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('events'=>$_smarty_tpl->tpl_vars['person']->value['e'],'eventtype'=>"person",'defaultevent'=>"0"), 0);?>

</td></tr>
<tr><td align="center">
<input type="submit" name="save" value="Save">
</td></tr></table>

<script type="text/javascript">
	new AutoComplete('person_prefix', '/suggest.php?field=prefix&value=',{threshold: 1});
	new AutoComplete('person_suffix', '/suggest.php?field=suffix&value=',{threshold: 1});
	new AutoComplete('person_title', '/suggest.php?field=title&value=',{threshold: 1});
	new AutoComplete('person_national_origin', '/suggest.php?field=national_origin&value=',{threshold: 1});
	new AutoComplete('person_occupation', '/suggest.php?field=occupation&value=',{threshold: 2});
</script>


</form>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>