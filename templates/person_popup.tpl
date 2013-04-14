<table class="person_popup">
<tr>
<td>ZZ
{if $p.person_id}
	<div style="float:right;">
	<a href="/person/{$p.person_id}"><img src="img/btn_individual.png" title="Individual Details" width="16" height="16" border="0" /></a><br />
	<img src="img/blank.gif" width="16" height="2" border="0" /><br />
	<a href="/family/{$p.person_id}"><img src="img/btn_family.png" title="Family Details" width="16" height="16" border="0" /></a><br />
	<img src="img/blank.gif" width="16" height="2" border="0" /><br />
	<a href="descendants.php?person_id={$p.person_id}"><img src="img/btn_descend.png" title="Descendants" width="16" height="16" border="0" /></a><br />
	</div>
{/if}
{if $c.person_id}{$seq}) 
{if $p.person_id}
	<a href="ped.php?person_id={$p.person_id}">{$p.given_name} {$p.family_name}</a>
{else}
<i>UNKNOWN</i>
{/if}
{if $rowspan > 1}
	<hr>
	<div title="birth date">B: {$p.e.BIRT.event_date}</div>
	<div title="birth place">P: {$p.e.BIRT.location}</div>
{/if}
{if $rowspan > 2}
	<div title="death date">D: {$p.e.DEAT.event_date}</div>
	<div title="death place">P: {$p.e.DEAT.location}</div>
{/if}
{/if}</td>
{if $parent_id}<td>{if $p.person_id}<a href="ped.php?person_id={$parent_id}">&gt;&gt;</a>{else}&nbsp;{/if}</td>{/if}
