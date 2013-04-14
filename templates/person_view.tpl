{include file="header.tpl" title=$title includejs=1}
{literal}
<style>
.eventnote {
	display: none;
}
</style>
<script type="text/javascript">
function showNote(whichLayer) {
	var layer = document.getElementById(whichLayer).style;
	layer.display = "block";
}
function hideNote(whichLayer) {
	var layer = document.getElementById(whichLayer).style;
	layer.display = "none";
}
</script>
{/literal}
<h2>{$title}</h2>

<table class="portal">
<tr align="left">
<td width="350">
<div class="errors" align="center">
{if $person.merged_into}
	<a href="{$person.merged_into}">Merged into Individual {$person.merged_into}</a> <br>at {$person.update_date}<br /><br />
	{if $person.merge_id}
		<a href="#" onclick="stConfirm('You cannot undo this action! \nAre you sure you want to undo this merge?', '/merge.php?undo={$person.merge_id}&returnto=family.php%3fperson_id%3D{$person_id}');">Undo Merge</a> <br /><br />
	{/if}
{else}
{if $person.delete_date}
	<a href="/person_history.php?person_id={$person_id}">Deleted on {$person.delete_date}</a> <br /><br />
	<a href="#" onclick="stConfirm('This will not undo changes already made to other records. \nAre you sure you want to add this person back?', '/person_edit.php?person_id={$person_id}&action=restore');">Restore this record</a> <br /><br />
{/if}
{/if}
{if $time}Version: {$time} <a href="/person_edit.php?person_id={$person_id}&time={$time}"><img src="/img/btn_edit.png" title="Revert to this Version" width="16" height="16" border="0" /><br />
<a href="{$person_id}">&lt;&lt; Back to Current Version</a><br />{/if}
{if $show_this_is_you}<a href="/profile.php?addperson={$person_id}">Click here if this is you</a><br />{/if}
{if $person.public_flag == 0}** Private record **<br />{/if}
</div>
{if $invite == 1}
<form method="post" action="../invite.php">
	<input type="hidden" name="name[{$person.person_id}]" value="{$person.given_name} {$person.family_name}"/>
	<b>Email:</b> <input type="text" name="email[{$person.person_id}]" size="30" />
	<input type="submit" name="invite" value="Invite" />
</form>
{/if}

<label>Last name:</label> {if $person.orig_family_name}{$person.orig_family_name} <span title="translation" style="cursor: help">({$person.family_name})</span>{else}{$person.family_name}{/if}<br>
<label>Given name:</label> {if $person.orig_given_name}{$person.orig_given_name} <span title="translation" style="cursor: help">({$person.given_name})</span>{else}{$person.given_name}{/if}<br>
<label>Gender:</label>
{if $person.gender=="M"}Male{/if}
{if $person.gender=="F"}Female{/if}
{if $person.gender=="U"}Unknown{/if}<br>
{if $person.prefix}<label>Prefix:</label> {$person.prefix}<br>{/if}
{if $person.suffix}<label>Suffix:</label> {$person.suffix}<br>{/if}
{if $person.nickname}<label>Nickname:</label> {$person.nickname}<br>{/if}
{if $person.title}<label>Title\Royalty:</label> {$person.title}<br>{/if}
{if $person.afn}<label>AFN:</label> {$person.afn}<br>{/if}
{if $person.national_id}<label>SSN or Nat'l ID:</label> {$person.national_id}<br>{/if}
{if $person.national_origin}<label>Nationality/Origin:</label> {$person.national_origin}<br>{/if}
{if $person.occupation}<label>Occupation:</label> {$person.occupation}<br>{/if}

{if $person.age > 0}<label>Age:</label> {$person.age}<br>{/if}
{if $person.ancestor_count > 0}<label>Ancestors:</label> {$person.ancestor_count}<br>{/if}
{if $person.descendant_count > 0}<label>Descendants:</label> {$person.descendant_count}<br>{/if}
{if $person.trace_meaning}<label>Relation to You:</label> {$person.trace_meaning}<br>{/if}

