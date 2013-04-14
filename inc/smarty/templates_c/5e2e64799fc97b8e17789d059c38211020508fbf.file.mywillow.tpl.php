<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:37:03
         compiled from "/var/www/sharedtree/templates/mywillow.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17034760915132c55fd7e3c5-52706424%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e2e64799fc97b8e17789d059c38211020508fbf' => 
    array (
      0 => '/var/www/sharedtree/templates/mywillow.tpl',
      1 => 1252772906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17034760915132c55fd7e3c5-52706424',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'myline' => 0,
    'bookmarks' => 0,
    'bookmark' => 0,
    'relatives' => 0,
    'relative' => 0,
    'photos' => 0,
    'photo' => 0,
    'person_changes' => 0,
    'change' => 0,
    'posts' => 0,
    'post' => 0,
    'bios' => 0,
    'announcements' => 0,
    'announce' => 0,
    'recent_forum_posts' => 0,
    'viewed' => 0,
    'person' => 0,
    'count_person' => 0,
    'count_family' => 0,
    'count_user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132c56032114',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132c56032114')) {function content_5132c56032114($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/modifier.truncate.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>"My Tree"), 0);?>


<br clear="all" />
<table>
<tr valign="top"><td width="30%"><!-- left panel -->
<table class="portal" width="100%">
<tr><td width="100%">
	&gt;&gt; <a href="simple.php"><b>Click Here to Get Started</b></a> &lt;&lt;
	<br><br><br>

<?php if ($_smarty_tpl->tpl_vars['user']->value['person_id']>0){?>
	<h3>My Personal Genealogy Record</h3>
	<label><?php echo $_smarty_tpl->tpl_vars['user']->value['p_given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['p_family_name'];?>
</label><br />
	<?php echo $_smarty_tpl->getSubTemplate ("person_nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('nav_id'=>$_smarty_tpl->tpl_vars['user']->value['person_id'],'direction'=>"flat"), 0);?>

	<br><br />
	<?php if ($_smarty_tpl->tpl_vars['myline']->value['person']==0){?>
		<span class="errors">You need to <a href="familytree.php">rebuild your family tree</a>!</span>
	<?php }else{ ?>
		You currently have <?php echo $_smarty_tpl->tpl_vars['myline']->value['person'];?>
 individuals <br>
		in your <a href="familytree.php">family tree</a>
	<?php }?>
<?php }?>
</td></tr>
<tr><td>
	<div style="float:right"><a href="profile.php"><img src="img/btn_edit.png" title="Edit Profile" width="16" height="16" border="0"/>Edit</a></div>
	<h3>My Profile</h3>
	<span class="label">Name:</span> <?php echo $_smarty_tpl->tpl_vars['user']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['family_name'];?>
<br>
	<span class="label">Email:</span> <?php echo $_smarty_tpl->tpl_vars['user']->value['email'];?>
<br>
	<span class="label">Address:</span>
	<?php if ($_smarty_tpl->tpl_vars['user']->value['address_line1']||$_smarty_tpl->tpl_vars['user']->value['city']){?>
		<?php if ($_smarty_tpl->tpl_vars['user']->value['address_line1']){?><?php echo $_smarty_tpl->tpl_vars['user']->value['address_line1'];?>
<?php }else{ ?><a href="profile.php">No Street Address</a><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['user']->value['address_line2']){?><?php echo $_smarty_tpl->tpl_vars['user']->value['address_line2'];?>
<?php }?>
		<?php echo $_smarty_tpl->tpl_vars['user']->value['city'];?>
, <?php echo $_smarty_tpl->tpl_vars['user']->value['state_code'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['postal_code'];?>

	<?php }else{ ?>
		<a href="profile.php"><i>Add your mailing address</i></a>
	<?php }?>
</td></tr>
<tr><td>
	<h3>My Bookmarks</h3>
	<?php if ($_smarty_tpl->tpl_vars['bookmarks']->value){?>
	<ul>
		<?php  $_smarty_tpl->tpl_vars['bookmark'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bookmark']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['bookmarks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bookmark']->key => $_smarty_tpl->tpl_vars['bookmark']->value){
$_smarty_tpl->tpl_vars['bookmark']->_loop = true;
?>
		<li><a href="/person/<?php echo $_smarty_tpl->tpl_vars['bookmark']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['bookmark']->value['family_name'];?>
, <?php echo $_smarty_tpl->tpl_vars['bookmark']->value['given_name'];?>
</a></li>
		<?php } ?>
	</ul>
	<?php }else{ ?>
		<i>No Bookmarks</i><br />
	<?php }?>
	<a href="watch.php">Manage Bookmarks &amp; Watchlist</a>
</td></tr>
<?php if ($_smarty_tpl->tpl_vars['user']->value['person_id']>0){?>
<tr><td>
	<h3>My Online Relatives</h3>
	<ul>
		<?php  $_smarty_tpl->tpl_vars['relative'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['relative']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['relatives']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['relative']->key => $_smarty_tpl->tpl_vars['relative']->value){
$_smarty_tpl->tpl_vars['relative']->_loop = true;
?>
		<li><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['relative']->value['user_id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['relative']->value['trace'];?>
"><?php echo $_smarty_tpl->tpl_vars['relative']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['relative']->value['family_name'];?>
</a> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['relative']->value['last_visit_date'],"%e %b %H:%M");?>
</li>
		<?php } ?>
	</ul>
<a href="relatives.php">View all relatives</a> |
<a href="invite.php">Invite others</a>
</td></tr>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['photos']->value){?>
<tr><td>
        <h3>New Photos &amp; Source Documents</h3>
        <ul>
                <?php  $_smarty_tpl->tpl_vars['photo'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['photo']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['photos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['photos']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['photo']->key => $_smarty_tpl->tpl_vars['photo']->value){
$_smarty_tpl->tpl_vars['photo']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['photos']['index']++;
?>
<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['photos']['index']==0){?>
		<img src="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photo']->value['image_id'];?>
&size=thumb" align="right" border="1" />
<?php }?>
                <li><a href="image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photo']->value['image_id'];?>
&action=summary"><?php echo $_smarty_tpl->tpl_vars['photo']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['photo']->value['family_name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['photo']->value['birth_year'];?>
)</a><br />
		<font size="1">added by <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['photo']->value['updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['photo']->value['updated_name'];?>
</a> on <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['photo']->value['update_date'],"%b %e");?>
</font></li>
                <?php } ?>
        </ul>
<a href="relatives.php">View all relatives</a> |
<a href="invite.php">Invite others</a>
</td></tr>
<?php }?>
</table>
</td>
<?php if ($_smarty_tpl->tpl_vars['user']->value['person_id']>0){?>
<td width="40%"><!-- middle panel -->
<table class="portal" width="100%">
<tr><td width="100%">
<h3>Recent Changes</h3>

<b>Individuals</b>
<?php if ($_smarty_tpl->tpl_vars['person_changes']->value){?>
<table class="grid">
	<?php  $_smarty_tpl->tpl_vars['change'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['change']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['person_changes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['change']->key => $_smarty_tpl->tpl_vars['change']->value){
$_smarty_tpl->tpl_vars['change']->_loop = true;
?>
<tr><td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['change']->value['person_id'];?>
" title="Relation: <?php echo $_smarty_tpl->tpl_vars['change']->value['trace'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['change']->value['family_name'];?>
</a></td>
	<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['change']->value['actual_start_date'],"%e %b %H:%M");?>
</td>
	<td><a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['change']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['change']->value['user_name'];?>
</a></td>
</tr>
	<?php } ?>
</table>
<?php }else{ ?>
	<p>There have been no changes to individuals yet</p>
<?php }?>
<a href="recent_changes.php">Recent Changes</a>

<p>
<b>Posts</b>
<table class="grid">
<?php  $_smarty_tpl->tpl_vars['post'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['post']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['posts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['post']->key => $_smarty_tpl->tpl_vars['post']->value){
$_smarty_tpl->tpl_vars['post']->_loop = true;
?>
	<tr>
	<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['post']->value['update_date'],"%e %b %H:%M");?>
 <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['post']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['ugiven_name'];?>
</a></td>
	<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['post']->value['person_id'];?>
#post<?php echo $_smarty_tpl->tpl_vars['post']->value['post_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['post']->value['family_name'];?>
</a> - <?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['post']->value['post_text']),50);?>
</a></td>
	</tr>
<?php } ?>
</table>

</p>

<p>
<b>Biographies</b>
<table class="grid">
<?php  $_smarty_tpl->tpl_vars['post'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['post']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['bios']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['post']->key => $_smarty_tpl->tpl_vars['post']->value){
$_smarty_tpl->tpl_vars['post']->_loop = true;
?>
	<tr>
	<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['post']->value['update_date'],"%b %e");?>
 <a href="profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['post']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['ugiven_name'];?>
</a></td>
	<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['post']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['post']->value['family_name'];?>
</a> - <?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['post']->value['post_text']),50);?>
</a></td>
	</tr>
<?php } ?>
</table>

