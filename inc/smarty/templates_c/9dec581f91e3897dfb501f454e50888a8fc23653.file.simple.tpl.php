<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:37:36
         compiled from "/var/www/sharedtree/templates/simple.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3166907715132c580bc1ca5-32442832%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9dec581f91e3897dfb501f454e50888a8fc23653' => 
    array (
      0 => '/var/www/sharedtree/templates/simple.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3166907715132c580bc1ca5-32442832',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'message' => 0,
    'individual' => 0,
    'father' => 0,
    'day31_options' => 0,
    'month_options' => 0,
    'ffather' => 0,
    'fmother' => 0,
    'mother' => 0,
    'mfather' => 0,
    'mmother' => 0,
    'user' => 0,
    'gender_options' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c581072bb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c581072bb')) {function content_5132c581072bb($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_options.php';
if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<br>
<script type="text/javascript">

function confirmSubmit($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}

</script>

<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>

<?php if ($_smarty_tpl->tpl_vars['message']->value=="DONE"){?>
<table class="portal">
<tr><td>
Congratulations! You've successfully started your family tree. You now have several ways to continue:
<ul>
<li>Add Siblings and Spouses by clicking on the Family links below</li>
<li><a href="import.php">Import data</a> from your personal computer</li>
<li>Search for matches by click on the Search links below</li>
<li>Continue adding more generations by clicking on the Family links below</li>
</ul>
</td></tr></table>
<?php }else{ ?><b><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</b> <br><?php }?>

<table class="table1" border="0">
<?php if ($_smarty_tpl->tpl_vars['individual']->value['person_id']>0){?>
<tr>
	<td rowspan="2">
	<fieldset>
		<legend>You</legend>
		<label><?php echo $_smarty_tpl->tpl_vars['individual']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['individual']->value['family_name'];?>
</label><br>
		Gender: <?php echo $_smarty_tpl->tpl_vars['individual']->value['gender'];?>
<br>
		Birth: <?php echo $_smarty_tpl->tpl_vars['individual']->value['e']['BIRT']['event_date'];?>
<br>
		Place: <?php echo $_smarty_tpl->tpl_vars['individual']->value['e']['BIRT']['location'];?>

		<br>
		<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
		<a href="/family/<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add Spouse</a>
	</fieldset>
	<td>
		<fieldset>
			<legend>Your Father</legend>
		<?php if ($_smarty_tpl->tpl_vars['father']->value['person_id']>0){?>
				<label><?php echo $_smarty_tpl->tpl_vars['father']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['father']->value['family_name'];?>
</label><br>
				Birth: <?php echo $_smarty_tpl->tpl_vars['father']->value['e']['BIRT']['event_date'];?>
<br>
				Place: <?php echo $_smarty_tpl->tpl_vars['father']->value['e']['BIRT']['location'];?>

				<br>
				<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['father']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
				<a href="/family/<?php echo $_smarty_tpl->tpl_vars['father']->value['person_id'];?>
"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add Your Siblings</a>
		<?php }else{ ?>
			<form method="POST" action="person_edit.php" style="margin: 0px;">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="build_fti" value="1">
			<input type="hidden" name="return_to" value="simple.php">
			<input type="hidden" name="person[gender]" value="M">
			<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['bio_family_id'];?>
">
			<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
">
			<table class="invisible">
			<tr><td><label>First name(s)</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
				<td><label>Last name</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"last_name"), 0);?>
</td>
			</tr>
			<tr><td><input type="text" name="person[given_name]"></td>
				<td><input type="text" name="person[family_name]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['family_name'];?>
"></td>
			</tr>
			</table>
			<table class="invisible">
			<tr><td><label>Birthdate:</label></td>
				<td>
				<?php echo smarty_function_html_options(array('name'=>"person[e][BIRT][dd]",'options'=>$_smarty_tpl->tpl_vars['day31_options']->value),$_smarty_tpl);?>

				<?php echo smarty_function_html_options(array('name'=>"person[e][BIRT][mon]",'options'=>$_smarty_tpl->tpl_vars['month_options']->value),$_smarty_tpl);?>

				<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"dates"), 0);?>

				</td>
			</tr>
			<tr><td><label>Place:</label></td>
				<td><input type="text" name="person[e][BIRT][location]" size="30"> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"places"), 0);?>
