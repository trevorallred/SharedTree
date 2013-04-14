<form action="merge.php" method="POST">
<h4>Prepare for Merge</h4>
<input type="button" onclick="new Effect.SwitchOff($('displayscreen')); new Effect.Appear($('pedigree'));" value="Close" />
<input type="hidden" name="returnto" value="{$returnto}">
<input type="hidden" name="p1" value="{$p1.person_id}">
<input type="hidden" name="p2" value="{$p2.person_id}">
<table class="grid">
<tr>
	<td></td>
	<td></td>
	<td align="center">
	<label title="Merge into this record">Record #{$p1.person_id}</label><br />
	{include file="person_nav.tpl" nav_id=$p1.person_id direction="flat"}
	</td>
	<td></td>
	<td align="center">
	<label title="Delete this record">Record #{$p2.person_id}</label><br />
	{include file="person_nav.tpl" nav_id=$p2.person_id direction="flat"}
	</td>
	<th>Show</th>
</tr>
{include file="merge_piece.tpl" prompt="Family name" var="family_name" }
{include file="merge_piece.tpl" prompt="Given name" var="given_name" }
{include file="merge_piece.tpl" prompt="Nickname" var="nickname" }
{include file="merge_piece.tpl" prompt="Gender" var="gender" }
{include file="merge_piece.tpl" prompt="Title" var="title" }
{include file="merge_piece.tpl" prompt="Biography" var="wiki_text" }
{include file="merge_piece.tpl" prompt="RIN" var="rin" }
{include file="merge_piece.tpl" prompt="AFN" var="afn" }
{include file="merge_piece.tpl" prompt="Nat'l ID" var="national_id" }
{include file="merge_piece.tpl" prompt="Prefix" var="prefix" }
{include file="merge_piece.tpl" prompt="Suffix" var="suffix" }
{include file="merge_piece.tpl" prompt="Occupation" var="occupation" }
{include file="merge_piece.tpl" prompt="Wikipedia" var="wikipedia" }

{foreach item=gcode from=$gedcomcodes}
	{assign var=i value=$gcode.gedcom_code}
	{assign var=e1 value=$p1.e.$i}
	{assign var=e2 value=$p2.e.$i}
	
	{if $e1.event_id > 0 || $e2.event_id > 0}
		<tr>
		<td colspan="5" align="center">{$gcode.prompt}</td>
		</tr>
		{include file="merge_event_piece.tpl" prompt="Date" var="event_date" }
		{include file="merge_event_piece.tpl" prompt="Location" var="location" }
		{include file="merge_event_piece.tpl" prompt="Temple" var="temple_code" }
		{include file="merge_event_piece.tpl" prompt="Status" var="status" }
		{include file="merge_event_piece.tpl" prompt="Aproximation" var="date_approx" }
		{include file="merge_event_piece.tpl" prompt="Age at Event" var="age_at_event" }
		{include file="merge_event_piece.tpl" prompt="Notes" var="notes" }
		{include file="merge_event_piece.tpl" prompt="Source" var="source" }
	{/if}
{/foreach}
	<tr>
	<td>Spouses</td>
	<td></td>
	<td width="250">{$p1.spouses}</td>
	<td></td>
	<td width="250">{$p2.spouses}</td>
	</tr>
	<tr>
	<td colspan="5" align="center">Audit Information</td>
	</tr>
	{include file="merge_piece.tpl" prompt="Created by" var="created_user" info="true" }
	{include file="merge_piece.tpl" prompt="Created on" var="creation_date" info="true" }
	{include file="merge_piece.tpl" prompt="Updated by" var="updated_user" info="true" }
	{include file="merge_piece.tpl" prompt="Updated on" var="update_date" info="true" }
</table>
Optional notes about merge (posted to {$p1.person_id}): <br>
<input type="textbox" name="message" size="80">

{if $forumposts}
All spouses, children, and discussion messages will transfer 
from <a href="/person/{$p2.person_id}">{$p2.person_id}</a> 
to <a href="/person/{$p1.person_id}">{$p1.person_id}</a>.<br>
<table class="grid">
<tr><th>Post date</th>
    <th>Poster name</th>
    <th>Forum post</th>
</tr>
{foreach item=post from=$forumposts}
	<tr><td>{$post.creation_date}</td>
	    <td>{$post.given_name} {$post.family_name}</td>
	    <td>{$post.post_text}</td>
	</tr>
{/foreach}
</table>
{/if}

</p>
</form>

