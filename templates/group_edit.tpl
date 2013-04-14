{include file="header.tpl"}

<h2>{if $group_id}Edit{else}Create{/if} Group</h2>

<a href="group.php">Return to Group List</a>
{if $group_id} | <a href="group.php?group_id={$group_id}">Show {$group.group_name}</a>{/if}
<table class="portal">
<tr align="left">
<td width="350">


<form method="POST" action="group.php">
<input type="hidden" name="action" value="save">
<input type="hidden" name="group_id" value="{$group.group_id}">
<label>Group name:</label> <input type="text" class="textfield" name="group_name" size="30" value="{$group.group_name}"><br>
<label>Initials:</label>   <input type="text" class="textfield" name="initials" size="3" value="{$group.initials}"><br>
<label>Start year:</label> <input type="text" class="textfield" name="start_year" size="5" value="{$group.start_year}"><br>
<label>End year:</label>   <input type="text" class="textfield" name="end_year" size="5" value="{$group.end_year}"><br>
<label>Description:</label><br>
<textarea name="description" cols="50" rows="10">{$group.description}</textarea>
<input type="submit" value="Save"></td>

</tr>
</table>

</td></tr></table>
</form>

<i>* Please read the <a href="http://www.sharedtree.com/w/Group_Guidelines">Group Guidelines</a> before adding a new group <br />or making changes to an existing group.</i>


</td>
</tr>
</table>

{include file="footer.tpl"}