</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td><input type="submit" name="save" value="Add Father"></td>
			</tr>
			</table>
			</form>
		<?php }?>
		</fieldset>
	</td>
	<td>
		<?php if ($_smarty_tpl->tpl_vars['father']->value['person_id']>0){?>
			<fieldset>
				<legend>His Father</legend>
			<?php if ($_smarty_tpl->tpl_vars['ffather']->value['person_id']>0){?>
					<label><?php echo $_smarty_tpl->tpl_vars['ffather']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['ffather']->value['family_name'];?>
</label> (<?php echo $_smarty_tpl->tpl_vars['ffather']->value['e']['BIRT']['event_date'];?>
)
					<br>
					<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['ffather']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/<?php echo $_smarty_tpl->tpl_vars['ffather']->value['person_id'];?>
"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name=<?php echo $_smarty_tpl->tpl_vars['ffather']->value['family_name'];?>
&given_name=<?php echo $_smarty_tpl->tpl_vars['ffather']->value['given_name'];?>
&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			<?php }else{ ?>
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="M">
				<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['father']->value['bio_family_id'];?>
">
				<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['father']->value['person_id'];?>
">
				<table class="invisible">
				<tr><td><label>First name(s)</label>
					<?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
					<td><label>Last name</label> 
					<?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]" value="<?php echo $_smarty_tpl->tpl_vars['father']->value['family_name'];?>
"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandfather"></td>
				</tr>
				</table>
				</form>
			<?php }?>
			</fieldset>
			<fieldset>
				<legend>His Mother</legend>
			<?php if ($_smarty_tpl->tpl_vars['fmother']->value['person_id']>0){?>
					<label><?php echo $_smarty_tpl->tpl_vars['fmother']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['fmother']->value['family_name'];?>
</label> (<?php echo $_smarty_tpl->tpl_vars['fmother']->value['e']['BIRT']['event_date'];?>
)
					<br>
					<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['fmother']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/<?php echo $_smarty_tpl->tpl_vars['fmother']->value['person_id'];?>
"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name=<?php echo $_smarty_tpl->tpl_vars['fmother']->value['family_name'];?>
&given_name=<?php echo $_smarty_tpl->tpl_vars['fmother']->value['given_name'];?>
&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			<?php }else{ ?>
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="F">
				<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['father']->value['bio_family_id'];?>
">
				<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['father']->value['person_id'];?>
">
				<table class="invisible">
				<tr><td><label>First name(s)</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
					<td><label>Last name</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"last_name"), 0);?>
</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandmother"></td>
				</tr>
				</table>
				</form>
			<?php }?>
			</fieldset>
		<?php }?>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
			<legend>Your Mother</legend>
		<?php if ($_smarty_tpl->tpl_vars['mother']->value['person_id']>0){?>
				<label><?php echo $_smarty_tpl->tpl_vars['mother']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['mother']->value['family_name'];?>
</label><br>
				Birth: <?php echo $_smarty_tpl->tpl_vars['mother']->value['e']['BIRT']['event_date'];?>
<br>
				Place: <?php echo $_smarty_tpl->tpl_vars['mother']->value['e']['BIRT']['location'];?>

				<br>
				<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['mother']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
		<?php }else{ ?>
			<form method="POST" action="person_edit.php" style="margin: 0px;">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="build_fti" value="1">
			<input type="hidden" name="return_to" value="simple.php">
			<input type="hidden" name="person[gender]" value="F">
			<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['bio_family_id'];?>
">
			<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
">
			<table class="invisible">
			<tr><td><label>First name(s)</label>
				<?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
				<td><label>Last name</label> 
				<?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
			</tr>
			<tr><td><input type="text" name="person[given_name]"></td>
				<td><input type="text" name="person[family_name]"></td>
			</tr>
			</table>
			<table class="invisible">
			<tr><td><label>Birthdate:</label></td>
				<td>
				<?php echo smarty_function_html_options(array('name'=>"person[e][BIRT][dd]",'options'=>$_smarty_tpl->tpl_vars['day31_options']->value),$_smarty_tpl);?>

				<?php echo smarty_function_html_options(array('name'=>"person[e][BIRT][mon]",'options'=>$_smarty_tpl->tpl_vars['month_options']->value),$_smarty_tpl);?>

				<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"dates"), 0);?>

				</td>
			</tr>
			<tr><td><label>Place:</label></td>
				<td><input type="text" name="person[e][BIRT][location]" size="30"> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"places"), 0);?>
