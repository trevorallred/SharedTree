{include file="header.tpl" title="Post message" background="edit" includejs="1"}

<h2>Post Message</h2>

<table class="portal">
<tr><td>
<form method="POST" action="/person/{$person_id}">
<input type="hidden" name="action" value="discussedit">
<input type="hidden" name="dpost[post_id]" value="{$dpost.post_id}">
<input type="hidden" name="dpost[person_id]" value="{$dpost.person_id}">
<label>Message Type:</label> {html_options options=$dtypes selected=$dpost.post_type name="dpost[post_type]"}<br />
<label>Message:</label><br />
<textarea name="dpost[post_text]" cols="60" rows="10">{$dpost.post_text}</textarea><br />
{if !$dpost.post_id}
	<label><input type="checkbox" name="post_message" value="1" checked>Post to message to website</label> (recommended)<br />
{else}
	<input type="hidden" name="post_message" value="1">
{/if}
<br />
{if $user.email_confirmed}
<fieldset>
<legend>Send Email:</legend><br />
&nbsp;&nbsp;<label>From:</label> <font face="Courier">{$user.email}</font><br />
&nbsp;&nbsp;<label>To:</label>
	<select name="email">
		<option value="A">All contributors (recommended)</option>
		<option value="R">Relatives only</option>
		<option value="S">Submitter only</option>
		<option value="" {if $dpost.post_id}SELECTED{/if}>No email</option>
	</select><br />
<br />
<div id="contrib_link">
	<a href="#" onclick="$('contrib_list').show(); $('contrib_link').hide(); return false;">Show user list</a>
</div>
<div id="contrib_list" style="display: none">
	<a href="#" onclick="$('contrib_link').show(); $('contrib_list').hide(); return false;">Hide list</a><hr />
<ul>
{foreach from=$users item=u}
	<li><a href="/user.php?id={$u.user_id}">{$u.given_name} {$u.family_name}</a></li>
{/foreach}
</ul>
</div>
</fieldset>
{else}
<font color="red">Your email address <a href="/profile.php">has not been confirmed</a>! <br />You cannot send emails to other users.</font>
{/if}
<label><input type="checkbox" name="watch" value="1" checked>Watch for changes to this individual</label><br />
<br />
<input type="submit" name="save" value="Post Message">
</form>

</td></tr>
</table>

<table class="portal" width="400">
<tr><td>
<h2 style="text-align: center">Help</h2>

<h4>Why would I not want to post the message to the website?</h4>
In some cases, you need to send a time sensitive message to only certain users and that will not be relevant to others in the future. This keeps the person's genealogical record cleaner. However, in most cases you should post your message for others to read in the future. Questions should almost always be posted rather than just emailed.<br />

<h4>Which contributors are included in the email?</h4>
When you select the "All contributors" option, SharedTree sends an email addressed <b>from you</b> to any user who:
<ul>
<li>has made changes to the individual</li>
<li>has submitted photos for this individual</li>
<li>is watching this individual</li>
<li>previously posted a message</li>
<li>or is related to this individual</li>
</ul>
<i>Any one of the users above may chose to opt-out of these posts in the future.</i>

</td></tr>
</table>


{include file="footer.tpl"}
