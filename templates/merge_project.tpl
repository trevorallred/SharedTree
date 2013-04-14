{include file="header.tpl" includejs=1}
<script type="text/javascript">
var project_id = {$project.project_id};
{literal}
function call(action, target, pars) {
	//$('project_page_status').show();
	pars = 'action='+action+pars;
	pars = pars + '&project_id={/literal}{$project.project_id}{literal}';
	$('project_page_status').innerHTML = action + target + pars;
	var myAjax = new Ajax.Updater(target, 'merge_project.php', {method: 'get', parameters: pars});
}
function showMerge(person2_id, person1_id) {
	$('merge_area').innerHTML = '<table class="portal" width="200"><tr><td align="center" style="text-align: center"><br>Retrieving Records<br><br><img src="img/spinner_orange.gif" /><br><br><br><br></td></tr></table>';
	var returnto = escape('merge_project.php?action=main_merge&project_id={/literal}{$project.project_id}{literal}');
	pars = 'ajax=1&p1='+person1_id+'&p2='+person2_id+'&returnto='+returnto;
	var myAjax = new Ajax.Updater('merge_area', 'merge.php', {method: 'get', parameters: pars});
}
function selectSpouse(id) {
	var pars = "";
	var form = $('spouses'+id);
	
	var input = form['family_id'];
	if ($F(input)=="") {
		alert("You must select a match option before continuing");
		return false;
	}
	pars = pars + '&family1_id='+$F(input);
	pars = pars + '&family2_id='+id;
	call('select_spouse', 'tr_fam'+id, pars);
}
function selectChild(id) {
	var pars = "";
	var form = $('children'+id);
	
	var input = form['person_id'];
	if ($F(input)=="") {
		alert("You must select a match option before continuing");
		return false;
	}
	pars = pars + '&person1_id='+$F(input);
	pars = pars + '&person2_id='+id;
	call('select_child', 'tr_chil'+id, pars);
}
function undoSpouse(id) {
	call('select_spouse', 'tr_fam'+id, '&family2_id='+id);
}
function undoChild(id) {
	call('select_child', 'tr_chil'+id, '&person2_id='+id);
}
function matchParent(family_id, person_id, match_action) {
	call('match_parent', 'tr_par'+family_id, "&family_id="+family_id+"&person_id="+person_id+"&match_action="+match_action);
}

function checkHistory(p1, p2, table, field) {
        var pars = 'action=ajax&person_id='+p1+'&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history1_'+table+field, '../ajax_fieldchanges.php', {method: 'get', parameters: pars});

        var pars = 'action=ajax&person_id='+p2+'&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history2_'+table+field, '../ajax_fieldchanges.php', {method: 'get', parameters: pars});
}

function highlight(person, highlighted) {
	return;
	if (highlighted) {
		$(person).setStyle({'border': '1px solid #333'});
	} else {
		$(person).setStyle({'border': '1px solid #AAA'});
	}
}
</script>
<style>
div.person {
	margin: 0px;
	padding-left: 10px;
	padding-top: 3px;
	padding-bottom: 1px;
	padding-right: 1px;
	border: 1px solid #AAA;
}
table.pending {
	background-color: white;
}
table.matched {
	background-color: #DDA;
}

#project_page_status {
	position: absolute;
	background: #FFF;
	width: 200px;
	top: 2px;
	right: 2px;
	margin: 1px;
	border: 1px solid black;
	padding: 3px;
	display: none;
}
#project_pages
	{
		width: 90%;
	}

#tabnav
	{
		height: 20px;
		margin: 0;
		padding-left: 10px;
		background: url(img/tab_bottom.gif) repeat-x bottom;
	}

#tabnav li
	{
		margin: 0; 
		padding: 0;
  		display: inline;
  		list-style-type: none;
  		cursor: pointer;
  	}
	
#tabnav a:link, #tabnav a:visited
	{
		float: left;
		background: #CC9;
		font-size: 10px;
		line-height: 14px;
		font-weight: bold;
		padding: 2px 10px 2px 10px;
		margin-right: 4px;
		border: 1px solid #666;
		text-decoration: none;
		color: #666;
	}

#tabnav a:link.active, #tabnav a:visited.active
	{
		border-bottom: 1px solid #DDA;
		background: #DDA;
		color: #160;
	}

#tabnav a:hover
	{
		background: #DDA;
	}
	
#content {
	font-family: arial;
	background: #DDA;
	border-bottom: 1px solid #666;
	border-left: 1px solid #666;
	border-right: 1px solid #666;
	border-top: 0px;
	padding: 10px;
	margin: 0px;
	width: 100%;
	position: relative;
	text-align: left;
}

</style>
{/literal}

<div style="text-align: left">
<a href="merge_project.php?action=list" style="text-decoration: none;">&lt;&lt;&nbsp;Return to List</a>
</div>
<h2>{$title}</h2>

<table class="portal">
<tr align="left">
<td class="label">Merge</td>
<td rowspan="2" style="vertical-align: middle; background-color: #DDD"><b>&gt;&gt; <br> &gt;&gt;</b></td>
<td class="label">Keep</td>
</tr>
<tr align="left">
<td><a href="person/{$tree.person_id}" target="_BLANK"><strong>{$tree.full_name}</strong></a>
	{if $tree.b_date || $tree.b_location}<br>Birth: {$tree.b_date} in {$tree.b_location}{/if}
	{if $tree.d_date || $tree.d_location}<br>Death: {$tree.d_date} in {$tree.d_location}{/if}
</td>
<td><a href="person/{$tree.match.person_id}" target="_BLANK"><strong>{$tree.match.full_name}</strong></a>
	{if $tree.match.b_date || $tree.match.b_location}<br>Birth: {$tree.match.b_date} in {$tree.match.b_location}{/if}
	{if $tree.match.d_date || $tree.match.d_location}<br>Death: {$tree.match.d_date} in {$tree.match.d_location}{/if}
</td>
</tr>
</table>

<div id="project_pages">
{if $action=="main"}
	{include file="merge_project_desc.tpl"}
{else}
	{include file="merge_project_merge.tpl"}
{/if}
</div>

{include file="footer.tpl"}
