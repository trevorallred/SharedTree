<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:46:13
         compiled from "/var/www/sharedtree/templates/event_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6407152165132c785ca6830-87065771%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67a8aa1fff967d1d8a1be6a48e40cab639c03959' => 
    array (
      0 => '/var/www/sharedtree/templates/event_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6407152165132c785ca6830-87065771',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gedcomcodes' => 0,
    'gcode' => 0,
    'defaultevent' => 0,
    'show_lds' => 0,
    'event' => 0,
    'i' => 0,
    'events' => 0,
    'e' => 0,
    'eventtype' => 0,
    'adbc' => 0,
    'ad_option' => 0,
    'date_approx' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c785e46b1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c785e46b1')) {function content_5132c785e46b1($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
if (!is_callable('smarty_function_html_options')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_options.php';
?><?php  $_smarty_tpl->tpl_vars['gcode'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['gcode']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gedcomcodes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['gcode']->key => $_smarty_tpl->tpl_vars['gcode']->value){
$_smarty_tpl->tpl_vars['gcode']->_loop = true;
?>
	<?php if (($_smarty_tpl->tpl_vars['gcode']->value['default_flag']==$_smarty_tpl->tpl_vars['defaultevent']->value)&&($_smarty_tpl->tpl_vars['show_lds']->value==1||$_smarty_tpl->tpl_vars['event']->value['lds_flag']==0)){?>
		<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['gcode']->value['gedcom_code'], null, 0);?>
		<?php $_smarty_tpl->tpl_vars['e'] = new Smarty_variable($_smarty_tpl->tpl_vars['events']->value[$_smarty_tpl->tpl_vars['i']->value], null, 0);?>
		
		<a href="#" onclick="toggleLayer('edit_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
'); return false;"><img src="img/btn_edit.png" width="16" height="16" border="0" id="ico_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" title="click to expand and collapse"/></a> 
		<label><a href="#" onclick="toggleLayer('edit_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
'); return false;" title="click to expand and collapse"><?php echo $_smarty_tpl->tpl_vars['gcode']->value['prompt'];?>
</a> <font size=1>click to expand or collapse</font></label>
		<?php if ($_smarty_tpl->tpl_vars['e']->value['event_date']){?>
			<?php echo $_smarty_tpl->tpl_vars['e']->value['date_approx'];?>
 <?php echo $_smarty_tpl->tpl_vars['e']->value['event_date'];?>
<?php if ($_smarty_tpl->tpl_vars['e']->value['ad']=='0'){?> B.C.<?php }?>
		<?php }else{ ?>
			<?php if ($_smarty_tpl->tpl_vars['e']->value['age_at_event']){?>Age: <?php echo $_smarty_tpl->tpl_vars['e']->value['age_at_event'];?>
<?php }?>
		<?php }?> <?php echo $_smarty_tpl->tpl_vars['e']->value['status'];?>

		<?php if ($_smarty_tpl->tpl_vars['e']->value['temple']){?>in <?php echo $_smarty_tpl->tpl_vars['e']->value['temple'];?>
<?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['e']->value['location']){?>in <?php echo $_smarty_tpl->tpl_vars['e']->value['location'];?>
<?php }?><?php }?>
		<div id="edit_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" class="editEvent">
		<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][event_id]" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['event_id'];?>
">
	<table class="editPerson" width="100%">
		<tr>
			<th>Date:</th>
			<td>
			<?php if ($_smarty_tpl->tpl_vars['e']->value['date_text']){?><?php echo $_smarty_tpl->tpl_vars['e']->value['date_text'];?>
<br /><?php }?>
			<input type="text" class="textfield" name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][event_date]" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['event_date'];?>
"><br />
			<?php if ($_smarty_tpl->tpl_vars['e']->value['ad']==''){?>
				<?php $_smarty_tpl->tpl_vars['ad_option'] = new Smarty_variable(1, null, 0);?>
			<?php }else{ ?>
				<?php $_smarty_tpl->tpl_vars['ad_option'] = new Smarty_variable($_smarty_tpl->tpl_vars['e']->value['ad'], null, 0);?>
			<?php }?>
			<?php echo smarty_function_html_radios(array('name'=>"person[e][".($_smarty_tpl->tpl_vars['i']->value)."][ad]",'options'=>$_smarty_tpl->tpl_vars['adbc']->value,'selected'=>$_smarty_tpl->tpl_vars['ad_option']->value),$_smarty_tpl);?>
</td>
			<td width="150">Format<br> 01 JUL 1901 or <br>JUL 1901 or <br>1901</td>
		</tr>

		<?php if ($_smarty_tpl->tpl_vars['gcode']->value['lds_flag']==1){?>
		<tr>
			<th>Status:</th>
			<td><input type="text" class="textfield" name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][status]" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['status'];?>
"></td>
			<td width="150">LDS temple status of this ordinance such as BIC (born in covenant), CHILD, STILLBORN, SUBMITTED, or DONTDOIT</td>
		</tr>
		<tr>
			<th>Temple:</th>
			<td><input type="text" class="textfield" name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][temple_code]" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['temple_code'];?>
"></td>
			<td width="150">The LDS temple code for this ordinance</td>
		</tr>
	<?php }else{ ?>
		<tr>
			<th>Date Approximation:</th>
			<td><?php echo smarty_function_html_options(array('name'=>"person[e][".($_smarty_tpl->tpl_vars['i']->value)."][date_approx]",'options'=>$_smarty_tpl->tpl_vars['date_approx']->value,'selected'=>$_smarty_tpl->tpl_vars['e']->value['date_approx']),$_smarty_tpl);?>
</td>
			<td width="150"></td>
		</tr>
		<?php if ($_smarty_tpl->tpl_vars['i']->value!="BIRT"){?>
		<tr>
			<th>Age:</th>
			<td><input type="text" class="textfield" name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][age_at_event]" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['age_at_event'];?>
" size="4"></td>
			<td width="150">The age the event occurred. This is only used if the date is unknown.</td>
		</tr>
		<?php }?>
	<?php }?>
		<tr>
			<th>Location:</th>
			<td><input type="text" class="textfield" id="location<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][location]" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['location'];?>
">
<script type="text/javascript">
        new AutoComplete('location<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
', '/suggest.php?field=location&value=');
</script>
<?php if ($_smarty_tpl->tpl_vars['e']->value['location_id']>0){?>
				<a href="locations.php?location_id=<?php echo $_smarty_tpl->tpl_vars['e']->value['location_id'];?>
" target="_blank">Browse</a><?php }?>
</td>
			<td width="150">The name of the place in which this event occurred. Format - <i>City, County, State</i>. Read <a href="/w/Location_Naming_Conventions" target="_BLANK">Location Naming Conventions</a> for more information.</td>
		</tr>
		<tr>
			<th>Source:</th>
			<td><textarea name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][source]" rows="3"><?php echo $_smarty_tpl->tpl_vars['e']->value['source'];?>
</textarea></td>
			<td width="150">The primary or secondary source for this information.</td>
		</tr>
		<tr>
			<th>Notes:</th>
			<td><textarea name="<?php echo $_smarty_tpl->tpl_vars['eventtype']->value;?>
[e][<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][notes]" rows="3"><?php echo $_smarty_tpl->tpl_vars['e']->value['notes'];?>
</textarea></td>
			<td width="150">General notes about this event.</td>
		</tr>
	</table>
		</div>
		<br />
	<?php }?>
<?php } ?>
<?php }} ?>