</p>

</td></tr>
</table>
</td>
<?php }?>
<td width="30%"><!-- right panel -->
<table class="portal" width="100%">
<?php if ($_smarty_tpl->tpl_vars['announcements']->value){?>
<tr><td width="100%">
<h3>Announcements</h3>
<?php  $_smarty_tpl->tpl_vars['announce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['announce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['announcements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['announce']->key => $_smarty_tpl->tpl_vars['announce']->value){
$_smarty_tpl->tpl_vars['announce']->_loop = true;
?>
	<p><label><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['announce']->value['start_date'],"%b %e");?>
:</label> <?php echo $_smarty_tpl->tpl_vars['announce']->value['announcement'];?>
 <br /><i>Posted by: <?php echo $_smarty_tpl->tpl_vars['announce']->value['user_name'];?>
</i></p>
<?php } ?>
</td></tr>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['recent_forum_posts']->value){?>
<tr><td width="100%">
<h3>Recent Forum Posts</h3>
<?php  $_smarty_tpl->tpl_vars['posts'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['posts']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['recent_forum_posts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['posts']->key => $_smarty_tpl->tpl_vars['posts']->value){
$_smarty_tpl->tpl_vars['posts']->_loop = true;
?>
	<label><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['posts']->value['topic_last_post_time'],"%b %e");?>
</label> <i>by <?php echo $_smarty_tpl->tpl_vars['posts']->value['topic_last_poster_name'];?>
</i> <a href="forums/viewtopic.php?t=<?php echo $_smarty_tpl->tpl_vars['posts']->value['topic_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['posts']->value['topic_title'];?>
</a><br />
<?php } ?>
</td></tr>
<?php }?>
<tr><td>
<h3>Recently Viewed</h3>
<?php if ($_smarty_tpl->tpl_vars['viewed']->value){?>
<table class="grid">
<tr>
	<th>Individual</th>
	<th>Last Viewed</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['viewed']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
<tr>
	<td><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
</a></td>
	<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['person']->value['last_update_date'],"%e %b %H:%M");?>
