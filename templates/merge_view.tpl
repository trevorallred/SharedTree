{if $ajax!=1}
	{include file="header.tpl" title="Merge Individuals" includejs=1}
	<h3>Merge Individuals</h3>
	<b><i>If in doubt, don't merge!! Let someone else with more experience and information merge the records</i></b><br><br>
	
	<a href="merge.php?action=list">Show List</a>
{/if}

{literal}
<script type="text/javascript">
function checkHistory(p1, p2, table, field) {
        var pars = 'action=ajax&person_id='+p1+'&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history1_'+table+field, '../ajax_fieldchanges.php', {method: 'get', parameters: pars});

        var pars = 'action=ajax&person_id='+p2+'&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history2_'+table+field, '../ajax_fieldchanges.php', {method: 'get', parameters: pars});
}
</script>
{/literal}
<form action="merge.php" method="POST">
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
{include file="merge_piece.tpl" prompt="Merge Rank" var="merge_rank" info="true" }
{include file="merge_piece.tpl" prompt="Family name" var="family_name" }
{include file="merge_piece.tpl" prompt="Given name" var="given_name" }
{include file="merge_piece.tpl" prompt="Nickname" var="nickname" }
{include file="merge_piece.tpl" prompt="Gender" var="gender" }
{include file="merge_piece.tpl" prompt="Title" var="title" }
{include file="merge_piece.tpl" prompt="Parents" var="bio_family_id" }
{include file="merge_piece.tpl" prompt="Biography" var="wiki_text" }
{include file="merge_piece.tpl" prompt="Child Order" var="child_order" }
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
		<td colspan="6" align="center">{$gcode.prompt}</td>
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
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td colspan="6" align="center">Audit Information</td>
	</tr>
	{include file="merge_piece.tpl" prompt="Created by" var="created_user" info="true" }
	{include file="merge_piece.tpl" prompt="Created on" var="creation_date" info="true" }
	{include file="merge_piece.tpl" prompt="Updated by" var="updated_user" info="true" }
	{include file="merge_piece.tpl" prompt="Updated on" var="update_date" info="true" }
</table>
<p>
All spouses, children, and discussion messages will transfer 
from <a href="/person/{$p2.person_id}">{$p2.person_id}</a> 
to <a href="/person/{$p1.person_id}">{$p1.person_id}</a>.<br>
Optional notes about merge (posted to {$p1.person_id}): <br>
<input type="textbox" name="message" size="80">

{if $forumposts}
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

<p align="center">
{if $ajax==1}
	<input type="hidden" name="next" value="return">
	<a name="submit" />
	<input type="submit" name="save" value="Merge" style="font-size: 18px; width: 200px;">
{else}
	<label>After merge:</label>
	<input type="radio" name="next" id="next1" value="next" checked><label for="next1">show the next match</label>
	{if $returnto}<input type="radio" name="next" id="next2" value="return" checked><label for="next2">return to previous page</label>{/if}
	<input type="radio" name="next" id="next3" value="show"><label for="next3">show merged individual</label>
	<input type="radio" name="next" id="next4" value="list"><label for="next4">return to list</label>
	<br />
	<input type="submit" name="reject" value="Reject" style="font-size: 16px; width: 150px; color: #500;">
	<input type="submit" name="save" value="Merge" style="font-size: 16px; width: 150px; color: #050; font-weight: bold;">
{/if}
</p>
</form>

{if $ajax!=1}
<a href="merge_project.php?action=saveproject&p1={$p1.person_id}&p2={$p2.person_id}">Begin New Merge Project (beta)</a>
{include file="footer.tpl"}
{/if}
