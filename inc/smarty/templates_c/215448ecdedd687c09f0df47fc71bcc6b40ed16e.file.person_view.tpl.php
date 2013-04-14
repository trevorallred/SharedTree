<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:03:02
         compiled from "/var/www/sharedtree/templates/person_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12592101745132bd6644cd02-73866572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '215448ecdedd687c09f0df47fc71bcc6b40ed16e' => 
    array (
      0 => '/var/www/sharedtree/templates/person_view.tpl',
      1 => 1222116502,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12592101745132bd6644cd02-73866572',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'person' => 0,
    'person_id' => 0,
    'time' => 0,
    'show_this_is_you' => 0,
    'invite' => 0,
    'show_lds' => 0,
    'event' => 0,
    'is_logged_on' => 0,
    'eid' => 0,
    'photos' => 0,
    'duplicates' => 0,
    'dupe' => 0,
    'father' => 0,
    'mother' => 0,
    'siblings' => 0,
    'child' => 0,
    'marriages' => 0,
    'marriage' => 0,
    'groups' => 0,
    'persgroup' => 0,
    'wiki' => 0,
    'posts' => 0,
    'message' => 0,
    'user' => 0,
    'desc_users' => 0,
    'extendTree' => 0,
    'listviews' => 0,
    'views' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd66c2bc1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd66c2bc1')) {function content_5132bd66c2bc1($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/sharedtree/Smarty-3.1.7/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value,'includejs'=>1), 0);?>


<style>
.eventnote {
	display: none;
}
</style>
<script type="text/javascript">
function showNote(whichLayer) {
	var layer = document.getElementById(whichLayer).style;
	layer.display = "block";
}
function hideNote(whichLayer) {
	var layer = document.getElementById(whichLayer).style;
	layer.display = "none";
}
</script>

<h2><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>

<table class="portal">
<tr align="left">
<td width="350">
<div class="errors" align="center">
<?php if ($_smarty_tpl->tpl_vars['person']->value['merged_into']){?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['person']->value['merged_into'];?>
">Merged into Individual <?php echo $_smarty_tpl->tpl_vars['person']->value['merged_into'];?>
</a> <br>at <?php echo $_smarty_tpl->tpl_vars['person']->value['update_date'];?>
<br /><br />
	<?php if ($_smarty_tpl->tpl_vars['person']->value['merge_id']){?>
		<a href="#" onclick="stConfirm('You cannot undo this action! \nAre you sure you want to undo this merge?', '/merge.php?undo=<?php echo $_smarty_tpl->tpl_vars['person']->value['merge_id'];?>
&returnto=family.php%3fperson_id%3D<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
');">Undo Merge</a> <br /><br />
	<?php }?>
<?php }else{ ?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['delete_date']){?>
	<a href="/person_history.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">Deleted on <?php echo $_smarty_tpl->tpl_vars['person']->value['delete_date'];?>
</a> <br /><br />
	<a href="#" onclick="stConfirm('This will not undo changes already made to other records. \nAre you sure you want to add this person back?', '/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=restore');">Restore this record</a> <br /><br />
<?php }?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>Version: <?php echo $_smarty_tpl->tpl_vars['time']->value;?>
 <a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
"><img src="/img/btn_edit.png" title="Revert to this Version" width="16" height="16" border="0" /><br />
<a href="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">&lt;&lt; Back to Current Version</a><br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_this_is_you']->value){?><a href="/profile.php?addperson=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">Click here if this is you</a><br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['public_flag']==0){?>** Private record **<br /><?php }?>
</div>
<?php if ($_smarty_tpl->tpl_vars['invite']->value==1){?>
<form method="post" action="../invite.php">
	<input type="hidden" name="name[<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
"/>
	<b>Email:</b> <input type="text" name="email[<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
]" size="30" />
	<input type="submit" name="invite" value="Invite" />
</form>
<?php }?>

