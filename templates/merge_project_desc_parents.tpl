{if $pp1.person1.person_id > 0 && $pp2.person1.person_id > 0}
	<div class="person" 
		id="fam_f{$pp2.family_id}"
		onmouseover="highlight('fam_f{$pp2.family_id}', true)" 
		onmouseout="highlight('fam_f{$pp2.family_id}', false)">
	<b>Father:</b>
	{include file="merge_project_desc_parents_parent.tpl" p2=$pp2.person1 p1=$pp1.person1 which="1"}
	</div>
{/if}

{if $pp1.person2.person_id > 0 && $pp2.person2.person_id > 0}
	<div class="person" 
		id="fam_m{$pp2.family_id}"
		onmouseover="highlight('fam_m{$pp2.family_id}', true)" 
		onmouseout="highlight('fam_m{$pp2.family_id}', false)">
	<b>Mother:</b>
	{include file="merge_project_desc_parents_parent.tpl" p2=$pp2.person2 p1=$pp1.person2 which="2"}
	</div>
{/if}
{if $pp2.children}
	<b>Sibling(s):</b><br>
	{foreach item=child from=$pp2.children}
		<div class="person"
			id="tr_chil{$child.person_id}" 
			onmouseover="highlight('tr_chil{$child.person_id}', true)" 
			onmouseout="highlight('tr_chil{$child.person_id}', false)">
			{include file="merge_project_desc_child.tpl" p=$child p1s=$pp1.children}
		</div>
	{/foreach}
{/if}
