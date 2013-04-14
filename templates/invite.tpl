{include file="header.tpl" title="Invite Relatives"}
<br clear="all" />
<br />

<h1>Invite Relatives to SharedTree</h1>

<a href="/wiki/index.php?title=Invite_Relatives#How_do_I_invite_my_non-related_friends.3F">How do I invite my friends?</a>

{if $list}
	<table class="portal">
	<tr><td>
	<form method="post">
	<ul>
	{foreach item=person from=$list}
	<input type="hidden" name="name[{$person.person_id}]" value="{$person.name}" />
	<input type="hidden" name="email[{$person.person_id}]" value="{$person.email}" />
		<li>{$person.name} &lt;{$person.email}&gt;</li>
	{/foreach}
	</ul>
	</td></tr>
	<tr><td>
	Send the following email to the above individuals. You may edit the contents before clicking send.<br /><br />
	Subject: <input type="text" name="subject" value="SharedTree Invitation" /><br />
	<textarea cols="70" rows="10" class="email" name="message">PERSONS_NAME

Here is an invitation to SharedTree. By joining this free genealogy service you automatically get access to our family's genealogy and have the ability to contribute yourself. Use the link below to register. This secret link will allow you to access your own genealogy record instantly.

{$domain_name}register.php?person_id=PERSON_ID&key=SECRETKEY

{$user.given_name} {$user.family_name}</textarea>
	<br />
	<input type="submit" name="send" value="Send Email(s)" />
	</td></tr>
	</form>
	</table>
{else}
	{if $sent}
		<table class="portal">
		<tr><td>
		You sent invitations to the following:
		<ul>
		{foreach item=person from=$sent}
			<li>{$person.name} &lt;{$person.email}&gt;</li>
		{/foreach}
		</ul>
		</td></tr></table>
	{/if}

	{if $relatives}
	<form method="post">
	<input type="submit" name="invite" value="Invite Relatives" />
	<table class="grid">
		<tr>
			<td class="label">Name</td>
			<td class="label">Email</td>
		</tr>
		{foreach item=result from=$relatives}
			<tr>
				<td><input type="hidden" name="name[{$result.person_id}]" value="{$result.given_name} {$result.family_name}"/>
				<a href="family.php?person_id={$result.person_id}">{$result.given_name} {$result.family_name}</a></td>
				<td><input type="text" name="email[{$result.person_id}]" size="30" /></td>
			</tr>
		{/foreach}
	</table>
	<input type="submit" name="invite" value="Invite Relatives" />
	</form>
	{else}
	<table class="portal">
		<tr>
			<td colspan="2" width="400">
			You have no living relatives listed in SharedTree yet. Please add those relatives, rebuild your family tree, and try again. Click Help for more information.
			</td>
		</tr>
	</table>
	{/if}
{/if}

{include file="footer.tpl"}
