{if $p.match_action == "ADD"}
	<table class="matched">
	<tr valign="top">
	<td><a href="person/{$p.person_id}" target="_BLANK"><strong>{$p.full_name}</strong></a>
				{if $p.b_date || $p.b_location}<br>Birth: {$p.b_date} in {$p.b_location}{/if}
				{if $p.d_date || $p.d_location}<br>Death: {$p.d_date} in {$p.d_location}{/if}
	</td>
	<td>
	<form action="">
		Adding child as new
		<input type="button" name="undo" value="Undo" onclick="undoChild({$p.person_id});">
	</form>
	</td>
	</tr>
	</table>
{else}
	{if $p.match}
		<table class="matched">
		<tr valign="top">
		<td><a href="person/{$p.person_id}" target="_BLANK"><strong>{$p.full_name}</strong></a>
			{if $p.b_date || $p.b_location}<br>Birth: {$p.b_date} in {$p.b_location}{/if}
			{if $p.d_date || $p.d_location}<br>Death: {$p.d_date} in {$p.d_location}{/if}
		</td>
		<td><form action="">
				<input type="button" name="undo" value="Undo" onclick="undoChild({$p.person_id});">
			</form>
		</td>
		<td><a href="person/{$p.match.person_id}" target="_BLANK">{$p.match.full_name}</a>
			{if $p.match.b_date || $p.match.b_location}<br>Birth: {$p.match.b_date} in {$p.match.b_location}{/if}
			{if $p.match.d_date || $p.match.d_location}<br>Death: {$p.match.d_date} in {$p.match.d_location}{/if}
		</td>
		</tr>
		</table>
		<div>
		{if $p.marriages}
			{foreach item=fam from=$p.marriages}
				<div class="person"
					id="tr_fam{$fam.family_id}"
					onmouseover="highlight('tr_fam{$fam.family_id}', true)" 
					onmouseout="highlight('tr_fam{$fam.family_id}', false)">
					{include file="merge_project_desc_spouse.tpl" f=$fam f1s=$p.match.marriages}
				</div>
			{/foreach}
		{/if}
		</div>
	{else}
		<table class="pending">
		<tr valign="top">
		<td><a href="person/{$p.person_id}" target="_BLANK"><strong>{$p.full_name}</strong></a>
			{if $p.b_date || $p.b_location}<br>Birth: {$p.b_date} in {$p.b_location}{/if}
			{if $p.d_date || $p.d_location}<br>Death: {$p.d_date} in {$p.d_location}{/if}
		</td>
		<td>
			<form action="" id="children{$p.person_id}">
			<input type="button" value="Select" onclick="selectChild({$p.person_id});">
			<br />
			<select name="person_id" multiple="multiple" size="{math equation="1 + option_size" option_size=$p1s|@count}">
				<option value="ADD" style="font-style: italic">(+) add as new child</option>
				{foreach item=p1 from=$p1s}
					<option value="{$p1.person_id}">{$p1.full_name}
					{if $p1.b_date}b:{$p1.b_date}{/if}
					{if $p1.d_date}d:{$p1.d_date}{/if}
					</option>
				{/foreach}
			</select>
			</form>
		</td>
		</tr>
		</table>
	{/if}
{/if}
