<ul id="tabnav">
	<li><a href="?action=main&project_id={$project.project_id}">Match</a></li>
	<li><a href="?action=main_merge&project_id={$project.project_id}" class="active">Merge</a></li>
</ul>
<div id="content">
	<div id="project_page_status"></div>
	<table border="0" width="100%">
	<tr>
	<td valign="top" style="border: 1px solid black; background: white; width: 300px; text-align: left">
		<ul>
		{foreach item=person from=$list}
			{if $person.person_id == $project.person2_id && $list|@count > 1}
				<li>{$person.full_name}</li>
			{else}
				<li><a href="#" onclick="showMerge('{$person.person_id}', '{$person.match_id}'); return false;">{$person.full_name}</a></li>
			{/if}
		{/foreach}
		</ul>
		</div>
	</td>
	<td id="merge_area" valign="middle" style="text-align: left">
		<p align="center">
	{if $next_item}
		Select a person on the left to begin merging
	{else}
		<a href="person/{$project.person1_id}">Continue</a>
	{/if}
		</p>
	</td>
	</tr>
	</table>
</div>
{if $next_item}
<script type="text/javascript">
showMerge('{$next_item.person_id}', '{$next_item.match_id}');
</script>
{/if}

