{include file="header.tpl" title="Users"}

<h2>SharedTree Users</h2>
<form method="GET" action="user.php">
<table class="search">
<tr><td class="search" align="left">
<table border="0">
<tr><td>Last name:</td>
	<td><input type="text" name="family_name" value="{$request.family_name|escape:'html'}" class="textfield"></td>
<tr><td>Given name:</td>
	<td><input type="text" name="given_name" value="{$request.given_name|escape:'html'}" class="textfield"></td>
</tr>
<tr><td>Username:</td>
	<td><input type="text" name="username" value="{$request.username|escape:'html'}" class="textfield"></td>
</tr>
<tr><td>Sort by:</td>
	<td><select name="sortby" class="textfield">
		<option value="creation_date">Registration date</option>
		<option value="family_name"{if $request.sortby == "family_name"} selected{/if}>Last name</option>
		<option value="given_name"{if $request.sortby == "given_name"} selected{/if}>First name</option>
		</select></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="search" value="Search"></td>
</table>
</td></tr></table>
</form>

<table class="grid">
<tr>
	<th>Last</th>
	<th>First</th>
	<th>Username</th>
	<th>From</th>
	<th>Member since</th>
	<th>Last visit</th>
	<th>Page views</th>
	<th>Family tree</th>
</tr>
	{foreach item=user from=$users}
<tr><td><a href="profile.php?user_id={$user.user_id}">{$user.family_name}</a></td>
	<td><a href="profile.php?user_id={$user.user_id}">{$user.given_name}</a></td>
	<td>{$user.username}</td>
	<td>{$user.city}{if $user.city && $user.state_code}, {/if}{$user.state_code}</td>
	<td>{$user.creation_date|date_format}</td>
	<td>{$user.last_visit_date|date_format}</td>
	<td>{$user.visits}</td>
	<td>{$user.line_update_date|date_format}</td>
</tr>
	{/foreach}
</table>

{if $pages > 1}
	Page:
	{section name=page start=1 loop=$pages+1}
	{if $smarty.section.page.index != $request.page}
		<a href="user.php?page={$smarty.section.page.index}&family_name={$request.family_name|escape:'url'}&given_name={$request.given_name|escape:'url'}&sortby={$request.sortby}">{$smarty.section.page.index}</a>
	{else}
		{$smarty.section.page.index}
	{/if}
	{/section}
{/if}

{include file="footer.tpl"}