</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td><input type="submit" name="save" value="Add Mother"></td>
			</tr>
			</table>
			</form>
		<?php }?>
		</fieldset>
	</td>
	<td>
		<?php if ($_smarty_tpl->tpl_vars['mother']->value['person_id']>0){?>
			<fieldset>
				<legend>Her Father</legend>
			<?php if ($_smarty_tpl->tpl_vars['mfather']->value['person_id']>0){?>
					<label><?php echo $_smarty_tpl->tpl_vars['mfather']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['mfather']->value['family_name'];?>
</label> (<?php echo $_smarty_tpl->tpl_vars['mfather']->value['e']['BIRT']['event_date'];?>
)
					<br>
					<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['mfather']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/<?php echo $_smarty_tpl->tpl_vars['mfather']->value['person_id'];?>
"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name=<?php echo $_smarty_tpl->tpl_vars['mfather']->value['family_name'];?>
&given_name=<?php echo $_smarty_tpl->tpl_vars['mfather']->value['given_name'];?>
&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			<?php }else{ ?>
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="M">
				<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['mother']->value['bio_family_id'];?>
">
				<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['mother']->value['person_id'];?>
">
				<table class="invisible">
				<tr><td><label>First name(s)</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
					<td><label>Last name</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"last_name"), 0);?>
</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]" value="<?php echo $_smarty_tpl->tpl_vars['mother']->value['family_name'];?>
"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandfather"></td>
				</tr>
				</table>
				</form>
			<?php }?>
			</fieldset>
			<fieldset>
				<legend>Her Mother</legend>
			<?php if ($_smarty_tpl->tpl_vars['mmother']->value['person_id']>0){?>
					<label><?php echo $_smarty_tpl->tpl_vars['mmother']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['mmother']->value['family_name'];?>
</label> (<?php echo $_smarty_tpl->tpl_vars['mmother']->value['e']['BIRT']['event_date'];?>
)
					<br>
					<a href="person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['mmother']->value['person_id'];?>
"><img src="img/btn_edit.png" border="0" style="vertical-align: middle" width="16" height="16">Edit</a>
					<a href="/family/<?php echo $_smarty_tpl->tpl_vars['mmother']->value['person_id'];?>
"><img src="img/ico_family.gif" border="0" style="vertical-align: middle" width="14" height="15">Add more</a>
					<a href="search.php?family_name=<?php echo $_smarty_tpl->tpl_vars['mmother']->value['family_name'];?>
&given_name=<?php echo $_smarty_tpl->tpl_vars['mmother']->value['given_name'];?>
&context=all&search=Search"><img src="img/btn_search.png" border="0" style="vertical-align: middle" width="16" height="16">Search</a>
			<?php }else{ ?>
				<form method="POST" action="person_edit.php" style="margin: 0px;">
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="build_fti" value="1">
				<input type="hidden" name="return_to" value="simple.php">
				<input type="hidden" name="person[gender]" value="F">
				<input type="hidden" name="person[marriage_id]" value="<?php echo $_smarty_tpl->tpl_vars['mother']->value['bio_family_id'];?>
">
				<input type="hidden" name="person[child_id]" value="<?php echo $_smarty_tpl->tpl_vars['mother']->value['person_id'];?>
">
				<table class="invisible">
				<tr><td><label>First name(s)</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
					<td><label>Last name</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"last_name"), 0);?>
</td>
				</tr>
				<tr><td><input type="text" name="person[given_name]"></td>
					<td><input type="text" name="person[family_name]"></td>
				</tr>
				<tr><td><label>Birthyear:</label>
						<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"></td>
					<td><input type="submit" name="save" value="Add Grandmother"></td>
				</tr>
				</table>
				</form>
			<?php }?>
			</fieldset>
		<?php }?>
	</td>
