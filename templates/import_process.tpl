{include file="import_nav.tpl" istep="3"}

{literal}
<script type="text/javascript">
<!--
function complete_progress(time, exectext) {
	progress = document.getElementById("progress_header");
	progress.innerHTML = '<b>Import is complete</b><br />Completed in '+time+' seconds<br /><br />';
}
{/literal}
var FILE_SIZE = {$file_size};
var TIME_LIMIT = {$time_limit};
{literal}
function update_progress(bytes, time) {
	perc = Math.round(100*(bytes / FILE_SIZE));
	remain = Math.round((time * (FILE_SIZE / bytes)) - time);
	if (perc>100) perc = 100;
	progress = document.getElementById("progress_div");
	progress.style.width = perc+"%";
	progress.innerHTML = perc+"%";
	progress = document.getElementById("time_remaining");
	progress.innerHTML = remain+" seconds remaining";
	if (remain>60) {
		remain = Math.round(remain / 60, 1);
		progress.innerHTML = remain+" minutes remaining";
	}
	
	perc = Math.round(100*(time / TIME_LIMIT));
	if (perc>100) perc = 100;
	progress = document.getElementById("time_div");
	progress.style.width = perc+"%";
	progress.innerHTML = perc+"%";
}
//-->
</script>
{/literal}

<div id="progress_header" style="width: 350px; margin: 10px; text-align: center;">
	<b>Import Progress</b>
	<div style="left: 10px; right: 10px; width: 300px; height: 20px; border: inset #CCCCCC 3px; background-color: #FFFFFF; text-align: left;">
		<div id="progress_div" style="width: 1%; height: 18px; text-align: center; color: #FFFFFF; background-color: #111111; overflow: hidden;">1%</div>
	</div>
	<div id="time_remaining">? seconds remaining</div><br>
	<b>Time limit {$time_limit} seconds</b>
	<div style="left: 10px; right: 10px; width: 300px; height: 20px; border: inset #CCCCCC 3px; background-color: #FFFFFF; text-align: left;">
		<div id="time_div" style="width: 1%; height: 18px; text-align: center; color: #FFFFFF; background-color: #111111; overflow: hidden;">1%</div>
	</div>
</div>

<div id="link1">&nbsp;</div>