<br clear="all" />
{if $person.e}
<h3 align="center">Events</h3>
<table class="grid" width="100%">
{foreach item=event from=$person.e}
	{if $show_lds==1 || $event.lds_flag==0}
	{assign var="eid" value=$event.event_id}
	<tr onmouseover="showNote('note{$event.event_id}');" onmouseout="hideNote('note{$event.event_id}');" id="row{$event.event_id}')">
		<th>{$event.prompt}:</th>
		<td>
		{if $is_logged_on}<a href="#" onclick="stConfirm('Are you sure you want to delete this {$event.prompt}?', '/person_edit.php?person_id={$person_id}&event_id={$event.event_id}&action=deleteevent');"><img src="/img/btn_drop.png" title="Remove {$event.prompt}" border="0" height="16" width="16" align="right"></a>{/if}
		{$event.date_approx}
		{if $event.event_date}
			{$event.event_date}{if $event.ad == '0'} B.C.{/if}
		{else}
			{if $event.date_text}Unreadable date: <i>{$event.date_text}</i><br>{/if}
			{if $event.eyear && $event.event_type=="BIRT"}
				{$event.eyear} *Computer estimated
			{/if}
		{/if} {$event.status}
		{if $event.age_at_event}Age: {$event.age_at_event}{/if}
		<br />{$event.location|escape} {$event.temple_code}

		<div id="note{$event.event_id}" class="eventnote">
		{if $photos.$eid.image_id > 0}<a href="/image.php?image_id={$photos.$eid.image_id}&action=summary"><img src="/image.php?image_id={$photos.$eid.image_id}&size=thumb" border="0" align="right" width="25" height="25"></a>{/if}
		<br />Source image: {$event.source|escape}  {if !$photos.$eid.image_id && $is_logged_on}<a href="/image.php?action=edit&data[person_id]={$person_id}&data[event_id]={$eid}">Upload</a>{/if}
		
		{if $event.notes}<br />Notes: {$event.notes|escape}{/if}
		</div>
		</td>
	</tr>
	{/if}
{/foreach}
</table>
{/if}

{if $is_logged_on && $duplicates}
<h3>Potential Duplicate(s):</h3>
	<ul>
	{foreach item=dupe from=$duplicates}
		<li><a href="/merge.php?p1={$person.person_id}&p2={$dupe.person_id}&returnto=person/{$person.person_id}">Merge</a> | <a href="{$dupe.person_id}">View</a> | {$dupe.person_id} - {$dupe.given_name} {$dupe.family_name}</li>
	{/foreach}
	</ul>
{/if}
<p align="center">
<form method=post action="http://wc.rootsweb.com/cgi-bin/igm.cgi" target="_BLANK">
<a href="../search.php?given_name={$person.given_name|escape:'url'}&family_name={$person.family_name|escape:'url'}&birth_year={$person.birth_year}&range=5&adbc=1&sort=birth&search=Search">Search SharedTree</a>
<input type=hidden name=op value="Search">
<input type=hidden name=includedb value="">
<input type=hidden name=lang value="en">
<input type=hidden name=ti value="">

<input type=hidden name=surname value="{$person.family_name|escape}">
<input type=hidden name=stype value="Exact">
<input type=hidden name=given value="{$person.given_name|escape}">
<input type=hidden name=byear value="{$person.birth_year}">
<input type=hidden name=brange value="1">
<input type=hidden name=period value="All">
<input type=submit name=submit.x value="Search Rootsweb">
</form>
</p>

<p align="center" style="color: #555; font-size: 10px;">Updated by: 
<a href="/profile.php?user_id={$person.any_updated_by}">{$person.any_updated_name}</a> at 
<a href="/person_history.php?person_id={$person_id}">{$person.any_update_date}</a></p>
{if $is_logged_on && !$person.delete_date}
<p align="center">
	<a href="/person_edit.php?person_id={$person_id}&return_to=/person/{$person_id}"><img src="/img/btn_edit.png" border="0" /></a><a href="/person_edit.php?person_id={$person_id}&return_to=/person/{$person_id}">Edit</a>
	&nbsp;&nbsp;
	<a href="#" onclick="stConfirm('Are you sure you want to delete this person?', '/person_edit.php?person_id={$person_id}&action=delete');"><img src="/img/btn_drop.png" border="0" /></a><a href="#" onclick="stConfirm('Are you sure you want to delete this person?', '/person_edit.php?person_id={$person_id}&action=delete');">Delete</a>
