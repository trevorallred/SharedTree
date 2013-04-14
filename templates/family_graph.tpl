{include file="header.tpl" title="Family Graph"}
<h2>Family Graph: {$user.given_name} {$user.family_name}</h2>

<table border="1"
<tr>
	<td colspan=2 title="{$r.P.description}">{$r.P.total}</td>
</tr>
<tr>
	<td title="{$r.PC.description}">{$r.PC.total}</td>
	<td title="{$r.X.description}">{$r.X.total}</td>
</tr>
</table>

{include file="footer.tpl"}
