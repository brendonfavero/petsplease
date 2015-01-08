{$pagination}

<div class="store_product_grid clearfix">
	{foreach from=$listings item=listing}
		<div class="store_product{if $listing@iteration is div by 3} nomargin{/if}">
			<div class="image">{$listing.image}</div>
			<div class="description">
				<div class="title"><a href="index.php?a=2&b={$listing.id}">{$listing.title|urldecode}</a></div>
				<div class="price">{$listing.price}</div>
				{if $listing.sold_displayed == 1}
					<img src="/geo_templates/default/external/images/sold.png" alt="">
				{/if}
			</div>
		</div>
	{/foreach}
</div>

{$pagination}