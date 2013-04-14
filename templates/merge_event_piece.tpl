{if $e1.$var || $e2.$var}
	<tr onmouseover="$('history1_{$i}{$var}').show(); $('history2_{$i}{$var}').show();" onmouseout="$('history1_{$i}{$var}').hide(); $('history2_{$i}{$var}').hide();">
	<td>{$prompt}</td>
	<td><input type="radio" id="merge{$i}{$var}1" name="merge[e][{$i}][{$var}]" value="1" {if $e1.$var}checked{/if}></td>
	<td><label for="merge{$i}{$var}1">{$e1.$var}</label> <a name="{$i}{$var}" /><div id="history1_{$i}{$var}"></div></td>
	<td>
{if $e1.$var == $e2.$var}
	-
{else}
<input type="radio" id="merge{$i}{$var}2" name="merge[e][{$i}][{$var}]" value="2"
{if $var == "date_approx"}
	{if $e1.event_date <= $e2.event_date && $e1.$var == "" && $e2.$var}checked{/if}
{else}
	{if $e1.$var == "" && $e2.$var}checked{/if}
{/if}
>
{/if}</td>
	<td><label for="merge{$i}{$var}2">{$e2.$var}</label> <div id="history2_{$i}{$var}"></div></td>
	<td>
		<a href="#{$i}{$var}" title="Show past history of changes to {$var} {$prompt} listed by the number of contributers for each value" onclick="checkHistory({$p1.person_id}, {$p2.person_id}, '{$i}', '{$var}'); return false;">Show</a></td>
	</tr>
{/if}
