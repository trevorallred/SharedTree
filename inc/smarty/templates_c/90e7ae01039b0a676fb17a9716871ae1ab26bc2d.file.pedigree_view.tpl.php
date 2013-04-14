<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:02:52
         compiled from "/var/www/sharedtree/templates/pedigree_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15586377725132bd5c14e9a4-04202618%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '90e7ae01039b0a676fb17a9716871ae1ab26bc2d' => 
    array (
      0 => '/var/www/sharedtree/templates/pedigree_view.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15586377725132bd5c14e9a4-04202618',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'spouses' => 0,
    'person_id' => 0,
    'spouse' => 0,
    'child' => 0,
    's' => 0,
    'f' => 0,
    'm' => 0,
    'mmmm' => 0,
    'mmmf' => 0,
    'mmfm' => 0,
    'mmff' => 0,
    'mfmm' => 0,
    'mfmf' => 0,
    'mffm' => 0,
    'mfff' => 0,
    'fmmm' => 0,
    'fmmf' => 0,
    'fmfm' => 0,
    'fmff' => 0,
    'ffmm' => 0,
    'ffmf' => 0,
    'fffm' => 0,
    'ffff' => 0,
    'mmm' => 0,
    'mmf' => 0,
    'mfm' => 0,
    'mff' => 0,
    'fmm' => 0,
    'fmf' => 0,
    'ffm' => 0,
    'fff' => 0,
    'mm' => 0,
    'mf' => 0,
    'fm' => 0,
    'ff' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd5c459e6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd5c459e6')) {function content_5132bd5c459e6($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<script language=javascript type='text/javascript'>
function showhide(id) {
	if (document.getElementById(id).style.display == 'block') {
		document.getElementById(id).style.display = 'none';
	} else {
		document.getElementById(id).style.display = 'block';
	}
}
</script>
<style>
.personframe {
	position: absolute;
	text-align: left;
}
.personbox {
	background: #EEE;
	border: solid 1px black;
	width: 200px;
	height: 16px;
	font-size: 12px;
	padding: 2px;
	margin: 0px;
	cursor: pointer;
}
.personinfo {
	display: none;
	border: solid 3px #ddd;
	position: absolute;
	background: #669;
	color: #FEE;
	font-size: 11px;
	margin: 2px;
	padding: 2px;
	height: 90px;
	width: 300px;
	z-index: 99;
 }
.personinfo table {
	padding: 0px;
}
.personinfo td {
	padding: 0px;
	font-size: 10px;
}
.personinfo a {
	color: #FFF;
}

#pedigree {
	font-family: arial;
	background: #DDA;
	border: groove 3px #CC9;
	padding: 10px;
	margin: 5px;
	height: 420px;
	width: 763px;
	position: relative;
}
#pedigree select {
	font-size:8pt;
}
#pedigree input {
	font-size:8pt;
}

#ped_title {
	position: absolute;
	left:5px;
	top:5px;
	font-size: 20px;
}
#gen1 {
	position: absolute;
	left:10px;
}
#gen2 {
	position: absolute;
	left:80px;
}
#gen3 {
	position: absolute;
	left:190px;
}
#gen4 {
	position: absolute;
	left:335px;
}
#gen5 {
	position: absolute;
	left:550px;
}
#nav5 {
	position: absolute;
	left:760px;
}
#nav5 a {
	text-decoration: none;
	color: #111;
}

</style>


<br clear="all" /><br />

<div id="pedigree">
<?php if ($_smarty_tpl->tpl_vars['spouses']->value){?>
	<div style="top: 400px; left: 10px; position: absolute;">
	<form method="get" name="frmChoose" action="ped.php">
	<select name="person_id" onchange="frmChoose.submit()">
	<option value="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">-- Spouse &amp; Children --</option>
	<?php  $_smarty_tpl->tpl_vars['spouse'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['spouse']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spouses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['spouse']->key => $_smarty_tpl->tpl_vars['spouse']->value){
$_smarty_tpl->tpl_vars['spouse']->_loop = true;
?>
		<option value="<?php echo $_smarty_tpl->tpl_vars['spouse']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['spouse']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['spouse']->value['family_name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['spouse']->value['b_date'];?>
)</option>
		<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spouse']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
			<option value="<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
"><font size=1>&nbsp;&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['child']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['child']->value['family_name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['child']->value['b_date'];?>
)</font></option>
		<?php } ?>
	<?php } ?>
	</select>
	<input type="submit" value="Go" />
	</form>
	</div>
