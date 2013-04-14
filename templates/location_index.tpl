{include file="header.tpl" title="Locations"}

{foreach item=loc from=$locations}
	{foreach item=place from=$loc}
	  <a href="list.php?family={$family.family_name}">{$family.family_name} ({$family.total})</a><br />
	{/foreach}
{/foreach}


{include file="footer.tpl"}
