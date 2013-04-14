<div class="personframe" style="top: {$ht}px">
{if $p.person_id > 0}
	<div class="personbox" onclick="showhide('info{$boxid}');">{$p.full_name} ({$p.birth_year})</div>
	<div class="personinfo" id="info{$boxid}">
	{include file="person_nav.tpl" nav_id=$p.person_id}
		<img src="image.php?person_id={$p.person_id}" align="left" height="80">
		<div title="birth date">B: {$p.e.BIRT.event_date}</div>
		<div title="birth place">P: {$p.e.BIRT.location}</div>
		<div title="death date">D: {$p.e.DEAT.event_date}</div>
		<div title="death place">P: {$p.e.DEAT.location}</div>
	</div>
{else}
	<div class="personbox" style="cursor: default"></div>
{/if}
</div>
