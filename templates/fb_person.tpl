{include file="fb_header.tpl"}

<table cellspacing="0" cellpadding="5" width="100%">
<tr><td rowspan="3" class="content" width="50%">
	<div style="position: relative; text-align: right;"><a href="http://www.sharedtree.com/person/{$individual.person_id}" target="_BLANK">Show on SharedTree.com</a></div>
	<h1>{$individual.full_name}</h1>
	{if $photos[$individual.person_id]}<img src="/image.php?image_id={$photos[$individual.person_id]}&size=thumb" border="0" align="left">{/if}
	<span class="label">Gender:</span> {if $individual.gender=="M"}Male{/if}
		{if $individual.gender=="F"}Female{/if}
		{if $individual.gender=="U"}Unknown{/if}<br />
	<span class="label">Birth: </span>
	{include file="birth_year.tpl" birth_year=$individual.birth_year birth_date=$individual.e.BIRT.event_date}<br />
	<span class="label">Location:</span> {$individual.e.BIRT.location}<br /><br />
{if $individual.e.DEAT}
	<span class="label">Death:</span> {$individual.e.DEAT.event_date}{if $individual.e.DEAT.ad == '0'} B.C.{/if} <br />
	<span class="label">Location:</span> {$individual.e.DEAT.location}
{/if}
    </td>
    <td class="content" width="50%" style="border-left: 3px black solid; border-bottom: 3px black solid;">
	<h3>Father:</h3>
{if $father.person_id > 0}
	<b><a href="?pid={$father.person_id}">{$father.full_name}</a></b><br /><br />
	<label>Birth: </label>
	{include file="birth_year.tpl" birth_year=$father.birth_year birth_date=$father.e.BIRT.event_date}<br />
	<label>Location:</label>
	{$father.e.BIRT.location}<br />
{if $father.e.DEAT}
	<label>Death:</label>
	{$father.e.DEAT.event_date}{if $father.e.DEAT.ad == '0'} B.C.{/if} <br />
	<label>Location:</label> {$father.e.DEAT.location}
{/if}
{else}
	<i>UNKNOWN</i>
{/if}
</td>
</tr>
<tr><td class="content" style="border-left: 3px black solid;">
	<h3>Mother:</h3>
{if $mother.person_id > 0}
		<b><a href="?pid={$mother.person_id}">{$mother.full_name}</a></b><br /><br />
		<label>Birth: </label>
	{include file="birth_year.tpl" birth_year=$mother.birth_year birth_date=$mother.e.BIRT.event_date}<br />
		<label>Location:</label>
		{$mother.e.BIRT.location}<br />
{if $mother.e.DEAT}
		<label>Death:</label>
		{$mother.e.DEAT.event_date}{if $mother.e.DEAT.ad == '0'} B.C.{/if} <br />
		<label>Location:</label> {$mother.e.DEAT.location}
{/if}
{else}
	<i>UNKNOWN</i>
{/if}
</td>
</tr>
</table>
<br />

{if $marriages}
{foreach item=marriage from=$marriages}
	<table class="table1" width="800">
	<tr valign="top"><td width="40%" class="content">
	<h3>Spouse:</h3>
{if $marriage.person_id > 0}
	<b><a href="?pid={$marriage.person_id}">{$marriage.full_name}</a></b><br /><br />
	<label>Birth: </label>
	{include file="birth_year.tpl" birth_year=$marriage.birth_year birth_date=$marriage.b_date}<br />
	<label>Location:</label>
	{$marriage.b_location}<br /><br />
{if $marriage.d_date || $marriage.d_location}
	<label>Death:</label>
	{$marriage.d_date}{if $marriage.d_ad == '0'} B.C.{/if} <br />
	<label>Location:</label> {$marriage.d_location} <br /><br />
{/if}
{/if}
		<label>Married:</label>
		{$marriage.m_date}{if $marriage.m_ad == '0'} B.C.{/if} <br />
		<label>Location:</label> {$marriage.m_location} {$marriage.m_temple_code}<br />
		<label>Status:</label>
		{if $marriage.status_code=="M"}Married{/if}
		{if $marriage.status_code=="S"}Separated{/if}
		{if $marriage.status_code=="D"}Divorced{/if}
		{if $marriage.status_code=="N"}Not Married{/if}
		{if $marriage.status_code=="U"}Unknown{/if}<br>
	</td>
	<td class="content">	
	<table>
	<tr>
		<th>Children's Name</th>
		<th>Sex</th>
		<th>Birthdate</th>
	</tr>
	{foreach item=child from=$marriage.children}
		<tr>
			<td>{if $child.protected==1}{$child.full_name}{else}<a href="?pid={$child.person_id}">{$child.full_name}</a>{/if}</td>
			<td>{$child.gender}</td>
			<td>{include file="birth_year.tpl" birth_year=$child.birth_year birth_date=$child.b_date}</td>
		</tr>
	{/foreach}

	</table>
	</td></tr>
	
	</td></tr>
	</table>
	<br />
{/foreach}
{/if}

{include file="fb_footer.tpl"}
