<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SharedTree Mailer</title>
<style>
{literal}
body { background: #FDFDFD;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small; }
a { color: #39f; }
{/literal}
</style>
</head>
<body>
<a href="http://www.sharedtree.com/"><img src="http://www.sharedtree.com/img/stree_logo_small.png" width="150" height="133" alt="SharedTree Logo" align="right" border="0" /></a>
<br><br><br><br>
<h4 style="color: #160; font-size: 14">Dear {$user.given_name},</h4>

<p>You have changes to your family tree!</p>

{if $relatives}
<h4 style="color: #160; font-size: 14">Please welcome your new family member(s) to SharedTree</h4>
<ul>
{foreach item=relative from=$relatives}
	<li><a href="http://www.sharedtree.com/profile.php?user_id={$relative.user_id}" title="{$relative.trace}">{$relative.given_name} {$relative.family_name}</a>
		{$relative.email} {$relative.city} {$relative.state_code}</li>
{/foreach}
</ul>
{/if}

{if $people}
<h4 style="color: #160; font-size: 14">Changes that others have made to your SharedTree</h4>
<ul>
{foreach item=person from=$people}
	<li><a href="http://www.sharedtree.com/person/{$person.person_id}" title="{$person.trace}">{$person.given_name} {$person.family_name} ({$person.birth_year})</a> </li>
{/foreach}
<p style="font-size: 10px; color: #555">* Changes may include new genealogy records, changes to existing individuals, new posts, edited biographies, newly uploaded photos, or edited events.</p>
</ul>
{/if}

<h4 style="color: #160; font-size: 14">Other Helpful Links</h4>
<p class="links">
<a href="http://www.sharedtree.com/login.php?email={$user.email}">Login to SharedTree</a> as {$user.email}<br />
<a href="http://www.sharedtree.com/search.php">Search SharedTree</a><br />
<a href="http://www.sharedtree.com/profile.php">Edit Profile and Preferences</a><br />
</p>

<p style="font-size: 10px; color: #555">
Email based on changes to your family tree on SharedTree.com since {$since_date|date_format}.<br />
Please send feedback to <a href="mailto:trevorallred@gmail.com">trevorallred@gmail.com</a>.<br />
If you would like to unsubscribe to this notification, then please send an email to <a href="mailto:mailer@sharedtree.com">mailer@sharedtree.com</a>.
</p>

</BODY>
</HTML>
