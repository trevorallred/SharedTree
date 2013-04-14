{include file="header.tpl" title="Family View" includejs=1}
<br  clear="all"/>
<table class="portal" width="700">
<tr>
	<td width="45%">
	<center>
	{if $family.person1_id}
	<h2><a href="/person/{$family.person1_id}">{$family.given_name1} {$family.family_name1}</a></h2>
		{include file="person_nav.tpl" nav_id=$family.person1_id direction="flat"}
	{else}<h2><i>UNKNOWN</i></h2>{/if}
	</center>
	</td>
	<td><h2 align="center">&amp;</h2></td>
	<td width="45%">
	<center>
	{if $family.person2_id}
	<h2><a href="/person/{$family.person2_id}">{$family.given_name2} {$family.family_name2}</a></h2>
		{include file="person_nav.tpl" nav_id=$family.person2_id direction="flat"}
	{else}<h2><i>UNKNOWN</i></h2>{/if}
	</center>
	</td>
</tr>
<tr><td colspan="3">
<div class="errors" align="center">
{if $family.delete_date}
	<a href="/family_history.php?family_id={$family_id}">Deleted on {$family.delete_date}</a> <br /><br />
{else}
	{if $time}Version: {$time} <a href="family_edit.php?family_id={$family_id}&time={$time}"><img src="/img/btn_edit.png" title="Revert to this Version" width="16" height="16" border="0" /><br />
	<a href="/marriage.php?family_id={$family_id}">&lt;&lt; Back to Current Version</a><br />{/if}
{/if}
</div>

<div align="right">
	<a href="/marriage.php?family_id={$family_id}&action=edit">Edit Marriage</a>
	<a href="/marriage.php?family_id={$family_id}&action=edit"><img src="/img/btn_edit.png" title="Edit Family" width="16" height="16" border="0" /></a>
</div>
<label>Status:</label>
{if $family.status_code=="M"}Married{/if}
{if $family.status_code=="N"}Not Married{/if}
{if $family.status_code=="S"}Separated{/if}
{if $family.status_code=="D"}Divorced{/if}
{if $family.status_code=="U"}Unknown{/if}<br>

{foreach item=event from=$family.e}
{if $show_lds==1 || $event.lds_flag==0}
	<label>{$event.prompt}:</label>
	{if $event.event_date}
		{$event.date_approx} {$event.event_date}{if $event.ad == '0'} B.C.{/if}
	{else}
		{if $event.age_at_event}Age: {$event.age_at_event}{/if}
	{/if}
	in {if $event.temple}{$event.temple|escape}{else}{$event.location|escape}{/if} <br />
	{if $event.source}Source: <br />{$event.source|escape}<br />{/if}
	{if $event.notes}Notes: <br />{$event.notes|escape}<br />{/if}
{/if}
{/foreach}

<p>{$family.notes|escape}</p>
</td></tr>
<tr><td colspan="3" class="content">

<h3>Children</h3>
<table class="grid" width="100%">
<tr><th>Navigation</th>
	<th>Ord</th>
	<th>Children's Name</th>
	<th>Sex</th>
	<th>Birth</th>
	<th>Death</th>
	<th>Created</th>
	{if $is_logged_on}
		<th>Remove</th>
	{/if}
</tr>
{foreach item=child from=$children}
	<tr><td>{include file="person_nav.tpl" nav_id=$child.person_id direction="flat"}</td>
		<td>{$child.child_order}</td>
		<td><a href="/person/{$child.person_id}">{$child.given_name|escape} {$child.family_name|escape}</a></td>
		<td>{$child.gender}</td>
		<td>{$child.b_date}<br>{$child.b_location|escape}</td>
		<td>{$child.d_date}<br>{$child.d_location|escape}</td>
		<td><a href="profile.php?user_id={$child.created_by}">{$child.created_by}</a> {$child.creation_date}</td>
	{if $is_logged_on}
		<td><a href="#" onclick="stConfirm('Are you sure you want to remove {$child.given_name} {$child.family_name} from this family?','family.php?person_id={$individual.person_id}&removechild={$child.person_id}');"><img src="/img/btn_drop.png" title="Remove child from family" width="16" height="16" border="0" /></a></td>
	{/if}
	</tr>
{/foreach}
</table>

{if $is_logged_on && !$family.delete_date}
        <p align="center"><a href="#" onclick="stConfirm('Are you sure you want to delete this marriage?', '/marriage.php?family_id={$family_id}&action=delete');">Delete this family record</a></p>
{/if}

</td></tr>
<tr><td colspan="3" style="background: #CCC;">
<h2>Change & Audit History</h2>
{foreach item=change from=$history}
  <a href="/marriage.php?family_id={$family_id}&time={$change.update_date}">{$change.update_date}</a> by <a href="profile.php?user_id={$change.user_id}">{$change.given_name} {$change.family_name}</a><br />
{/foreach}
</td></tr>
</table>

<form method="GET" action="/chart.php" target="_BLANK">
	<input type="hidden" name="family_id" value="{$family.family_id}" />
	<input type="hidden" name="chart" value="familygroup" />
	<input type="submit" value="Create Printable PDF">
</form>

{include file="footer.tpl"}
