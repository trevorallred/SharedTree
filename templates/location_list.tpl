{include file="header.tpl" title="Locations: Listing"}

<h2>Country Listings</h2>
<a href="list.php">Family Index</a> | <a href="group.php">Group Index</a><br>

<table class="portal">
<tr><td>
<ul>
{foreach item=location from=$nations}
	<li><a href="locations.php?location_id={$location.location_id}">{$location.location_name}</a></li>
{/foreach}
	<li><a href="locations.php?action=add&location[location_type]=N">Add new country</a></li>
</ul>

<a href="locations.php?action=match">Match existing Event Locations</a>
</td></tr>
</table>

{include file="footer.tpl"}
