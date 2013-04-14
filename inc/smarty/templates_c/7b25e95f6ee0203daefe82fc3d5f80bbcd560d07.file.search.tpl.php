<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:24:33
         compiled from "/var/www/sharedtree/templates/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15562228625132c271a4d7d2-29977140%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b25e95f6ee0203daefe82fc3d5f80bbcd560d07' => 
    array (
      0 => '/var/www/sharedtree/templates/search.tpl',
      1 => 1222143045,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15562228625132c271a4d7d2-29977140',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'request' => 0,
    'result_count' => 0,
    'total_records' => 0,
    'pages' => 0,
    'results' => 0,
    'result' => 0,
    'gender_options' => 0,
    'adbc' => 0,
    'is_logged_on' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c2720e29d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c2720e29d')) {function content_5132c2720e29d($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_html_radios')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Search"), 0);?>


<script type="text/javascript">
<!-- Begin
$i1 = null;
$i2 = null;
$lastid = null;
function mergePerson($id) {
	$thisbox = document.getElementById("check"+$id);

	// uncheck this box and set the value to null
	if (!$thisbox.checked) {
		if ($i1 == $id) $i1 = null;
		if ($i2 == $id) $i2 = null;
	} else {
		if ($i1 != $id) {
			$i2 = $i1;
			$i1 = $id;

			$list = document.getElementsByName("personlist");
			for (i = 0; i < $list.length; i++) {
				if ($list[i].value != $i1 && $list[i].value != $i2) {
					$list[i].checked = false;
				}
				//alert($list[i].value + $list[i].checked);
			}
		}
	}
	document.getElementById("merge1").value = $i1;
	document.getElementById("merge2").value = $i2;
}

//  End -->
</script>

<style>
span.datalabel {
	font-weight: bold;
	font-size: 10px;
	cursor: pointer;
}
</style>


<h1 align="center">Search for Individuals</h1>

<div class="errors"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</div>
<?php if ($_smarty_tpl->tpl_vars['request']->value['search']){?>
	Showing <?php echo $_smarty_tpl->tpl_vars['result_count']->value;?>
 of <?php echo $_smarty_tpl->tpl_vars['total_records']->value;?>
 records

<?php if ($_smarty_tpl->tpl_vars['pages']->value>1){?>
	Page:
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
	<?php if ($_smarty_tpl->getVariable('smarty')->value['section']['page']['index']!=$_smarty_tpl->tpl_vars['request']->value['page']){?>
		<a href="search.php?search=1&page=<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>
&family_name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['family_name']);?>
&given_name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['given_name']);?>
&gender=<?php echo $_smarty_tpl->tpl_vars['request']->value['gender'];?>
&birth_year=<?php echo $_smarty_tpl->tpl_vars['request']->value['birth_year'];?>
&birth_place=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['birth_place']);?>
&death_year=<?php echo $_smarty_tpl->tpl_vars['request']->value['death_year'];?>
&death_place=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['death_place']);?>
&adbc=<?php echo $_smarty_tpl->tpl_vars['request']->value['adbc'];?>
&range=<?php echo $_smarty_tpl->tpl_vars['request']->value['range'];?>
&context=<?php echo $_smarty_tpl->tpl_vars['request']->value['context'];?>
&created_by=<?php echo $_smarty_tpl->tpl_vars['request']->value['created_by'];?>
&sort=<?php echo $_smarty_tpl->tpl_vars['request']->value['sort'];?>
"><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>
</a>
	<?php }else{ ?>
		<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>

	<?php }?>
	<?php endfor; endif; ?>
<?php }?>

<form action="merge.php" method="GET">
	<input type="hidden" name="returnto" value="<?php echo $_SERVER['REQUEST_URI'];?>
">
	<input type="hidden" name="p1" id="merge1" size="6">
	<input type="hidden" name="p2" id="merge2" size="6">
	<table border="1" class="table1">
		<tr>
			<td class="label"><input type="submit" value="Merge"></td>
			<td class="label">Links</td>
			<td class="label">Family name</td>
			<td class="label">Given name</td>
			<td class="label">Birth</td>
			<td class="label">Place</td>
			<td class="label">Sex</td>
			<td class="label">Data</td>
			<td class="label">Created</td>
		</tr>
	<?php if ($_smarty_tpl->tpl_vars['results']->value){?>
		<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['results']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
$_smarty_tpl->tpl_vars['result']->_loop = true;
?>
		<tr id="row<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
">
			<td><input type=checkbox name="personlist" id="check<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
"  onClick="mergePerson(<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
);"></td>
			<td><?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['result']->value['person_id'],'direction'=>"flat"), 0);?>
