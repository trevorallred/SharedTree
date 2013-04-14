<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:03:11
         compiled from "/var/www/sharedtree/templates/gen_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6447263455132bd6f15b3c5-14044095%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '72d09fa59c08cbf6f488c2ad976a922cd05a6f5e' => 
    array (
      0 => '/var/www/sharedtree/templates/gen_view.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6447263455132bd6f15b3c5-14044095',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'time' => 0,
    'person_id' => 0,
    'individual' => 0,
    'photos' => 0,
    'father' => 0,
    'parents' => 0,
    'is_logged_on' => 0,
    'mother' => 0,
    'marriages' => 0,
    'marriage' => 0,
    'child' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd6f5daf6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd6f5daf6')) {function content_5132bd6f5daf6($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value,'includejs'=>1), 0);?>

<br clear="all" />
<br />
<script type="text/javascript">

$i = 1;
function mergePerson($id) {
	$("merge"+$i).value = $id;
	if ($i==1) $i = 2;
	else $i = 1;
	$('merge').show();
}

</script>

<table class="table1" cellspacing="0" cellpadding="0" width="800">
<tr><td rowspan="3" width="50%" class="content">
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>
<div class="errors">Version: <?php echo $_smarty_tpl->tpl_vars['time']->value;?>
 <a href="/family/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">&lt;&lt; Back to Current Version</a></div>
<?php }?>
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['individual']->value['person_id']), 0);?>

	<font size="+1"><b><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['individual']->value['full_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
</b></font><?php echo $_smarty_tpl->getSubTemplate ("newgif.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('new'=>$_smarty_tpl->tpl_vars['individual']->value['new']), 0);?>

	<?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['individual']->value['person_id']]){?><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['individual']->value['person_id']];?>
&size=thumb" border="0" align="left"><?php }?>
	<br /><br />
	<span class="label">Gender:</span> <?php if ($_smarty_tpl->tpl_vars['individual']->value['gender']=="M"){?>Male<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['individual']->value['gender']=="F"){?>Female<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['individual']->value['gender']=="U"){?>Unknown<?php }?><br />
	<?php echo $_smarty_tpl->getSubTemplate ("gen_detail.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('indi'=>$_smarty_tpl->tpl_vars['individual']->value), 0);?>

    </td>
    <td width="50%" class="content" style="border-left: 3px black solid; border-bottom: 3px black solid;">
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['father']->value['person_id'],'return_to'=>"/family/".($_smarty_tpl->tpl_vars['person_id']->value)), 0);?>

	<h3>Father:</h3>
<?php if ($_smarty_tpl->tpl_vars['father']->value['person_id']>0){?>
	<b><a href="/family/<?php echo $_smarty_tpl->tpl_vars['father']->value['person_id'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['father']->value['full_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
</a></b><?php echo $_smarty_tpl->getSubTemplate ("newgif.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('new'=>$_smarty_tpl->tpl_vars['father']->value['new']), 0);?>
<br /><br />
	<?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['father']->value['person_id']]){?><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['father']->value['person_id']];?>
&size=thumb" border="0" align="left"><?php }?>
	<?php echo $_smarty_tpl->getSubTemplate ("gen_detail.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('indi'=>$_smarty_tpl->tpl_vars['father']->value), 0);?>

<br clear="all" />
<br>
	<table border=0 align="right">
	<tr>
		<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['parents']->value['family_id'];?>
"><img src="/img/btn_individual.png" title="Marriage Details" width="16" height="16" border="0" /></a></td>
		<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['parents']->value['family_id'];?>
">View Marriage</a></td>
	</tr>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
	<tr>
		<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['parents']->value['family_id'];?>
&action=edit"><img src="/img/btn_edit.png" title="Edit Marriage" width="16" height="16" border="0" /></a></td>
		<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['parents']->value['family_id'];?>
&action=edit">Edit Marriage</a></td>
	</tr>
	<?php }?>
	</table>
	<h3>Marriage:</h3>
		<label>Date:</label>
		<?php echo $_smarty_tpl->tpl_vars['parents']->value['e']['MARR']['event_date'];?>
