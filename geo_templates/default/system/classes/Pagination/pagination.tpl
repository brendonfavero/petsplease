{* 7.1beta3-41-g0650062 *}
<ul class="pagination{if $css} {$css}{/if}">
	{if $previousPage}
		<li><a href="{$url}{$previousPage}{$postUrl}">&lt;</a></li>
	{/if}
	
	{foreach from=$links item=page}
		{if $page == $currentPage}
			<li class="current">{$page}</li>
		{else}
			<li><a href="{$url}{$page}{$postUrl}">{$page}</a></li>
		{/if}
	{/foreach}
	
	{if $nextPage}
		<li><a href="{$url}{$nextPage}{$postUrl}">&gt;</a></li>
	{/if}
</ul>

<div class="clr"></div>