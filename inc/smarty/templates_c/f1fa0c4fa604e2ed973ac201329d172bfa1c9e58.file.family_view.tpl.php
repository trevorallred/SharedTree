<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:15:13
         compiled from "/var/www/sharedtree/templates/family_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17118688895132c04115d3e9-83123718%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f1fa0c4fa604e2ed973ac201329d172bfa1c9e58' => 
    array (
      0 => '/var/www/sharedtree/templates/family_view.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17118688895132c04115d3e9-83123718',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'family' => 0,
    'family_id' => 0,
    'time' => 0,
    'show_lds' => 0,
    'event' => 0,
    'is_logged_on' => 0,
    'children' => 0,
    'child' => 0,
    'individual' => 0,
    'history' => 0,
    'change' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c041537dd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c041537dd')) {function content_5132c041537dd($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Family View",'includejs'=>1), 0);?>

<br  clear="all"/>
<table class="portal" width="700">
<tr>
	<td width="45%">
	<center>
	<?php if ($_smarty_tpl->tpl_vars['family']->value['person1_id']){?>
	<h2><a href="/person/<?php echo $_smarty_tpl->tpl_vars['family']->value['person1_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['family']->value['given_name1'];?>
 <?php echo $_smarty_tpl->tpl_vars['family']->value['family_name1'];?>
</a></h2>
		<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['family']->value['person1_id'],'direction'=>"flat"), 0);?>

	<?php }else{ ?><h2><i>UNKNOWN</i></h2><?php }?>
	</center>
	</td>
	<td><h2 align="center">&amp;</h2></td>
	<td width="45%">
	<center>
	<?php if ($_smarty_tpl->tpl_vars['family']->value['person2_id']){?>
	<h2><a href="/person/<?php echo $_smarty_tpl->tpl_vars['family']->value['person2_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['family']->value['given_name2'];?>
 <?php echo $_smarty_tpl->tpl_vars['family']->value['family_name2'];?>
</a></h2>
		<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['family']->value['person2_id'],'direction'=>"flat"), 0);?>

	<?php }else{ ?><h2><i>UNKNOWN</i></h2><?php }?>
	</center>
	</td>
</tr>
<tr><td colspan="3">
<div class="errors" align="center">
<?php if ($_smarty_tpl->tpl_vars['family']->value['delete_date']){?>
	<a href="/family_history.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
">Deleted on <?php echo $_smarty_tpl->tpl_vars['family']->value['delete_date'];?>
</a> <br /><br />
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['time']->value){?>Version: <?php echo $_smarty_tpl->tpl_vars['time']->value;?>
 <a href="family_edit.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
"><img src="/img/btn_edit.png" title="Revert to this Version" width="16" height="16" border="0" /><br />
	<a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
">&lt;&lt; Back to Current Version</a><br /><?php }?>
<?php }?>
</div>

<div align="right">
	<a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
&action=edit">Edit Marriage</a>
	<a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
&action=edit"><img src="/img/btn_edit.png" title="Edit Family" width="16" height="16" border="0" /></a>
</div>
<label>Status:</label>
<?php if ($_smarty_tpl->tpl_vars['family']->value['status_code']=="M"){?>Married<?php }?>
<?php if ($_smarty_tpl->tpl_vars['family']->value['status_code']=="N"){?>Not Married<?php }?>
<?php if ($_smarty_tpl->tpl_vars['family']->value['status_code']=="S"){?>Separated<?php }?>
<?php if ($_smarty_tpl->tpl_vars['family']->value['status_code']=="D"){?>Divorced<?php }?>
<?php if ($_smarty_tpl->tpl_vars['family']->value['status_code']=="U"){?>Unknown<?php }?><br>

