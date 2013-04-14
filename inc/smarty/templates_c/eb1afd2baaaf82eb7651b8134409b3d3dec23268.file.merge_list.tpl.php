<?php /* Smarty version Smarty-3.1.7, created on 2013-03-06 13:13:45
         compiled from "/var/www/sharedtree/templates/merge_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20566769965137b189b56756-79415138%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eb1afd2baaaf82eb7651b8134409b3d3dec23268' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_list.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20566769965137b189b56756-79415138',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'matched' => 0,
    'matches' => 0,
    'match' => 0,
    'show' => 0,
    'pages' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5137b18a057f2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5137b18a057f2')) {function content_5137b18a057f2($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Match Individuals"), 0);?>


<h2>Merge Individuals</h2>
<?php if ($_smarty_tpl->tpl_vars['matched']->value){?>
Successfully merged: <a href="/family/<?php echo $_smarty_tpl->tpl_vars['matched']->value;?>
">View Family</a> or <a href="/person/<?php echo $_smarty_tpl->tpl_vars['matched']->value;?>
">View Individual</a><br><br>
<?php }?>

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
<?php  $_smarty_tpl->tpl_vars['match'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['match']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['matches']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['match']->key => $_smarty_tpl->tpl_vars['match']->value){
$_smarty_tpl->tpl_vars['match']->_loop = true;
?>
<tr>
	<td><?php echo $_smarty_tpl->tpl_vars['match']->value['person_to_id'];?>
 - <a href="/person/<?php echo $_smarty_tpl->tpl_vars['match']->value['person_to_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['match']->value['given_name1'];?>
 <?php echo $_smarty_tpl->tpl_vars['match']->value['family_name1'];?>
</a> (<?php echo $_smarty_tpl->tpl_vars['match']->value['birth_year1'];?>
)</td>
	<td>P: <?php echo $_smarty_tpl->tpl_vars['match']->value['parents_to'];?>
 <br />S:<?php echo $_smarty_tpl->tpl_vars['match']->value['spouses_to'];?>
</td>
	<td>&lt;&lt;</td>
	<td><?php echo $_smarty_tpl->tpl_vars['match']->value['person_from_id'];?>
 - <a href="/person/<?php echo $_smarty_tpl->tpl_vars['match']->value['person_from_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['match']->value['given_name2'];?>
 <?php echo $_smarty_tpl->tpl_vars['match']->value['family_name2'];?>
</a> (<?php echo $_smarty_tpl->tpl_vars['match']->value['birth_year2'];?>
)</td>
	<td>P:<?php echo $_smarty_tpl->tpl_vars['match']->value['parents_from'];?>
<br />S:<?php echo $_smarty_tpl->tpl_vars['match']->value['spouses_from'];?>
</td>
	<td style="background: <?php echo $_smarty_tpl->tpl_vars['match']->value['color'];?>
"><?php echo $_smarty_tpl->tpl_vars['match']->value['similarity_score'];?>
</td>
	<td>
		<a href="merge.php?p1=<?php echo $_smarty_tpl->tpl_vars['match']->value['person_from_id'];?>
&p2=<?php echo $_smarty_tpl->tpl_vars['match']->value['person_to_id'];?>
">Review</a><br />
		<a href="merge_project.php?action=saveproject&p2=<?php echo $_smarty_tpl->tpl_vars['match']->value['person_from_id'];?>
&p1=<?php echo $_smarty_tpl->tpl_vars['match']->value['person_to_id'];?>
">Project</a>
	</td>
</tr>
<?php } ?>
</table>
<label>Filter:</label>
<?php if ($_smarty_tpl->tpl_vars['show']->value=="all"){?>
	<a href="merge.php?action=list">Show Only My Records Needing Matches</a>
<?php }else{ ?>
	<a href="merge.php?action=list&show=all">Show Everyone's Records Needing Matches</a>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['pages']->value>1){?>
	<label>Page:</label>
	<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['page'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['page']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['name'] = 'page';
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'] = (int)1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['pages']->value+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'] = 1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['page']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['page']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['page']['total']);
?>
	<?php if ($_smarty_tpl->getVariable('smarty')->value['section']['page']['index']!=$_smarty_tpl->tpl_vars['page']->value){?>
		<a href="merge.php?action=list&page=<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>
<?php if ($_smarty_tpl->tpl_vars['show']->value=="all"){?>&show=all<?php }?>"><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>
</a>
	<?php }else{ ?>
		<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>

	<?php }?>
	<?php endfor; endif; ?>
<?php }?>

<table class="portal">
<tr><td>
<h3>Utilities</h3>
<a href="merge_history.php">Review Past Merges</a> - Display a list of recent merges that have been done by all users<br>
<a href="?action=match">Run Matching Engine</a> - Using a list of individuals you recently added, run a matching program to find potential duplicates

</td></tr></table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>