{include file="header.tpl"}
<table class="portal" width="700">
<tr><td>

<p align="left"><a href="import.php">Return to File List</a></p>

<table border="0" cellpadding="5" width="100%">
<tr>
<td><a href="?action=viewfile&import_id={$import_id}" {if $istep==1}style="font-weight: bold"{/if}>Step 1: Upload GEDCOM</a></td>
<td><a href="?action=validate&import_id={$import_id}" {if $istep==2}style="font-weight: bold"{/if}>Step 2: Validate File</a></td>
<td>
	{if $file.current_step >= 2}<a href="?action=import&import_id={$import_id}" {if $istep==3}style="font-weight: bold"{/if}>Step 3: Import Data</a>
	{else}Step 3: Import Data{/if}</td>
</td>
<td>
	{if $file.current_step >= 3}<a href="?action=match&import_id={$import_id}" {if $istep==4}style="font-weight: bold"{/if}>Step 4: Match &amp; Merge</a>
	{else}Step 4: Match &amp; Merge{/if}</td>
</td>
</tr>
</table>

<h2>
Step {$istep} of 4: 
{if $istep==1}Upload a GEDCOM file{/if}
{if $istep==2}Validate the File{/if}
{if $istep==3}Import data into SharedTree{/if}
{if $istep==4}Match &amp; Merge Individuals{/if}
</h2>

</td></tr>
</table>