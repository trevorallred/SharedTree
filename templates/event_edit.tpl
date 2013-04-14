{foreach item=gcode from=$gedcomcodes}
	{if ($gcode.default_flag==$defaultevent) && ($show_lds==1 || $event.lds_flag==0) }
		{assign var=i value=$gcode.gedcom_code}
		{assign var=e value=$events.$i}
		
		<a href="#" onclick="toggleLayer('edit_{$i}'); return false;"><img src="img/btn_edit.png" width="16" height="16" border="0" id="ico_{$i}" title="click to expand and collapse"/></a> 
		<label><a href="#" onclick="toggleLayer('edit_{$i}'); return false;" title="click to expand and collapse">{$gcode.prompt}</a> <font size=1>click to expand or collapse</font></label>
		{if $e.event_date}
			{$e.date_approx} {$e.event_date}{if $e.ad == '0'} B.C.{/if}
		{else}
			{if $e.age_at_event}Age: {$e.age_at_event}{/if}
		{/if} {$e.status}
		{if $e.temple}in {$e.temple}{else}{if $e.location}in {$e.location}{/if}{/if}
		<div id="edit_{$i}" class="editEvent">
		<input type="hidden" name="{$eventtype}[e][{$i}][event_id]" value="{$e.event_id}">
	<table class="editPerson" width="100%">
		<tr>
			<th>Date:</th>
			<td>
			{if $e.date_text}{$e.date_text}<br />{/if}
			<input type="text" class="textfield" name="{$eventtype}[e][{$i}][event_date]" value="{$e.event_date}"><br />
			{if $e.ad == ''}
				{assign var=ad_option value=1}
			{else}
				{assign var=ad_option value=$e.ad}
			{/if}
			{html_radios name="person[e][$i][ad]" options=$adbc selected=$ad_option }</td>
			<td width="150">Format<br> 01 JUL 1901 or <br>JUL 1901 or <br>1901</td>
		</tr>

		{if $gcode.lds_flag==1}
		<tr>
			<th>Status:</th>
			<td><input type="text" class="textfield" name="{$eventtype}[e][{$i}][status]" value="{$e.status}"></td>
			<td width="150">LDS temple status of this ordinance such as BIC (born in covenant), CHILD, STILLBORN, SUBMITTED, or DONTDOIT</td>
		</tr>
		<tr>
			<th>Temple:</th>
			<td><input type="text" class="textfield" name="{$eventtype}[e][{$i}][temple_code]" value="{$e.temple_code}"></td>
			<td width="150">The LDS temple code for this ordinance</td>
		</tr>
	{else}
		<tr>
			<th>Date Approximation:</th>
			<td>{html_options name="person[e][$i][date_approx]" options=$date_approx selected=$e.date_approx}</td>
			<td width="150"></td>
		</tr>
		{if $i <> "BIRT"}
		<tr>
			<th>Age:</th>
			<td><input type="text" class="textfield" name="{$eventtype}[e][{$i}][age_at_event]" value="{$e.age_at_event}" size="4"></td>
			<td width="150">The age the event occurred. This is only used if the date is unknown.</td>
		</tr>
		{/if}
	{/if}
		<tr>
			<th>Location:</th>
			<td><input type="text" class="textfield" id="location{$i}" name="{$eventtype}[e][{$i}][location]" value="{$e.location}">
<script type="text/javascript">
        new AutoComplete('location{$i}', '/suggest.php?field=location&value=');
</script>
{if $e.location_id > 0}
				<a href="locations.php?location_id={$e.location_id}" target="_blank">Browse</a>{/if}
</td>
			<td width="150">The name of the place in which this event occurred. Format - <i>City, County, State</i>. Read <a href="/w/Location_Naming_Conventions" target="_BLANK">Location Naming Conventions</a> for more information.</td>
		</tr>
		<tr>
			<th>Source:</th>
			<td><textarea name="{$eventtype}[e][{$i}][source]" rows="3">{$e.source}</textarea></td>
			<td width="150">The primary or secondary source for this information.</td>
		</tr>
		<tr>
			<th>Notes:</th>
			<td><textarea name="{$eventtype}[e][{$i}][notes]" rows="3">{$e.notes}</textarea></td>
			<td width="150">General notes about this event.</td>
		</tr>
	</table>
		</div>
		<br />
	{/if}
{/foreach}
