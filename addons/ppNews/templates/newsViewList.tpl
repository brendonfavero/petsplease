<ul id="ppNews_viewList">
    {foreach from=$articles item=article }
       <li><a href="/news/?article={$article.id}" >{$article.label}</a></li>
    {/foreach}
</ul>


