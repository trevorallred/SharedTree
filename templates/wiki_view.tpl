{include file="person_header.tpl"}

<h2>Biography for {$person.given_name} {$person.family_name} ({$person.b_date})</h2>
<p></p>
<hr>
{if $wiki.wiki_text}
<a href="person.php?person_id={$person_id}&action=wikiedit&wiki_id={$wiki.wiki_id}">Edit</a><br>
{$wiki.wiki_text}
{else}
There is no biography for this individual yet. <a href="person.php?person_id={$person_id}&action=wikiedit&wiki_id={$wiki.wiki_id}">Click here</a> to start one.
{/if}

{include file="footer.tpl"}
