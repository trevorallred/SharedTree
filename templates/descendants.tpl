{include file="header.tpl" title=$title includejs=1}
{literal}
<style>
div.navbox {
	display: none;
	padding: 3px;
}
div.family {
	display: block;
}
div.familyhide {
	display: none;
}
div.spouse {
	margin-left: 30px;
}
</style>
<script type="text/javascript">
function toggleFamily(whichLayer, getnew) {
	var imgplus = document.getElementById("img"+whichLayer);
	var family = document.getElementById("family"+whichLayer).style;
	if (imgplus.src.search("ico_minus") > 0) {
		imgplus.src = "img/ico_plus.gif";
		family.display = "none";
	} else {
		if (getnew==1) {
			var pars = 'action=ajax&person_id='+whichLayer;
			var myAjax = new Ajax.Updater('person'+whichLayer, 'descendants.php', {method: 'get', parameters: pars});
		}
		imgplus.src = "img/ico_minus.gif";
		family.display = "block";
	}
}
function toggleNav(whichLayer) {
	var navbox = document.getElementById("navbox"+whichLayer).style;
	if (navbox.display == "block") navbox.display = "none";
	else navbox.display = "block";
}

</script>
{/literal}
<br clear="all" />
<table class="portal" width="600">
<tr><td>
<label>Father:</label> <a href="?person_id={$parents.father.id}">{$parents.father.full_name}</a><br>
<label>Mother:</label> <a href="?person_id={$parents.mother.id}">{$parents.mother.full_name}</a><br>
<div class="person" id="person{$data.id}">
{include file="desc_family.tpl" child=$data getnew=0}
</div>
</td></tr></table>

<br><br><br>
<form method="GET" action="/chart.php" target="_BLANK">
	<input type="hidden" name="person_id" value="{$person.person_id}" />
	<input type="hidden" name="chart" value="desc_circle" />
	<input type="submit" value="Create PDF Circle Chart">
	<select name="gen">
		<option>2</option>
		<option>3</option>
		<option selected="selected">4</option>
		<option>5</option>
		<option>6</option>
	</select> generations
</form>

{include file="footer.tpl"}
