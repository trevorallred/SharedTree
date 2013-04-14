<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:04:09
         compiled from "/var/www/sharedtree/templates/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4543125695132bda9c64d69-44746178%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '077af910baa5e51b37eed397a6ab517d95eb78ca' => 
    array (
      0 => '/var/www/sharedtree/templates/login.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4543125695132bda9c64d69-44746178',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'fromurl' => 0,
    'email' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bda9e0486',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bda9e0486')) {function content_5132bda9e0486($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Login"), 0);?>


<h1 align="center">Login</h1>
<?php echo $_smarty_tpl->tpl_vars['error']->value;?>

<form method="POST" action="login.php">
	<input type="hidden" name="fromurl" value="<?php echo $_smarty_tpl->tpl_vars['fromurl']->value;?>
">
<table class="search" width="500">
<tr><td class="search">
<table border="0">
<tr><td>Email:</td>
	<td><input type="text" name="email" class="textfield" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['email']->value, ENT_QUOTES, 'ISO-8859-1', true);?>
"></td>
<tr><td>Password:</td>
	<td><input type="password" name="password" class="textfield"></td>
</tr>
<tr><td>&nbsp;</td><td align="center">
	<input type="submit" name="login" value="Login">
	<br /><br /><br /><br />
	<input type="submit" name="reset" value="Send New Password">
</td>
</tr>
</table>
	</td>
	<td class="helparea">
<a href="register.php">Create an account</a><br /><br />

Have you forgotten your password? We'll create a new password for you and send it to the email address you enter.
</td></tr></table>
</form>
<br />
<br />
<br />

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>