</p>
{/if}
</td>
<td>
<table>
<tr><td>Childhood</td>
	<td>Adulthood</td>
	<td>Later Years</td>
</tr>
<tr><td>
{if $photos.P1.image_id > 0}
	<a href="/image.php?image_id={$photos.P1.image_id}&action=summary"><img src="/image.php?image_id={$photos.P1.image_id}&size=thumb" border="0"></a>
{else}
	{if $is_logged_on}
		<a href="/image.php?action=edit&data[person_id]={$person_id}&data[image_order]=1">Add Photo</a>
	{else}no photo
	{/if}
{/if}
	</td>
	<td>
{if $photos.P2.image_id > 0}
	<a href="/image.php?image_id={$photos.P2.image_id}&action=summary"><img src="/image.php?image_id={$photos.P2.image_id}&size=thumb" border="0"></a>
{else}
	{if $is_logged_on}
		<a href="/image.php?action=edit&data[person_id]={$person_id}&data[image_order]=2">Add Photo</a>
	{else}no photo
	{/if}
{/if}
	</td>
	<td>
{if $photos.P3.image_id > 0}
	<a href="/image.php?image_id={$photos.P3.image_id}&action=summary"><img src="/image.php?image_id={$photos.P3.image_id}&size=thumb" border="0"></a>
{else}
	{if $is_logged_on}
		<a href="/image.php?action=edit&data[person_id]={$person_id}&data[image_order]=3">Add Photo</a>
	{else}no photo
	{/if}
{/if}
	</td>
</tr>
</table>
	<h2>Family</h2>
<label>Father:</label> 
{if $father.protected==1}
	{$father.given_name}
{else}
	<a href="{$father.person_id}">{$father.full_name}</a>
	({include file="birth_year.tpl" birth_year=$father.birth_year birth_date=$father.e.BIRT.event_year} - {$father.e.DEAT.event_year}) <font size="1">{$father.trace_meaning}</font>
{/if}<br />
<label>Mother:</label>
{if $mother.protected==1}
	{$mother.given_name}
{else}
	<a href="{$mother.person_id}">{$mother.full_name}</a>
	({include file="birth_year.tpl" birth_year=$mother.birth_year birth_date=$mother.e.BIRT.event_year} - {$mother.e.DEAT.event_year}) <font size="1">{$mother.trace_meaning}</font>
{/if}<br />
{if $siblings}
	&nbsp;<label>Siblings:</label>
{foreach item=child from=$siblings name=sibling}
	{if $child.person_id <> $person.person_id}
		{if $child.protected==1}
			{$child.full_name}{else}
			<a href="{$child.person_id}">{$child.given_name}</a>{/if}{if !$smarty.foreach.sibling.last},
			{if $smarty.foreach.sibling.index % 5 == 4}<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}
		{/if}
	{/if}
{/foreach}<br />
{/if}
{foreach item=marriage from=$marriages}
	&nbsp;<label>Spouse:</label> <a href="{$marriage.person_id}">{$marriage.full_name}</a> <a href="/marriage.php?family_id={$marriage.family_id}"><img src="/img/ico_family.gif" width="14" height="15" border="0" title="View Marriage"></a> ({include file="birth_year.tpl" birth_year=$marriage.birth_year birth_date=$marriage.b_date}) <font size="1">{$marriage.trace_meaning}</font><br>
	{foreach item=child from=$marriage.children}
	&nbsp;&nbsp;&nbsp;&nbsp;<label>Child:</label> {if $child.protected==1}{$child.full_name}{else}<a href="{$child.person_id}">{$child.given_name}</a> ({include file="birth_year.tpl" birth_year=$child.birth_year birth_date=$child.b_date}) <font size="1">{$child.trace_meaning}</font>{/if}<br>
	{/foreach}
{/foreach}
	<h2>Biography & History</h2>