<label>Last name:</label> <?php if ($_smarty_tpl->tpl_vars['person']->value['orig_family_name']){?><?php echo $_smarty_tpl->tpl_vars['person']->value['orig_family_name'];?>
 <span title="translation" style="cursor: help">(<?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
)</span><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['person']->value['family_name'];?>
<?php }?><br>
<label>Given name:</label> <?php if ($_smarty_tpl->tpl_vars['person']->value['orig_given_name']){?><?php echo $_smarty_tpl->tpl_vars['person']->value['orig_given_name'];?>
 <span title="translation" style="cursor: help">(<?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
)</span><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['person']->value['given_name'];?>
<?php }?><br>
<label>Gender:</label>
<?php if ($_smarty_tpl->tpl_vars['person']->value['gender']=="M"){?>Male<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['gender']=="F"){?>Female<?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['gender']=="U"){?>Unknown<?php }?><br>
<?php if ($_smarty_tpl->tpl_vars['person']->value['prefix']){?><label>Prefix:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['prefix'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['suffix']){?><label>Suffix:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['suffix'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['nickname']){?><label>Nickname:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['nickname'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['title']){?><label>Title\Royalty:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['title'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['afn']){?><label>AFN:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['afn'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['national_id']){?><label>SSN or Nat'l ID:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['national_id'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['national_origin']){?><label>Nationality/Origin:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['national_origin'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['occupation']){?><label>Occupation:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['occupation'];?>
<br><?php }?>

<?php if ($_smarty_tpl->tpl_vars['person']->value['age']>0){?><label>Age:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['age'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['ancestor_count']>0){?><label>Ancestors:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['ancestor_count'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['descendant_count']>0){?><label>Descendants:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['descendant_count'];?>
<br><?php }?>
<?php if ($_smarty_tpl->tpl_vars['person']->value['trace_meaning']){?><label>Relation to You:</label> <?php echo $_smarty_tpl->tpl_vars['person']->value['trace_meaning'];?>
<br><?php }?>

<br clear="all" />
<?php if ($_smarty_tpl->tpl_vars['person']->value['e']){?>
<h3 align="center">Events</h3>
<table class="grid" width="100%">
<?php  $_smarty_tpl->tpl_vars['event'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['event']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['person']->value['e']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['event']->key => $_smarty_tpl->tpl_vars['event']->value){
$_smarty_tpl->tpl_vars['event']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['show_lds']->value==1||$_smarty_tpl->tpl_vars['event']->value['lds_flag']==0){?>
	<?php $_smarty_tpl->tpl_vars["eid"] = new Smarty_variable($_smarty_tpl->tpl_vars['event']->value['event_id'], null, 0);?>
	<tr onmouseover="showNote('note<?php echo $_smarty_tpl->tpl_vars['event']->value['event_id'];?>
');" onmouseout="hideNote('note<?php echo $_smarty_tpl->tpl_vars['event']->value['event_id'];?>
');" id="row<?php echo $_smarty_tpl->tpl_vars['event']->value['event_id'];?>
')">
		<th><?php echo $_smarty_tpl->tpl_vars['event']->value['prompt'];?>
:</th>
		<td>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?><a href="#" onclick="stConfirm('Are you sure you want to delete this <?php echo $_smarty_tpl->tpl_vars['event']->value['prompt'];?>
?', '/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&event_id=<?php echo $_smarty_tpl->tpl_vars['event']->value['event_id'];?>
&action=deleteevent');"><img src="/img/btn_drop.png" title="Remove <?php echo $_smarty_tpl->tpl_vars['event']->value['prompt'];?>
" border="0" height="16" width="16" align="right"></a><?php }?>
		<?php echo $_smarty_tpl->tpl_vars['event']->value['date_approx'];?>

		<?php if ($_smarty_tpl->tpl_vars['event']->value['event_date']){?>
			<?php echo $_smarty_tpl->tpl_vars['event']->value['event_date'];?>
<?php if ($_smarty_tpl->tpl_vars['event']->value['ad']=='0'){?> B.C.<?php }?>
		<?php }else{ ?>
			<?php if ($_smarty_tpl->tpl_vars['event']->value['date_text']){?>Unreadable date: <i><?php echo $_smarty_tpl->tpl_vars['event']->value['date_text'];?>
</i><br><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['event']->value['eyear']&&$_smarty_tpl->tpl_vars['event']->value['event_type']=="BIRT"){?>
				<?php echo $_smarty_tpl->tpl_vars['event']->value['eyear'];?>
 *Computer estimated
			<?php }?>
		<?php }?> <?php echo $_smarty_tpl->tpl_vars['event']->value['status'];?>

		<?php if ($_smarty_tpl->tpl_vars['event']->value['age_at_event']){?>Age: <?php echo $_smarty_tpl->tpl_vars['event']->value['age_at_event'];?>
<?php }?>
		<br /><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['location'], ENT_QUOTES, 'ISO-8859-1', true);?>
 <?php echo $_smarty_tpl->tpl_vars['event']->value['temple_code'];?>


		<div id="note<?php echo $_smarty_tpl->tpl_vars['event']->value['event_id'];?>
" class="eventnote">
		<?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['eid']->value]['image_id']>0){?><a href="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['eid']->value]['image_id'];?>
&action=summary"><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['eid']->value]['image_id'];?>
&size=thumb" border="0" align="right" width="25" height="25"></a><?php }?>
		<br />Source image: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['source'], ENT_QUOTES, 'ISO-8859-1', true);?>
  <?php if (!$_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->tpl_vars['eid']->value]['image_id']&&$_smarty_tpl->tpl_vars['is_logged_on']->value){?><a href="/image.php?action=edit&data[person_id]=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&data[event_id]=<?php echo $_smarty_tpl->tpl_vars['eid']->value;?>
">Upload</a><?php }?>
		
		<?php if ($_smarty_tpl->tpl_vars['event']->value['notes']){?><br />Notes: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['event']->value['notes'], ENT_QUOTES, 'ISO-8859-1', true);?>
<?php }?>
		</div>
		</td>
	</tr>
	<?php }?>
<?php } ?>
</table>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value&&$_smarty_tpl->tpl_vars['duplicates']->value){?>
<h3>Potential Duplicate(s):</h3>
	<ul>
	<?php  $_smarty_tpl->tpl_vars['dupe'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dupe']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['duplicates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dupe']->key => $_smarty_tpl->tpl_vars['dupe']->value){
$_smarty_tpl->tpl_vars['dupe']->_loop = true;
?>
		<li><a href="/merge.php?p1=<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
&p2=<?php echo $_smarty_tpl->tpl_vars['dupe']->value['person_id'];?>
&returnto=person/<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
">Merge</a> | <a href="<?php echo $_smarty_tpl->tpl_vars['dupe']->value['person_id'];?>
">View</a> | <?php echo $_smarty_tpl->tpl_vars['dupe']->value['person_id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['dupe']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['dupe']->value['family_name'];?>
</li>
	<?php } ?>
	</ul>
<?php }?>
<p align="center">
<form method=post action="http://wc.rootsweb.com/cgi-bin/igm.cgi" target="_BLANK">
<a href="../search.php?given_name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['person']->value['given_name']);?>
&family_name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['person']->value['family_name']);?>
&birth_year=<?php echo $_smarty_tpl->tpl_vars['person']->value['birth_year'];?>
&range=5&adbc=1&sort=birth&search=Search">Search SharedTree</a>
<input type=hidden name=op value="Search">
<input type=hidden name=includedb value="">
<input type=hidden name=lang value="en">
<input type=hidden name=ti value="">

<input type=hidden name=surname value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
">
<input type=hidden name=stype value="Exact">
<input type=hidden name=given value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['person']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
">
<input type=hidden name=byear value="<?php echo $_smarty_tpl->tpl_vars['person']->value['birth_year'];?>
">
<input type=hidden name=brange value="1">
<input type=hidden name=period value="All">
<input type=submit name=submit.x value="Search Rootsweb">
</form>
</p>

<p align="center" style="color: #555; font-size: 10px;">Updated by: 
<a href="/profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['person']->value['any_updated_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['any_updated_name'];?>
</a> at 
<a href="/person_history.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['any_update_date'];?>
</a></p>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value&&!$_smarty_tpl->tpl_vars['person']->value['delete_date']){?>
<p align="center">
	<a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&return_to=/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_edit.png" border="0" /></a><a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&return_to=/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
">Edit</a>
	&nbsp;&nbsp;
	<a href="#" onclick="stConfirm('Are you sure you want to delete this person?', '/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=delete');"><img src="/img/btn_drop.png" border="0" /></a><a href="#" onclick="stConfirm('Are you sure you want to delete this person?', '/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=delete');">Delete</a>
</p>
<?php }?>
</td>
<td>
<table>
<tr><td>Childhood</td>
	<td>Adulthood</td>
	<td>Later Years</td>
