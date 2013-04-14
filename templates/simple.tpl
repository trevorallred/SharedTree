{include file="header.tpl"}
<br>
<script type="text/javascript">
{literal}
function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}
{/literal}
</script>

<h1>{$title}</h1>

{if $message=="DONE"}
<table class="portal">
<tr><td>
Congratulations! You've successfully started your family tree. You now have several ways to continue:
<ul>
<li>Add Siblings and Spouses by clicking on the Family links below</li>
<li><a href="import.php">Import data</a> from your personal computer</li>
<li>Search for matches by click on the Search links below</li>
<li>Continue adding more generations by clicking on the Family links below</li>
</ul>
</td></tr></table>
{else}<b>{$message}</b> <br>{/if}

<table class="table1" border="0">
{if $individual.person_id > 0}
<tr>
	<td rowspan="2">
	<fieldset>
		<legend>You</legend>
		<label>{$individual.given_name} {$individual.family_name}</label><br>
		Gender: {$individual.gender}<br>
		Birth: {$individual.e.BIRT.event_date}<br>
		Place: {$individual.e.BIRT.location}
		<br>
		<a href="person_edit.php?person_id={$individual.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
		<a href="/family/{$individual.person_id}"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add Spouse</a>
	</fieldset>
	<td>
		<fieldset>
			<legend>Your Father</legend>
		{if $father.person_id > 0}
				<label>{$father.given_name} {$father.family_name}</label><br>
				Birth: {$father.e.BIRT.event_date}<br>
				Place: {$father.e.BIRT.location}
				<br>
				<a href="person_edit.php?person_id={$father.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
				<a href="/family/{$father.person_id}"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add Your Siblings</a>
		{else}
			<form method="POST" action="person_edit.php" style="margin: 0px;">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="build_fti" value="1">
			<input type="hidden" name="return_to" value="simple.php">
			<input type="hidden" name="person[gender]" value="M">
			<input type="hidden" name="person[marriage_id]" value="{$individual.bio_family_id}">
			<input type="hidden" name="person[child_id]" value="{$individual.person_id}">
			<table class="invisible">
			<tr><td><label>First name(s)</label> {include file="help.tpl" help_text="first_name"}</td>
				<td><label>Last name</label> {include file="help.tpl" help_text="last_name"}</td>
			</tr>
			<tr><td><input type="text" name="person[given_name]"></td>
				<td><input type="text" name="person[family_name]" value="{$individual.family_name}"></td>
			</tr>
			</table>
			<table class="invisible">
			<tr><td><label>Birthdate:</label></td>
				<td>
				{html_options name="person[e][BIRT][dd]" options=$day31_options}
				{html_options name="person[e][BIRT][mon]" options=$month_options}
				<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"> {include file="help.tpl" help_text="dates"}
				</td>
			</tr>
			<tr><td><label>Place:</label></td>
				<td><input type="text" name="person[e][BIRT][location]" size="30"> {include file="help.tpl" help_text="places"}</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td><input type="submit" name="save" value="Add Father"></td>
			</tr>
			</table>
			</form>
		{/if}
		</fieldset>
	</td>
	<td>
		{if $father.person_id > 0}
			<fieldset>
				<legend>His Father</legend>
			{if $ffather.person_id > 0}
					<label>{$ffather.given_name} {$ffather.family_name}</label> ({$ffather.e.BIRT.event_date})
					<br>
					<a href="person_edit.php?person_id={$ffather.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/{$ffather.person_id}"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name={$ffather.family_name}&given_name={$ffather.given_name}&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			{else}
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="M">
				<input type="hidden" name="person[marriage_id]" value="{$father.bio_family_id}">
				<input type="hidden" name="person[child_id]" value="{$father.person_id}">
				<table class="invisible">
				<tr><td><label>First name(s)</label>
					{include file="help.tpl" help_text="first_name"}</td>
					<td><label>Last name</label> 
					{include file="help.tpl" help_text="first_name"}</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]" value="{$father.family_name}"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandfather"></td>
				</tr>
				</table>
				</form>
			{/if}
			</fieldset>
			<fieldset>
				<legend>His Mother</legend>
			{if $fmother.person_id > 0}
					<label>{$fmother.given_name} {$fmother.family_name}</label> ({$fmother.e.BIRT.event_date})
					<br>
					<a href="person_edit.php?person_id={$fmother.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/{$fmother.person_id}"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name={$fmother.family_name}&given_name={$fmother.given_name}&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			{else}
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="F">
				<input type="hidden" name="person[marriage_id]" value="{$father.bio_family_id}">
				<input type="hidden" name="person[child_id]" value="{$father.person_id}">
				<table class="invisible">
				<tr><td><label>First name(s)</label> {include file="help.tpl" help_text="first_name"}</td>
					<td><label>Last name</label> {include file="help.tpl" help_text="last_name"}</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandmother"></td>
				</tr>
				</table>
				</form>
			{/if}
			</fieldset>
		{/if}
	</td>
