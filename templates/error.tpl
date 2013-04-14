<p class="errors">
Sorry but the following error ocurred while attempting to submit your request:
</p>
<hr />
<p class="errors">
{$error}

{if $url}<br /><br /><a href="{$url}">Continue &gt;&gt;</a>{/if}
</p>

{if $logging}
	<h3>Developer Logging</h3>
	<pre class="errors">{$logging}</pre>
{/if}

{include file="footer.tpl"}