<?php if ($_smarty_tpl->tpl_vars['parents']->value['e']['MARR']['ad']=='0'){?> B.C.<?php }?> <br />
		<label>Location:</label> <?php echo $_smarty_tpl->tpl_vars['parents']->value['e']['MARR']['location'];?>
 <?php echo $_smarty_tpl->tpl_vars['parents']->value['e']['MARR']['temple_code'];?>
<br />
		<label>Status:</label>
		<?php if ($_smarty_tpl->tpl_vars['parents']->value['status_code']=="M"){?>Married<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['parents']->value['status_code']=="S"){?>Separated<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['parents']->value['status_code']=="D"){?>Divorced<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['parents']->value['status_code']=="N"){?>Not Married<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['parents']->value['status_code']=="U"){?>Unknown<?php }?><br>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['father']->value['protected']){?>
		<b><?php echo $_smarty_tpl->tpl_vars['father']->value['given_name'];?>
</b><br /><br /><br />
	<?php }else{ ?>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
			<form action="/person_edit.php" method="get">
				<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['bio_family_id'];?>
">
				<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
">
				<input type="hidden" name="person[gender]" value="M">
				<input type="hidden" name="return_to" value="/family/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">
				<table align="center" class="addPerson">
				<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td></td></tr>
				<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20"></td>
					<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['individual']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
					<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
					<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
				</tr>
				</table>
			</form>
		<?php }else{ ?>
			<i>UNKNOWN</i>
		<?php }?>
	<?php }?>
<?php }?>
</td>
</tr>
<tr><td width="50%" class="content" style="border-left: 3px black solid;">
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['mother']->value['person_id'],'return_to'=>"/family/".($_smarty_tpl->tpl_vars['person_id']->value)), 0);?>

	<h3>Mother:</h3>
<?php if ($_smarty_tpl->tpl_vars['mother']->value['person_id']>0){?>
		<b><a href="/family/<?php echo $_smarty_tpl->tpl_vars['mother']->value['person_id'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['mother']->value['full_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
</a></b><?php echo $_smarty_tpl->getSubTemplate ("newgif.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('new'=>$_smarty_tpl->tpl_vars['mother']->value['new']), 0);?>
<br /><br />
		<?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['mother']->value['person_id']]){?><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['mother']->value['person_id']];?>
&size=thumb" border="0" align="left"><?php }?>
		<?php echo $_smarty_tpl->getSubTemplate ("gen_detail.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('indi'=>$_smarty_tpl->tpl_vars['mother']->value), 0);?>

<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['mother']->value['protected']){?>
		<b><?php echo $_smarty_tpl->tpl_vars['mother']->value['given_name'];?>
</b><br /><br /><br />
	<?php }else{ ?>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
			<form action="/person_edit.php" method="get">
				<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['bio_family_id'];?>
">
				<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
">
				<input type="hidden" name="person[gender]" value="F">
				<input type="hidden" name="return_to" value="/family/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">
				<table align="center" class="addPerson">
				<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td></td></tr>
				<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20"></td>
					<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20"></td>
					<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
					<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
				</tr>
				</table>
			</form>
		<?php }else{ ?>
			<i>UNKNOWN</i>
		<?php }?>
	<?php }?>
<?php }?>
</td>
</tr>
</table>
<br />

<?php if ($_smarty_tpl->tpl_vars['marriages']->value){?>
<?php  $_smarty_tpl->tpl_vars['marriage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['marriage']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['marriages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['marriage']->key => $_smarty_tpl->tpl_vars['marriage']->value){
$_smarty_tpl->tpl_vars['marriage']->_loop = true;
?>
	<table class="table1" width="800">
	<tr valign="top"><td width="40%" class="content">
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['marriage']->value['person_id'],'return_to'=>"/family/".($_smarty_tpl->tpl_vars['person_id']->value)), 0);?>

	<h3>Spouse:</h3>
