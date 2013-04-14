{include file="header.tpl" title="Family Security" help="Users" includejs="1"}

<h1>Privacy Control</h1>
<table class="portal" width="500">
<tr><td colspan="2">
<p>Individuals who are living (or presumed living) are restricted and not viewable by the public.</p>
<p>You are responsible for controlling who can see your genealogy record and those of your family (if any) not already regeistered on SharedTree.</p>
<tr><td width="50%">
<h3>My Family</h3>
The following is a list of close family members not registered on SharedTree.
<ul>
{foreach item=change from=$list}
	<li><a href="/person/{$change.person_id}">{$change.family_name}, {$change.given_name}</a></li>
{/foreach}
</ul>
<i>Note: If you have just recently joined or made major changes to your family tree, some changes may not be reflected above.</i>
</td>
<td width="50%">
<h3>Control</h3>
<form action="profile.php" method="POST">
<input type="radio" name="control" value="1" CHECKED onclick="$('user_list').show(); $('user_choose').hide();"> Let SharedTree control access to my family members (recommended) <br />
<input type="radio" name="control" value="0" onclick="$('user_list').hide(); $('user_choose').show();"> Let me restrict access even further <br />

<div id="user_choose" style="display: none">
<p>Please choose which relatives can access your family tree.</p>
{foreach item=change from=$relatives}

<input type="checkbox" name="chooseuser[{$change.user_id}]" value="1" />
<a href="profile.php?user_id={$change.user_id}">{$change.family_name}, {$change.given_name}</a> {$change.status}<br />
{/foreach}
</div>

<div id="user_list" style="display: block">
<p>The following relatives currently have access to all or part of your family.</p>
{foreach item=change from=$relatives}
<a href="profile.php?user_id={$change.user_id}">{$change.family_name}, {$change.given_name}</a> {$change.status}<br />
{/foreach}
</div>
<input type="submit" name="savesecurity" value="Save" />
</form>
</td>
</tr>
</table>

{include file="footer.tpl"}
