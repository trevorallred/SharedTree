{include file="import_nav.tpl" istep="3"}

<script type="text/javascript">
{literal}
function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}

function toggleLayer(whichLayer) {
	if (document.getElementById) {
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = style2.display? "":"block";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = style2.display? "":"block";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = style2.display? "":"block";
	}
}
{/literal}
</script>
<br>
<b><a href="import.php?action=match&import_id={$import_id}">Continue to the Next Step &gt;&gt;</a></b>

<br><br>

{if $file.status_code == 'A'}
	This file has been locked because you approved the results or other people have made changes to records you imported. Once locked, the records cannot be deleted. <a href="import.php?action=unlock&import_id={$import_id}">Try to Unlock</a>
{else}
	<a href="import.php?action=approve&import_id={$import_id}">Approve and Lock</a>
	or
	<a href="#" onclick="confirmSubmit('Are you sure you want to erase ALL data previously imported? This cannot be undone!','import.php?action=clear&import_id={$file.import_id}');">Erase Data</a>
{/if}
{if !$user_person_id}
	<div class="errors"><br><b><i>You have not yet added a genealogical record for yourself. <br>If you just imported yourself, then please click Choose Self next to your record below.</i></b></div>
{/if}

<br><br>
{if $pages > 1}
- {$pages} pages
<br>
{/if}
<table border="1" class="table1">
	<tr>
		<td class="label">FileID</td>
{if !$user_person_id}
		<td class="label">Choose Self</td>
{/if}
		<td class="label">Family name</td>
		<td class="label">Given name</td>
		<td class="label">Birth</td>
		<td class="label">Last Changed</td>
		<td class="label">By User</td>
		<td class="label"><i>click below to view source data</i></td>
	</tr>
{if $individuals}
{foreach item=result from=$individuals}
	<tr>
		<td>{$result.individual}</a></td>
{if !$user_person_id}
		<td><a href="profile.php?addperson={$result.person_id}">Choose Self</a></td>
{/if}
		<td><a href="/person/{$result.person_id}">{$result.family_name}</a></td>
		<td><a href="/person/{$result.person_id}">{$result.given_name}</a></td>
		<td>{$result.birth_year}</td>
		<td>{$result.update_date}</td>
		<td><a href="profile.php?user_id={$result.created_by}">{$result.user_name}</a></td>
		<td onclick="toggleLayer('P{$result.person_id}'); return false;" style="cursor: pointer" width="40%"><div id="P{$result.person_id}" class="editEvent"><pre>{$result.gedcom_text}</pre></div></td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="7" align="center">No records exist. This could occur you never imported records, or the records have been deleted or merged.</td>
	</tr>
{/if}
</table>

{include file="footer.tpl"}
