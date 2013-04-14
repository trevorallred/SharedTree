{include file="header.tpl" title="Match Individuals"}

<h2>Merge Individuals</h2>
{if $matched}
Successfully merged: <a href="/family/{$matched}">View Family</a> or <a href="/person/{$matched}">View Individual</a><br><br>
{/if}

<font size=1 color="#999999"><i>Merging records can be a complex process. Please read the <a href="/w/How_To_Merge">Help Guide</a> before attempting to merge your own records and especially before attempting to merge records entered by others.</i></font><br />
<table class="grid">
<tr>
	<td>Keep</td>
	<td>Parents &amp; Spouses</td>
	<td></td>
	<td>Remove</td>
	<td>Parents &amp; Spouses</td>
	<td>Score</td>
	<td></td>
</tr>
{foreach item=match from=$matches}
<tr>
	<td>{$match.person_to_id} - <a href="/person/{$match.person_to_id}">{$match.given_name1} {$match.family_name1}</a> ({$match.birth_year1})</td>
	<td>P: {$match.parents_to} <br />S:{$match.spouses_to}</td>
	<td>&lt;&lt;</td>
	<td>{$match.person_from_id} - <a href="/person/{$match.person_from_id}">{$match.given_name2} {$match.family_name2}</a> ({$match.birth_year2})</td>
	<td>P:{$match.parents_from}<br />S:{$match.spouses_from}</td>
	<td style="background: {$match.color}">{$match.similarity_score}</td>
	<td>
		<a href="merge.php?p1={$match.person_from_id}&p2={$match.person_to_id}">Review</a><br />
		<a href="merge_project.php?action=saveproject&p2={$match.person_from_id}&p1={$match.person_to_id}">Project</a>
	</td>
</tr>
{/foreach}
</table>
<label>Filter:</label>
{if $show=="all"}
	<a href="merge.php?action=list">Show Only My Records Needing Matches</a>
{else}
	<a href="merge.php?action=list&show=all">Show Everyone's Records Needing Matches</a>
{/if}
{if $pages > 1}
	<label>Page:</label>
	{section name=page start=1 loop=$pages+1}
	{if $smarty.section.page.index != $page}
		<a href="merge.php?action=list&page={$smarty.section.page.index}{if $show=="all"}&show=all{/if}">{$smarty.section.page.index}</a>
	{else}
		{$smarty.section.page.index}
	{/if}
	{/section}
{/if}

<table class="portal">
<tr><td>
<h3>Utilities</h3>
<a href="merge_history.php">Review Past Merges</a> - Display a list of recent merges that have been done by all users<br>
<a href="?action=match">Run Matching Engine</a> - Using a list of individuals you recently added, run a matching program to find potential duplicates

</td></tr></table>

{include file="footer.tpl"}
