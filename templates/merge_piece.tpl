{if $p1.$var || $p2.$var}
	<tr onmouseover="$('history1_{$var}').show(); $('history2_{$var}').show();" onmouseout="$('history1_{$var}').hide(); $('history2_{$var}').hide();">
	<td>{$prompt}</td>
	<td>{if $info != "true"}<input type="radio" id="merge{$var}1" name="merge[{$var}]" value="1" {if $p1.$var}checked{/if}>{/if}</td>
	<td><label for="merge{$var}1">{$p1.$var}
		{if $var == "bio_family_id"} <a href="marriage.php?family_id={$p1.$var}">{$p1.parents}</a>{/if}</label>
		<div id="history1_{$var}"></div>
	</td>
	<td>{if $info != "true"}{if $p1.$var == $p2.$var}-{else}<input type="radio" id="merge{$var}2" name="merge[{$var}]" value="2"  {if $p1.$var == "" && $p2.$var}checked{/if}>{/if}{/if}</td>
	<td><label for="merge{$var}2">{$p2.$var}
		{if $var == "bio_family_id"} <a href="marriage.php?family_id={$p2.$var}">{$p2.parents}</a>{/if}</label>
		<div id="history2_{$var}"></div>
	</td>
	<td>
		{if $info != "true" && $var!="wiki_text"}
		<a href="#" title="Show past history of changes to {$prompt} listed by the number of contributers for each value" onclick="checkHistory({$p1.person_id}, {$p2.person_id}, '', '{$var}'); return false;">Show</a>
		{/if}
	</tr>
{/if}
