{foreach item=person from=$ancestors}
	<a href="index.php?pid={$person.person_id}">{$person.family_name}, {$person.given_name} ({$person.birth_year})</a> {$relationship} 
	<a clickrewriteurl='http://www.sharedtree.com/facebook/index.php?ajax=list&del={$person.person_id}' clickrewriteform='list_form' clickrewriteid='list_content'>remove</a>
<br />
{/foreach}