</tr>
<?php }else{ ?>
<tr>
<td class="content">
	<fieldset>
		<legend>You</legend>
	<form method="POST" action="person_edit.php">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="return_to" value="simple.php">
	<input type="hidden" name="attachuser" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['user_id'];?>
">
	<table class="invisible">
	<tr><td><label>First name(s)</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"first_name"), 0);?>
</td>
		<td><label>Last name</label> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"last_name"), 0);?>
</td>
	</tr>
	<tr><td><input type="text" name="person[given_name]" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['given_name'];?>
"></td>
		<td><input type="text" name="person[family_name]" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['family_name'];?>
"></td>
	</tr>
	</table>
	<table class="invisible">
	<tr><td><label>Gender:</label></td>
		<td><?php echo smarty_function_html_radios(array('name'=>"person[gender]",'options'=>$_smarty_tpl->tpl_vars['gender_options']->value),$_smarty_tpl);?>
</td>
	</tr>
	<tr><td><label>Birthdate:</label></td>
		<td>
		<?php echo smarty_function_html_options(array('name'=>"person[e][BIRT][dd]",'options'=>$_smarty_tpl->tpl_vars['day31_options']->value),$_smarty_tpl);?>

		<?php echo smarty_function_html_options(array('name'=>"person[e][BIRT][mon]",'options'=>$_smarty_tpl->tpl_vars['month_options']->value),$_smarty_tpl);?>

		<input type="text" name="person[e][BIRT][yyyy]" size="4" maxlength="4"> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"dates"), 0);?>

		</td>
	</tr>
	<tr><td><label>Place:</label></td>
		<td><input type="text" name="person[e][BIRT][location]" size="30"> <?php echo $_smarty_tpl->getSubTemplate ("help.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('help_text'=>"places"), 0);?>
</td>
	</tr>
	<tr><td>&nbsp;</td>
		<td><input type="submit" name="save" value="Save"></td>
	</tr>
	</table>
	</form>
	</fieldset>
</td></tr>
<?php }?>
</table>

<table class="portal">
<?php if ($_smarty_tpl->tpl_vars['individual']->value['person_id']>0){?>
<br>
<tr>
<td>
<form method="GET" action="chart.php" target="_BLANK">
	<input type="hidden" name="person_id" value="<?php echo $_smarty_tpl->tpl_vars['individual']->value['person_id'];?>
" />
	<input type="hidden" name="chart" value="pedigree" />
	<input type="hidden" name="gen" value="4" />
	<input type="submit" value="Create Pedigree Chart">
	<br>
<a href="/w/Printing_PDFs">Tips for printing PDFs</a>
</form>
</td>
<td width="500">
<h3>Navigation tips</h3>

<table>
<tr>
	<td style="vertical-align: middle"><img src="img/ico_help.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Help</label></td>
	<td>Click Help in the top right hand corner if you're ever confused about what to do next. This opens a new help window that will assist you.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/ico_family.gif" border="0" width="14" height="15"></td>
	<td style="vertical-align: middle"><label>Family</label></td>
	<td>This page shows the person and their immediate family members (parents, spouses, and children). You go here to add more family members.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/ico_indi.gif" border="0" width="11" height="15"></td>
	<td style="vertical-align: middle"><label>Individual</label></td>
	<td>This page focuses on the individual, events in their life and specific research and family history regarding that person. Add photos, post messages, and write biographies about this individual here.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_pedigree.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Pedigree</label></td>
	<td>See and print a person's ancestors.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_descend.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Descendents</label></td>
	<td>See and print a person's descendents.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_edit.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Edit</label></td>
	<td>Make changes to a person's name, life events, and other important details.</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/btn_docs.png" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>History</label></td>
	<td>View and revert changes to a person</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/larrow.gif" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Export</label></td>
	<td>Export several generations of data to a GEDCOM</td>
</tr>
<tr>
	<td style="vertical-align: middle"><img src="img/rarrow.gif" border="0" width="16" height="16"></td>
	<td style="vertical-align: middle"><label>Import</label></td>
	<td>Import a GEDCOM file into SharedTree</td>
</tr>
</table>

</td>
</tr>
<?php }?>

<tr>
<td colspan="2">
<label>Other Links</label> - 
<a href="/w/Getting_Started">Help: Getting Started</a> | 
<a href="import.php">Import a GEDCOM</a> |
<a href="index.php">My Tree</a>
</td>
</tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>