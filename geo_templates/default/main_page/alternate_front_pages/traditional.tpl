{include file="head.tpl"}

<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell">
		<!-- START EXTRA COLUMN -->
		<div id="extra_column">
			<div class="content_box">
				<a href="index.php?a=1"><img src="{external file='images/buttons/place_listing.gif'}" alt="Place A Listing" title="Place A Listing" style="width: 189px; height: 68px;" /></a>
			</div>
			
			<br />
			<div class="content_box">
				<h1 class="title">Site Stats</h1>
				{module tag='module_total_live_users'}
				{module tag='module_total_registered_users'}
			</div>
			<br />
			<div class="content_box">
				<h2 class="title">Newest Listings</h2>
				{module tag='newest_ads_link'}
				{module tag='newest_ads_link_1'}
				{module tag='newest_ads_link_2'}
				{module tag='newest_ads_link_3'}
			</div>
			<br />
			<div>
				<!-- EDIT THE FOLLOWING LINE OF CODE WITH YOUR BANNER OR ADSENSE CODE -->
				<a href="http://geodesicsolutions.com/support/geocore-wiki/doku.php/id,tutorials;using_a_banner_system;adsense/" onclick="window.open(this.href); return false;"><img src="{external file='images/banners/banner1_adsense_200x200.jpg'}" alt="Banner Example" title="Banner Example" width="200" height="200" /></a>
	            <!-- EDIT THE ABOVE LINE OF CODE WITH YOUR BANNER OR ADSENSE CODE -->
			</div>
		</div>
		<!-- END EXTRA COLUMN -->
		
		<!-- START CATEGORIES -->
		<div id="category_column">
			<div id="left_categories">
				<h1 class="title">Categories</h1>
				{module tag='main_classified_navigation_1'}
			</div>
		</div>
		<!-- END CATEGORIES -->
		
		<!-- START CONTENT BLOCK -->
		<div id="content_column">
			{module tag='module_featured_pic_1'}
			
			<br />
			
			<div id="half_column_left">
				<div class="content_box">
					<h2 class="title">Hottest Listings</h2>
					{module tag='module_hottest_ads'}
				</div>
			</div>
			
			<div id="half_column_right">
				<div class="content_box">
					<h1 class="title">Recent Listings</h1>
					{module tag='newest_ads_1'}
				</div>
			</div>
			
			<div class="clr"><br /></div>
			
			<div class="content_box">
				{module tag='featured_ads_1'}
			</div>
		</div>
		<!-- END CONTENT BLOCK -->
		<div class="clr"></div>
	</div>
</div>

{include file="footer.tpl"}
