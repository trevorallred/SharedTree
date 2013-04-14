<?php /* Smarty version Smarty-3.1.7, created on 2013-03-03 18:22:49
         compiled from "/var/www/sharedtree/templates/invite.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1186542581513405798a2125-97801345%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9d1a8cb22065d99c9f9a3343120b16ceb23411f' => 
    array (
      0 => '/var/www/sharedtree/templates/invite.tpl',
      1 => 1210468626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1186542581513405798a2125-97801345',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'list' => 0,
    'person' => 0,
    'domain_name' => 0,
    'user' => 0,
    'sent' => 0,
    'relatives' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51340579b6bea',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51340579b6bea')) {function content_51340579b6bea($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"Invite Relatives"), 0);?>

<br clear="all" />
<br />

<h1>Invite Relatives to SharedTree</h1>

<a href="/wiki/index.php?title=Invite_Relatives#How_do_I_invite_my_non-related_friends.3F">How do I invite my friends?</a>

<?php if ($_smarty_tpl->tpl_vars['list']->value){?>
	<table class="portal">
	<tr><td>
	<form method="post">
	<ul>
	<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
	<input type="hidden" name="name[<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['name'];?>
" />
	<input type="hidden" name="email[<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['email'];?>
" />
		<li><?php echo $_smarty_tpl->tpl_vars['person']->value['name'];?>
 &lt;<?php echo $_smarty_tpl->tpl_vars['person']->value['email'];?>
&gt;</li>
	<?php } ?>
	</ul>
	</td></tr>
	<tr><td>
	Send the following email to the above individuals. You may edit the contents before clicking send.<br /><br />
	Subject: <input type="text" name="subject" value="SharedTree Invitation" /><br />
	<textarea cols="70" rows="10" class="email" name="message">PERSONS_NAME

Here is an invitation to SharedTree. By joining this free genealogy service you automatically get access to our family's genealogy and have the ability to contribute yourself. Use the link below to register. This secret link will allow you to access your own genealogy record instantly.

<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
register.php?person_id=PERSON_ID&key=SECRETKEY

<?php echo $_smarty_tpl->tpl_vars['user']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['family_name'];?>
</textarea>
	<br />
	<input type="submit" name="send" value="Send Email(s)" />
	</td></tr>
	</form>
	</table>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['sent']->value){?>
		<table class="portal">
		<tr><td>
		You sent invitations to the following:
		<ul>
		<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sent']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
			<li><?php echo $_smarty_tpl->tpl_vars['person']->value['name'];?>
 &lt;<?php echo $_smarty_tpl->tpl_vars['person']->value['email'];?>
&gt;</li>
		<?php } ?>
		</ul>
		</td></tr></table>
	<?php }?>

	<?php if ($_smarty_tpl->tpl_vars['relatives']->value){?>
	<form method="post">
	<input type="submit" name="invite" value="Invite Relatives" />
	<table class="grid">
		<tr>
			<td class="label">Name</td>
			<td class="label">Email</td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['relatives']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
$_smarty_tpl->tpl_vars['result']->_loop = true;
?>
			<tr>
				<td><input type="hidden" name="name[<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value['family_name'];?>
"/>
				<a href="family.php?person_id=<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value['family_name'];?>
</a></td>
				<td><input type="text" name="email[<?php echo $_smarty_tpl->tpl_vars['result']->value['person_id'];?>
]" size="30" /></td>
			</tr>
		<?php } ?>
	</table>
	<input type="submit" name="invite" value="Invite Relatives" />
	</form>
	<?php }else{ ?>
	<table class="portal">
		<tr>
			<td colspan="2" width="400">
			You have no living relatives listed in SharedTree yet. Please add those relatives, rebuild your family tree, and try again. Click Help for more information.
			</td>
		</tr>
	</table>
	<?php }?>
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>