<?php }?>

<div id="ped_title">
	PEDIGREE for <?php echo $_smarty_tpl->tpl_vars['s']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['s']->value['family_name'];?>

</div>
<div id="nav5">
<?php if ($_smarty_tpl->tpl_vars['f']->value['person_id']>0){?>
	<div style="position: absolute; top: 100px"><a href="ped.php?person_id=<?php echo $_smarty_tpl->tpl_vars['f']->value['person_id'];?>
">&gt;&gt;</a></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['m']->value['person_id']>0){?>
	<div style="position: absolute; top: 300px"><a href="ped.php?person_id=<?php echo $_smarty_tpl->tpl_vars['m']->value['person_id'];?>
">&gt;&gt;</a></div>
<?php }?>
</div>
<div id="gen5">
<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mmmm']->value,'ht'=>387.5,'boxid'=>31), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mmmf']->value,'ht'=>362.5,'boxid'=>30), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mmfm']->value,'ht'=>337.5,'boxid'=>29), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mmff']->value,'ht'=>312.5,'boxid'=>28), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mfmm']->value,'ht'=>287.5,'boxid'=>27), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mfmf']->value,'ht'=>262.5,'boxid'=>26), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mffm']->value,'ht'=>237.5,'boxid'=>25), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mfff']->value,'ht'=>212.5,'boxid'=>24), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fmmm']->value,'ht'=>187.5,'boxid'=>23), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fmmf']->value,'ht'=>162.5,'boxid'=>22), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fmfm']->value,'ht'=>137.5,'boxid'=>21), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fmff']->value,'ht'=>112.5,'boxid'=>20), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['ffmm']->value,'ht'=>87.5,'boxid'=>19), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['ffmf']->value,'ht'=>62.5,'boxid'=>18), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fffm']->value,'ht'=>37.5,'boxid'=>17), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['ffff']->value,'ht'=>12.5,'boxid'=>16), 0);?>

</div>
<div id="gen4">
<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mmm']->value,'ht'=>375,'boxid'=>15), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mmf']->value,'ht'=>325,'boxid'=>14), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mfm']->value,'ht'=>275,'boxid'=>13), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mff']->value,'ht'=>225,'boxid'=>12), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fmm']->value,'ht'=>175,'boxid'=>11), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fmf']->value,'ht'=>125,'boxid'=>10), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['ffm']->value,'ht'=>75,'boxid'=>9), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fff']->value,'ht'=>25,'boxid'=>8), 0);?>

</div>
<div id="gen3">
<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mm']->value,'ht'=>350,'boxid'=>7), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['mf']->value,'ht'=>250,'boxid'=>6), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['fm']->value,'ht'=>150,'boxid'=>5), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['ff']->value,'ht'=>50,'boxid'=>4), 0);?>

</div>
<div id="gen2">
<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['m']->value,'ht'=>300,'boxid'=>3), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['f']->value,'ht'=>100,'boxid'=>2), 0);?>

</div>
<div id="gen1">
<?php echo $_smarty_tpl->getSubTemplate ("pedigree_piece2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('p'=>$_smarty_tpl->tpl_vars['s']->value,'ht'=>200,'boxid'=>1), 0);?>

</div>
</div>

<br><br>

<form method="GET" action="chart.php" target="_BLANK">
	<input type="hidden" name="person_id" value="<?php echo $_smarty_tpl->tpl_vars['s']->value['person_id'];?>
" />
	<input type="hidden" name="chart" value="pedigree" />
	Generations:
	<select name="gen">
		<option>3</option>
		<option>4</option>
		<option selected="selected">5</option>
		<option>6</option>
		<option>7</option>
		<option>8</option>
	</select>
	<input type="submit" value="Create Printable PDF">
</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>