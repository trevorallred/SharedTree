{include file="header.tpl" title="Edit Profile" includejs=1}
<script type="text/javascript">
{literal}
function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=375,height=300,left = 388.5,top = 282');");
}
{/literal}
</script>


<h2>Edit Profile</h2>
{if $errors}
	<ul>
		{foreach item=error from=$errors}
		<li>{$error}</li>
		{/foreach}
	</ul>
{/if}
{if $rebuild}
	{if $user.person_id > 0}
		You have successfully added a genealogy record to your profile. Before you can view this record and the records of your family members, you must rebuild your family tree index. <a href="family_tree.php">Click to Continue &gt;&gt;</a>
	{else}
		You have successfully removed the previous genealogy record from your profile. If you rebuild your family tree index before attaching to your new correct genealogy record, then you will lose access to all private records in that family tree. Try searching for your new genealogy record in your existing tree, or start a new family tree now.
	{/if}
{/if}
<p>
<a href="profile.php?user_id={$request.user_id}">View Public Profile</a>
{if $request.person_id} | 
<a href="familytree.php">View or Rebuild Family Tree</a> |
<a href="relatives.php">View Relatives</a> | 
<a href="invite.php">Invite Others</a>
{/if}
</p>
<form method="POST">
<table class="editPerson" width="600px">
<tr><td colspan="3" align="center"><h3>Personal Genealogy Record</h3></td></tr>
<tr>
	<th>Individual:</th>
{if $request.person_id}
	<td><a href="/person/{$request.person_id}">{$request.p_given_name} {$request.p_family_name}</a></td>
	<td>
	Each user has one and only one genealogical record. Make sure you're connected to the right person (yourself) or certain permissions won't work correctly. Also, each genealogical record switch is reviewed for security reasons. <br>
		<a href="#" onclick="stConfirm('Are you sure you want to remove {$request.p_given_name} {$request.p_family_name} from your profile?','profile.php?addperson=null');">Remove from Profile</a><br />
	</td>
{else}
	<td colspan="2"><a href="simple.php">Start your own family tree</a></td>
{/if}
</tr>
<tr><td colspan="3" align="center"><h3>Required</h3></td></tr>
<tr>
	<th>Email address:</th>
	<td><input type="text" name="email" size="25" value="{$request.email}"></td>
	<td>Used to login to your account and <br />to receive a new password if you lose yours</td>
</tr>
{if $request.email_confirmed==0}
<tr>
	<th>Email unconfirmed:</th>
	<td><a href="javascript:popUp('profile.php?send_confirmation=1')">Resend Confirmation Email</a></td>
	<td>You have not confirmed your email yet. You are not eligible to receive important email messages from SharedTree until you confirm your email address.</td>
</tr>
{/if}
<tr>
	<th>First name:</th>
	<td><input type="text" name="given_name" size="15" value="{$request.given_name}"></td>
	<td>Your first given name or nickname</td>
</tr>
<tr>
	<th>Last name:</th>
	<td><input type="text" name="family_name" size="15" value="{$request.family_name}"></td>
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
	<td><input type="text" name="username" size="30" value="{$request.username}"></td>
	<td>A simple username used when editing the Wiki or posting messages to the User Forums.</td>
</tr>
<tr>
	<th>Address line 1:</th>
	<td><input type="text" name="address_line1" size="30" value="{$request.address_line1}"></td>
	<td>Your street address</td>
</tr>
<tr>
	<th>Address line 2:</th>
	<td><input type="text" name="address_line2" size="30" value="{$request.address_line2}"></td>
	<td>An optional second address line such as Apartment or Suite</td>
</tr>
<tr>
	<th>City:</th>
	<td><input type="text" name="city" size="25" value="{$request.city}"></td>
	<td>The city in which you reside</td>
</tr>
<tr>
	<th>State/Province:</th>
	<td><input type="text" name="state_code" size="10" value="{$request.state_code}"></td>
	<td>The state in which you reside</td>
</tr>
<tr>
	<th>Postal code:</th>
	<td><input type="text" name="postal_code" size="10" value="{$request.postal_code}"></td>
	<td>Your zip code</td>
</tr>
<tr>
	<th>Country:</th>
	<td>{html_options name="country_id" options=$countries selected=$request.country_id}</td>
	<td>The country you live in.</td>
</tr>
<tr>
	<th>Language:</th>
	<td>{html_options name="language" options=$languages selected=$request.language}</td>
	<td>Your native language (eventually SharedTree will support other languages)</td>
</tr>
<tr>
	<th>Who can view my family:</th>
	<td>{html_radios name="restrict_access" options=$restrict_access selected=$request.restrict_access separator='<br />'}</td>
	<td>Do you want to allow all relatives to see living family members or choose which ones can get access?</td>
</tr>
<tr>
	<th>Show LDS data:</th>
	<td>{html_radios name="show_lds" options=$yesno_options selected=$request.show_lds}</td>
	<td>Would you like to see LDS data?</td>
</tr>
<tr>
	<th>Send Messages:</th>
	<td>{html_options name="send_messages" options=$send_messages_options selected=$request.send_messages}</td>
	<td>Where do you want us to send messages from other users?</td>
</tr>
<tr>
	<th>Send Weekly Digest:</th>
	<td>{html_radios name="weekly_email" options=$yesno_options selected=$request.weekly_email}</td>
	<td>IF any changes are made to your family tree by other members, would you like to receive notification on a weekly basis? We recommend you keep this turned on.</td>
</tr>
<tr>
	<th>Personal Description:</th>
	<td><textarea rows="10" cols="30" name="description">{$request.description}</textarea></td>
	<td>Your personal description available for others to see on your profile page.</td>
</tr>
<tr><td colspan="3" align="center"><input type="submit" name="save" value="Save Profile"></td></tr>
</table>
</form>

{include file="footer.tpl"}
