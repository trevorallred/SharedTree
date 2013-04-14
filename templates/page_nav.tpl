{if $pages > 1}
        <label>Page:</label>
        {section name=page start=1 loop=$pages+1}
        {if $smarty.section.page.index != $page}
                <a href="?page={$smarty.section.page.index}">{$smarty.section.page.index}</a>
        {else}
                {$smarty.section.page.index}
        {/if}
        {/section}
{/if}

