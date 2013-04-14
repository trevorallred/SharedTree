<html><head><title>SharedTree: {$title}</title></head>
<body>
<h1>{$title}</h1>
{if $father}<a href="#father">Father</a> {/if}
{if $mother}<a href="#mother">Mother</a> {/if}
{if $marriages}<a href="#spouse">Spouse(s)</a> {/if}
<a href="#details">Details</a> 
<a href="search.php">Search</a>
<hr>
{if $person.e.BIRT}B: {$person.e.BIRT.event_date} in {$person.e.BIRT.location}<br>{/if}
{if $person.e.DEAT}D: {$person.e.DEAT.event_date} in {$person.e.DEAT.location}<br>{/if}

{if $father || $mother}<hr>{/if}
{if $father}
	<a name="father" />Father:
		<a href="?id={$father.person_id}">{$father.given_name} {$father.family_name}</a> ({$father.e.BIRT.event_year} - {$father.e.DEAT.event_year})<br>
	{if $ffather}&nbsp;&nbsp;&nbsp;&nbsp;Grandfather:
		<a href="?id={$ffather.person_id}">{$ffather.given_name} {$ffather.family_name}</a> ({$ffather.e.BIRT.event_year} - {$ffather.e.DEAT.event_year})<br>
	{/if}
	{if $fmother}&nbsp;&nbsp;&nbsp;&nbsp;Grandmother:
		<a href="?id={$fmother.person_id}">{$fmother.given_name} {$fmother.family_name}</a> ({$fmother.e.BIRT.event_year} - {$fmother.e.DEAT.event_year})<br>
	{/if}
{/if}
{if $mother}
	<a name="mother" />Mother:
		<a href="?id={$mother.person_id}">{$mother.given_name} {$mother.family_name}</a> ({$mother.e.BIRT.event_year} - {$mother.e.DEAT.event_year})<br>
	{if $mfather}&nbsp;&nbsp;&nbsp;&nbsp;Grandfather:
		<a href="?id={$mfather.person_id}">{$mfather.given_name} {$mfather.family_name}</a> ({$mfather.e.BIRT.event_year} - {$mfather.e.DEAT.event_year})<br>
	{/if}
	{if $mmother}&nbsp;&nbsp;&nbsp;&nbsp;Grandmother:
		<a href="?id={$mmother.person_id}">{$mmother.given_name} {$mmother.family_name}</a> ({$mmother.e.BIRT.event_year} - {$mmother.e.DEAT.event_year})<br>
	{/if}
{/if}

{if $marriages}
	<hr>
	<a name="spouse" />
	{foreach item=spouse from=$marriages}
		Spouse:
		{if $child.protected}{$spouse.given_name}<br />
		{else}
			<a href="?id={$spouse.person_id}">{$spouse.given_name} {$spouse.family_name}</a> ({$spouse.b_date} - {$spouse.d_date})<br>
			{if $spouse.m_date || $spouse.m_location}
				Married: {$spouse.m_date} in {$spouse.m_location} {$spouse.m_temple_code}<br />
			{/if}
			{if $spouse.status_code}
				Status:
				{if $spouse.status_code=="M"}Married{/if}
				{if $spouse.status_code=="S"}Separated{/if}
				{if $spouse.status_code=="D"}Divorced{/if}
				{if $spouse.status_code=="N"}Not Married{/if}
				{if $spouse.status_code=="U"}Unknown{/if}<br>
			{/if}
		{/if}

		Children:
		<ul>
		{foreach item=child from=$spouse.children}
			<li>{$child.gender} {if !$child.protected}<a href="?id={$child.person_id}">{$child.full_name}</a>{else}{$child.full_name}{/if}
			({$child.b_date} - {$child.d_date})</li>
		{/foreach}
		</ul>
	{/foreach}
{/if}

<hr>
<a name="details" />Details for {$person.given_name} {$person.family_name}<br>
Gender: {if $person.gender=="M"}Male{/if}{if $person.gender=="F"}Female{/if}{if $person.gender=="U"}Unknown{/if}<br>

{if $person.prefix}<label>Prefix:</label> {$person.prefix}<br>{/if}
{if $person.suffix}<label>Suffix:</label> {$person.suffix}<br>{/if}
{if $person.nickname}<label>Nickname:</label> {$person.nickname}<br>{/if}
{if $person.title}<label>Title\Royalty:</label> {$person.title}<br>{/if}
{if $person.afn}<label>AFN:</label> {$person.afn}<br>{/if}
{if $person.national_id}<label>SSN or Nat'l ID:</label> {$person.national_id}<br>{/if}
{if $person.national_origin}<label>Nationality/Origin:</label> {$person.national_origin}<br>{/if}
{if $person.occupation}<label>Occupation:</label> {$person.occupation}<br>{/if}

<br clear="all" />
{if $person.e}
<table border="1">
{foreach item=event from=$person.e}
	{if $event.lds_flag==0}
	{assign var="eid" value=$event.event_id}
	<tr>
		<td>{$event.prompt}</td>
		<td>
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
		</td>
		<td>{$event.location} {$event.temple_code}</td>
	</tr>
	{/if}
{/foreach}
</table>
{/if}
</body></html>
