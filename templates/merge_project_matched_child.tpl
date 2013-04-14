<table class="grid" id="child{$kid1.person_id}" width="100%">
	<tr valign="top">
	<td width="40%">
	<label>{$kid1.full_name}</label> {$kid1.b_date}-{$kid1.d_date}
	</td>
	<td width="60%">
	<label>{$kid2.full_name}</label> {$kid2.b_date}-{$kid2.d_date} <a href="#" onclick="">Undo</a>
	</td>
	</tr>
</table><br /><br />
{/foreach}

</form>

