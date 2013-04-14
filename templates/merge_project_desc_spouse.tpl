{if $f.match_action == "C" || $f.spouse.person_id == $f.spouse.match.person_id}
	<a href="person/{$f.spouse.person_id}" target="_BLANK"><strong>{$f.spouse.full_name}</strong></a> 
	<font color="#336633"><b>ALREADY MERGED</b></font>
{else}
	<table class="{if $f.match_action == ""}pending{else}matched{/if}">
	<tr valign="top">
	<td><a href="person/{$f.spouse.person_id}" target="_BLANK"><strong>{$f.spouse.full_name}</strong></a>
		{if $f.spouse.b_date || $f.b_location}<br>Birth: {$f.spouse.b_date} in {$f.spouse.b_location}{/if}
		{if $f.spouse.d_date || $f.d_location}<br>Death: {$f.spouse.d_date} in {$f.spouse.d_location}{/if}
	</td>
	<td><form action="" id="spouses{$f.family_id}">
	{if $f.match_action == "ADD"}
			<input type="button" name="undo" value="Undo" onclick="call('select_spouse', 'tr_fam{$f.family_id}', '&family2_id={$f.family_id}');">
			<br>Adding new marriage
	{else}
		{if $f.match_action == "M"}
			<input type="button" name="undo" value="Undo" onclick="call('select_spouse', 'tr_fam{$f.family_id}', '&family2_id={$f.family_id}');">
		{else}
			<input type="button" value="Select" onclick="selectSpouse({$f.family_id});">
			<br />
			<select name="family_id" multiple="multiple" size="{math equation="1 + option_size" option_size=$f1s|@count}">
				<option value="ADD" style="font-style: italic">(+) add as new spouse</option>
				{foreach item=f1 from=$f1s}
					<option value="{$f1.family_id}">{$f1.spouse.full_name}
					{if $f1.spouse.b_date}b:{$f1.spouse.b_date}{/if}
					{if $f1.spouse.d_date}d:{$f1.spouse.d_date}{/if}
					</option>
				{/foreach}
			</select>
		{/if}
	{/if}
	</form>
	</td>
	{if $f.match_action == "M"}
		<td><a href="person/{$f.spouse.match.person_id}" target="_BLANK">{$f.spouse.match.full_name}</a>
			{if $f.spouse.match.b_date || $f.spouse.match.b_location}<br>Birth: {$f.spouse.match.b_date} in {$f.spouse.match.b_location}{/if}
			{if $f.spouse.match.d_date || $f.spouse.match.d_location}<br>Death: {$f.spouse.match.d_date} in {$f.spouse.match.d_location}{/if}
		</td>
	{/if}
	</tr>
	</table>
	{if $f.spouse.match.person_id > 0 && $f.spouse.parents.family_id > 0}
		<div id="tr_par{$f.spouse.parents.family_id}">
			{include file="merge_project_desc_parents.tpl" pp2=$f.spouse.parents pp1=$f.spouse.match.parents}
		</div>
	{/if}
{/if}
{if $f.children}
	{foreach item=child from=$f.children}
	<div class="person"
		id="tr_chil{$child.person_id}"
		onmouseover="highlight('tr_chil{$child.person_id}', true)" 
		onmouseout="highlight('tr_chil{$child.person_id}', false)">
		{include file="merge_project_desc_child.tpl" p=$child p1s=$f.matchchildren}
	</div>
	{/foreach}
{/if}
