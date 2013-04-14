<?php /* Smarty version Smarty-3.1.7, created on 2013-03-02 19:02:50
         compiled from "/var/www/sharedtree/templates/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3929831735132bd5af24961-64858668%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '433af991a76e1068897eee8d9d928e5932a7398a' => 
    array (
      0 => '/var/www/sharedtree/templates/header.tpl',
      1 => 1325981571,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3929831735132bd5af24961-64858668',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'noindex' => 0,
    'area_type' => 0,
    'includejs' => 0,
    'title' => 0,
    'background' => 0,
    'is_logged_on' => 0,
    'user' => 0,
    'person_id' => 0,
    'primary_person' => 0,
    'time' => 0,
    'help' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5132bd5b248ae',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5132bd5b248ae')) {function content_5132bd5b248ae($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if ($_smarty_tpl->tpl_vars['noindex']->value||$_smarty_tpl->tpl_vars['area_type']->value!="prod"){?><meta name="robots" content="noindex, nofollow"><?php }?>
<?php if ($_smarty_tpl->tpl_vars['includejs']->value){?>
<script src="/js/sharedtree.js" type="text/javascript"></script>
<script src="/js/prototype.js" type="text/javascript"></script>
<script src="/js/scriptaculous.js" type="text/javascript"></script>
<?php }?>
<title>SharedTree<?php if ($_smarty_tpl->tpl_vars['area_type']->value!="prod"){?>_<?php echo $_smarty_tpl->tpl_vars['area_type']->value;?>
<?php }?>: <?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link href="/styles.css" type="text/css" rel="stylesheet" />
<?php if ($_smarty_tpl->tpl_vars['background']->value=="edit"){?>
<style>

html, body {
        background: #FFC url(img/bg_sky.jpg) repeat-x;
}

</style>
<?php }?>
</head>
<body>
<div id="header">
	<div id="nav1">
		<ul>
			<li style="width: 150px">&nbsp;</li>
		<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/index.php"){?>class="current"<?php }?> title="Show the Home Page for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
"><a href="/index.php"><img src="/img/ico_home.png" />My Tree</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/search.php"){?>class="current"<?php }?>><a href="/search.php"><img src="/img/btn_search.png" />Search</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/image.php"){?>class="current"<?php }?> title="See photos of your relatives"><a href="/image.php?action=list"><img src="/img/btn_photos.png" />Photos</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/merge.php"){?>class="current"<?php }?>><a href="/merge.php?action=list"><img src="/img/btn_arrow_join.png" />Merge</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/profile.php"){?>class="current"<?php }?> title="Edit your profile"><a href="/profile.php"><img src="/img/btn_discuss.png" />Profile</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/import.php"){?>class="current"<?php }?>><a href="/import.php"><img src="/img/btn_arrow_in.png" height="16" />Import</a></li>
			<li title="Log <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value['given_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value['family_name'], ENT_QUOTES, 'ISO-8859-1', true);?>
 out"><a href="/login.php?logout=1"><img src="/img/btn_exit.png" height="16" />Logout</a></li>
		<?php }else{ ?>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/index.php"){?>class="current"<?php }?>><a href="/index.php"><img src="/img/ico_home.png" />Intro</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/search.php"){?>class="current"<?php }?>><a href="/search.php"><img src="/img/btn_search.png" />Search</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/list.php"){?>class="current"<?php }?>><a href="/list.php"><img src="/img/btn_search.png" />Family Index</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/login.php"){?>class="current"<?php }?>><a href="/login.php?fromurl=<?php echo rawurlencode($_SERVER['REQUEST_URI']);?>
"><img src="/img/rarrow.gif" height="16" />Login</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/register.php"){?>class="current"<?php }?>><a href="/register.php"><img src="/img/rarrow.gif" height="16" />Register</a></li>
		<?php }?>
		</ul>
	</div>
<?php if ($_smarty_tpl->tpl_vars['person_id']->value>0){?>
	<div id="nav2">
		<ul>
			<li class="personname"><?php echo $_smarty_tpl->tpl_vars['primary_person']->value;?>
</li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/person.php"){?>class="current"<?php }?>><a href="/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>"><img src="/img/ico_indi.gif" />Individual</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/family.php"){?>class="current"<?php }?>><a href="/family/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
<?php if ($_smarty_tpl->tpl_vars['time']->value){?>&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
<?php }?>""><img src="/img/ico_family.gif" />Family</a></li>

			<li <?php if ($_SERVER['SCRIPT_NAME']=="/ped.php"){?>class="current"<?php }?>><a href="/ped.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_pedigree.png" />Pedigree</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/descendants.php"){?>class="current"<?php }?>><a href="/descendants.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_descend.png" />Descendants</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/chart.php"){?>class="current"<?php }?>><a href="/chart.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_report.png" height="16" />Reports</a></li>
	<?php if ($_smarty_tpl->tpl_vars['is_logged_on']->value){?>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/person_edit.php"){?>class="current"<?php }?>><a href="/person_edit.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
&return_to=/person/<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_edit.png" />Edit</a></li>
	<?php }?>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/person_history.php"){?>class="current"<?php }?>><a href="/person_history.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_docs.png" />History</a></li>
			<li <?php if ($_SERVER['SCRIPT_NAME']=="/export.php"){?>class="current"<?php }?>><a href="/export.php?person_id=<?php echo $_smarty_tpl->tpl_vars['person_id']->value;?>
"><img src="/img/btn_arrow_out.png" height="16" />Export</a></li>

		</ul>
	</div>
<?php }?>
	<div id="helplink">
		<a href="/w/<?php if ($_smarty_tpl->tpl_vars['help']->value){?><?php echo $_smarty_tpl->tpl_vars['help']->value;?>
<?php }else{ ?>Category:Help<?php }?>" target="_BLANK" title="opens in new window">Help</a>
		<a href="/w/<?php if ($_smarty_tpl->tpl_vars['help']->value){?><?php echo $_smarty_tpl->tpl_vars['help']->value;?>
<?php }else{ ?>Category:Help<?php }?>" target="_BLANK" title="opens in new window"><img src="/img/ico_help.png" width="16" height="16" border="0" ></a>
	</div>
	<div class="toplogo"><a href="/index.php"><img src="/img/stree_logo_small.png" width="150" height="133" border="0" title="SharedTree - revolutionizing genealogy" /></a></div>
	<div id="topbanner">
<?php if (false){?>
<?php echo $_smarty_tpl->getSubTemplate ("ad_banner.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['area_type']->value=="prod"){?>
		<script type="text/javascript"><!--
		google_ad_client = "pub-7448944120653323";
		google_ad_width = 468;
		google_ad_height = 60;
		google_ad_format = "468x60_as";
		google_ad_type = "text_image";
		google_ad_channel = "";
		google_color_border = "336699";
		google_color_bg = "FFFFFF";
		google_color_link = "0000FF";
		google_color_text = "000000";
		google_color_url = "008000";
		//--></script>
		<script type="text/javascript"
		  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	<?php }else{ ?>
		<a href=""><img src="/img/sharedtree_beta.gif" width="468" height="60" title="banner ad doesn't show in dev" border="0" /></a>
	<?php }?>
<?php }?>
	</div>
</div>
<div align="center">
<img src="/img/blank.gif" height="140" width="1" border="0">
<br clear="all" />
<!-- Start Content -->
<?php if ($_smarty_tpl->tpl_vars['person_id']->value>0){?>
<br />
<?php }?>
<?php }} ?>