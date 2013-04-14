{if $p2.match_action == "C" || $p1.person_id == $p2.person_id}
	<a href="person/{$p1.person_id}" target="_BLANK"><strong>{$p1.full_name}</strong></a>
	<font color="#336633"><b>ALREADY MERGED</b></font>
{else}
	<table {if $p2.match_action == ""}class="pending"{/if}>
	<tr valign="top">
	<td><a href="person/{$p2.person_id}" target="_BLANK"><strong>{$p2.full_name}</strong></a>
		{if $p2.b_date || $p2.b_location}<br>Birth: {$p2.b_date} in {$p2.b_location}{/if}
		{if $p2.d_date || $p2.d_location}<br>Death: {$p2.d_date} in {$p2.d_location}{/if}
	</td>
	<td>
		<form action="" id="parents{$p2.person_id}">
		{if $p2.match_action == "M" || $p2.match_action == "R"}
			<input type="button" value="Undo" onclick="matchParent({$pp2.family_id}, {$p2.person_id}, 'U');">
		{else}
			<input type="button" value="Match" onclick="matchParent({$pp2.family_id}, {$p2.person_id}, 'M');"><br>
			<input type="button" value="Reject" onclick="matchParent({$pp2.family_id}, {$p2.person_id}, 'R');">
		{/if}
		</form>
	</td>
	<td>
		{if $p2.match_action == "R"}
			MATCH REJECTED
		{else}
			<a href="person/{$p1.person_id}" target="_BLANK"><strong>{$p1.full_name}</strong></a>
			{if $p1.b_date || $p1.b_location}<br>Birth: {$p1.b_date} in {$p1.b_location}{/if}
			{if $p1.d_date || $p1.d_location}<br>Death: {$p1.d_date} in {$p1.d_location}{/if}
		{/if}
	</td>
	</tr>
	</table>
{/if}
{if $p2.match.person_id > 0 && $p2.parents.family_id > 0}
	<div id="tr_par{$p2.parents.family_id}"
		onmouseover="highlight('tr_par{$p2.parents.family_id}', true)" 
		onmouseout="highlight('tr_par{$p2.parents.family_id}', false)">
		{include file="merge_project_desc_parents.tpl" pp2=$p2.parents pp1=$p2.match.parents}
	</div>
{/if}
<br />