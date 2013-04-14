{include file="header.tpl" title="Match Individuals"}

<h2>Merge Individuals</h2>

<font size=1 color="#999999"><i>Merging records can be a complex process. Please read the <a href="/w/How_To_Merge">Help Guide</a> before attempting to merge your own records and especially before attempting to merge records entered by others.</i></font><br />
<table class="grid">
<tr>
	<td>Keep</td>
	<td>Merge</td>
</tr>
{foreach item=match from=$projects}
<tr>
	<td><a href="merge_project.php?action=main&project_id={$match.project_id}">{$match.person1_id} - {$match.person_name1} ({$match.byear1})</a></td>
	<td><a href="merge_project.php?action=main&project_id={$match.project_id}">{$match.person2_id} - {$match.person_name2} ({$match.byear2})</a></td>
</tr>
{/foreach}
</table>

{include file="footer.tpl"}