<?php  $_smarty_tpl->tpl_vars['event'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['event']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['family']->value['e']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['event']->key => $_smarty_tpl->tpl_vars['event']->value){
$_smarty_tpl->tpl_vars['event']->_loop = true;
?>
<?php if ($_smarty_tpl->tpl_vars['show_lds']->value==1||$_smarty_tpl->tpl_vars['event']->value['lds_flag']==0){?>
	<label><?php echo $_smarty_tpl->tpl_vars['event']->value['prompt'];?>
:</label>
	<?php if ($_smarty_tpl->tpl_vars['event']->value['event_date']){?>
		<?php echo $_smarty_tpl->tpl_vars['event']->value['date_approx'];?>
 <?php echo $_smarty_tpl->tpl_vars['event']->value['event_date'];?>
<?php if ($_smarty_tpl->tpl_vars['event']->value['ad']=='0'){?> B.C.<?php }?>
	<?php }else{ ?>
		<?php if ($_smarty_tpl->tpl_vars['event']->value['age_at_event']){?>Age: <?php echo $_smarty_tpl->tpl_vars['event']->value['age_at_event'];?>
<?php }?>
	<?php }?>
	in <?php if ($_smarty_tpl->tpl_vars['event']->value['temple']){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['temple'], ENT_QUOTES, 'ISO-8859-1', true);?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['location'], ENT_QUOTES, 'ISO-8859-1', true);?>
<?php }?> <br />
	<?php if ($_smarty_tpl->tpl_vars['event']->value['source']){?>Source: <br /><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['source'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><?php }?>
	<?php if ($_smarty_tpl->tpl_vars['event']->value['notes']){?>Notes: <br /><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['notes'], ENT_QUOTES, 'ISO-8859-1', true);?>
<br /><?php }?>
<?php }?>
<?php } ?>

<p><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['family']->value['notes'], ENT_QUOTES, 'ISO-8859-1', true);?>
</p>
</td></tr>
<tr><td colspan="3" class="content">

<h3>Children</h3>
<table class="grid" width="100%">
<tr><th>Navigation</th>
	<th>Ord</th>
	<th>Children's Name</th>
	<th>Sex</th>
	<th>Birth</th>
	<th>Death</th>
	<th>Created</th>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
		<th>Remove</th>
	<?php }?>
</tr>
<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['children']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
	<tr><td><?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['child']->value['person_id'],'direction'=>"flat"), 0);?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['child']->value['child_order'];?>
</td>
		<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['child']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['child']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
</a></td>
		<td><?php echo $_smarty_tpl->tpl_vars['child']->value['gender'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['child']->value['b_date'];?>
<br><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['child']->value['b_location'], ENT_QUOTES, 'ISO-8859-1', true);?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['child']->value['d_date'];?>
<br><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['child']->value['d_location'], ENT_QUOTES, 'ISO-8859-1', true);?>
</td>
		<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['child']->value['created_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['created_by'];?>
</a> <?php echo $_smarty_tpl->tpl_vars['child']->value['creation_date'];?>
</td>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
		<td><a href="#" onclick="stConfirm('Are you sure you want to remove <?php echo $_smarty_tpl->tpl_vars['child']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['child']->value['family_name'];?>
 from this family?','family.php?person_id=<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
&removechild=<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
');"><img src="/img/btn_drop.png" title="Remove child from family" width="16" height="16" border="0" /></a></td>
	<?php }?>
	</tr>
<?php } ?>
</table>

<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value&&!$_smarty_tpl->tpl_vars['family']->value['delete_date']){?>
        <p align="center"><a href="#" onclick="stConfirm('Are you sure you want to delete this marriage?', '/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
&action=delete');">Delete this family record</a></p>
<?php }?>

</td></tr>
<tr><td colspan="3" style="background: #CCC;">
<h2>Change & Audit History</h2>
<?php  $_smarty_tpl->tpl_vars['change'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['change']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['history']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['change']->key => $_smarty_tpl->tpl_vars['change']->value){
$_smarty_tpl->tpl_vars['change']->_loop = true;
?>
  <a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['family_id']->value;?>
&time=<?php echo $_smarty_tpl->tpl_vars['change']->value['update_date'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['update_date'];?>
</a> by <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['change']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['change']->value['family_name'];?>
</a><br />
<?php } ?>
</td></tr>
</table>

<form method="GET" action="/chart.php" target="_BLANK">
	<input type="hidden" name="family_id" value="<?php echo $_smarty_tpl->tpl_vars['family']->value['family_id'];?>
" />
	<input type="hidden" name="chart" value="familygroup" />
	<input type="submit" value="Create Printable PDF">
</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>