</tr>
<tr><td>
<?php if ($_smarty_tpl->tpl_vars['photos']->value['P1']['image_id']>0){?>
	<a href="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value['P1']['image_id'];?>
&action=summary"><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value['P1']['image_id'];?>
&size=thumb" border="0"></a>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
		<a href="/image.php?action=edit&data[person_id]=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&data[image_order]=1">Add Photo</a>
	<?php }else{ ?>no photo
	<?php }?>
<?php }?>
	</td>
	<td>
<?php if ($_smarty_tpl->tpl_vars['photos']->value['P2']['image_id']>0){?>
	<a href="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value['P2']['image_id'];?>
&action=summary"><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value['P2']['image_id'];?>
&size=thumb" border="0"></a>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
		<a href="/image.php?action=edit&data[person_id]=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&data[image_order]=2">Add Photo</a>
	<?php }else{ ?>no photo
	<?php }?>
<?php }?>
	</td>
	<td>
<?php if ($_smarty_tpl->tpl_vars['photos']->value['P3']['image_id']>0){?>
	<a href="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value['P3']['image_id'];?>
&action=summary"><img src="/image.php?image_id=<?php echo $_smarty_tpl->tpl_vars['photos']->value['P3']['image_id'];?>
&size=thumb" border="0"></a>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
		<a href="/image.php?action=edit&data[person_id]=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&data[image_order]=3">Add Photo</a>
	<?php }else{ ?>no photo
	<?php }?>
