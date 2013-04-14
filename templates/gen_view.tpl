{include file="header.tpl" title=$title includejs=1}
<br clear="all" />
<br />
<script type="text/javascript">
{literal}
$i = 1;
function mergePerson($id) {
	$("merge"+$i).value = $id;
	if ($i==1) $i = 2;
	else $i = 1;
	$('merge').show();
}
{/literal}
</script>

<table class="table1" cellspacing="0" cellpadding="0" width="800">
<tr><td rowspan="3" width="50%" class="content">
{if $time}
<div class="errors">Version: {$time} <a href="/family/{$person_id}">&lt;&lt; Back to Current Version</a></div>
{/if}
	{include file="person_nav.tpl" nav_id=$individual.person_id}
	<font size="+1"><b>{$individual.full_name|escape}</b></font>{include file="newgif.tpl" new=$individual.new}
	{if $photos[$individual.person_id]}<img src="/image.php?image_id={$photos[$individual.person_id]}&size=thumb" border="0" align="left">{/if}
	<br /><br />
	<span class="label">Gender:</span> {if $individual.gender=="M"}Male{/if}
		{if $individual.gender=="F"}Female{/if}
		{if $individual.gender=="U"}Unknown{/if}<br />
	{include file="gen_detail.tpl" indi=$individual}
    </td>
    <td width="50%" class="content" style="border-left: 3px black solid; border-bottom: 3px black solid;">
	{include file="person_nav.tpl" nav_id=$father.person_id return_to="/family/$person_id"}
	<h3>Father:</h3>
{if $father.person_id > 0}
	<b><a href="/family/{$father.person_id}">{$father.full_name|escape}</a></b>{include file="newgif.tpl" new=$father.new}<br /><br />
	{if $photos[$father.person_id]}<img src="/image.php?image_id={$photos[$father.person_id]}&size=thumb" border="0" align="left">{/if}
	{include file="gen_detail.tpl" indi=$father}
<br clear="all" />
<br>
	<table border=0 align="right">
	<tr>
		<td><a href="/marriage.php?family_id={$parents.family_id}"><img src="/img/btn_individual.png" title="Marriage Details" width="16" height="16" border="0" /></a></td>
		<td><a href="/marriage.php?family_id={$parents.family_id}">View Marriage</a></td>
	</tr>
	{if $is_logged_on}
	<tr>
		<td><a href="/marriage.php?family_id={$parents.family_id}&action=edit"><img src="/img/btn_edit.png" title="Edit Marriage" width="16" height="16" border="0" /></a></td>
		<td><a href="/marriage.php?family_id={$parents.family_id}&action=edit">Edit Marriage</a></td>
	</tr>
	{/if}
	</table>
	<h3>Marriage:</h3>
		<label>Date:</label>
		{$parents.e.MARR.event_date}{if $parents.e.MARR.ad == '0'} B.C.{/if} <br />
		<label>Location:</label> {$parents.e.MARR.location} {$parents.e.MARR.temple_code}<br />
		<label>Status:</label>
		{if $parents.status_code=="M"}Married{/if}
		{if $parents.status_code=="S"}Separated{/if}
		{if $parents.status_code=="D"}Divorced{/if}
		{if $parents.status_code=="N"}Not Married{/if}
		{if $parents.status_code=="U"}Unknown{/if}<br>
{else}
	{if $father.protected}
		<b>{$father.given_name}</b><br /><br /><br />
	{else}
		{if $is_logged_on}
			<form action="/person_edit.php" method="get">
				<input type="hidden" name="person[marriage_id]" value="{$individual.bio_family_id}">
				<input type="hidden" name="person[child_id]" value="{$individual.person_id}">
				<input type="hidden" name="person[gender]" value="M">
				<input type="hidden" name="return_to" value="/family/{$person_id}">
				<table align="center" class="addPerson">
				<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td></td></tr>
				<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20"></td>
					<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20" value="{$individual.family_name|escape:'html'}"></td>
					<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
					<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
				</tr>
				</table>
			</form>
		{else}
			<i>UNKNOWN</i>
		{/if}
	{/if}
{/if}
</td>
</tr>
<tr><td width="50%" class="content" style="border-left: 3px black solid;">
	{include file="person_nav.tpl" nav_id=$mother.person_id return_to="/family/$person_id"}
	<h3>Mother:</h3>
{if $mother.person_id > 0}
		<b><a href="/family/{$mother.person_id}">{$mother.full_name|escape}</a></b>{include file="newgif.tpl" new=$mother.new}<br /><br />
		{if $photos[$mother.person_id]}<img src="/image.php?image_id={$photos[$mother.person_id]}&size=thumb" border="0" align="left">{/if}
		{include file="gen_detail.tpl" indi=$mother}
{else}
	{if $mother.protected}
		<b>{$mother.given_name}</b><br /><br /><br />
	{else}
		{if $is_logged_on}
			<form action="/person_edit.php" method="get">
				<input type="hidden" name="person[marriage_id]" value="{$individual.bio_family_id}">
				<input type="hidden" name="person[child_id]" value="{$individual.person_id}">
				<input type="hidden" name="person[gender]" value="F">
				<input type="hidden" name="return_to" value="/family/{$person_id}">
				<table align="center" class="addPerson">
				<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td></td></tr>
				<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20"></td>
					<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20"></td>
					<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
					<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
				</tr>
				</table>
			</form>
		{else}
			<i>UNKNOWN</i>
		{/if}
	{/if}
{/if}
</td>
</tr>
</table>
<br />

