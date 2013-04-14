<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:17:23
         compiled from "/var/www/sharedtree/templates/person_history.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3938763655132c0c37c4ce4-47801336%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd2c6a02c8d6ed066d5dad56a12ad57ff1052f7fe' => 
    array (
      0 => '/var/www/sharedtree/templates/person_history.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3938763655132c0c37c4ce4-47801336',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'person_id' => 0,
    'person_hist' => 0,
    'row' => 0,
    'prev' => 0,
    'event_hist' => 0,
    'gedcom' => 0,
    'merge_hist' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c0c3c5834',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c0c3c5834')) {function content_5132c0c3c5834($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<h2><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>
<a href="/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">Back to Individual Detail</a>

<table class="portal">
<tr align="left"><td>

<h3>Changes to Individual Record</h3>
<table class="grid">
	<tr>
		<td>Modified</td>
		<td>By</td>
		<td>Sex</td>
		<td>Last</td>
		<td>Given</td>
		<td>Nick</td>
		<td>Title</td>
		<td>Pref</td>
		<td>Suff</td>
		<td>Orig</td>
		<td>Job</td>
		<td>AFN</td>
		<td>RIN</td>
		<td>NID</td>
		<td>Fam</td>
		<td>Order</td>
	</tr>
<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['person_hist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
	<tr>
		<td><a href="person/<?php echo $_smarty_tpl->tpl_vars['row']->value['person_id'];?>
&time=<?php echo $_smarty_tpl->tpl_vars['row']->value['actual_start_date'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['actual_start_date'];?>
</a></td>
		<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['row']->value['updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</a></td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['gender']!=$_smarty_tpl->tpl_vars['prev']->value['gender']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['gender'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['family_name']!=$_smarty_tpl->tpl_vars['prev']->value['family_name']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['family_name'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['given_name']!=$_smarty_tpl->tpl_vars['prev']->value['given_name']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['given_name'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['nickname']!=$_smarty_tpl->tpl_vars['prev']->value['nickname']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['nickname'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['title']!=$_smarty_tpl->tpl_vars['prev']->value['title']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['title'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['prefix']!=$_smarty_tpl->tpl_vars['prev']->value['prefix']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['prefix'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['suffix']!=$_smarty_tpl->tpl_vars['prev']->value['suffix']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['suffix'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['national_origin']!=$_smarty_tpl->tpl_vars['prev']->value['national_origin']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['national_origin'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['occupation']!=$_smarty_tpl->tpl_vars['prev']->value['occupation']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['occupation'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['afn']!=$_smarty_tpl->tpl_vars['prev']->value['afn']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['afn'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['rin']!=$_smarty_tpl->tpl_vars['prev']->value['rin']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['rin'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['national_id']!=$_smarty_tpl->tpl_vars['prev']->value['national_id']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['national_id'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['bio_family_id']!=$_smarty_tpl->tpl_vars['prev']->value['bio_family_id']){?> class="highlight"<?php }?>><a href="marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['row']->value['bio_family_id'];?>
&time=<?php echo $_smarty_tpl->tpl_vars['row']->value['actual_start_date'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['bio_family_id'];?>
</a></td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['child_order']!=$_smarty_tpl->tpl_vars['prev']->value['child_order']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['child_order'];?>
</td>
	</tr>
	<?php $_smarty_tpl->tpl_vars['prev'] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value, null, 0);?>
<?php } ?>
</table>

</td></tr>
<tr><td>
<h3>Changes to Event Records</h3>
<table class="grid">
	<tr>
		<td>Modified</td>
		<td>By</td>
		<td>Type</td>
		<td>Date</td>
		<td>AD</td>
		<td>Aprox</td>
		<td>Age</td>
		<td>Loc</td>
		<td>LID</td>
		<td>Temple</td>
		<td>Status</td>
		<td>Notes</td>
		<td>Source</td>
	</tr>
<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['event_hist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['row']->value['event_type']!=$_smarty_tpl->tpl_vars['prev']->value['event_type']){?><?php $_smarty_tpl->tpl_vars['prev'] = new Smarty_variable('', null, 0);?><?php }?>
	<tr>
		<td><?php echo $_smarty_tpl->tpl_vars['row']->value['actual_start_date'];?>
</td>
		<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['row']->value['updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</a></td>
		<td><?php echo $_smarty_tpl->tpl_vars['row']->value['event_type'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['event_date']!=$_smarty_tpl->tpl_vars['prev']->value['event_date']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['event_date'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['ad']!=$_smarty_tpl->tpl_vars['prev']->value['ad']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['ad'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['date_approx']!=$_smarty_tpl->tpl_vars['prev']->value['date_approx']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['date_approx'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['age_at_event']!=$_smarty_tpl->tpl_vars['prev']->value['age_at_event']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['age_at_event'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['location']!=$_smarty_tpl->tpl_vars['prev']->value['location']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['location'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['location_id']!=$_smarty_tpl->tpl_vars['prev']->value['location_id']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['location_id'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['temple_code']!=$_smarty_tpl->tpl_vars['prev']->value['temple_code']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['temple_code'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['status']!=$_smarty_tpl->tpl_vars['prev']->value['status']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['status'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['notes']!=$_smarty_tpl->tpl_vars['prev']->value['notes']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['notes'];?>
</td>
		<td <?php if ($_smarty_tpl->tpl_vars['row']->value['source']!=$_smarty_tpl->tpl_vars['prev']->value['source']){?> class="highlight"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['source'];?>
</td>
	</tr>
	<?php $_smarty_tpl->tpl_vars['prev'] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value, null, 0);?>
<?php } ?>
</table>

</td></tr>
<tr><td>
<h3>GEDCOM Import Record(s)</h3>
<table class="grid">
<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gedcom']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
<tr><td><pre>
<?php echo $_smarty_tpl->tpl_vars['row']->value['gedcom_text'];?>

</pre></td></tr>
<?php } ?>
</table>
</td></tr>

<?php if ($_smarty_tpl->tpl_vars['merge_hist']->value){?>
<tr><td>
<h3>Merged Individuals</h3>
<table class="grid">
	<tr>
		<td>ID</td>
		<td>Last</td>
		<td>Given</td>
		<td>Goto</td>
		<td>Goto</td>
		<td>Merge Date</td>
		<td>By</td>
	</tr>
<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['merge_hist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
	<tr><td><?php echo $_smarty_tpl->tpl_vars['row']->value['person_id'];?>

		<td><?php echo $_smarty_tpl->tpl_vars['row']->value['family_name'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['row']->value['given_name'];?>
</td>
		<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['row']->value['person_id'];?>
&time=<?php echo $_smarty_tpl->tpl_vars['row']->value['actual_start_date'];?>
">Individual</a></td>
		<td><a href="person_history.php?person_id=<?php echo $_smarty_tpl->tpl_vars['row']->value['person_id'];?>
">History</a></td>
		<td><?php echo $_smarty_tpl->tpl_vars['row']->value['actual_end_date'];?>
</td>
		<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['row']->value['updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['username'];?>
</a></td>
	</tr>
<?php } ?>
</table>
</td></tr>
<?php }?>
<tr><td>
<i>Disclaimer: We will try to maintain as much history as possible. Considering the cheap cost of space and the speed and quality of modern databases, we should be able to keep a lot. However, do not be surprised if we start deleting changes after 365, 180, or even 30 days.</i>
</td></tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>