</tr>
<tr>
	<td>
		<fieldset>
			<legend>Your Mother</legend>
		{if $mother.person_id > 0}
				<label>{$mother.given_name} {$mother.family_name}</label><br>
				Birth: {$mother.e.BIRT.event_date}<br>
				Place: {$mother.e.BIRT.location}
				<br>
				<a href="person_edit.php?person_id={$mother.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
		{else}
			<form method="POST" action="person_edit.php" style="margin: 0px;">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="build_fti" value="1">
			<input type="hidden" name="return_to" value="simple.php">
			<input type="hidden" name="person[gender]" value="F">
			<input type="hidden" name="person[marriage_id]" value="{$individual.bio_family_id}">
			<input type="hidden" name="person[child_id]" value="{$individual.person_id}">
			<table class="invisible">
			<tr><td><label>First name(s)</label>
				{include file="help.tpl" help_text="first_name"}</td>
				<td><label>Last name</label> 
				{include file="help.tpl" help_text="first_name"}</td>
			</tr>
			<tr><td><input type="text" name="person[given_name]"></td>
				<td><input type="text" name="person[family_name]"></td>
			</tr>
			</table>
			<table class="invisible">
			<tr><td><label>Birthdate:</label></td>
				<td>
				{html_options name="person[e][BIRT][dd]" options=$day31_options}
				{html_options name="person[e][BIRT][mon]" options=$month_options}
				<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"> {include file="help.tpl" help_text="dates"}
				</td>
			</tr>
			<tr><td><label>Place:</label></td>
				<td><input type="text" name="person[e][BIRT][location]" size="30"> {include file="help.tpl" help_text="places"}</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td><input type="submit" name="save" value="Add Mother"></td>
			</tr>
			</table>
			</form>
		{/if}
		</fieldset>
	</td>
	<td>
		{if $mother.person_id > 0}
			<fieldset>
				<legend>Her Father</legend>
			{if $mfather.person_id > 0}
					<label>{$mfather.given_name} {$mfather.family_name}</label> ({$mfather.e.BIRT.event_date})
					<br>
					<a href="person_edit.php?person_id={$mfather.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/{$mfather.person_id}"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name={$mfather.family_name}&given_name={$mfather.given_name}&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			{else}
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="M">
				<input type="hidden" name="person[marriage_id]" value="{$mother.bio_family_id}">
				<input type="hidden" name="person[child_id]" value="{$mother.person_id}">
				<table class="invisible">
				<tr><td><label>First name(s)</label> {include file="help.tpl" help_text="first_name"}</td>
					<td><label>Last name</label> {include file="help.tpl" help_text="last_name"}</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]" value="{$mother.family_name}"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandfather"></td>
				</tr>
				</table>
				</form>
			{/if}
			</fieldset>
			<fieldset>
				<legend>Her Mother</legend>
			{if $mmother.person_id > 0}
					<label>{$mmother.given_name} {$mmother.family_name}</label> ({$mmother.e.BIRT.event_date})
					<br>
					<a href="person_edit.php?person_id={$mmother.person_id}"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/{$mmother.person_id}"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name={$mmother.family_name}&given_name={$mmother.given_name}&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			{else}
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="F">
				<input type="hidden" name="person[marriage_id]" value="{$mother.bio_family_id}">
				<input type="hidden" name="person[child_id]" value="{$mother.person_id}">
				<table class="invisible">
				<tr><td><label>First name(s)</label> {include file="help.tpl" help_text="first_name"}</td>
					<td><label>Last name</label> {include file="help.tpl" help_text="last_name"}</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandmother"></td>
				</tr>
				</table>
				</form>
			{/if}
			</fieldset>
		{/if}
	</td>