</td>
			<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['family_name'];?>
</a></td>
			<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['given_name'];?>
</a></td>
			<td><?php echo $_smarty_tpl->tpl_vars['result']->value['birth_year'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['result']->value['location'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['result']->value['gender'];?>
</td>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['result']->value['ancestor_count']>0){?><span class="datalabel" title="<?php echo $_smarty_tpl->tpl_vars['result']->value['ancestor_count'];?>
 ancestor<?php if ($_smarty_tpl->tpl_vars['result']->value['ancestor_count']>1){?>s<?php }?>">A</span><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['result']->value['marriage_count']>0){?><span class="datalabel" title="<?php echo $_smarty_tpl->tpl_vars['result']->value['marriage_count'];?>
 marriage<?php if ($_smarty_tpl->tpl_vars['result']->value['marriage_count']>1){?>s<?php }?>">M</span><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['result']->value['descendant_count']>0){?><span class="datalabel" title="<?php echo $_smarty_tpl->tpl_vars['result']->value['descendant_count'];?>
 descendent<?php if ($_smarty_tpl->tpl_vars['result']->value['descendant_count']>1){?>s<?php }?>">D</span><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['result']->value['biography_size']>0){?><span class="datalabel" title="<?php echo $_smarty_tpl->tpl_vars['result']->value['biography_size'];?>
 characters in biography">B</span><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['result']->value['forum_count']>0){?><span class="datalabel" title="<?php echo $_smarty_tpl->tpl_vars['result']->value['forum_count'];?>
 forum post<?php if ($_smarty_tpl->tpl_vars['result']->value['forum_count']>1){?>s<?php }?>">F</span><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['result']->value['photo_count']>0){?><span class="datalabel" title="<?php echo $_smarty_tpl->tpl_vars['result']->value['photo_count'];?>
 image<?php if ($_smarty_tpl->tpl_vars['result']->value['photo_count']>1){?>s<?php }?>">I</span><?php }?>
			</td>
			<td><font size=1><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['result']->value['creation_date']);?>
 by <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['result']->value['created_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['user_name'];?>
</a></font></td>
		</tr>
		<?php } ?>
	<?php }else{ ?>
		<tr>
			<td colspan="7" align="center">Sorry, no results were found</td>
		</tr>
	<?php }?>
	</table>
	</form>

	<p><a href="person_edit.php">Add a new individual</a></p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['pages']->value>1){?>
	Page:
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
	<?php if ($_smarty_tpl->getVariable('smarty')->value['section']['page']['index']!=$_smarty_tpl->tpl_vars['request']->value['page']){?>
		<a href="search.php?search=1&page=<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>
&family_name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['family_name']);?>
&given_name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['given_name']);?>
&gender=<?php echo $_smarty_tpl->tpl_vars['request']->value['gender'];?>
&birth_year=<?php echo $_smarty_tpl->tpl_vars['request']->value['birth_year'];?>
&birth_place=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['birth_place']);?>
&death_year=<?php echo $_smarty_tpl->tpl_vars['request']->value['death_year'];?>
&death_place=<?php echo rawurlencode($_smarty_tpl->tpl_vars['request']->value['death_place']);?>
&adbc=<?php echo $_smarty_tpl->tpl_vars['request']->value['adbc'];?>
&range=<?php echo $_smarty_tpl->tpl_vars['request']->value['range'];?>
&context=<?php echo $_smarty_tpl->tpl_vars['request']->value['context'];?>
&created_by=<?php echo $_smarty_tpl->tpl_vars['request']->value['created_by'];?>
&sort=<?php echo $_smarty_tpl->tpl_vars['request']->value['sort'];?>
"><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>
</a>
	<?php }else{ ?>
		<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['page']['index'];?>

	<?php }?>
	<?php endfor; endif; ?>