<?php if ($_smarty_tpl->tpl_vars['marriage']->value['person_id']>0){?>
	<b><a href="/family/<?php echo $_smarty_tpl->tpl_vars['marriage']->value['person_id'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['marriage']->value['full_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
</a></b><?php echo $_smarty_tpl->getSubTemplate ("newgif.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('new'=>$_smarty_tpl->tpl_vars['marriage']->value['new']), 0);?>
<br /><br />
	<?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['marriage']->value['person_id']]){?><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['marriage']->value['person_id']];?>
&size=thumb" border="0" align="left"><?php }?>
	<label>Birth: </label>
	<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['marriage']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['marriage']->value['b_date']), 0);?>
<br />
	<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['marriage']->value['b_location'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><br />
<?php if ($_smarty_tpl->tpl_vars['marriage']->value['d_date']||$_smarty_tpl->tpl_vars['marriage']->value['d_location']){?>
	<label>Death:</label>
	<?php echo $_smarty_tpl->tpl_vars['marriage']->value['d_date'];?>
<?php if ($_smarty_tpl->tpl_vars['marriage']->value['d_ad']=='0'){?> B.C.<?php }?> <br />
	<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['marriage']->value['d_location'], ENT_QUOTES, 'ISO-8859-1', true);?>

<?php }?>
<?php }else{ ?>
	<form action="/person_edit.php" method="get">
		<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
">
		<input type="hidden" name="person[gender]" value="F">
		<input type="hidden" name="return_to" value="/family/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td></td><td></td></tr>
		<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20"></td>
			<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20"></td>
			<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
<?php }?>
		<br clear="all">
		<br>
		<table border=0 align="right">
		<tr>
			<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
"><img src="/img/btn_individual.png" title="Marriage Details" width="16" height="16" border="0" /></a></td>
			<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
">View Marriage</a></td>
		</tr>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
		<tr>
			<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
&action=edit"><img src="/img/btn_edit.png" title="Edit Marriage" width="16" height="16" border="0" /></a></td>
			<td><a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
&action=edit">Edit Marriage</a></td>
		</tr>
		<?php }?>
		</table>
		<h3 <?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>style="cursor: pointer;" onclick="mergePerson(<?php echo $_smarty_tpl->tpl_vars['marriage']->value['person_id'];?>
);" title="Click to select <?php echo $_smarty_tpl->tpl_vars['marriage']->value['full_name'];?>
 for merge. See below for details."<?php }?>>Marriage: </h3>
		<label>Date:</label>
		<?php echo $_smarty_tpl->tpl_vars['marriage']->value['m_date'];?>
<?php if ($_smarty_tpl->tpl_vars['marriage']->value['m_ad']=='0'){?> B.C.<?php }?> <br />
		<label>Location:</label> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['marriage']->value['m_location'], ENT_QUOTES, 'ISO-8859-1', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['marriage']->value['m_temple_code'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br />
		<label>Status:</label>
		<?php if ($_smarty_tpl->tpl_vars['marriage']->value['status_code']=="M"){?>Married<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['marriage']->value['status_code']=="S"){?>Separated<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['marriage']->value['status_code']=="D"){?>Divorced<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['marriage']->value['status_code']=="N"){?>Not Married<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['marriage']->value['status_code']=="U"){?>Unknown<?php }?><br>
	</td>
	<td class="content">	
	<h3>Children</h3>
	<table class="grid" width="100%">
	<tr><th width="100px">Navigation</th>
		<th>Ord</th>
		<th>Children's Name</th>
		<th>Sex</th>
		<th>Age</th>
		<th>Birthdate</th>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
			<th>Mrge</th>
			<th>Remove</th>
		<?php }?>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['marriage']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
		<tr><td><?php if ($_smarty_tpl->tpl_vars['child']->value['protected']!=1){?><?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['child']->value['person_id'],'direction'=>"flat",'return_to'=>"/family/".($_smarty_tpl->tpl_vars['person_id']->value)), 0);?>
