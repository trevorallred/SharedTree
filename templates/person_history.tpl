{include file="header.tpl" title=$title}

<h2>{$title}</h2>
<a href="/person/{$person_id}">Back to Individual Detail</a>

<table class="portal">
<tr align="left"><td>

<h3>Changes to Individual Record</h3>
<table class="grid">
	<tr>
		<td>Modified</td>
		<td>By</td>
		<td>Sex</td>
		<td>Last</td>
		<td>Given</td>
		<td>Nick</td>
		<td>Title</td>
		<td>Pref</td>
		<td>Suff</td>
		<td>Orig</td>
		<td>Job</td>
		<td>AFN</td>
		<td>RIN</td>
		<td>NID</td>
		<td>Fam</td>
		<td>Order</td>
	</tr>
{foreach item=row from=$person_hist}
	<tr>
		<td><a href="person/{$row.person_id}&time={$row.actual_start_date}">{$row.actual_start_date}</a></td>
		<td><a href="profile.php?user_id={$row.updated_by}">{$row.username}</a></td>
		<td {if $row.gender != $prev.gender} class="highlight"{/if}>{$row.gender}</td>
		<td {if $row.family_name != $prev.family_name} class="highlight"{/if}>{$row.family_name}</td>
		<td {if $row.given_name != $prev.given_name} class="highlight"{/if}>{$row.given_name}</td>
		<td {if $row.nickname != $prev.nickname} class="highlight"{/if}>{$row.nickname}</td>
		<td {if $row.title != $prev.title} class="highlight"{/if}>{$row.title}</td>
		<td {if $row.prefix != $prev.prefix} class="highlight"{/if}>{$row.prefix}</td>
		<td {if $row.suffix != $prev.suffix} class="highlight"{/if}>{$row.suffix}</td>
		<td {if $row.national_origin != $prev.national_origin} class="highlight"{/if}>{$row.national_origin}</td>
		<td {if $row.occupation != $prev.occupation} class="highlight"{/if}>{$row.occupation}</td>
		<td {if $row.afn != $prev.afn} class="highlight"{/if}>{$row.afn}</td>
		<td {if $row.rin != $prev.rin} class="highlight"{/if}>{$row.rin}</td>
		<td {if $row.national_id != $prev.national_id} class="highlight"{/if}>{$row.national_id}</td>
		<td {if $row.bio_family_id != $prev.bio_family_id} class="highlight"{/if}><a href="marriage.php?family_id={$row.bio_family_id}&time={$row.actual_start_date}">{$row.bio_family_id}</a></td>
		<td {if $row.child_order != $prev.child_order} class="highlight"{/if}>{$row.child_order}</td>
	</tr>
	{assign var='prev' value=$row}
{/foreach}
</table>

</td></tr>
<tr><td>
<h3>Changes to Event Records</h3>
<table class="grid">
	<tr>
		<td>Modified</td>
		<td>By</td>
		<td>Type</td>
		<td>Date</td>
		<td>AD</td>
		<td>Aprox</td>
		<td>Age</td>
		<td>Loc</td>
		<td>LID</td>
		<td>Temple</td>
		<td>Status</td>
		<td>Notes</td>
		<td>Source</td>
	</tr>
{foreach item=row from=$event_hist}
	{if $row.event_type != $prev.event_type}{assign var='prev' value=""}{/if}
	<tr>
		<td>{$row.actual_start_date}</td>
		<td><a href="profile.php?user_id={$row.updated_by}">{$row.username}</a></td>
		<td>{$row.event_type}</td>
		<td {if $row.event_date != $prev.event_date} class="highlight"{/if}>{$row.event_date}</td>
		<td {if $row.ad != $prev.ad} class="highlight"{/if}>{$row.ad}</td>
		<td {if $row.date_approx != $prev.date_approx} class="highlight"{/if}>{$row.date_approx}</td>
		<td {if $row.age_at_event != $prev.age_at_event} class="highlight"{/if}>{$row.age_at_event}</td>
		<td {if $row.location != $prev.location} class="highlight"{/if}>{$row.location}</td>
		<td {if $row.location_id != $prev.location_id} class="highlight"{/if}>{$row.location_id}</td>
		<td {if $row.temple_code != $prev.temple_code} class="highlight"{/if}>{$row.temple_code}</td>
		<td {if $row.status != $prev.status} class="highlight"{/if}>{$row.status}</td>
		<td {if $row.notes != $prev.notes} class="highlight"{/if}>{$row.notes}</td>
		<td {if $row.source != $prev.source} class="highlight"{/if}>{$row.source}</td>
	</tr>
	{assign var='prev' value=$row}
{/foreach}
</table>

</td></tr>
<tr><td>
<h3>GEDCOM Import Record(s)</h3>
<table class="grid">
{foreach item=row from=$gedcom}
<tr><td><pre>
{$row.gedcom_text}
</pre></td></tr>
{/foreach}
</table>
</td></tr>

{if $merge_hist}
<tr><td>
<h3>Merged Individuals</h3>
<table class="grid">
	<tr>
		<td>ID</td>
		<td>Last</td>
		<td>Given</td>
		<td>Goto</td>
		<td>Goto</td>
		<td>Merge Date</td>
		<td>By</td>
	</tr>
{foreach item=row from=$merge_hist}
	<tr><td>{$row.person_id}
		<td>{$row.family_name}</td>
		<td>{$row.given_name}</td>
		<td><a href="/person/{$row.person_id}&time={$row.actual_start_date}">Individual</a></td>
		<td><a href="person_history.php?person_id={$row.person_id}">History</a></td>
		<td>{$row.actual_end_date}</td>
		<td><a href="profile.php?user_id={$row.updated_by}">{$row.username}</a></td>
	</tr>
{/foreach}
</table>
</td></tr>
{/if}
<tr><td>
<i>Disclaimer: We will try to maintain as much history as possible. Considering the cheap cost of space and the speed and quality of modern databases, we should be able to keep a lot. However, do not be surprised if we start deleting changes after 365, 180, or even 30 days.</i>
</td></tr>
</table>

{include file="footer.tpl"}
