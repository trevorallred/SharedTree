<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:35:30
         compiled from "/var/www/sharedtree/templates/error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19706821805132c50292fe64-58035287%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8af951b91cb1b07bb1151d423cdb37d3abbca9ea' => 
    array (
      0 => '/var/www/sharedtree/templates/error.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19706821805132c50292fe64-58035287',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'url' => 0,
    'logging' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c502aaba7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c502aaba7')) {function content_5132c502aaba7($_smarty_tpl) {?><p class="errors">
Sorry but the following error ocurred while attempting to submit your request:
</p>
<hr />
<p class="errors">
<?php echo $_smarty_tpl->tpl_vars['error']->value;?>


<?php if ($_smarty_tpl->tpl_vars['url']->value){?><br /><br /><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
">Continue &gt;&gt;</a><?php }?>
</p>

<?php if ($_smarty_tpl->tpl_vars['logging']->value){?>
	<h3>Developer Logging</h3>
	<pre class="errors"><?php echo $_smarty_tpl->tpl_vars['logging']->value;?>
</pre>
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>