<?php }?></td>
			<td><?php echo $_smarty_tpl->tpl_vars['child']->value['child_order'];?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['child']->value['protected']==1){?><?php echo $_smarty_tpl->tpl_vars['child']->value['full_name'];?>
<?php }else{ ?><a href="/family/<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['child']->value['full_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
</a><?php }?><?php echo $_smarty_tpl->getSubTemplate ("newgif.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('new'=>$_smarty_tpl->tpl_vars['child']->value['new']), 0);?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['child']->value['gender'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['child']->value['age'];?>
</td>
			<td><?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['child']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['child']->value['b_date']), 0);?>
</td>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
			<td style="cursor: pointer;" onclick="mergePerson(<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
);" title="Click to select <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['child']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 for merge. See below for details.">&nbsp;</td>
			<td><?php if ($_smarty_tpl->tpl_vars['child']->value['protected']!=1){?><a href="#" onclick="stConfirm('Are you sure you want to remove <?php echo $_smarty_tpl->tpl_vars['child']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['child']->value['family_name'];?>
 from this family?','/family/<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
&removechild=<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
');"><img src="/img/btn_drop.png" title="Remove child from family" width="16" height="16" border="0" /></a><?php }?></td>
		<?php }?>
		</tr>
	<?php } ?>

	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
	<tr><td colspan="7" align="center">
	<h3 id="addchildh<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
"><a href="#" onclick="$('addchild<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
').show(); $('addchildh<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
').hide(); $('addchild<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
_name').focus(); return false;">Add Child to Marriage</a></h3>
	<form action="/person_edit.php" method="post" id="addchild<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
" style="display: none">
		<input type="hidden" name="person[parents_id]" value="<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
">
		<input type="hidden" name="parents_id" value="<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
">
		<input type="hidden" name="return_to" value="/family/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td><a href="#" onclick="$('addchild<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
').hide(); $('addchildh<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
').show(); return false;">Hide</a></td></tr>
		<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20" id="addchild<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
_name"></td>
			<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20" value="<?php if ($_smarty_tpl->tpl_vars['marriage']->value['gender']=='M'){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['marriage']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['individual']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
<?php }?>"></td>
			<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
			<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	<?php }?>
	</table>
	</td></tr>
	
	</td></tr>
	</table>
	<br />
<?php } ?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<table class="table1">
<tr valign="top"><td class="content" align="center">
<h3 id="addspouseh"><a href="#" onclick="$('addspouse').show(); $('addspouseh').hide(); $('addSpouseName').focus(); return false;">Add Spouse</a></h3>
<form action="/person_edit.php" method="post" id="addspouse" style="display: none">
<h3>New Spouse:</h3>
	<input type="hidden" name="person[spouse_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
">
	<table align="center" class="addPerson">
	<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td><a href="#" onclick="$('addspouse').hide(); $('addspouseh').show(); return false;">Hide</a></td></tr>
	<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20" id="addSpouseName"></td>
		<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20"></td>
		<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
		<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
	</tr>
	</table>
</form>
</td>
</tr></table>
<br>
<table class="table1" id="merge" style="display: none">
<td class="content">
<h3>Merge Family Members:</h3>
<form action="/merge.php" method="GET">
<a name="merge"></a>
<input type="hidden" name="returnto" value="/family/<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
">
Merge ID#<input type="textbox" class="textfield" size="6" name="p1" id="merge1"> with ID#<input type="textbox" class="textfield" size="6" name="p2" id="merge2">
<input type="submit" value="Merge">
</form>
</td></tr></table>
<br />
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>