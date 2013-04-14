<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:02:50
         compiled from "/var/www/sharedtree/templates/descendants.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6447675975132bd5ad3caa4-06967639%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ec95e41d5626099e662782b0f208a7c6e8673924' => 
    array (
      0 => '/var/www/sharedtree/templates/descendants.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6447675975132bd5ad3caa4-06967639',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'parents' => 0,
    'data' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd5af0b35',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd5af0b35')) {function content_5132bd5af0b35($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value,'includejs'=>1), 0);?>


<style>
div.navbox {
	display: none;
	padding: 3px;
}
div.family {
	display: block;
}
div.familyhide {
	display: none;
}
div.spouse {
	margin-left: 30px;
}
</style>
<script type="text/javascript">
function toggleFamily(whichLayer, getnew) {
	var imgplus = document.getElementById("img"+whichLayer);
	var family = document.getElementById("family"+whichLayer).style;
	if (imgplus.src.search("ico_minus") > 0) {
		imgplus.src = "img/ico_plus.gif";
		family.display = "none";
	} else {
		if (getnew==1) {
			var pars = 'action=ajax&person_id='+whichLayer;
			var myAjax = new Ajax.Updater('person'+whichLayer, 'descendants.php', {method: 'get', parameters: pars});
		}
		imgplus.src = "img/ico_minus.gif";
		family.display = "block";
	}
}
function toggleNav(whichLayer) {
	var navbox = document.getElementById("navbox"+whichLayer).style;
	if (navbox.display == "block") navbox.display = "none";
	else navbox.display = "block";
}

</script>

<br clear="all" />
<table class="portal" width="600">
<tr><td>
<label>Father:</label> <a href="?person_id=<?php echo $_smarty_tpl->tpl_vars['parents']->value['father']['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['parents']->value['father']['full_name'];?>
</a><br>
<label>Mother:</label> <a href="?person_id=<?php echo $_smarty_tpl->tpl_vars['parents']->value['mother']['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['parents']->value['mother']['full_name'];?>
</a><br>
<div class="person" id="person<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
">
<?php echo $_smarty_tpl->getSubTemplate ("desc_family.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('child'=>$_smarty_tpl->tpl_vars['data']->value,'getnew'=>0), 0);?>

</div>
</td></tr></table>

<br><br><br>
<form method="GET" action="/chart.php" target="_BLANK">
	<input type="hidden" name="person_id" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
" />
	<input type="hidden" name="chart" value="desc_circle" />
	<input type="submit" value="Create PDF Circle Chart">
	<select name="gen">
		<option>2</option>
		<option>3</option>
		<option selected="selected">4</option>
		<option>5</option>
		<option>6</option>
	</select> generations
</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>