<?php }?>
	</td>
</tr>
</table>
	<h2>Family</h2>
<label>Father:</label> 
<?php if ($_smarty_tpl->tpl_vars['father']->value['protected']==1){?>
	<?php echo $_smarty_tpl->tpl_vars['father']->value['given_name'];?>

<?php }else{ ?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['father']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['father']->value['full_name'];?>
</a>
	(<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['father']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['father']->value['e']['BIRT']['event_year']), 0);?>
 - <?php echo $_smarty_tpl->tpl_vars['father']->value['e']['DEAT']['event_year'];?>
) <font size="1"><?php echo $_smarty_tpl->tpl_vars['father']->value['trace_meaning'];?>
</font>
<?php }?><br />
<label>Mother:</label>
<?php if ($_smarty_tpl->tpl_vars['mother']->value['protected']==1){?>
	<?php echo $_smarty_tpl->tpl_vars['mother']->value['given_name'];?>

<?php }else{ ?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['mother']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['mother']->value['full_name'];?>
</a>
	(<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['mother']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['mother']->value['e']['BIRT']['event_year']), 0);?>
 - <?php echo $_smarty_tpl->tpl_vars['mother']->value['e']['DEAT']['event_year'];?>
) <font size="1"><?php echo $_smarty_tpl->tpl_vars['mother']->value['trace_meaning'];?>
</font>
<?php }?><br />
<?php if ($_smarty_tpl->tpl_vars['siblings']->value){?>
	&nbsp;<label>Siblings:</label>
<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['siblings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['child']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['child']->iteration=0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['sibling']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
 $_smarty_tpl->tpl_vars['child']->iteration++;
 $_smarty_tpl->tpl_vars['child']->last = $_smarty_tpl->tpl_vars['child']->iteration === $_smarty_tpl->tpl_vars['child']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['sibling']['index']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['sibling']['last'] = $_smarty_tpl->tpl_vars['child']->last;
?>
	<?php if ($_smarty_tpl->tpl_vars['child']->value['person_id']!=$_smarty_tpl->tpl_vars['person']->value['person_id']){?>
		<?php if ($_smarty_tpl->tpl_vars['child']->value['protected']==1){?>
			<?php echo $_smarty_tpl->tpl_vars['child']->value['full_name'];?>
<?php }else{ ?>
			<a href="<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['given_name'];?>
</a><?php }?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['sibling']['last']){?>,
			<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['sibling']['index']%5==4){?><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php }?>
		<?php }?>
	<?php }?>
