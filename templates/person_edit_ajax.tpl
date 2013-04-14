{if $is_logged_on}
<form id="edit{$tag}" method="post" action="family.php" class="editPerson">
	<input type="hidden" name="do" value="save{$tag}">
	<input type="hidden" name="person_id" value="{$person_id}">
	<input type="hidden" name="person[person_id]" value="{$pers.person_id}">
	<input type="hidden" name="person[bio_family_id]" value="{$pers.bio_family_id}">
	{if $person.marriage_id}
	<input type="hidden" name="person[marriage_id]" value="{$pers.marriage_id}">
	{/if}
	<input type="hidden" name="person[spouse]" value="{$pers.spouse}">
	<input type="hidden" name="person[child_id]" value="{$pers.child_id}">
<table width="100%" align="center" >
<tr><td>Given name(s):</td>
    <td>Family name:</td>
</tr>
<tr><td><input type="text" name="person[given_name]" value="{$pers.given_name}" class="editPerson"></td>
    <td><input type="text" name="person[family_name]" value="{$pers.family_name}" class="editPerson"></td>
</tr>
</table>
<table width="100%">
<tr><td>Gender:</td>
    <td>{html_radios name="person[gender]" options=$gender_options selected=$person.gender}</td>
</tr>
<tr><td>Birth date:</td>
    <td><input type="text" name="person[b_date]" value="{$pers.b_date}" size="10" class="editPerson"> 
    {html_options name="person[b_ad]" options=$adbc selected=$person.b_ad}</td>
</tr>
<tr><td>Birth place:</td>
    <td><input type="text" name="person[b_location]" value="{$pers.b_location}" size="30" class="editPerson"></td>
</tr>

<tr><td>Death date:</td>
    <td><input type="text" name="person[d_date]" value="{$pers.d_date}" size="10" class="editPerson"> 
    {html_options name="person[d_ad]" options=$adbc selected=$pers.d_ad}</td>
</tr>
<tr><td>Death place:</td>
    <td><input type="text" name="person[d_location]" value="{$pers.d_location}" size="30" class="editPerson"></td>
</tr>
</table>

<table width="100%">
<tr><td align="left"><input type="button" value="Cancel" onclick="hideByID('edit{$tag}'); showByID('show{$tag}');" class="editPerson" /></td>
    <td><p style="text-align: right"><input type="submit" value="Save" class="editPerson" /></p></td>
</tr>
</table>
</form>
{/if}