</tr>
{else}
<tr>
<td class="content">
	<fieldset>
		<legend>You</legend>
	<form method="POST" action="person_edit.php">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="return_to" value="simple.php">
	<input type="hidden" name="attachuser" value="{$user.user_id}">
	<table class="invisible">
	<tr><td><label>First name(s)</label> {include file="help.tpl" help_text="first_name"}</td>
		<td><label>Last name</label> {include file="help.tpl" help_text="last_name"}</td>
	</tr>
	<tr><td><input type="text" name="person[given_name]" value="{$user.given_name}"></td>
		<td><input type="text" name="person[family_name]" value="{$user.family_name}"></td>
	</tr>
	</table>
	<table class="invisible">
	<tr><td><label>Gender:</label></td>
		<td>{html_radios name="person[gender]" options=$gender_options}</td>
	</tr>
	<tr><td><label>Birthdate:</label></td>
		<td>
		{html_options name="person[e][BIRT][dd]" options=$day31_options}
		{html_options name="person[e][BIRT][mon]" options=$month_options}
		<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"> {include file="help.tpl" help_text="dates"}
		</td>
	</tr>
	<tr><td><label>Place:</label></td>
		<td><input type="text" name="person[e][BIRT][location]" size="30"> {include file="help.tpl" help_text="places"}</td>
	</tr>
	<tr><td>&nbsp;</td>
		<td><input type="submit" name="save" value="Save"></td>
	</tr>
	</table>
	</form>
	</fieldset>
</td></tr>
{/if}
</table>

<table class="portal">
{if $individual.person_id > 0}
<br>
<tr>
<td>
<form method="GET" action="chart.php" target="_BLANK">
	<input type="hidden" name="person_id" value="{$individual.person_id}" />
	<input type="hidden" name="chart" value="pedigree" />
	<input type="hidden" name="gen" value="4" />
	<input type="submit" value="Create Pedigree Chart">
	<br>
<a href="/w/Printing_PDFs">Tips for printing PDFs</a>
</form>
</td>
<td width="500">
<h3>Navigation tips</h3>

<table>
<tr>
	<td style="vertical-align: middle"><img src="img/ico_help.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Help</label></td>
	<td>Click Help in the top right hand corner if you're ever confused about what to do next. This opens a new help window that will assist you.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/ico_family.gif" border="0" width="14" height="15"></td>
	<td style="vertical-align: middle"><label>Family</label></td>
	<td>This page shows the person and their immediate family members (parents, spouses, and children). You go here to add more family members.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/ico_indi.gif" border="0" width="11" height="15"></td>
	<td style="vertical-align: middle"><label>Individual</label></td>
	<td>This page focuses on the individual, events in their life and specific research and family history regarding that person. Add photos, post messages, and write biographies about this individual here.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_pedigree.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Pedigree</label></td>
	<td>See and print a person's ancestors.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_descend.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Descendents</label></td>
	<td>See and print a person's descendents.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_edit.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Edit</label></td>
	<td>Make changes to a person's name, life events, and other important details.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_docs.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>History</label></td>
	<td>View and revert changes to a person</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/larrow.gif" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Export</label></td>
	<td>Export several generations of data to a GEDCOM</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/rarrow.gif" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Import</label></td>
	<td>Import a GEDCOM file into SharedTree</td>
</tr>
</table>

</td>
</tr>
{/if}

<tr>
<td colspan="2">
<label>Other Links</label> - 
<a href="/w/Getting_Started">Help: Getting Started</a> | 
<a href="import.php">Import a GEDCOM</a> |
<a href="index.php">My Tree</a>
</td>
</tr>
</table>

{include file="footer.tpl"}
