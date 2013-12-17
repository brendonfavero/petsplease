<div id="ppNews_newsViewSingle">
	{foreach from=$status item=message}
        <div class="message">{$message}</div>
    {/foreach}   
    
    {if is_array($article) && count($article) > 0)
        <div class="psContentBox">
        <h1>
            <div class="psContentBoxTick"></div>
            <div class="title">{$article.label}</div></h1>
        <div class="psContentBoxBody">
            {$article.data}
        </div>
    </div>
    {/if}
</div>

<script>		
// {literal}
	
// {/literal}	
</script>
