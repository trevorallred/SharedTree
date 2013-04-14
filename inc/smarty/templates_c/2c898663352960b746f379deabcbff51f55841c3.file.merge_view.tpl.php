<?php /* Smarty version Smarty-3.1.7, created on 2013-03-08 19:01:46
         compiled from "/var/www/sharedtree/templates/merge_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1262277111513aa61a88a2b4-76272033%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c898663352960b746f379deabcbff51f55841c3' => 
    array (
      0 => '/var/www/sharedtree/templates/merge_view.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1262277111513aa61a88a2b4-76272033',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ajax' => 0,
    'returnto' => 0,
    'p1' => 0,
    'p2' => 0,
    'gedcomcodes' => 0,
    'gcode' => 0,
    'i' => 0,
    'e1' => 0,
    'e2' => 0,
    'forumposts' => 0,
    'post' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_513aa61ad20b4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_513aa61ad20b4')) {function content_513aa61ad20b4($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['ajax']->value!=1){?>
	<?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Merge Individuals",'includejs'=>1), 0);?>

	<h3>Merge Individuals</h3>
	<b><i>If in doubt, don't merge!! Let someone else with more experience and information merge the records</i></b><br><br>
	
	<a href="merge.php?action=list">Show List</a>
<?php }?>


<script type="text/javascript">
function checkHistory(p1, p2, table, field) {
        var pars = 'action=ajax&person_id='+p1+'&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history1_'+table+field, '../ajax_fieldchanges.php', {method: 'get', parameters: pars});

        var pars = 'action=ajax&person_id='+p2+'&table='+table+'&field='+field;
        var myAjax = new Ajax.Updater('history2_'+table+field, '../ajax_fieldchanges.php', {method: 'get', parameters: pars});
}
</script>

<form action="merge.php" method="POST">
<input type="hidden" name="returnto" value="<?php echo $_smarty_tpl->tpl_vars['returnto']->value;?>
">
<input type="hidden" name="p1" value="<?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
">
<input type="hidden" name="p2" value="<?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
">
<table class="grid">
<tr>
	<td></td>
	<td></td>
	<td align="center">
	<label title="Merge into this record">Record #<?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
</label><br />
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['p1']->value['person_id'],'direction'=>"flat"), 0);?>

	</td>
	<td></td>
	<td align="center">
	<label title="Delete this record">Record #<?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
</label><br />
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['p2']->value['person_id'],'direction'=>"flat"), 0);?>

	</td>
	<th>Show</th>
</tr>
<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Merge Rank",'var'=>"merge_rank",'info'=>"true"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Family name",'var'=>"family_name"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Given name",'var'=>"given_name"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Nickname",'var'=>"nickname"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Gender",'var'=>"gender"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Title",'var'=>"title"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Parents",'var'=>"bio_family_id"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Biography",'var'=>"wiki_text"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Child Order",'var'=>"child_order"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"RIN",'var'=>"rin"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"AFN",'var'=>"afn"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Nat'l ID",'var'=>"national_id"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Prefix",'var'=>"prefix"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Suffix",'var'=>"suffix"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Occupation",'var'=>"occupation"), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Wikipedia",'var'=>"wikipedia"), 0);?>


<?php  $_smarty_tpl->tpl_vars['gcode'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['gcode']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gedcomcodes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['gcode']->key => $_smarty_tpl->tpl_vars['gcode']->value){
$_smarty_tpl->tpl_vars['gcode']->_loop = true;
?>
	<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['gcode']->value['gedcom_code'], null, 0);?>
	<?php $_smarty_tpl->tpl_vars['e1'] = new Smarty_variable($_smarty_tpl->tpl_vars['p1']->value['e'][$_smarty_tpl->tpl_vars['i']->value], null, 0);?>
	<?php $_smarty_tpl->tpl_vars['e2'] = new Smarty_variable($_smarty_tpl->tpl_vars['p2']->value['e'][$_smarty_tpl->tpl_vars['i']->value], null, 0);?>
	
	<?php if ($_smarty_tpl->tpl_vars['e1']->value['event_id']>0||$_smarty_tpl->tpl_vars['e2']->value['event_id']>0){?>
		<tr>
		<td colspan="6" align="center"><?php echo $_smarty_tpl->tpl_vars['gcode']->value['prompt'];?>
</td>
		</tr>
		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Date",'var'=>"event_date"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Location",'var'=>"location"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Temple",'var'=>"temple_code"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Status",'var'=>"status"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Aproximation",'var'=>"date_approx"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Age at Event",'var'=>"age_at_event"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Notes",'var'=>"notes"), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("merge_event_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Source",'var'=>"source"), 0);?>

	<?php }?>
<?php } ?>
	<tr>
	<td>Spouses</td>
	<td></td>
	<td width="250"><?php echo $_smarty_tpl->tpl_vars['p1']->value['spouses'];?>
</td>
	<td></td>
	<td width="250"><?php echo $_smarty_tpl->tpl_vars['p2']->value['spouses'];?>
</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td colspan="6" align="center">Audit Information</td>
	</tr>
	<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Created by",'var'=>"created_user",'info'=>"true"), 0);?>

	<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Created on",'var'=>"creation_date",'info'=>"true"), 0);?>

	<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Updated by",'var'=>"updated_user",'info'=>"true"), 0);?>

	<?php echo $_smarty_tpl->getSubTemplate ("merge_piece.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('prompt'=>"Updated on",'var'=>"update_date",'info'=>"true"), 0);?>

</table>
<p>
All spouses, children, and discussion messages will transfer 
from <a href="/person/<?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
</a> 
to <a href="/person/<?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
</a>.<br>
Optional notes about merge (posted to <?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
): <br>
<input type="textbox" name="message" size="80">

<?php if ($_smarty_tpl->tpl_vars['forumposts']->value){?>
<table class="grid">
<tr><th>Post date</th>
    <th>Poster name</th>
    <th>Forum post</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['post'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['post']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['forumposts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['post']->key => $_smarty_tpl->tpl_vars['post']->value){
$_smarty_tpl->tpl_vars['post']->_loop = true;
?>
	<tr><td><?php echo $_smarty_tpl->tpl_vars['post']->value['creation_date'];?>
</td>
	    <td><?php echo $_smarty_tpl->tpl_vars['post']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['post']->value['family_name'];?>
</td>
	    <td><?php echo $_smarty_tpl->tpl_vars['post']->value['post_text'];?>
</td>
	</tr>
<?php } ?>
</table>
<?php }?>

</p>

<p align="center">
<?php if ($_smarty_tpl->tpl_vars['ajax']->value==1){?>
	<input type="hidden" name="next" value="return">
	<a name="submit" />
	<input type="submit" name="save" value="Merge" style="font-size: 18px; width: 200px;">
<?php }else{ ?>
	<label>After merge:</label>
	<input type="radio" name="next" id="next1" value="next" checked><label for="next1">show the next match</label>
	<?php if ($_smarty_tpl->tpl_vars['returnto']->value){?><input type="radio" name="next" id="next2" value="return" checked><label for="next2">return to previous page</label><?php }?>
	<input type="radio" name="next" id="next3" value="show"><label for="next3">show merged individual</label>
	<input type="radio" name="next" id="next4" value="list"><label for="next4">return to list</label>
	<br />
	<input type="submit" name="reject" value="Reject" style="font-size: 16px; width: 150px; color: #500;">
	<input type="submit" name="save" value="Merge" style="font-size: 16px; width: 150px; color: #050; font-weight: bold;">
<?php }?>
</p>
</form>

<?php if ($_smarty_tpl->tpl_vars['ajax']->value!=1){?>
<a href="merge_project.php?action=saveproject&p1=<?php echo $_smarty_tpl->tpl_vars['p1']->value['person_id'];?>
&p2=<?php echo $_smarty_tpl->tpl_vars['p2']->value['person_id'];?>
">Begin New Merge Project (beta)</a>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>
<?php }} ?>