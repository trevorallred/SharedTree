<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 17:10:53
         compiled from "/var/www/sharedtree/templates/profile_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18536880655133f49d67a1f9-41139418%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4f8da50ab69cdfc6c8f6529c34e999a299dc7c27' => 
    array (
      0 => '/var/www/sharedtree/templates/profile_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18536880655133f49d67a1f9-41139418',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'errors' => 0,
    'error' => 0,
    'rebuild' => 0,
    'user' => 0,
    'request' => 0,
    'countries' => 0,
    'languages' => 0,
    'restrict_access' => 0,
    'yesno_options' => 0,
    'send_messages_options' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5133f49d9d4db',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5133f49d9d4db')) {function content_5133f49d9d4db($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_options.php';
if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Edit Profile",'includejs'=>1), 0);?>

<script type="text/javascript">

function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=375,height=300,left = 388.5,top = 282');");
}

</script>


<h2>Edit Profile</h2>
<?php if ($_smarty_tpl->tpl_vars['errors']->value){?>
	<ul>
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
<?php if ($_smarty_tpl->tpl_vars['rebuild']->value){?>
	<?php if ($_smarty_tpl->tpl_vars['user']->value['person_id']>0){?>
		You have successfully added a genealogy record to your profile. Before you can view this record and the records of your family members, you must rebuild your family tree index. <a href="family_tree.php">Click to Continue &gt;&gt;</a>
	<?php }else{ ?>
		You have successfully removed the previous genealogy record from your profile. If you rebuild your family tree index before attaching to your new correct genealogy record, then you will lose access to all private records in that family tree. Try searching for your new genealogy record in your existing tree, or start a new family tree now.
	<?php }?>
<?php }?>
<p>
<a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['request']->value['user_id'];?>
">View Public Profile</a>
<?php if ($_smarty_tpl->tpl_vars['request']->value['person_id']){?> | 
<a href="familytree.php">View or Rebuild Family Tree</a> |
<a href="relatives.php">View Relatives</a> | 
<a href="invite.php">Invite Others</a>
<?php }?>
</p>
<form method="POST">
<table class="editPerson" width="600px">
<tr><td colspan="3" align="center"><h3>Personal Genealogy Record</h3></td></tr>
<tr>
	<th>Individual:</th>
<?php if ($_smarty_tpl->tpl_vars['request']->value['person_id']){?>
	<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['request']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['request']->value['p_given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['request']->value['p_family_name'];?>
</a></td>
	<td>
	Each user has one and only one genealogical record. Make sure you're connected to the right person (yourself) or certain permissions won't work correctly. Also, each genealogical record switch is reviewed for security reasons. <br>
		<a href="#" onclick="stConfirm('Are you sure you want to remove <?php echo $_smarty_tpl->tpl_vars['request']->value['p_given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['request']->value['p_family_name'];?>
 from your profile?','profile.php?addperson=null');">Remove from Profile</a><br />
	</td>
<?php }else{ ?>
	<td colspan="2"><a href="simple.php">Start your own family tree</a></td>
<?php }?>
</tr>
<tr><td colspan="3" align="center"><h3>Required</h3></td></tr>
<tr>
	<th>Email address:</th>
	<td><input type="text" name="email" size="25" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['email'];?>
"></td>
	<td>Used to login to your account and <br />to receive a new password if you lose yours</td>
</tr>
<?php if ($_smarty_tpl->tpl_vars['request']->value['email_confirmed']==0){?>
<tr>
	<th>Email unconfirmed:</th>
	<td><a href="javascript:popUp('profile.php?send_confirmation=1')">Resend Confirmation Email</a></td>
	<td>You have not confirmed your email yet. You are not eligible to receive important email messages from SharedTree until you confirm your email address.</td>
</tr>
<?php }?>
<tr>
	<th>First name:</th>
	<td><input type="text" name="given_name" size="15" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['given_name'];?>
"></td>
	<td>Your first given name or nickname</td>
</tr>
<tr>
	<th>Last name:</th>
	<td><input type="text" name="family_name" size="15" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['family_name'];?>
"></td>
	<td>Your last name or family name</td>
</tr>
<tr><td colspan="3" align="center"><h3>Change Password</h3></td></tr>
<tr>
	<th>Old password:</th>
	<td><input type="password" name="oldpassword" size="15" value=""></td>
	<td>Your current password. For security purposes, we will not change your password unless you remember your old one.</td>
</tr>
<tr>
	<th>New password:</th>
	<td><input type="password" name="newpassword" size="15" value=""></td>
	<td>A secret password you can use to access this website<br />
		All passwords are encrypted. If you lose yours, we <br />will create a new one for you.</td>
</tr>
<tr>
	<th>New password again:</th>
	<td><input type="password" name="newpassword2" size="15"></td>
	<td>Enter the new password again</td>
</tr>
<tr><td colspan="3" align="center"><h3>Optional</h3></td></tr>
<tr>
	<th>Username:</th>
	<td><input type="text" name="username" size="30" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['username'];?>
"></td>
	<td>A simple username used when editing the Wiki or posting messages to the User Forums.</td>
</tr>
<tr>
	<th>Address line 1:</th>
	<td><input type="text" name="address_line1" size="30" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['address_line1'];?>
"></td>
	<td>Your street address</td>
</tr>
<tr>
	<th>Address line 2:</th>
	<td><input type="text" name="address_line2" size="30" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['address_line2'];?>
"></td>
	<td>An optional second address line such as Apartment or Suite</td>
</tr>
<tr>
	<th>City:</th>
	<td><input type="text" name="city" size="25" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['city'];?>
"></td>
	<td>The city in which you reside</td>
</tr>
<tr>
	<th>State/Province:</th>
	<td><input type="text" name="state_code" size="10" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['state_code'];?>
"></td>
	<td>The state in which you reside</td>
</tr>
<tr>
	<th>Postal code:</th>
	<td><input type="text" name="postal_code" size="10" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['postal_code'];?>
"></td>
	<td>Your zip code</td>
</tr>
<tr>
	<th>Country:</th>
	<td><?php echo smarty_function_html_options(array('name'=>"country_id",'options'=>$_smarty_tpl->tpl_vars['countries']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['country_id']),$_smarty_tpl);?>
</td>
	<td>The country you live in.</td>
</tr>
<tr>
	<th>Language:</th>
	<td><?php echo smarty_function_html_options(array('name'=>"language",'options'=>$_smarty_tpl->tpl_vars['languages']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['language']),$_smarty_tpl);?>
</td>
	<td>Your native language (eventually SharedTree will support other languages)</td>
</tr>
<tr>
	<th>Who can view my family:</th>
	<td><?php echo smarty_function_html_radios(array('name'=>"restrict_access",'options'=>$_smarty_tpl->tpl_vars['restrict_access']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['restrict_access'],'separator'=>'<br />'),$_smarty_tpl);?>
</td>
	<td>Do you want to allow all relatives to see living family members or choose which ones can get access?</td>
</tr>
<tr>
	<th>Show LDS data:</th>
	<td><?php echo smarty_function_html_radios(array('name'=>"show_lds",'options'=>$_smarty_tpl->tpl_vars['yesno_options']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['show_lds']),$_smarty_tpl);?>
</td>
	<td>Would you like to see LDS data?</td>
</tr>
<tr>
	<th>Send Messages:</th>
	<td><?php echo smarty_function_html_options(array('name'=>"send_messages",'options'=>$_smarty_tpl->tpl_vars['send_messages_options']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['send_messages']),$_smarty_tpl);?>
</td>
	<td>Where do you want us to send messages from other users?</td>
</tr>
<tr>
	<th>Send Weekly Digest:</th>
	<td><?php echo smarty_function_html_radios(array('name'=>"weekly_email",'options'=>$_smarty_tpl->tpl_vars['yesno_options']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['weekly_email']),$_smarty_tpl);?>
</td>
	<td>IF any changes are made to your family tree by other members, would you like to receive notification on a weekly basis? We recommend you keep this turned on.</td>
</tr>
<tr>
	<th>Personal Description:</th>
	<td><textarea rows="10" cols="30" name="description"><?php echo $_smarty_tpl->tpl_vars['request']->value['description'];?>
</textarea></td>
	<td>Your personal description available for others to see on your profile page.</td>
</tr>
<tr><td colspan="3" align="center"><input type="submit" name="save" value="Save Profile"></td></tr>
</table>
</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>