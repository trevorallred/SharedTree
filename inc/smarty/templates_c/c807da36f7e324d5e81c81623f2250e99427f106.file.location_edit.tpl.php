<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 14:51:07
         compiled from "/var/www/sharedtree/templates/location_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20194039545133d3db53d4f2-96479434%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c807da36f7e324d5e81c81623f2250e99427f106' => 
    array (
      0 => '/var/www/sharedtree/templates/location_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20194039545133d3db53d4f2-96479434',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'location' => 0,
    'parent_locations' => 0,
    'types' => 0,
    'spellings' => 0,
    'spell' => 0,
    'new_spelling' => 0,
    'event_years' => 0,
    'year' => 0,
    'events' => 0,
    'event' => 0,
    'children' => 0,
    'child' => 0,
    'similar' => 0,
    'loc' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5133d3db92245',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5133d3db92245')) {function content_5133d3db92245($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_options.php';
if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Locations: Edit",'includejs'=>1), 0);?>


<h2><a href="locations.php">All</a>: 
<?php if ($_smarty_tpl->tpl_vars['location']->value['parent_id']>0){?><a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['location']->value['parent_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['location']->value['parent_name'];?>
</a>:<?php }?> 
<?php echo $_smarty_tpl->tpl_vars['location']->value['display_name'];?>
</h2>
[<a href="locations.php?action=match">Match Event Locations</a>]
<table class="portal">
<tr><td>
<form method="POST">
<input type="hidden" name="action" value="save">
<input type="hidden" name="location[location_id]" value="<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
">
Move inside: <?php echo smarty_function_html_options(array('name'=>"location[parent_id]",'options'=>$_smarty_tpl->tpl_vars['parent_locations']->value,'selected'=>$_smarty_tpl->tpl_vars['location']->value['parent_id']),$_smarty_tpl);?>
<br>
Location type: <?php echo smarty_function_html_radios(array('name'=>"location[location_type]",'options'=>$_smarty_tpl->tpl_vars['types']->value,'selected'=>$_smarty_tpl->tpl_vars['location']->value['location_type']),$_smarty_tpl);?>
<br>
Display text: <input type="text" name="location[display_name]" value="<?php echo $_smarty_tpl->tpl_vars['location']->value['display_name'];?>
" size="50" maxlength="255"><br>
Description: <input type="text" name="location[description]" value="<?php echo $_smarty_tpl->tpl_vars['location']->value['description'];?>
" size="50" maxlength="255"><br>
<br>
Various spellings:
<ul>
<?php  $_smarty_tpl->tpl_vars['spell'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['spell']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spellings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['spell']->key => $_smarty_tpl->tpl_vars['spell']->value){
$_smarty_tpl->tpl_vars['spell']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['location']->value['location_name']==$_smarty_tpl->tpl_vars['spell']->value){?><li><?php echo $_smarty_tpl->tpl_vars['spell']->value;?>
</li><?php }?>
<?php } ?>
<?php  $_smarty_tpl->tpl_vars['spell'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['spell']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spellings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['spell']->key => $_smarty_tpl->tpl_vars['spell']->value){
$_smarty_tpl->tpl_vars['spell']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['location']->value['location_name']!=$_smarty_tpl->tpl_vars['spell']->value){?>
		<li><?php echo $_smarty_tpl->tpl_vars['spell']->value;?>
 
			<a href="locations.php?action=save&location[location_id]=<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
&location[location_name]=<?php echo $_smarty_tpl->tpl_vars['spell']->value;?>
">Make Primary</a> 
			<a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
&action=delete_spelling&spelling=<?php echo $_smarty_tpl->tpl_vars['spell']->value;?>
">Remove</a>
		</li>
	<?php }?>
<?php } ?>
	<li>Add new: <input type="text" name="new_spelling" value="<?php echo $_smarty_tpl->tpl_vars['new_spelling']->value;?>
"><br>
</ul>

<input type="submit" name="save" value="Save"><br>
</form>

<br>
<br>
<?php  $_smarty_tpl->tpl_vars['year'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['year']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['event_years']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['foo']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['year']->key => $_smarty_tpl->tpl_vars['year']->value){
$_smarty_tpl->tpl_vars['year']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['foo']['index']++;
?>
	<a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
&year=<?php echo $_smarty_tpl->tpl_vars['year']->value['event_year'];?>
"><?php echo $_smarty_tpl->tpl_vars['year']->value['event_year'];?>
</a><font size="1" color="#999">(<?php echo $_smarty_tpl->tpl_vars['year']->value['total'];?>
)</font>
	<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['foo']['index']%10==9){?><br /><?php }?>
<?php } ?>

<?php if ($_smarty_tpl->tpl_vars['events']->value){?>
<ul>
<?php  $_smarty_tpl->tpl_vars['event'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['event']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['events']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['event']->key => $_smarty_tpl->tpl_vars['event']->value){
$_smarty_tpl->tpl_vars['event']->_loop = true;
?>
	<li><?php echo $_smarty_tpl->tpl_vars['event']->value['event_type'];?>
 <?php echo $_smarty_tpl->tpl_vars['event']->value['event_date'];?>
 <?php if ($_smarty_tpl->tpl_vars['event']->value['ad']==0){?> B.C.<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['event']->value['table_type']=="F"){?>
		<a href="marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['event']->value['key_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['event']->value['hgiven_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['event']->value['hfamily_name'];?>
 &amp; <?php echo $_smarty_tpl->tpl_vars['event']->value['wgiven_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['event']->value['wfamily_name'];?>
</a></li>
	<?php }else{ ?>
		<a href="/person/<?php echo $_smarty_tpl->tpl_vars['event']->value['key_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['event']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['event']->value['family_name'];?>
</a>
	<?php }?>
	in <?php echo $_smarty_tpl->tpl_vars['event']->value['location'];?>

	</li>
<?php } ?>
</ul>
<br>
<br>
<?php }?>

<br>
<br>
<br>
<a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
&action=delete">Permanently Delete <?php echo $_smarty_tpl->tpl_vars['location']->value['display_name'];?>
</a>
</td>
<td>
<p>
<b>Locations within <?php echo $_smarty_tpl->tpl_vars['location']->value['location_name'];?>
:</b>
<br>
<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['children']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
	<a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['child']->value['location_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['type_meaning'];?>
 - <?php echo $_smarty_tpl->tpl_vars['child']->value['location_name'];?>
</a><br />
<?php } ?>
<br>
<a href="locations.php?action=add&parent_id=<?php echo $_smarty_tpl->tpl_vars['location']->value['location_id'];?>
">Add new sub location</a><br />
</p>

<?php if ($_smarty_tpl->tpl_vars['similar']->value){?>
<p>
Locations with similar spellings:<br>
<?php  $_smarty_tpl->tpl_vars['loc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['loc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['similar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['loc']->key => $_smarty_tpl->tpl_vars['loc']->value){
$_smarty_tpl->tpl_vars['loc']->_loop = true;
?>
	<a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['loc']->value['location_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['loc']->value['type_meaning'];?>
 - <?php echo $_smarty_tpl->tpl_vars['loc']->value['location_name'];?>
</a><br />
<?php } ?>
</p>
<?php }?>
</td></tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>