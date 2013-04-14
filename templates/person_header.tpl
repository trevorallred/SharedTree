{include file="header.tpl"}
<div id="navPerson">
<ul>
	<li>{$person.family_name}, {$person.given_name} ({$person.b_year})</li>
	<li>&nbsp;&nbsp;--</li>
	<li><a href="ped.php?person_id={$person_id}">Pedigree</a></li>
	<li><a href="descendants.php?person_id={$person_id}">Descendants</a></li>
	<li><a href="family.php?person_id={$person_id}">View</a></li>
	<li><a href="person.php?person_id={$person_id}">Details</a></li>
	<li><a href="person.php?person_id={$person_id}&action=edit">Edit</a></li>
	<li><a href="person.php?person_id={$person_id}&action=wiki">Biography</a></li>
	<li><a href="person.php?person_id={$person_id}&action=discuss">Discuss</a></li>
</ul>
</div>

<br clear="all" />