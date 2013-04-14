<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:02:51
         compiled from "/var/www/sharedtree/templates/desc_family.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9408958125132bd5b24fb95-07476358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0da26fa63610d47d10ed5c5c74a650a9751cd2ed' => 
    array (
      0 => '/var/www/sharedtree/templates/desc_family.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9408958125132bd5b24fb95-07476358',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'child' => 0,
    'getnew' => 0,
    'spouse' => 0,
    'newchild' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd5b3a020',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd5b3a020')) {function content_5132bd5b3a020($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['child']->value['spouse']){?><a href="javascript:void(0)" onclick="toggleFamily('<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
','<?php echo $_smarty_tpl->tpl_vars['getnew']->value;?>
');"><img id="img<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
" src="img/ico_<?php if ($_smarty_tpl->tpl_vars['getnew']->value==1){?>plus_grey<?php }else{ ?>minus<?php }?>.gif" width="15" height="15" border="0"></a><?php }else{ ?><img src="img/blank.gif" width="15" height="15" border="0"><?php }?> 
<a href="javascript:void(0)" onclick="toggleNav('<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
');"><?php echo $_smarty_tpl->tpl_vars['child']->value['name'];?>
</a>
<div class="navbox" id="navbox<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
" style="margin-left:30px">
	<table border="0"><tr><td><img src="image.php?person_id=<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
&size=thumb"></td>
	<td>
	<b>B:</b> <?php echo $_smarty_tpl->tpl_vars['child']->value['bdate'];?>
<br />
	<b>P:</b> <?php echo $_smarty_tpl->tpl_vars['child']->value['bplace'];?>
<br />
	<b>D:</b> <?php echo $_smarty_tpl->tpl_vars['child']->value['ddate'];?>
<br />
	<b>P:</b> <?php echo $_smarty_tpl->tpl_vars['child']->value['dplace'];?>
<br />
	<?php if ($_smarty_tpl->tpl_vars['child']->value['trace']){?><b><?php echo $_smarty_tpl->tpl_vars['child']->value['trace'];?>
</b><br /><?php }?>
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['child']->value['id'],'direction'=>"flat"), 0);?>

	<br /><a href="descendants.php?person_id=<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
">View Descendents</a>
	</td></tr></table>
</div>
<div class="family<?php if ($_smarty_tpl->tpl_vars['getnew']->value==1){?>hide<?php }?>" id="family<?php echo $_smarty_tpl->tpl_vars['child']->value['id'];?>
">
<?php  $_smarty_tpl->tpl_vars['spouse'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['spouse']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['child']->value['spouse']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['spouse']->key => $_smarty_tpl->tpl_vars['spouse']->value){
$_smarty_tpl->tpl_vars['spouse']->_loop = true;
?>
	<div class="spouse">
	<?php if ($_smarty_tpl->tpl_vars['spouse']->value['restricted']){?>
		<?php echo $_smarty_tpl->tpl_vars['spouse']->value['name'];?>

	<?php }else{ ?>
		<a href="javascript:void(0)" onclick="toggleNav('<?php echo $_smarty_tpl->tpl_vars['spouse']->value['id'];?>
');"><?php echo $_smarty_tpl->tpl_vars['spouse']->value['name'];?>
</a>
		<div class="navbox" id="navbox<?php echo $_smarty_tpl->tpl_vars['spouse']->value['id'];?>
">
		<table border="0"><tr><td><img src="image.php?person_id=<?php echo $_smarty_tpl->tpl_vars['spouse']->value['id'];?>
&size=thumb"></td>
		<td>
		<b>B:</b> <?php echo $_smarty_tpl->tpl_vars['spouse']->value['bdate'];?>
<br />
		<b>P:</b> <?php echo $_smarty_tpl->tpl_vars['spouse']->value['bplace'];?>
<br />
		<b>D:</b> <?php echo $_smarty_tpl->tpl_vars['spouse']->value['ddate'];?>
<br />
		<b>P:</b> <?php echo $_smarty_tpl->tpl_vars['spouse']->value['dplace'];?>
<br />
		<?php if ($_smarty_tpl->tpl_vars['spouse']->value['trace']){?><b><?php echo $_smarty_tpl->tpl_vars['spouse']->value['trace'];?>
</b><br /><?php }?>
		<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['spouse']->value['id'],'direction'=>"flat"), 0);?>

		<br /><a href="descendants.php?person_id=<?php echo $_smarty_tpl->tpl_vars['spouse']->value['id'];?>
">View Descendents</a>
		</td></tr></table>
		</div>
	<?php }?>
		<?php  $_smarty_tpl->tpl_vars['newchild'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['newchild']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spouse']->value['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['newchild']->key => $_smarty_tpl->tpl_vars['newchild']->value){
$_smarty_tpl->tpl_vars['newchild']->_loop = true;
?>
			<div class="person" id="person<?php echo $_smarty_tpl->tpl_vars['newchild']->value['id'];?>
">
			<?php if ($_smarty_tpl->tpl_vars['newchild']->value['restricted']){?>
				<img src="img/blank.gif" width="15" height="15" border="0">
				<?php echo $_smarty_tpl->tpl_vars['newchild']->value['name'];?>
<br />
			<?php }else{ ?>
				<?php echo $_smarty_tpl->getSubTemplate ("desc_family.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('child'=>$_smarty_tpl->tpl_vars['newchild']->value,'getnew'=>1), 0);?>

			<?php }?>
			</div>
		<?php } ?>
	</div>
<?php } ?>
</div><?php }} ?>