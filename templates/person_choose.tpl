<html>
<head>
{literal}
<script type="text/javascript">
function updateParent($family, $action, $parent_id) {
	opener.location.href = 'marriage.php?family_id=' + $family + '&' + $action + '=' + $parent_id;
	self.close();
	return false;
}
function setFocus($name) {
	document.getElementById($name).focus()
}
</script>
{/literal}
</head>
<body onload="setFocus('family_name')">

<form method="GET">
<input type="hidden" name="family_id" value="{$family_id}">
<input type="hidden" name="action" value="{$action}">
ID #: <input type="text" name="search[person_id]" value="{$search.person_id}"><br>OR<br>
Family name: <input type="text" id="family_name" name="search[family_name]" value="{$search.family_name}"><br>
Given name: <input type="text" name="search[given_name]" value="{$search.given_name}"><br>
<input type="submit" value="Search"><br>
</form>

{if $results}
<table border="1">
<tr>
	<td>ID</td>
	<td>Family name</td>
	<td>Given name</td>
	<td>Birth</td>
	<td>Choose</td>
</tr>
{foreach item=result from=$results}
<tr>
	<td>{$result.person_id}</td>
	<td>{$result.family_name}</td>
	<td>{$result.given_name}</td>
	<td>{$result.birth_year}</td>
	<td><a href="marriage.php?family_id={$family_id}&{$action}={$result.person_id}" onclick="updateParent({$family_id}, '{$action}', {$result.person_id})">Choose</a></td>
</tr>
{/foreach}
</table>
{/if}
<br>
<br>
<form>
<input type=button value="Close" onClick="javascript:window.close();">
</form>
</body>
</html>