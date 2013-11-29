{if $msgs}
	<div class="content_box_1">
		{foreach $msgs as $msg}
			<p>{$msg}</p>
		{/foreach}
	</div>
{/if}

<h1 class="title">Cart</h1>
<div class="cart_holder">

	<div style="margin-bottom: 14px; text-align:right;">
		<a href="{if $laststorevisited}?a=2&b={$laststorevisited}{else}/{/if}" class="button">Continue Shopping</a>
	</div>

	{foreach $cart_items as $vendor}
		<div class="vendor_box">
			<div class="vendor_name">
				<div class="remove_all_from_vendor">
					<a href="?a=ap&addon=ppStoreSeller&page=merchantcart&action=removeitem&vendor={$vendor.shop_listing.seller}">&times;</a>
				</div>
				<a href="?a=2&b={$vendor.shop_listing.id}">
					{$vendor.shop_listing.title|urldecode}
				</a>
			</div>
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
							Qty: 
							<select id="qty-{$listing.id}">
								{section name="qtycount" loop=$listing.qtyavailable+1 start=1}
									<option{if $listing.cartqty == $smarty.section.qtycount.index} selected="selected"{/if}>{$smarty.section.qtycount.index}</option>
								{/section}
							</select>
							 {*$listing.cartqty*}<br>
							Price: {$listing.price}<br>
							Shipping: {$listing.shipping}<br>
							<span class="bold">Total: {$listing.total_price}</span><br>
							<br>
							<a href="?a=ap&addon=ppStoreSeller&page=merchantcart&action=movetofavourites&b={$listing.id}">Move to Favourites</a><br>
							<a href="?a=13&b={$listing.id}">Contact Shop Owner</a><br>
							<a href="?a=ap&addon=ppStoreSeller&page=merchantcart&action=removeitem&b={$listing.id}">Remove from Cart</a>

							<script type="text/javascript">
							jQuery(function() {
								var e = jQuery("#qty-{$listing.id}")
								e.on("change", function() {
									window.location = "?a=ap&addon=ppStoreSeller&page=merchantcart&action=updateqty&b={$listing.id}&qty=" + e.val()
								})
							})
							</script>
						</div>
					</div>
				{/foreach}
			</div>

			<div class="vendor_footer">
				<div>
					<span class="bold">Total: {$vendor.total_price_display}</span>
					<a href="?a=ap&addon=ppStoreSeller&page=checkout&vendor={$vendor.shop_listing.seller}" class="button">Checkout</a>
				</div>
			</div>
		</div>
	{foreachelse}
		The cart is empty. Please take a look around the site and add any products you may be interested in.
	{/foreach}
</div>