{if $marriages}
{foreach item=marriage from=$marriages}
	<table class="table1" width="800">
	<tr valign="top"><td width="40%" class="content">
	{include file="person_nav.tpl" nav_id=$marriage.person_id return_to="/family/$person_id"}
	<h3>Spouse:</h3>
{if $marriage.person_id > 0}
	<b><a href="/family/{$marriage.person_id}">{$marriage.full_name|escape}</a></b>{include file="newgif.tpl" new=$marriage.new}<br /><br />
	{if $photos[$marriage.person_id]}<img src="/image.php?image_id={$photos[$marriage.person_id]}&size=thumb" border="0" align="left">{/if}
	<label>Birth: </label>
	{include file="birth_year.tpl" birth_year=$marriage.birth_year birth_date=$marriage.b_date}<br />
	<label>Location:</label> {$marriage.b_location|escape}<br /><br />
{if $marriage.d_date || $marriage.d_location}
	<label>Death:</label>
	{$marriage.d_date}{if $marriage.d_ad == '0'} B.C.{/if} <br />
	<label>Location:</label> {$marriage.d_location|escape}
{/if}
{else}
	<form action="/person_edit.php" method="get">
		<input type="hidden" name="person[marriage_id]" value="{$marriage.family_id}">
		<input type="hidden" name="person[gender]" value="F">
		<input type="hidden" name="return_to" value="/family/{$person_id}">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td></td><td></td></tr>
		<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20"></td>
			<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20"></td>
			<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
{/if}
		<br clear="all">
		<br>
		<table border=0 align="right">
		<tr>
			<td><a href="/marriage.php?family_id={$marriage.family_id}"><img src="/img/btn_individual.png" title="Marriage Details" width="16" height="16" border="0" /></a></td>
			<td><a href="/marriage.php?family_id={$marriage.family_id}">View Marriage</a></td>
		</tr>
		{if $is_logged_on}
		<tr>
			<td><a href="/marriage.php?family_id={$marriage.family_id}&action=edit"><img src="/img/btn_edit.png" title="Edit Marriage" width="16" height="16" border="0" /></a></td>
			<td><a href="/marriage.php?family_id={$marriage.family_id}&action=edit">Edit Marriage</a></td>
		</tr>
		{/if}
		</table>
		<h3 {if $is_logged_on}style="cursor: pointer;" onclick="mergePerson({$marriage.person_id});" title="Click to select {$marriage.full_name} for merge. See below for details."{/if}>Marriage: </h3>
		<label>Date:</label>
		{$marriage.m_date}{if $marriage.m_ad == '0'} B.C.{/if} <br />
		<label>Location:</label> {$marriage.m_location|escape} {$marriage.m_temple_code|escape}<br />
		<label>Status:</label>
		{if $marriage.status_code=="M"}Married{/if}
		{if $marriage.status_code=="S"}Separated{/if}
		{if $marriage.status_code=="D"}Divorced{/if}
		{if $marriage.status_code=="N"}Not Married{/if}
		{if $marriage.status_code=="U"}Unknown{/if}<br>
	</td>
	<td class="content">	
	<h3>Children</h3>
	<table class="grid" width="100%">
	<tr><th width="100px">Navigation</th>
		<th>Ord</th>
		<th>Children's Name</th>
		<th>Sex</th>
		<th>Age</th>
		<th>Birthdate</th>
		{if $is_logged_on}
			<th>Mrge</th>
			<th>Remove</th>
		{/if}
	</tr>
	{foreach item=child from=$marriage.children}
		<tr><td>{if $child.protected!=1}{include file="person_nav.tpl" nav_id=$child.person_id direction="flat" return_to="/family/$person_id"}{/if}</td>
			<td>{$child.child_order}</td>
			<td>{if $child.protected==1}{$child.full_name}{else}<a href="/family/{$child.person_id}">{$child.full_name|escape}</a>{/if}{include file="newgif.tpl" new=$child.new}</td>
			<td>{$child.gender}</td>
			<td>{$child.age}</td>
			<td>{include file="birth_year.tpl" birth_year=$child.birth_year birth_date=$child.b_date}</td>
		{if $is_logged_on}
			<td style="cursor: pointer;" onclick="mergePerson({$child.person_id});" title="Click to select {$child.given_name|escape} for merge. See below for details.">&nbsp;</td>
			<td>{if $child.protected!=1}<a href="#" onclick="stConfirm('Are you sure you want to remove {$child.given_name} {$child.family_name} from this family?','/family/{$individual.person_id}&removechild={$child.person_id}');"><img src="/img/btn_drop.png" title="Remove child from family" width="16" height="16" border="0" /></a>{/if}</td>
		{/if}
		</tr>
	{/foreach}

	{if $is_logged_on}
	<tr><td colspan="7" align="center">
	<h3 id="addchildh{$marriage.family_id}"><a href="#" onclick="$('addchild{$marriage.family_id}').show(); $('addchildh{$marriage.family_id}').hide(); $('addchild{$marriage.family_id}_name').focus(); return false;">Add Child to Marriage</a></h3>
	<form action="/person_edit.php" method="post" id="addchild{$marriage.family_id}" style="display: none">
		<input type="hidden" name="person[parents_id]" value="{$marriage.family_id}">
		<input type="hidden" name="parents_id" value="{$marriage.family_id}">
		<input type="hidden" name="return_to" value="/family/{$person_id}">
		<table align="center" class="addPerson">
		<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td><a href="#" onclick="$('addchild{$marriage.family_id}').hide(); $('addchildh{$marriage.family_id}').show(); return false;">Hide</a></td></tr>
		<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20" id="addchild{$marriage.family_id}_name"></td>
			<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20" value="{if $marriage.gender == 'M'}{$marriage.family_name|escape}{else}{$individual.family_name|escape}{/if}"></td>
			<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
			<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	{/if}
	</table>
	</td></tr>
	
	</td></tr>
	</table>
	<br />
{/foreach}
{/if}
{if $is_logged_on}
<table class="table1">
<tr valign="top"><td class="content" align="center">
<h3 id="addspouseh"><a href="#" onclick="$('addspouse').show(); $('addspouseh').hide(); $('addSpouseName').focus(); return false;">Add Spouse</a></h3>
<form action="/person_edit.php" method="post" id="addspouse" style="display: none">
<h3>New Spouse:</h3>
	<input type="hidden" name="person[spouse_id]" value="{$individual.person_id}">
	<table align="center" class="addPerson">
	<tr><td class="label">Given name(s):</td><td class="label">Family name:</td><td class="label">Birthdate:</td><td><a href="#" onclick="$('addspouse').hide(); $('addspouseh').show(); return false;">Hide</a></td></tr>
	<tr><td><input type="text" class="textfield" name="person[given_name]" class="editPerson" size="20" id="addSpouseName"></td>
		<td><input type="text" class="textfield" name="person[family_name]" class="editPerson" size="20"></td>
		<td><input type="text" class="textfield" name="person[e][BIRT][event_date]" class="editPerson" size="10"></td>
		<td rowspan="2" align="center"><input type="submit" value="Add" /></td>
	</tr>
	</table>
</form>
</td>
</tr></table>
<br>
<table class="table1" id="merge" style="display: none">
<td class="content">
<h3>Merge Family Members:</h3>
<form action="/merge.php" method="GET">
<a name="merge"></a>
<input type="hidden" name="returnto" value="/family/{$individual.person_id}">
Merge ID#<input type="textbox" class="textfield" size="6" name="p1" id="merge1"> with ID#<input type="textbox" class="textfield" size="6" name="p2" id="merge2">
<input type="submit" value="Merge">
</form>
</td></tr></table>
<br />
{/if}

{include file="footer.tpl"}
