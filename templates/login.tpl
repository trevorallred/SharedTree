{include file="header.tpl" title="Login"}

<h1 align="center">Login</h1>
{$error}
<form method="POST" action="login.php">
	<input type="hidden" name="fromurl" value="{$fromurl}">
<table class="search" width="500">
<tr><td class="search">
<table border="0">
<tr><td>Email:</td>
	<td><input type="text" name="email" class="textfield" value="{$email|escape:'html'}"></td>
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

{include file="footer.tpl"}
