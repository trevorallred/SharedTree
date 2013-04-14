{include file="header.tpl" title="Register"}

<h1>Create an Account</h1>

<table class="portal" width="500">
<tr><td>
SharedTree is a revolutionary new genealogy application. Creating an account is <b>completely FREE</b> and comes with <a href="w/Benefits" target="_BLANK" title="opens in new window">numerous benefits</a>. Your information will remain completely <a href="w/SharedTree:Privacy_policy" target="_BLANK" title="opens in new window">private and secure</a>.
</td></tr>
</table>

<form method="POST">
<table class="editPerson" width=500>
{if $errors}
<tr>
	<td colspan="3">
	Please correct the following errors:
	<ul class="errors">
	{foreach item=error from=$errors}
		<li>{$error}</li>
	{/foreach}
	</ul>
	</td>
</tr>
{/if}
<tr>
	<th>Email address:</th>
	<td><input type="text" class="textfield" name="email" size="25" value="{$request.email}"></td>
	<td>Used to login to your account and <br />to receive a new password if you lose yours</td>
</tr>
<tr>
	<th>Given name:</th>
	<td><input type="text" class="textfield" name="given_name" size="15" value="{$request.given_name}"></td>
	<td>Your first given name or nickname</td>
</tr>
<tr>
	<th>Last name:</th>
	<td><input type="text" class="textfield" name="family_name" size="15" value="{$request.family_name}"></td>
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
	<td><input type="text" class="textfield" name="key" size="25" value="{$request.key}" />
	    <input type="hidden" name="person_id" value="{$request.person_id}" /></td>
	<td>Secret key your relatives gave you to join an existing family tree. If you don't have this, you should ask them for an invitation. If you're starting a new family tree, then you don't need this.</td>
</tr>
<tr><td></td>
	<td><input type="submit" name="save" value="Register"></td>
	<td style="color:red">* All fields except optional code are required</td></tr>
</table>
</form>

{include file="footer.tpl"}
