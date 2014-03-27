{* 7.0.2-183-gfa134f3 *}

<h2 class="title">Featured Pets and Products</h2>
<div class="content_box_1 gj_simple_carousel">
	{module tag='module_featured_pic_1' gallery_columns=6 module_thumb_width=120}
</div>

<div class="col_left">
	<div id="browsing_search">
		{addon author='pp_addons' addon='ppSearch' tag='searchSidebar' queryurl=$cfg.browse_url}
	</div>

	<div style="margin-bottom: 24px;">
	{addon addon="ppAds" tag="adspot" aid=1}
	</div>

	{addon addon="ppAds" tag="adspot" aid=2}
</div>

<div class="col_right">
	<h1 class="title">
		{$current_category_name}
	</h1>
	<br/><br/>
	{if $pagination}
		{$pagination}
	{/if}
	{if $multiple_locations_found}
		<div class="no_results_box">
			<p>The location you entered matched multiple areas, but only the first was chosen. Your results may not be from your area. Below are other possible matches:</p>
			{foreach $multiple_locations_found as $location}
				<div style="text-align:center">
					<a href="?{$location.querystring}">
						{$location.suburb|ucwords}, {$location.state} {$location.postcode}
					</a>
				</div>
			{/foreach}
		</div>
	{/if}

	{if $invalid_location_entered}
		<div class="no_results_box">Unable to find the entered location.</div>
	{/if}

	{include file=$browse_tpl}
	
	{if $pagination}
		{$pagination}
	{/if}
</div>

