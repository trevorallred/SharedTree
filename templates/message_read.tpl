{include file="message_header.tpl" title="Message List"}

<table class="grid">
<tr>
	<th></th>
	<th>Date</th>
	<th>From</th>
	<th>Subject</th>
</tr>
	{foreach item=msg from=$messages}
<tr>
	<td></td>
	<td><a href="messages.php?action=read&msg_id={$msg.message_id}">{$msg.from_name}</a></td>
</tr>
	{/foreach}
</table>

{if $pages > 1}
	Page:
	{section name=page start=1 loop=$pages+1}
	{if $smarty.section.page.index != $request.page}
		<a href="messages.php?page={$smarty.section.page.index}&sort={$sort}">{$smarty.section.page.index}</a>
	{else}
		{$smarty.section.page.index}
	{/if}
	{/section}
{/if}

{include file="message_footer.tpl"}