<p><label>Groups:</label> 
{foreach item=persgroup from=$groups}
	<a href="/group.php?group_id={$persgroup.group_id}">{$persgroup.group_name}</a> | 
{/foreach}
{if $is_logged_on}<a href="/group.php?person_id={$person_id}&byear={$person.e.BIRT.event_year}&dyear={$person.e.DEAT.event_year}">add new</a>{else}log in to add{/if}
</p>
{if $person.wikipedia}
	<p><label>Wikipedia:</label> 
	<a href="http://en.wikipedia.com/wiki/{$person.wikipedia|strip_tags}">{$person.wikipedia}</a></p>
{/if}

{if $is_logged_on}
<span style="float: right"><a href="{$person_id}?action=wikiedit"><img src="/img/btn_edit.png" title="Edit Biography" width="16" height="16" border="0" /></a></span>
{/if}
{if $wiki.wiki_text}
	{$wiki.wiki_text}
{else}
	{if $is_logged_on}<center><br><br><br><font color="#999999">There is no biography for this individual yet. <br><a href="{$person_id}&action=wikiedit">Click here</a> to start one.</font></center>{/if}
{/if}
</td>
</tr>
<tr align="left">
<td colspan="2">
	<h2>Message Board: <font size="-1">Research, Reunions, and Questions</font></h2>
	<a href="{$person_id}&action=discussedit" title="Send an email or post a message here about this person"><b>Send/Post Message</b></a>
	<table border="0" width="100%">
	{foreach item=message from=$posts}
		<tr class="post_row">
			<td valign="top">
				<b class="user"><a href="/profile.php?user_id={$message.created_by}">{$message.given_name} {$message.family_name}</a></b><br />
				<label>Joined:</label> {$message.join_date|date_format}<br />
				<label>From:</label> {if $message.city || $message.state_code}
							{$message.city}{if $message.city && $message.state_code},{/if} {$message.state_code}
							{else}unknown{/if}
			</td>
			<td>
				<div style="float: right; margin: 5px"><label>Posted:</label> {$message.creation_date}
				{if $message.update_date != $message.creation_date}
					<br /><label>Last changed:</label> {$message.update_date}
				{/if}
				</div> 
				<label>{$message.post_meaning}:</label> {$message.post_text}
				{if $user.user_id eq $message.created_by}
					<br />
					<a href="/person/{$person_id}&action=discussedit&post_id={$message.post_id}">Edit</a>
					<a href="/person/{$person_id}&action=discussdelete&post_id={$message.post_id}">Delete</a>
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr><br><br><br>There are no messages or discussions for this individual<br><br><br>
		</td></tr>
	{/foreach}
	</table>
	<a href="{$person_id}&action=discussedit" title="Send an email or post a message here about this person"><b>Send/Post Message</b></a>
</td>
</tr>
{if $is_logged_on}
<tr>
<td style="background: #DDD;">
{if $person.watch_id > 0}
	<a href="/watch.php?action=unwatch&watch_id={$person.watch_id}">Unwatch</a>
{else}
	<a href="/watch.php?action=save&data[watch_id]={$person.watch_id}&data[person_id]={$person.person_id}&data[bookmark]=0">Watch</a> or 
	<a href="/watch.php?action=save&data[watch_id]={$person.watch_id}&data[person_id]={$person.person_id}&data[bookmark]=1">Bookmark</a>
{/if}
<hr>

<h2>Related Users</h2>
{foreach item=user from=$desc_users}
  <a href="/profile.php?user_id={$user.user_id}">{$user.given_name} {$user.family_name}</a> <font size="1">{$user.trace}</font><br />
{/foreach}
{if $extendTree > 0}<b>Added {$extendTree} related users</b>{/if}
</td>
<td style="background: #DDD;">
<h2>View History</h2>
{foreach item=views from=$listviews}
 {$views.last_update_date} by <a href="/profile.php?user_id={$views.user_id}">{$views.given_name} {$views.family_name}</a><br />
{/foreach}
{$person.page_views} page views
</td>
</tr>
{/if}
</table>

{if !$is_logged_on}
Is this your ancestor? <a href="/register.php">Create an account</a> and make changes today.
{/if}

{include file="footer.tpl"}
