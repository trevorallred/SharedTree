{if $indi.e.BIRT}
	<label>Birth: </label>
	{include file="birth_year.tpl" birth_year=$indi.birth_year birth_date=$indi.e.BIRT.event_date}<br />
	<label>Location:</label> {$indi.e.BIRT.location|escape}<br /><br />
{else}
	{if $indi.e.CHR}
		<label>Christening: </label>
		{include file="birth_year.tpl" birth_year=$indi.birth_year birth_date=$indi.e.CHR.event_date}<br />
		<label>Location:</label> {$indi.e.CHR.location|escape}<br /><br />
	{else}
		<label>Birth:</label> ~{$indi.birth_year}
	{/if}
{/if}
{if $indi.e.DEAT}
	<label>Death:</label>
	{$indi.e.DEAT.event_date}{if $indi.e.DEAT.ad == '0'} B.C.{/if} <br />
	<label>Location:</label> {$indi.e.DEAT.location|escape}<br /><br />
{else}
	{if $indi.e.BURI}
		<label>Burial: </label>
		{$indi.e.BURI.event_date}{if $indi.e.BURI.ad == '0'} B.C.{/if} <br />
		<label>Location:</label> {$indi.e.BURI.location|escape}<br /><br />
	{/if}
{/if}