<?php } ?><br />
<?php }?>
<?php  $_smarty_tpl->tpl_vars['marriage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['marriage']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['marriages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['marriage']->key => $_smarty_tpl->tpl_vars['marriage']->value){
$_smarty_tpl->tpl_vars['marriage']->_loop = true;
?>
	&nbsp;<label>Spouse:</label> <a href="<?php echo $_smarty_tpl->tpl_vars['marriage']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['marriage']->value['full_name'];?>
</a> <a href="/marriage.php?family_id=<?php echo $_smarty_tpl->tpl_vars['marriage']->value['family_id'];?>
"><img src="/img/ico_family.gif" width="14" height="15" border="0" title="View Marriage"></a> (<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['marriage']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['marriage']->value['b_date']), 0);?>
) <font size="1"><?php echo $_smarty_tpl->tpl_vars['marriage']->value['trace_meaning'];?>
</font><br>
	<?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['marriage']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
	&nbsp;&nbsp;&nbsp;&nbsp;<label>Child:</label> <?php if ($_smarty_tpl->tpl_vars['child']->value['protected']==1){?><?php echo $_smarty_tpl->tpl_vars['child']->value['full_name'];?>
<?php }else{ ?><a href="<?php echo $_smarty_tpl->tpl_vars['child']->value['person_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['given_name'];?>
</a> (<?php echo $_smarty_tpl->getSubTemplate ("birth_year.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('birth_year'=>$_smarty_tpl->tpl_vars['child']->value['birth_year'],'birth_date'=>$_smarty_tpl->tpl_vars['child']->value['b_date']), 0);?>
) <font size="1"><?php echo $_smarty_tpl->tpl_vars['child']->value['trace_meaning'];?>
</font><?php }?><br>
	<?php } ?>
<?php } ?>
	<h2>Biography & History</h2>
<p><label>Groups:</label> 
<?php  $_smarty_tpl->tpl_vars['persgroup'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['persgroup']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['persgroup']->key => $_smarty_tpl->tpl_vars['persgroup']->value){
$_smarty_tpl->tpl_vars['persgroup']->_loop = true;
?>
	<a href="/group.php?group_id=<?php echo $_smarty_tpl->tpl_vars['persgroup']->value['group_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['persgroup']->value['group_name'];?>
</a> | 
<?php } ?>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?><a href="/group.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&byear=<?php echo $_smarty_tpl->tpl_vars['person']->value['e']['BIRT']['event_year'];?>
&dyear=<?php echo $_smarty_tpl->tpl_vars['person']->value['e']['DEAT']['event_year'];?>
">add new</a><?php }else{ ?>log in to add<?php }?>
</p>
<?php if ($_smarty_tpl->tpl_vars['person']->value['wikipedia']){?>
	<p><label>Wikipedia:</label> 
	<a href="http://en.wikipedia.com/wiki/<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['person']->value['wikipedia']);?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['wikipedia'];?>
</a></p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<span style="float: right"><a href="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
?action=wikiedit"><img src="/img/btn_edit.png" title="Edit Biography" width="16" height="16" border="0" /></a></span>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['wiki']->value['wiki_text']){?>
	<?php echo $_smarty_tpl->tpl_vars['wiki']->value['wiki_text'];?>

<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?><center><br><br><br><font color="#999999">There is no biography for this individual yet. <br><a href="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=wikiedit">Click here</a> to start one.</font></center><?php }?>
<?php }?>
</td>
</tr>
<tr align="left">
<td colspan="2">
	<h2>Message Board: <font size="-1">Research, Reunions, and Questions</font></h2>
	<a href="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=discussedit" title="Send an email or post a message here about this person"><b>Send/Post Message</b></a>
	<table border="0" width="100%">
	<?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['posts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value){
$_smarty_tpl->tpl_vars['message']->_loop = true;
?>
		<tr class="post_row">
			<td valign="top">
				<b class="user"><a href="/profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['message']->value['created_by'];?>
"><?php echo $_smarty_tpl->tpl_vars['message']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['message']->value['family_name'];?>
</a></b><br />
				<label>Joined:</label> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['message']->value['join_date']);?>
