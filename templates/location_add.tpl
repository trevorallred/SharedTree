{include file="header.tpl" title="Locations: Add"}

<h2><a href="locations.php">All</a>: 
{if $parent.location_id}<a href="locations.php?location_id={$parent.location_id}">{$parent.display_name}</a>: {/if}
Add New</h2>
<table class="portal">
<tr><td>
<form method="POST">
<input type="hidden" name="action" value="save">
<input type="hidden" name="location[parent_id]" value="{$parent.location_id}">
<label>Location type:</label> {html_radios name="location[location_type]" options=$types selected=$location.location_type}<br>
<label>Location name:</label> <input type="text" name="location[location_name]" value="{$location.location_name}"><br>
<label>Display text:</label> <input type="text" name="location[display_name]" value="{$location.display_name}" size="50" maxlength="255"><br>
<label>Description:</label> <input type="text" name="location[description]" value="{$location.description}" size="50" maxlength="255"><br>
<br>

<input type="submit" name="save" value="Add"><br>
</form>
</td></tr></table>

{include file="footer.tpl"}
