<?php /* Smarty version Smarty-3.1.7, created on 2013-03-07 17:23:24
         compiled from "/var/www/sharedtree/templates/discuss_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:113082222751393d8c3280b0-69483885%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b973b38efe19dd848ff050988f8b873f31fd620f' => 
    array (
      0 => '/var/www/sharedtree/templates/discuss_edit.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '113082222751393d8c3280b0-69483885',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'person_id' => 0,
    'dpost' => 0,
    'dtypes' => 0,
    'user' => 0,
    'users' => 0,
    'u' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51393d8c68487',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51393d8c68487')) {function content_51393d8c68487($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Post message",'background'=>"edit",'includejs'=>"1"), 0);?>


<h2>Post Message</h2>

<table class="portal">
<tr><td>
<form method="POST" action="/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">
<input type="hidden" name="action" value="discussedit">
<input type="hidden" name="dpost[post_id]" value="<?php echo $_smarty_tpl->tpl_vars['dpost']->value['post_id'];?>
">
<input type="hidden" name="dpost[person_id]" value="<?php echo $_smarty_tpl->tpl_vars['dpost']->value['person_id'];?>
">
<label>Message Type:</label> <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['dtypes']->value,'selected'=>$_smarty_tpl->tpl_vars['dpost']->value['post_type'],'name'=>"dpost[post_type]"),$_smarty_tpl);?>
<br />
<label>Message:</label><br />
<textarea name="dpost[post_text]" cols="60" rows="10"><?php echo $_smarty_tpl->tpl_vars['dpost']->value['post_text'];?>
</textarea><br />
<?php if (!$_smarty_tpl->tpl_vars['dpost']->value['post_id']){?>
	<label><input type="checkbox" name="post_message" value="1" checked>Post to message to website</label> (recommended)<br />
<?php }else{ ?>
	<input type="hidden" name="post_message" value="1">
<?php }?>
<br />
<?php if ($_smarty_tpl->tpl_vars['user']->value['email_confirmed']){?>
<fieldset>
<legend>Send Email:</legend><br />
&nbsp;&nbsp;<label>From:</label> <font face="Courier"><?php echo $_smarty_tpl->tpl_vars['user']->value['email'];?>
</font><br />
&nbsp;&nbsp;<label>To:</label>
	<select name="email">
		<option value="A">All contributors (recommended)</option>
		<option value="R">Relatives only</option>
		<option value="S">Submitter only</option>
		<option value="" <?php if ($_smarty_tpl->tpl_vars['dpost']->value['post_id']){?>SELECTED<?php }?>>No email</option>
	</select><br />
<br />
<div id="contrib_link">
	<a href="#" onclick="$('contrib_list').show(); $('contrib_link').hide(); return false;">Show user list</a>
</div>
<div id="contrib_list" style="display: none">
	<a href="#" onclick="$('contrib_link').show(); $('contrib_list').hide(); return false;">Hide list</a><hr />
<ul>
<?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value){
$_smarty_tpl->tpl_vars['u']->_loop = true;
?>
	<li><a href="/user.php?id=<?php echo $_smarty_tpl->tpl_vars['u']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['u']->value['family_name'];?>
</a></li>
<?php } ?>
</ul>
</div>
</fieldset>
<?php }else{ ?>
<font color="red">Your email address <a href="/profile.php">has not been confirmed</a>! <br />You cannot send emails to other users.</font>
<?php }?>
<label><input type="checkbox" name="watch" value="1" checked>Watch for changes to this individual</label><br />
<br />
<input type="submit" name="save" value="Post Message">
</form>

</td></tr>
</table>

<table class="portal" width="400">
<tr><td>
<h2 style="text-align: center">Help</h2>

<h4>Why would I not want to post the message to the website?</h4>
In some cases, you need to send a time sensitive message to only certain users and that will not be relevant to others in the future. This keeps the person's genealogical record cleaner. However, in most cases you should post your message for others to read in the future. Questions should almost always be posted rather than just emailed.<br />

<h4>Which contributors are included in the email?</h4>
When you select the "All contributors" option, SharedTree sends an email addressed <b>from you</b> to any user who:
<ul>
<li>has made changes to the individual</li>
<li>has submitted photos for this individual</li>
<li>is watching this individual</li>
<li>previously posted a message</li>
<li>or is related to this individual</li>
</ul>
<i>Any one of the users above may chose to opt-out of these posts in the future.</i>

</td></tr>
</table>


<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>