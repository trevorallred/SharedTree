<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:44:22
         compiled from "/var/www/sharedtree/templates/profile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18998755085132c7161c07a7-52234072%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24cdea14540daf4a76217b5748e64e50fe86cc31' => 
    array (
      0 => '/var/www/sharedtree/templates/profile.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18998755085132c7161c07a7-52234072',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'profile' => 0,
    'tree_size' => 0,
    'is_logged_on' => 0,
    'tree_overlap' => 0,
    'overlap_percent' => 0,
    'common_relatives' => 0,
    'change' => 0,
    'person_count' => 0,
    'family_count' => 0,
    'event_count' => 0,
    'person_changes' => 0,
    'canlogin' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c71659f05',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c71659f05')) {function content_5132c71659f05($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help'=>"Users"), 0);?>


<h2>Profile for <?php echo $_smarty_tpl->tpl_vars['profile']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['profile']->value['family_name'];?>
</h2>
<table class="portal">
<tr><td>
<img src="image.php?person_id=<?php echo $_smarty_tpl->tpl_vars['profile']->value['person_id'];?>
" align="right">
<h3>Profile Details</h3>
<?php if ($_smarty_tpl->tpl_vars['profile']->value['person_id']){?>
<label>Linked Individual:</label> <a href="/person/<?php echo $_smarty_tpl->tpl_vars['profile']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['profile']->value['p_given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['profile']->value['p_family_name'];?>
</a> <i>(must be related)</i><br>
<?php }?>
<label>From:</label> <?php if ($_smarty_tpl->tpl_vars['profile']->value['city']||$_smarty_tpl->tpl_vars['profile']->value['state_code']){?>
<?php echo $_smarty_tpl->tpl_vars['profile']->value['city'];?>
, <?php echo $_smarty_tpl->tpl_vars['profile']->value['state_code'];?>

<?php }else{ ?>
<i>unknown location</i>
<?php }?><br>
<label>Member since:</label> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['profile']->value['creation_date'],"%e %b %Y");?>
<br>
<label>Last visit:</label> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['profile']->value['last_visit_date'],"%e %b %Y");?>
<br>
<?php if ($_smarty_tpl->tpl_vars['tree_size']->value){?><label>Family Tree Size:</label> <?php echo $_smarty_tpl->tpl_vars['tree_size']->value;?>
<br><?php }?>
<br>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<br />
<?php if ($_smarty_tpl->tpl_vars['tree_overlap']->value){?><label>Relatives in common with you:</label> <?php echo $_smarty_tpl->tpl_vars['tree_overlap']->value;?>
 (<i><?php echo $_smarty_tpl->tpl_vars['overlap_percent']->value;?>
% of <?php echo $_smarty_tpl->tpl_vars['profile']->value['given_name'];?>
's tree</i>)<br><?php }?>
<h3>Common Relative(s)</h3>
<ul>
	<?php  $_smarty_tpl->tpl_vars['change'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['change']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['common_relatives']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['change']->key => $_smarty_tpl->tpl_vars['change']->value){
$_smarty_tpl->tpl_vars['change']->_loop = true;
?>
	<li><a href="/person/<?php echo $_smarty_tpl->tpl_vars['change']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['change']->value['family_name'];?>
</a><br />
	<?php echo $_smarty_tpl->tpl_vars['profile']->value['given_name'];?>
's <?php echo $_smarty_tpl->tpl_vars['change']->value['r2_desc'];?>
<br />
	your <?php echo $_smarty_tpl->tpl_vars['change']->value['r1_desc'];?>
</li>
	<?php } ?>
</ul>
<?php }?>

<p><?php echo $_smarty_tpl->tpl_vars['profile']->value['description'];?>
</p>
<hr />
<p align="center"><a href="email.php?to=<?php echo $_smarty_tpl->tpl_vars['profile']->value['user_id'];?>
">Send Email</a></p>

</td>
<td>
<h3>All Changes to SharedTree</h3>
<?php if ($_smarty_tpl->tpl_vars['person_count']->value){?><label>To Individuals:</label> <?php echo $_smarty_tpl->tpl_vars['person_count']->value;?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['family_count']->value){?><label>To Marriages:</label> <?php echo $_smarty_tpl->tpl_vars['family_count']->value;?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['event_count']->value){?><label>To Events:</label> <?php echo $_smarty_tpl->tpl_vars['event_count']->value;?>
<br><?php }?>

<h3>Recent changes by <?php echo $_smarty_tpl->tpl_vars['profile']->value['given_name'];?>
</h3>
<?php if ($_smarty_tpl->tpl_vars['person_changes']->value){?>
<table class="grid">
<tr><td>Name</td>
	<td>Birth</td>
	<td>Change Date</td>
</tr>
	<?php  $_smarty_tpl->tpl_vars['change'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['change']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['person_changes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['change']->key => $_smarty_tpl->tpl_vars['change']->value){
$_smarty_tpl->tpl_vars['change']->_loop = true;
?>
<tr><td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['change']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['given_name'];?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['change']->value['family_name'];?>
</a></td>
	<td><?php if ($_smarty_tpl->tpl_vars['change']->value['public_flag']){?><?php echo $_smarty_tpl->tpl_vars['change']->value['birth_year'];?>
<?php }else{ ?>private<?php }?></td>
	<td><?php echo $_smarty_tpl->tpl_vars['change']->value['update_date'];?>
</td>
</tr>
	<?php } ?>
</table>
<?php }else{ ?>
	This user has not made any modifications yet.
<?php }?>
<a href="search.php?created_by=<?php echo $_smarty_tpl->tpl_vars['profile']->value['user_id'];?>
&sort=creation&search=Search">Search for individuals added by <?php echo $_smarty_tpl->tpl_vars['profile']->value['given_name'];?>
</a>
</td></tr></table>

<?php if ($_smarty_tpl->tpl_vars['canlogin']->value){?>
Admin: <a href="login.php?user_id=<?php echo $_smarty_tpl->tpl_vars['profile']->value['user_id'];?>
">Login as <?php echo $_smarty_tpl->tpl_vars['profile']->value['given_name'];?>
</a> | <a href="user.php">User List</a> |
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<a href="relatives.php">Your Relatives</a> | 
<a href="invite.php">Invite More</a>
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>