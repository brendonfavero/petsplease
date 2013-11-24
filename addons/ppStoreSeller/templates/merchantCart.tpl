Here is a list of items that are currently in the (merchant) cart!

{if $msgs}
	<div class="content_box_1">
		{foreach $msgs as $msg}
			<p>{$msg}</p>
		{/foreach}
	</div>
{/if}

{foreach $cart_items as $vendor}
	<div class="vendor_box">
		<div class="vendor_name">{$vendor.shop_listing.title|urldecode}</div>
		<div class="vendor_listings clearfix">
			{foreach $vendor.listings as $listing}
				<div class="vendor_listing">
					{if $listing.image_thumbnail}
						<div class="image">
							{$listing.image_thumbnail}
						</div>
					{/if}
					<div class="data">
						<a href="?a=2&b={$listing.id}">{$listing.title|urldecode}</a><br>
						Qty: {$listing.cartqty}<br>
						Price: {$listing.price}<br>
						Shipping: {$listing.shipping}<br>
						<span class="bold">Total: {$listing.total_price}</span><br>
						<a href="?a=ap&addon=ppStoreSeller&page=merchantcart&action=removeitem&b={$listing.id}">Remove from Cart</a>
					</div>
				</div>
			{/foreach}
		</div>

		<div class="vendor_footer">
			<div style="float:left;">
				<a href="?a=ap&addon=ppStoreSeller&page=merchantcart&action=removeitem&vendor={$vendor.shop_listing.seller}" class="button">Remove all</a>
			</div>
			<div>
				<span class="bold">Total: {$vendor.total_price_display}</span>
				<a href="?a=ap&addon=ppStoreSeller&page=checkout&vendor={$vendor.shop_listing.seller}" class="button">Checkout</a>
			</div>
		</div>
	</div>
{/foreach}
