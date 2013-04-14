<?php /* Smarty version Smarty-3.1.7, created on 2013-03-04 12:46:14
         compiled from "/var/www/sharedtree/templates/familytree.tpl" */ ?>
<?php /*%%SmartyHeaderCode:79230544651350816165219-91676652%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd2ad0ab6dd64d6c494af137066fdcf437a1a92e6' => 
    array (
      0 => '/var/www/sharedtree/templates/familytree.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '79230544651350816165219-91676652',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'trace' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5135081641db7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5135081641db7')) {function content_5135081641db7($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"My Relatives",'includejs'=>1), 0);?>



<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=375,height=300,left = 388.5,top = 282');");
}

function updateContent() {
	var trace = document.getElementById("trace");
	var pars = 'ajax=1&trace='+trace.value;
	new Ajax.Updater('tree_content', 'familytree.php', {method: 'get', parameters: pars}); 
}
// End -->
</script>


<h2>Your Relatives</h2>

<table class="portal">
<tr><td width="200">
	<a href="javascript:popUp('buildline.php')"><b>Rebuild your family tree index</b></a>
	<h3>Rebuild Family Tree</h3>
	Your family tree is an index to relatives, ancestors, and descendants. This index helps SharedTree determine to which users you are related, which private records you're able to view, and how you're related to people in your family tree.
	<br><br>
	As people make changes to your family tree, it's necessary to periodically rebuild your family tree index. How fast this runs dependes largely on how many people are in your family tree.
	<br><br>

	<h3>Legend</h3>
	<table class="grid">
	<tr><td>X</td><td>You</td></tr>
	<tr><td>S</td><td>Spouse</td></tr>
	<tr><td>C</td><td>Child</td></tr>
	<tr><td>P</td><td>Parent</td></tr>
	</table>

	<h3>Combination Examples</h3>
	<table class="grid">
	<tr><td>PC</td><td>Sibling</td></tr>
	<tr><td>PCS</td><td>Brother/Sister-in-law</td></tr>
	<tr><td>PCC</td><td>Niece/Nephew</td></tr>
	<tr><td>PP</td><td>Grandparent</td></tr>
	<tr><td>PPC</td><td>Aunt/Uncle</td></tr>
	<tr><td>PPCS</td><td>Aunt/Uncle</td></tr>
	<tr><td>PPCC</td><td>Cousin (1st)</td></tr>
	<tr><td>PPPCCC</td><td>Cousin (2nd)</td></tr>
	<tr><td>PS</td><td>Step-Parent</td></tr>
	<tr><td>PSC</td><td>Step-Sibling</td></tr>
	<tr><td>PPP</td><td>Great-Grandparent</td></tr>
	<tr><td>PPPC</td><td>Great Aunt/Uncle</td></tr>
	<tr><td>PPPPP</td><td>Great-Great-Great-Grandparent</td></tr>
	</table>

	<a href="familytree.php?show=relationships">Show All Valid Relationships</a>
</td>
<td>
	<form onsubmit="updateContent(); return false;">
	<label>Filter by Trace:</label> <input type="textbox" id="trace" name="trace" value="<?php echo $_smarty_tpl->tpl_vars['trace']->value;?>
">
	<input type="submit" value="Search"><br>
	Enter a trace above to see all relatives from that line. <br>Example: type S to see all people related thru your spouse.<br>Or type C to see all people related thru your children.
	</form>

	<div id="tree_content">
	<?php echo $_smarty_tpl->getSubTemplate ("familytree_partial.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	</div>

</td></tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>