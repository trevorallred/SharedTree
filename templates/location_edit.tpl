{include file="header.tpl" title="Locations: Edit" includejs=1}

<h2><a href="locations.php">All</a>: 
{if $location.parent_id > 0}<a href="locations.php?location_id={$location.parent_id}">{$location.parent_name}</a>:{/if} 
{$location.display_name}</h2>
[<a href="locations.php?action=match">Match Event Locations</a>]
<table class="portal">
<tr><td>
<form method="POST">
<input type="hidden" name="action" value="save">
<input type="hidden" name="location[location_id]" value="{$location.location_id}">
Move inside: {html_options name="location[parent_id]" options=$parent_locations selected=$location.parent_id}<br>
Location type: {html_radios name="location[location_type]" options=$types selected=$location.location_type}<br>
Display text: <input type="text" name="location[display_name]" value="{$location.display_name}" size="50" maxlength="255"><br>
Description: <input type="text" name="location[description]" value="{$location.description}" size="50" maxlength="255"><br>
<br>
Various spellings:
<ul>
{foreach item=spell from=$spellings}
	{if $location.location_name == $spell}<li>{$spell}</li>{/if}
{/foreach}
{foreach item=spell from=$spellings}
	{if $location.location_name != $spell}
		<li>{$spell} 
			<a href="locations.php?action=save&location[location_id]={$location.location_id}&location[location_name]={$spell}">Make Primary</a> 
			<a href="locations.php?location_id={$location.location_id}&action=delete_spelling&spelling={$spell}">Remove</a>
		</li>
	{/if}
{/foreach}
	<li>Add new: <input type="text" name="new_spelling" value="{$new_spelling}"><br>
</ul>

<input type="submit" name="save" value="Save"><br>
</form>

<br>
<br>
{foreach item=year from=$event_years name=foo}
	<a href="locations.php?location_id={$location.location_id}&year={$year.event_year}">{$year.event_year}</a><font size="1" color="#999">({$year.total})</font>
	{if $smarty.foreach.foo.index % 10 == 9}<br />{/if}
{/foreach}

{if $events}
<ul>
{foreach item=event from=$events}
	<li>{$event.event_type} {$event.event_date} {if $event.ad == 0} B.C.{/if}
	{if $event.table_type == "F"}
		<a href="marriage.php?family_id={$event.key_id}">{$event.hgiven_name} {$event.hfamily_name} &amp; {$event.wgiven_name} {$event.wfamily_name}</a></li>
	{else}
		<a href="/person/{$event.key_id}">{$event.given_name} {$event.family_name}</a>
	{/if}
	in {$event.location}
	</li>
{/foreach}
</ul>
<br>
<br>
{/if}

<br>
<br>
<br>
<a href="locations.php?location_id={$location.location_id}&action=delete">Permanently Delete {$location.display_name}</a>
</td>
<td>
<p>
<b>Locations within {$location.location_name}:</b>
<br>
{foreach item=child from=$children}
	<a href="locations.php?location_id={$child.location_id}">{$child.type_meaning} - {$child.location_name}</a><br />
{/foreach}
<br>
<a href="locations.php?action=add&parent_id={$location.location_id}">Add new sub location</a><br />
</p>

{if $similar}
<p>
Locations with similar spellings:<br>
{foreach item=loc from=$similar}
	<a href="locations.php?location_id={$loc.location_id}">{$loc.type_meaning} - {$loc.location_name}</a><br />
{/foreach}
</p>
{/if}
</td></tr>
</table>

{include file="footer.tpl"}
