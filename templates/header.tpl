<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
{if $noindex || $area_type!="prod"}<meta name="robots" content="noindex, nofollow">{/if}
{if $includejs}
<script src="/js/sharedtree.js" type="text/javascript"></script>
<script src="/js/prototype.js" type="text/javascript"></script>
<script src="/js/scriptaculous.js" type="text/javascript"></script>
{/if}
<title>SharedTree{if $area_type!="prod"}_{$area_type}{/if}: {$title}</title>
<link href="/styles.css" type="text/css" rel="stylesheet" />
{if $background == "edit"}
<style>
{literal}
html, body {
        background: #FFC url(img/bg_sky.jpg) repeat-x;
}
{/literal}
</style>
{/if}
</head>
<body>
<div id="header">
	<div id="nav1">
		<ul>
			<li style="width: 150px">&nbsp;</li>
		{if $is_logged_on}
			<li {if $smarty.server.SCRIPT_NAME=="/index.php"}class="current"{/if} title="Show the Home Page for {$user.given_name|escape} {$user.family_name|escape}"><a href="/index.php"><img src="/img/ico_home.png" />My Tree</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/search.php"}class="current"{/if}><a href="/search.php"><img src="/img/btn_search.png" />Search</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/image.php"}class="current"{/if} title="See photos of your relatives"><a href="/image.php?action=list"><img src="/img/btn_photos.png" />Photos</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/merge.php"}class="current"{/if}><a href="/merge.php?action=list"><img src="/img/btn_arrow_join.png" />Merge</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/profile.php"}class="current"{/if} title="Edit your profile"><a href="/profile.php"><img src="/img/btn_discuss.png" />Profile</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/import.php"}class="current"{/if}><a href="/import.php"><img src="/img/btn_arrow_in.png" height="16" />Import</a></li>
			<li title="Log {$user.given_name|escape} {$user.family_name|escape} out"><a href="/login.php?logout=1"><img src="/img/btn_exit.png" height="16" />Logout</a></li>
		{else}
			<li {if $smarty.server.SCRIPT_NAME=="/index.php"}class="current"{/if}><a href="/index.php"><img src="/img/ico_home.png" />Intro</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/search.php"}class="current"{/if}><a href="/search.php"><img src="/img/btn_search.png" />Search</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/list.php"}class="current"{/if}><a href="/list.php"><img src="/img/btn_search.png" />Family Index</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/login.php"}class="current"{/if}><a href="/login.php?fromurl={$smarty.server.REQUEST_URI|escape:'url'}"><img src="/img/rarrow.gif" height="16" />Login</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/register.php"}class="current"{/if}><a href="/register.php"><img src="/img/rarrow.gif" height="16" />Register</a></li>
		{/if}
		</ul>
	</div>
{if $person_id > 0}
	<div id="nav2">
		<ul>
			<li class="personname">{$primary_person}</li>
			<li {if $smarty.server.SCRIPT_NAME=="/person.php"}class="current"{/if}><a href="/person/{$person_id}{if $time}&time={$time}{/if}"><img src="/img/ico_indi.gif" />Individual</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/family.php"}class="current"{/if}><a href="/family/{$person_id}{if $time}&time={$time}{/if}""><img src="/img/ico_family.gif" />Family</a></li>

			<li {if $smarty.server.SCRIPT_NAME=="/ped.php"}class="current"{/if}><a href="/ped.php?person_id={$person_id}"><img src="/img/btn_pedigree.png" />Pedigree</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/descendants.php"}class="current"{/if}><a href="/descendants.php?person_id={$person_id}"><img src="/img/btn_descend.png" />Descendants</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/chart.php"}class="current"{/if}><a href="/chart.php?person_id={$person_id}"><img src="/img/btn_report.png" height="16" />Reports</a></li>
	{if $is_logged_on}
			<li {if $smarty.server.SCRIPT_NAME=="/person_edit.php"}class="current"{/if}><a href="/person_edit.php?person_id={$person_id}&return_to=/person/{$person_id}"><img src="/img/btn_edit.png" />Edit</a></li>
	{/if}
			<li {if $smarty.server.SCRIPT_NAME=="/person_history.php"}class="current"{/if}><a href="/person_history.php?person_id={$person_id}"><img src="/img/btn_docs.png" />History</a></li>
			<li {if $smarty.server.SCRIPT_NAME=="/export.php"}class="current"{/if}><a href="/export.php?person_id={$person_id}"><img src="/img/btn_arrow_out.png" height="16" />Export</a></li>

		</ul>
	</div>
{/if}
	<div id="helplink">
		<a href="/w/{if $help}{$help}{else}Category:Help{/if}" target="_BLANK" title="opens in new window">Help</a>
		<a href="/w/{if $help}{$help}{else}Category:Help{/if}" target="_BLANK" title="opens in new window"><img src="/img/ico_help.png" width="16" height="16" border="0" ></a>
	</div>
	<div class="toplogo"><a href="/index.php"><img src="/img/stree_logo_small.png" width="150" height="133" border="0" title="SharedTree - revolutionizing genealogy" /></a></div>
	<div id="topbanner">
{if false}
{include file="ad_banner.tpl"}
{else}
	{if $area_type=="prod"}
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
	{else}
		<a href=""><img src="/img/sharedtree_beta.gif" width="468" height="60" title="banner ad doesn't show in dev" border="0" /></a>
	{/if}
{/if}
	</div>
</div>
<div align="center">
<img src="/img/blank.gif" height="140" width="1" border="0">
<br clear="all" />
<!-- Start Content -->
{if $person_id > 0}
<br />
{/if}
