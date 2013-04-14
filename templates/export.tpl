{include file="header.tpl" title="GEDCOM Export"}

<table class="portal">
<tr><td>
<h2>Export GEDCOM file</h2>

<b>{$person.full_name}</b><br /><br />

<form action="export.php" method="post">
<input type="hidden" name="person_id" value="{$person_id}">
<label>Ancestors:</label>
<select name="gen_up">
	<option>0</option>
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
	<option>6</option>
	<option>7</option>
	<option>8</option>
	<option>9</option>
	<option>10</option>
</select> generations <br />
<label>Siblings:</label> <input type="checkbox" name="siblings" value="1"><br />

<label>Descendents:</label>
<select name="gen_down">
	<option>0</option>
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
</select> generations <br />
<input type="submit" value="Download" name="download">
</form>
</td></tr>
</table>

{include file="footer.tpl"}