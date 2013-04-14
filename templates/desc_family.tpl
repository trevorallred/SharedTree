{if $child.spouse}<a href="javascript:void(0)" onclick="toggleFamily('{$child.id}','{$getnew}');"><img id="img{$child.id}" src="img/ico_{if $getnew==1}plus_grey{else}minus{/if}.gif" width="15" height="15" border="0"></a>{else}<img src="img/blank.gif" width="15" height="15" border="0">{/if} 
<a href="javascript:void(0)" onclick="toggleNav('{$child.id}');">{$child.name}</a>
<div class="navbox" id="navbox{$child.id}" style="margin-left:30px">
	<table border="0"><tr><td><img src="image.php?person_id={$child.id}&size=thumb"></td>
	<td>
	<b>B:</b> {$child.bdate}<br />
	<b>P:</b> {$child.bplace}<br />
	<b>D:</b> {$child.ddate}<br />
	<b>P:</b> {$child.dplace}<br />
	{if $child.trace}<b>{$child.trace}</b><br />{/if}
	{include file="person_nav.tpl" nav_id=$child.id direction="flat"}
	<br /><a href="descendants.php?person_id={$child.id}">View Descendents</a>
	</td></tr></table>
</div>
<div class="family{if $getnew==1}hide{/if}" id="family{$child.id}">
{foreach item=spouse from=$child.spouse}
	<div class="spouse">
	{if $spouse.restricted}
		{$spouse.name}
	{else}
		<a href="javascript:void(0)" onclick="toggleNav('{$spouse.id}');">{$spouse.name}</a>
		<div class="navbox" id="navbox{$spouse.id}">
		<table border="0"><tr><td><img src="image.php?person_id={$spouse.id}&size=thumb"></td>
		<td>
		<b>B:</b> {$spouse.bdate}<br />
		<b>P:</b> {$spouse.bplace}<br />
		<b>D:</b> {$spouse.ddate}<br />
		<b>P:</b> {$spouse.dplace}<br />
		{if $spouse.trace}<b>{$spouse.trace}</b><br />{/if}
		{include file="person_nav.tpl" nav_id=$spouse.id direction="flat"}
		<br /><a href="descendants.php?person_id={$spouse.id}">View Descendents</a>
		</td></tr></table>
		</div>
	{/if}
		{foreach item=newchild from=$spouse.child}
			<div class="person" id="person{$newchild.id}">
			{if $newchild.restricted}
				<img src="img/blank.gif" width="15" height="15" border="0">
				{$newchild.name}<br />
			{else}
				{include file="desc_family.tpl" child=$newchild getnew=1}
			{/if}
			</div>
		{/foreach}
	</div>
{/foreach}
</div>