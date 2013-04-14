<div class="personframe" style="top: {$ht}px">
{if $p1.person_id > 0 && $p2.person_id > 0 && $p1.person_id <> $p2.person_id}
	<div class="mergeselect"><input type="checkbox" id="box{$boxid}" name="merge_{$p1.person_id}" value="{$p2.person_id}" onclick="chooseParent({$boxid});" checked /></div>
	<div class="personbox" onclick="showPerson({$p1.person_id}, {$p2.person_id});">{$p1.given_name} {$p1.family_name} ({$p1.birth_year})</div>
	<div class="personbox" onclick="showPerson({$p1.person_id}, {$p2.person_id});">{$p2.given_name} {$p2.family_name} ({$p2.birth_year})</div>
	{if $boxid < 8}
	{if $p1.bio_family_id > 0 && $p2.bio_family_id > 0 && $p1.bio_family_id <> $p2.bio_family_id}
		<div id="family_{$p1.bio_family_id}">&nbsp;&nbsp;<a href="#" onclick="showFamily({$p1.bio_family_id}, {$p2.bio_family_id}); return false;" title="Match his/her siblings">Match Siblings</a></div>
	{/if}
	{/if}
{else}
	<div class="mergeselect"><input type="checkbox" disabled="1" /></div>
	<div class="personbox" style="cursor: default; background: #AAA;">{$p1.given_name} {$p1.family_name}</div>
	<div class="personbox" style="cursor: default; background: #AAA;">{$p2.given_name} {$p2.family_name}</div>
{/if}
</div>
