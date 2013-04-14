<a href="import.php?action=listpeople&import_id={$import_id}">Review Imported Records</a>

<table class="grid">
<tr>
	<th>Execution time</th>
	<th>Bytes Read</th>
	<th>Records found</th>
	<th>Type</th>
</tr>
{foreach item=type from=$listtype}
<tr>
	<td align="right">{$type.exectime} secs</td>
	<td align="right">{$type.bytes}</td>
	<td align="right">{$type.i}</td>
	<td>{$type.type}</td>
</tr>
{/foreach}
<tr>
	<td align="right">{$exectime} secs</td>
	<td align="right">{$file_size}</td>
	<td align="right">{$total_records}&nbsp;</td>
	<td></td>
</tr>
</table>



<script type="text/javascript">update_progress({$file_size}, {$exectime});</script>
<script type="text/javascript">complete_progress({$exectime});</script>

{include file="footer.tpl"}