<?php }?>

<form method="GET" action="search.php">
<table class="search">
<tr><td class="search" align="left">
<table border="0">
<tr><td>Last name:</td>
	<td colspan="2"><input type="text" name="family_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['request']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="textfield"></td>
<tr><td>Given name:</td>
	<td colspan="2"><input type="text" name="given_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['request']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="textfield"></td>
</tr>
<tr><td>Gender:</td>
	<td colspan="2"><?php echo smarty_function_html_radios(array('name'=>"gender",'options'=>$_smarty_tpl->tpl_vars['gender_options']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['gender']),$_smarty_tpl);?>
</td>
</tr>
<tr><td>Birth year:</td>
	<td colspan="2"><input type="text" name="birth_year" size="5" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['birth_year'];?>
" class="textfield"></td>
</tr>
<tr><td>Birth place:</td>
	<td colspan="2"><input type="text" name="birth_place" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['request']->value['birth_place'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="textfield"></td>
</tr>
<tr><td>Death year:</td>
	<td colspan="2"><input type="text" name="death_year" size="5" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['death_year'];?>
" class="textfield"></td>
</tr>
<tr><td>Death place:</td>
	<td colspan="2"><input type="text" name="death_place" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['request']->value['death_place'], ENT_QUOTES, 'ISO-8859-1', true);?>
" class="textfield"></td>
</tr>
<tr><td>Range:</td>
	<td><select name="range" class="textfield">
		<option value=0 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==0){?>selected<?php }?>>Exact</option>
		<option value=1 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==1){?>selected<?php }?>>+/- 1 year</option>
		<option value=2 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==2){?>selected<?php }?>>+/- 2 years</option>
		<option value=5 selected>+/- 5 years</option>
		<option value=10 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==10){?>selected<?php }?>>+/- 10 years</option>
		<option value=15 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==15){?>selected<?php }?>>+/- 15 years</option>
		<option value=20 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==20){?>selected<?php }?>>+/- 20 years</option>
		<option value=50 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==50){?>selected<?php }?>>+/- 50 years</option>
		<option value=100 <?php if ($_smarty_tpl->tpl_vars['request']->value['range']==100){?>selected<?php }?>>+/- 100 years</option>
		</select></td>
	<td><?php echo smarty_function_html_radios(array('name'=>"adbc",'options'=>$_smarty_tpl->tpl_vars['adbc']->value,'selected'=>$_smarty_tpl->tpl_vars['request']->value['adbc']),$_smarty_tpl);?>
</td>
</tr>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<tr><td>Context:</td>
	<td colspan="2"><label><input type="radio" name="context" value="all" <?php if ($_smarty_tpl->tpl_vars['request']->value['context']!="tree"){?>checked<?php }?> />Everyone</label>
<label><input type="radio" name="context" value="tree" <?php if ($_smarty_tpl->tpl_vars['request']->value['context']=="tree"){?>checked<?php }?> />Your Family</label></td>
</tr>
<?php }?>
<tr><td>Submitter #:</td>
	<td colspan="2"><input type="text" name="created_by" size="7" value="<?php echo $_smarty_tpl->tpl_vars['request']->value['created_by'];?>
" class="textfield"></td>
</tr>
<tr><td>Sort:</td>
	<td colspan="2"><label><input type="radio" name="sort" value="name" <?php if ($_smarty_tpl->tpl_vars['request']->value['sort']!="date"){?>checked<?php }?> />Name</label>
<label><input type="radio" name="sort" value="birth" <?php if ($_smarty_tpl->tpl_vars['request']->value['sort']=="birth"){?>checked<?php }?> />Birth</label>
<label><input type="radio" name="sort" value="creation" <?php if ($_smarty_tpl->tpl_vars['request']->value['sort']=="creation"){?>checked<?php }?> />Submit Date</label></td>
</tr>
<tr><td>&nbsp;</td><td align="center" colspan="2"><input type="submit" name="search" value="Search"></td>
</tr>
</table>
</td></tr></table>
</form>
<label>Related Pages:</label>
<a href="list.php">Family Index</a> | 
<a href="group.php">Group Index</a> | 
<a href="locations.php">Browse Places</a> 
<br /><br />
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>