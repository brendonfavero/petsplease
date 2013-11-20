Here is a list of items that are currently in the (merchant) cart!

{if $msgs}
	<div class="content_box_1">
		{foreach $msgs as $msg}
			<p>{$msg}</p>
		{/foreach}
	</div>
{/if}

{foreach $cart_items as $vendor}
	<div class="vendor_name">{$vendor.shop_listing.title|urldecode}</div>
	<div class="content_box_1">

		<div class="vendor_listings">
			{foreach $vendor.listings as $listing}
				<div class="vendor_listing">
					<p><a href="?a=2&b={$listing.id}">{$listing.title|urldecode}</a></p>
					<p>Qty: {$listing.cartqty}</p>
					<a href="?a=ap&addon=ppStoreSeller&page=merchantcart&action=removeitem&b={$listing.id}">Remove from Cart</a>
				</div>
			{/foreach}
		</div>

		<div class="vendor_footer">
			Checkout
		</div>
	</div>
{/foreach}
