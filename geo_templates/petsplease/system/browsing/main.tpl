{* 7.1beta1-1140-g8fa08d9 *}




{$category_cache}

<div class="col_left">
	<div id="browsing_search">
		<h2>Refine your search</h2>

		<form>
			<div>
				<label>Category</label>
				<select>
					<option>Pets for Sale</option>
					<option>Pet Products</option>
					<option>Breeders</option>
					<option>Services</option>
					<option>Clubs</option>
				</select>
			</div>

			<div>
				<label>Type</label>
				<select>
					<option>Dogs &amp; Puppies</option>
					<option>Cats &amp; Kittens</option>
					<option>Birds</option>
					<option>Fish</option>
					<option>Reptiles</option>
					<option>Other Pets</option>
				</select>
			</div>

			<div>
				<label>Breed</label>
				<select>
					<option>Affenpinscher</option>
					<option>Afgan Hound</option>
				</select>
			</div>
		</form>
	</div>

	<div class="adspot160x600">
		<img src="{external file='images/ad_example_160x600.jpg'}" width="160" height="600" style="display:block;" />
	</div>
</div>

<div class="col_right">
	{include file='common/browse_mode_buttons.tpl'}	
	<div class="clear"></div>
	
	{if $show_featured_classifieds}
		<div class="content_box">
			<h1 class="title">
				{$messages.28} {$current_category_name}
				{if $featured_links}
					<a class="featured_ads_links" href="{$classifieds_file_name}?a=9&amp;b={$category_id}">{$messages.873}</a>
					<a class="featured_ads_links" href="{$classifieds_file_name}?a=8&amp;b={$category_id}">{$messages.872}</a>
				{/if}
			</h1>
			{include file=$browse_tpl
				listings=$featured_classifieds.listings
				no_listings=$featured_classifieds.no_listings
				addonHeaders=$featured_classifieds.addonHeaders
				cfg=$featured_classifieds.cfg
				headers=$featured_classifieds.headers}
		</div>
		<br />
	{/if}
	<div class="content_box">
		<h2 class="title">{$messages.200109} {$current_category_name}</h2>
		{include file=$browse_tpl
			listings=$classified_browse_result.listings
			no_listings=$classified_browse_result.no_listings
			addonHeaders=$classified_browse_result.addonHeaders
			cfg=$classified_browse_result.cfg
			headers=$classified_browse_result.headers}
	</div>
	<br />

	{if $pagination}
		{$messages.757} {$pagination}
	{/if}
</div>