<br />
				<label>From:</label> <?php if ($_smarty_tpl->tpl_vars['message']->value['city']||$_smarty_tpl->tpl_vars['message']->value['state_code']){?>
							<?php echo $_smarty_tpl->tpl_vars['message']->value['city'];?>
<?php if ($_smarty_tpl->tpl_vars['message']->value['city']&&$_smarty_tpl->tpl_vars['message']->value['state_code']){?>,<?php }?> <?php echo $_smarty_tpl->tpl_vars['message']->value['state_code'];?>

							<?php }else{ ?>unknown<?php }?>
			</td>
			<td>
				<div style="float: right; margin: 5px"><label>Posted:</label> <?php echo $_smarty_tpl->tpl_vars['message']->value['creation_date'];?>

				<?php if ($_smarty_tpl->tpl_vars['message']->value['update_date']!=$_smarty_tpl->tpl_vars['message']->value['creation_date']){?>
					<br /><label>Last changed:</label> <?php echo $_smarty_tpl->tpl_vars['message']->value['update_date'];?>

				<?php }?>
				</div> 
				<label><?php echo $_smarty_tpl->tpl_vars['message']->value['post_meaning'];?>
:</label> <?php echo $_smarty_tpl->tpl_vars['message']->value['post_text'];?>

				<?php if ($_smarty_tpl->tpl_vars['user']->value['user_id']==$_smarty_tpl->tpl_vars['message']->value['created_by']){?>
					<br />
					<a href="/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=discussedit&post_id=<?php echo $_smarty_tpl->tpl_vars['message']->value['post_id'];?>
">Edit</a>
					<a href="/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=discussdelete&post_id=<?php echo $_smarty_tpl->tpl_vars['message']->value['post_id'];?>
">Delete</a>
				<?php }?>
			</td>
		</tr>
	<?php }
if (!$_smarty_tpl->tpl_vars['message']->_loop) {
?>
		<tr><br><br><br>There are no messages or discussions for this individual<br><br><br>
		</td></tr>
	<?php } ?>
	</table>
	<a href="<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&action=discussedit" title="Send an email or post a message here about this person"><b>Send/Post Message</b></a>
</td>
</tr>
<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
<tr>
<td style="background: #DDD;">
<?php if ($_smarty_tpl->tpl_vars['person']->value['watch_id']>0){?>
	<a href="/watch.php?action=unwatch&watch_id=<?php echo $_smarty_tpl->tpl_vars['person']->value['watch_id'];?>
">Unwatch</a>
<?php }else{ ?>
	<a href="/watch.php?action=save&data[watch_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['watch_id'];?>
&data[person_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
&data[bookmark]=0">Watch</a> or 
	<a href="/watch.php?action=save&data[watch_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['watch_id'];?>
&data[person_id]=<?php echo $_smarty_tpl->tpl_vars['person']->value['person_id'];?>
&data[bookmark]=1">Bookmark</a>
<?php }?>
<hr>

<h2>Related Users</h2>
<?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['desc_users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
?>
  <a href="/profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['user']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['family_name'];?>
</a> <font size="1"><?php echo $_smarty_tpl->tpl_vars['user']->value['trace'];?>
</font><br />
<?php } ?>
<?php if ($_smarty_tpl->tpl_vars['extendTree']->value>0){?><b>Added <?php echo $_smarty_tpl->tpl_vars['extendTree']->value;?>
 related users</b><?php }?>
</td>
<td style="background: #DDD;">
<h2>View History</h2>
<?php  $_smarty_tpl->tpl_vars['views'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['views']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listviews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['views']->key => $_smarty_tpl->tpl_vars['views']->value){
$_smarty_tpl->tpl_vars['views']->_loop = true;
?>
 <?php echo $_smarty_tpl->tpl_vars['views']->value['last_update_date'];?>
 by <a href="/profile.php?user_id=<?php echo $_smarty_tpl->tpl_vars['views']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['views']->value['given_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['views']->value['family_name'];?>
</a><br />
<?php } ?>
<?php echo $_smarty_tpl->tpl_vars['person']->value['page_views'];?>
 page views
</td>
</tr>
<?php }?>
</table>

<?php if (!$_smarty_tpl->tpl_vars['is_logged_on']->value){?>
Is this your ancestor? <a href="/register.php">Create an account</a> and make changes today.
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>