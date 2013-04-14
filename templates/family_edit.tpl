{include file="header.tpl" title="Edit Family" background="edit"}
<script type="text/javascript">
{literal}
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

function openChild(file,window) {
    childWindow=open(file,window,'resizable=yes,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=yes, width=400, height=500');
    if (childWindow.opener == null) childWindow.opener = self;
}

function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}
{/literal}
</script>

<h2>Edit Family</h2>
[<a href="marriage.php?family_id={$family_id}">Back to Marriage</a>]

<table class="portal" width="600">
<tr><td width="50%">
<label>Husband:</label><br />
{if $family.person1_id}
	<a href="/family/{$family.person1_id}">{$family.given_name1} {$family.family_name1}</a>
	<a href="#" onclick="confirmSubmit('Are you sure you want to remove {$family.given_name1} from this marriage?','marriage.php?family_id={$family.family_id}&removeparent=1');"><img src="img/btn_drop.png" title="Remove Husband" width="16" height="16" border="0" /></a>
{else}
	<form action="/person_edit.php" method="get">
		<input type="hidden" name="person[marriage_id]" value="{$family.family_id}">
		<input type="hidden" name="person[gender]" value="M">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td>
			<td><input type="text" name="person[given_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Family name:</td>
			<td><input type="text" name="person[family_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Birthdate:</td><td><input type="text" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
{/if}
</td>
<td width="50%">
<label>Wife:</label><br />
{if $family.person2_id}
	<a href="/family/{$family.person2_id}">{$family.given_name2} {$family.family_name2}</a>
	<a href="#" onclick="confirmSubmit('Are you sure you want to remove {$family.given_name2} from this marriage?','marriage.php?family_id={$family.family_id}&removeparent=2');"><img src="img/btn_drop.png" title="Remove Wife" width="16" height="16" border="0" /></a>
{else}
	<form action="/person_edit.php" method="get">
		<input type="hidden" name="action" value="addparentchoose">
		<input type="hidden" name="person[marriage_id]" value="{$family.family_id}">
		<input type="hidden" name="person[gender]" value="F">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td>
			<td><input type="text" name="person[given_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Family name:</td>
			<td><input type="text" name="person[family_name]" class="editPerson" size="20"></td></tr>
		<tr><td class="label">Birthdate:</td><td><input type="text" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
{/if}
</td></tr>

<form method="POST">
<input type="hidden" name="action" value="add_save">
<input type="hidden" name="family_id" value="{$family.family_id}">
<tr><td colspan="2" style="text-align:center"><input type="submit" name="save" value="Save"></td></tr>
<tr>
<td colspan="2">
<label>Status:</label>
{html_radios name="family[status_code]" options=$marriage_statuses selected=$family.status_code}<br>
<br>

{include file="event_edit.tpl" events=$family.e eventtype="family" defaultevent="1"}

</td></tr>
<tr><td colspan="2" style="text-align:center"><input type="submit" name="save" value="Save"></td></tr>
<tr><td colspan="2">
<h3>Other Data</h3>
<label>Marriage Notes:</label><br>
<textarea name="family[notes]">{$family.notes}</textarea><br> <br>

{include file="event_edit.tpl" events=$family.e eventtype="family" defaultevent="0"}

</td></tr>
<tr><td colspan="2" style="text-align:center"><input type="submit" name="save" value="Save"></td></tr>
</table>
</form>

{include file="footer.tpl"}