</td>
</tr>
<?php } ?>
</table>
<?php }else{ ?>
	<p>You haven't seen any individuals yet</p>
<?php }?>
</td></tr>
<?php if ($_smarty_tpl->tpl_vars['user']->value['person_id']>0){?>
<tr><td>
<h3>Other Links</h3>
<ul class="sidelinks">
<li><a href="person_edit.php">Add Individual</a></li>
<li><a href="locations.php">Browse Places</a></li>
<li><a href="calendar.php">Calendar</a></li>
<li><a href="group.php">Groups</a></li>
<?php if ($_smarty_tpl->tpl_vars['user']->value['person_id']>0){?>
<li><a href="familytree.php">My Family Tree</a></li>
<li><a href="relatives.php">My Relatives</a></li>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['user']->value['show_lds']>0){?>
<li><a href="templework.php">Temple Work</a></li>
<?php }?>
<li><a href="orphans.php">Orphans</a></li>
<li><a href="stats.php">Stats</a></li>
<li><a href="list.php">Surname List</a></li>
<li><a href="wikipedia.php">Wikipedia Articles</a></li>
</ul>
</td></tr>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['user']->value['user_id']==1){?>
<tr><td>
<h3>Utilities for Trevor</h3>

<a href="util/merge_search.php">Merge Individuals</a><br>
<a href="http://phpmyadmin.mysite4now.com/">phpMyAdmin</a><br>
<a href="util/estimate_birth.php">Estimate Birth Years</a><br>
<a href="util/set_public_flag.php">Set Public Flags</a><br>
<a href="util/rebuild_trees.php">Build FTIs</a><br>
<a href="tests/">Unit Testing</a><br>
<a href="user.php">User List</a><br>
</td></tr>
<?php }?>
<tr><td width="100%">
<h3>Statistics</h3>
<?php echo $_smarty_tpl->tpl_vars['count_person']->value;?>
 individuals
<br><?php echo $_smarty_tpl->tpl_vars['count_family']->value;?>
 marriages
<br><?php echo $_smarty_tpl->tpl_vars['count_user']->value;?>
 users
</td></tr>

</td>
</tr>
</table>
</td></tr>
</table>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>