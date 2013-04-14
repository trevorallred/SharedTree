<ul id="tabnav">
	<li><a href="?action=main&project_id={$project.project_id}" class="active">Match</a></li>
	<li><a href="?action=main_merge&project_id={$project.project_id}">Merge</a></li>
</ul>
<div id="content">
	<div id="project_page_status"></div>
	<div id="tr_par{$tree.parents.family_id}">
		{include file="merge_project_desc_parents.tpl" pp2=$tree.parents pp1=$tree.match.parents}
	</div>

	{if $tree.marriages}
		<b>Spouse(s):</b>
		{foreach item=fam from=$tree.marriages}
			<div class="person" 
				id="tr_fam{$fam.family_id}" 
				onmouseover="highlight('tr_fam{$fam.family_id}', true)" 
				onmouseout="highlight('tr_fam{$fam.family_id}', false)">
				{include file="merge_project_desc_spouse.tpl" f=$fam f1s=$tree.match.marriages}
			</div>
		{/foreach}
	{else}
		There are no descendents to match
	{/if}
	<div style="float: left; margin: 2px; padding: 3px; border: 2px gray solid">
		<a href="?action=reset&project_id={$project.project_id}" style="text-decoration: none" title="Undo all matches and reset the project">Start Over</a>
	</div>
	<div style="float: right; margin: 2px; padding: 3px; border: 2px gray solid">
		<a href="?action=main_merge&project_id={$project.project_id}" style="text-decoration: none" title="Begin Merging">Begin Merging</a>
	</div>
</div>
