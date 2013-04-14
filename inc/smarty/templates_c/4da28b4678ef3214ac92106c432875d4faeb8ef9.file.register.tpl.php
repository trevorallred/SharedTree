<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:23:02
         compiled from "/var/www/sharedtree/templates/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16292866485132c216defa13-42388087%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4da28b4678ef3214ac92106c432875d4faeb8ef9' => 
    array (
      0 => '/var/www/sharedtree/templates/register.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16292866485132c216defa13-42388087',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'errors' => 0,
    'error' => 0,
    'request' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c21703cb4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c21703cb4')) {function content_5132c21703cb4($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Register"), 0);?>


<h1>Create an Account</h1>

<table class="portal" width="500">
<tr><td>
SharedTree is a revolutionary new genealogy application. Creating an account is <b>completely FREE</b> and comes with <a href="w/Benefits" target="_BLANK" title="opens in new window">numerous benefits</a>. Your information will remain completely <a href="w/SharedTree:Privacy_policy" target="_BLANK" title="opens in new window">private and secure</a>.
</td></tr>
</table>

<form method="POST">
<table class="editPerson" width=500>
<?php if ($_smarty_tpl->tpl_vars['errors']->value){?>
<tr>
	<td colspan="3">
	Please correct the following errors:
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
	</td>
</tr>
<?php }?>
<tr>
	<th>Email address:</th>
	<td><input type="text" class="textfield" name="email" size="25" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['email'];?>
"></td>
	<td>Used to login to your account and <br />to receive a new password if you lose yours</td>
</tr>
<tr>
	<th>Given name:</th>
	<td><input type="text" class="textfield" name="given_name" size="15" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['given_name'];?>
"></td>
	<td>Your first given name or nickname</td>
</tr>
<tr>
	<th>Last name:</th>
	<td><input type="text" class="textfield" name="family_name" size="15" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['family_name'];?>
"></td>
	<td>Your last name or family name</td>
</tr>
<tr>
	<th>Password:</th>
	<td><input type="password" class="textfield" name="password" size="15" value=""></td>
	<td>A secret password you can use to access this website<br />
		All passwords are encrypted. If you lose yours, we <br />will create a new one for you. <a href="w/Passwords" target="_BLANK" title="opens in new window">Help</a></td>
</tr>
<tr>
	<th>Password again:</th>
	<td><input type="password" class="textfield" name="password2" size="15"></td>
	<td>Enter the password again</td>
</tr>
<tr>
	<th>Optional invitation code:</th>
	<td><input type="text" class="textfield" name="key" size="25" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['key'];?>
" />
	    <input type="hidden" name="person_id" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['person_id'];?>
" /></td>
	<td>Secret key your relatives gave you to join an existing family tree. If you don't have this, you should ask them for an invitation. If you're starting a new family tree, then you don't need this.</td>
</tr>
<tr><td></td>
	<td><input type="submit" name="save" value="Register"></td>
	<td style="color:red">* All fields except optional code are required</td></tr>
</table>
</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>