{* 7.0.3-300-g047b6cd *}
{if $image.icon}
	<img src="{external file=$image.icon}" alt="" />
{else}
	<img src="{if $image.url}{$image.url}{elseif $image.thumb_url}{$image.thumb_url}{/if}" alt="" style="width: {$image.scaled.lead.width}px; height: {$image.scaled.lead.height}px;" />
{/if}