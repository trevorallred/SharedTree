<?
require_once("config.php");
require_once("inc/main.php");

require_once("inc/buildline.php");

require_login();
?>
<html>
<head>
<script type="text/javascript">
<!--
function update_progress(loop, added, remain) {
	total = added + remain;
	if (total > 0) perc = Math.round(100*(loop + (added/total))/13);
	else perc = 0;
	if (perc > 100) perc = 100;
	progress = document.getElementById("progress_div");
	progress.style.width = perc+"%";
	progress.innerHTML = perc+"%";
	progress2 = document.getElementById("progress_status");
	progress2.innerHTML = "Loop: "+loop+" -- "+added+" added "+remain+" remaining";
}
function refreshParent() {
  window.opener.location.href = window.opener.location.href;

  if (window.opener.progressWindow)
		
 {
    window.opener.progressWindow.close()
  }
  window.close();
}
//-->
</script>
</head>
<body>
<div id="progress_header" style="width: 350px; margin: 10px; text-align: center;">
	<b>Build Progress</b>
	<div style="left: 10px; right: 10px; width: 300px; height: 20px; border: inset #CCCCCC 3px; background-color: #FFFFFF; text-align: left;">
		<div id="progress_div" style="width: 1%; height: 18px; text-align: center; color: #FFFFFF; background-color: #111111; overflow: hidden;">1%</div>
	</div>
	<br clear="all">
	<div id="progress_status" ></div>
</div>

<?
if (ob_get_level() == 0) {
   ob_start();
}
buildFamilyTreeIndex($user->id, true);
ob_end_flush();
?>

<br /><br />
<b>Done</b>

<form>
<input type="button" onclick="refreshParent();" value="Close">
</form>

</body>
</html>
<? exit(); ?>
