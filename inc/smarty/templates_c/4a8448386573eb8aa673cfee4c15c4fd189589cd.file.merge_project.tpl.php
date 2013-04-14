<?php /* Smarty version Smarty-3.1.7, created on 2013-03-26 14:11:26
         compiled from "/var/www/sharedtree/templates/merge_project.tpl" */ ?>
<?php /*%%SmartyHeaderCode:69077302351520efecc92e1-60355496%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a8448386573eb8aa673cfee4c15c4fd189589cd' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_project.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69077302351520efecc92e1-60355496',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'project' => 0,
    'title' => 0,
    'tree' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51520eff247f7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51520eff247f7')) {function content_51520eff247f7($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('includejs'=>1), 0);?>

<script type="text/javascript">
var project_id = <?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
;

function call(action, target, pars) {
	//$('project_page_status').show();
	pars = 'action='+action+pars;
	pars = pars + '&project_id=<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
';
	$('project_page_status').innerHTML = action + target + pars;
	var myAjax = new Ajax.Updater(target, 'merge_project.php', {method: 'get', parameters: pars});
}
function showMerge(person2_id, person1_id) {
	$('merge_area').innerHTML = '<table class="portal" width="200"><tr><td align="center" style="text-align: center"><br>Retrieving Records<br><br><img src="img/spinner_orange.gif" /><br><br><br><br></td></tr></table>';
	var returnto = escape('merge_project.php?action=main_merge&project_id=<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
');
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


<div style="text-align: left">
<a href="merge_project.php?action=list" style="text-decoration: none;">&lt;&lt;&nbsp;Return to List</a>
</div>
<h2><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>

<table class="portal">
<tr align="left">
<td class="label">Merge</td>
<td rowspan="2" style="vertical-align: middle; background-color: #DDD"><b>&gt;&gt; <br> &gt;&gt;</b></td>
<td class="label">Keep</td>
</tr>
<tr align="left">
<td><a href="person/<?php echo $_smarty_tpl->tpl_vars['tree']->value['person_id'];?>
" target="_BLANK"><strong><?php echo $_smarty_tpl->tpl_vars['tree']->value['full_name'];?>
</strong></a>
	<?php if ($_smarty_tpl->tpl_vars['tree']->value['b_date']||$_smarty_tpl->tpl_vars['tree']->value['b_location']){?><br>Birth: <?php echo $_smarty_tpl->tpl_vars['tree']->value['b_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['tree']->value['b_location'];?>
<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['tree']->value['d_date']||$_smarty_tpl->tpl_vars['tree']->value['d_location']){?><br>Death: <?php echo $_smarty_tpl->tpl_vars['tree']->value['d_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['tree']->value['d_location'];?>
<?php }?>
</td>
<td><a href="person/<?php echo $_smarty_tpl->tpl_vars['tree']->value['match']['person_id'];?>
" target="_BLANK"><strong><?php echo $_smarty_tpl->tpl_vars['tree']->value['match']['full_name'];?>
</strong></a>
	<?php if ($_smarty_tpl->tpl_vars['tree']->value['match']['b_date']||$_smarty_tpl->tpl_vars['tree']->value['match']['b_location']){?><br>Birth: <?php echo $_smarty_tpl->tpl_vars['tree']->value['match']['b_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['tree']->value['match']['b_location'];?>
<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['tree']->value['match']['d_date']||$_smarty_tpl->tpl_vars['tree']->value['match']['d_location']){?><br>Death: <?php echo $_smarty_tpl->tpl_vars['tree']->value['match']['d_date'];?>
 in <?php echo $_smarty_tpl->tpl_vars['tree']->value['match']['d_location'];?>
<?php }?>
</td>
</tr>
</table>

<div id="project_pages">
<?php if ($_smarty_tpl->tpl_vars['action']->value=="main"){?>
	<?php echo $_smarty_tpl->getSubTemplate ("merge_project_desc.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<?php echo $_smarty_tpl->getSubTemplate ("merge_project_merge.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>