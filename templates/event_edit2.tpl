<br>
<table class="tabber">
<tr>
<td class="tabbernav">
	<ul class="tabbernav">
{foreach item=gcode from=$gedcomcodes}
	{if ($gcode.default_flag==$defaultevent) && ($show_lds==1 || $event.lds_flag==0) }
		{assign var=i value=$gcode.gedcom_code}
		<li onclick="chooseTab('{$i}')" id="tab{$i}">{$gcode.prompt}</li>
	{/if}
{/foreach}
	</ul>
</td>
<td class="tabbercontent">
{foreach item=gcode from=$gedcomcodes}
	{if ($gcode.default_flag==$defaultevent) && ($show_lds==1 || $event.lds_flag==0) }
		{assign var=i value=$gcode.gedcom_code}
		{assign var=e value=$events.$i}
<div class="tabcontent" id="content{$i}">
<h3>{$gcode.prompt}</h3>
{$gcode.description}
		<input type="hidden" name="{$eventtype}[e][{$i}][event_id]" value="{$e.event_id}">
	<table class="editPerson" width="500px">
		<tr>
			<th>Date:</th>
			<td><input type="text" name="{$eventtype}[e][{$i}][event_date]" value="{$e.event_date}"><br />
			{html_radios name="person[e][$i][ad]" options=$adbc selected=$e.ad}</td>
			<td>Ex: 01 JAN 1901 or JAN 1901 or 1901</td>
		</tr>

		{if $gcode.lds_flag==1}
		<tr>
			<th>Status:</th>
			<td><input type="text" name="{$eventtype}[e][{$i}][status]" value="{$e.status}"></td>
			<td width="150">LDS temple status of this ordinance such as BIC (born in covenant)</td>
		</tr>
		<tr>
			<th>Temple:</th>
			<td><input type="text" name="{$eventtype}[e][{$i}][temple_code]" value="{$e.temple_code}"></td>
			<td width="150">The LDS temple code for this ordinance</td>
		</tr>
	{else}
		<tr>
			<th>Approx:</th>
			<td>{html_options name="person[e][$i][date_approx]" options=$date_approx selected=$e.date_approx}</td>
			<td width="150"></td>
		</tr>
		<tr>
			<th>Age:</th>
		{if $i <> "BIRT"}
			<td><input type="text" name="{$eventtype}[e][{$i}][age_at_event]" value="{$e.age_at_event}"></td>
		{else}
			<td>N/A</td>
		{/if}
			<td width="150">The age the event occurred. This is only used if the date is unknown.</td>
		</tr>
	{/if}
		<tr>
			<th>Location:</th>
			<td><input type="text" name="{$eventtype}[e][{$i}][location]" value="{$e.location}">{if $e.location_id > 0}
				<a href="locations.php?location_id={$e.location_id}" target="_blank">Browse</a>{/if}</td>
			<td width="150">City, County, State or <br>City, Province, Country</td>
		</tr>
		<tr>
			<th>Source:</th>
			<td><textarea name="{$eventtype}[e][{$i}][source]">{$e.source}</textarea></td>
			<td width="150">Optional source for event information such as census, book, interview.</td>
		</tr>
		<tr>
			<th>Notes:</th>
			<td><textarea name="{$eventtype}[e][{$i}][notes]">{$e.notes}</textarea></td>
			<td width="150">Optional research notes about event.</td>
		</tr>
	</table>
</div>
	{/if}
{/foreach}
<br><br>
Click a tab on the left to edit events
</td>
</table>
<br>