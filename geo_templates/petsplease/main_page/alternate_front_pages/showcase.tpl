{include file='head.tpl'}
<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell">
		<!-- START CONTENT BLOCK -->
		<div id="navigation_column_left" style="width: 260px;">
			<!-- FEATURED CONTAINER BEGIN -->						
			<div class="content_box showcase">
				<div class="inner">
					<h1 class="title">Featured Listings</h1>
					<div class="gj_simple_carousel" style="width: 220px; margin: 10px auto;">
						{* 
							NOTE: In order to show a single listing at a time, the {module} tag
							below includes a number of parameters that over-write the
							module settings set in the admin.  You must change those
							settings "in-line" below to change them.
							
							Or, you can remove the parameter(s) from the {module}
							tag completely, and it will use the module settings
							as set in the admin panel.
							
							See the user manual entry for the {module} tag for
							a list of all parameters that can be over-written in
							this way.
						 *}
						{module tag='module_featured_pic_1' gallery_columns=1 module_thumb_width=196 module_thumb_height=200 module_number_of_ads_to_display=10}
					</div>
				</div>
			</div>
			<div class="clr"></div>
			<!-- FEATURED CONTAINER END -->
			
			<!-- START REGIONS -->					
			{if $enabledAddons.geographic_navigation}
				{* Only show this section if the geographic navigation addon is set up *}
				<div class="content_box">
					<h2 class="title">
						Select a Region:
					</h2>
					{addon author='geo_addons' addon='geographic_navigation' tag='navigation'} 
				</div>
				<div class="clr"><br /></div>
			{/if}
			<!-- END REGIONS -->
			
			<!-- SELL CONTAINER BEGIN -->						
			<div class="content_box showcase">
				<div class="inner center">
					<div class="forsale">
						<img src="{external file='images/forsale_stuff.png'}" alt="Sell Now" />
					</div>
					<div class="forsale_text">
						Sell Your Stuff<br />
						<span style="font-size: 1.8em;">Today!</span>
					</div>
					<a href="index.php?a=1" class="button orange">Get Started Now!</a>
				</div>	
			</div>
			<!-- SELL CONTAINER END -->	

			<!-- BANNER BEGIN -->
			{* Example place for 250x210 image banners *}
			<div class="content_box">
				<a href="#"><img src="{external file='images/banners/banner2.jpg'}" alt="" /></a>
				<br /><br />
				<a href="#"><img src="{external file='images/banners/banner1.jpg'}" alt="" /></a>
			</div>
			<!-- BANNER END -->
		</div>
		
		<div id="content_column_navigation" style="padding-left: 20px;">
			<!-- MAIN COLUMN BEGIN -->					
			
			<!-- FIND FORM BEGIN -->
			<div class="content_box showcase search">
				<div class="inner">
					<div class="search_fade_box">
						<div class="gj_image_fade">
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/house.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/jersey.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/puppies.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/car4.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/horse.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/ipad2.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/guitar.png'}" alt="" /></div>
							<div style="display: none;"><img src="{external file='images/showcase_slideshow/car6.png'}" alt="" /></div>
						</div>
						<ul class="search_bullets">
							<li id="active"><a href="index.php?a=28&amp;b=137" id="current">How it Works</a></li>
							<li><a href="index.php?a=28&amp;b=135">Buyer/Seller Features</a></li>
							<li><a href="index.php?a=28&amp;b=141">Help</a></li>
						</ul>
					</div>
					<h1 class="search_title">What are you Looking For?</h1>
					<div class="form_search_divider"></div>
					<form method="get" action="index.php" style="display: inline;" class="showcase">
						<div class="cntr">
							<div class="form_search webkit-fix">
								<input type="hidden" name="a" value="19" />
								<input type="hidden" name="b[subcategories_also]" value="1" />
								<input class="keyword" type="text" placeholder="search keywords..." name="b[search_text]" />
							</div>
						</div>
						{if $enabledAddons.zipsearch}
							<div class="cntr" style="display: nowrap;">
								Within&nbsp;
								<select class="field" name="b[by_zip_code_distance]">
									<option value="1">Walking Distance</option>
									<option value="5">5 miles</option>
									<option value="10">10 miles</option>
									<option value="15">15 miles</option>
									<option value="20">20 miles</option>
									<option value="25">25 miles</option>
									<option value="30">30 miles</option>
									<option value="40">40 miles</option>
									<option value="50">50 miles</option>
									<option value="75">75 miles</option>
									<option value="100">100 miles</option>
									<option value="200">200 miles</option>
									<option value="300">300 miles</option>
									<option value="400">400 miles</option>
									<option value="500">500 miles</option>
								</select>
								&nbsp;of&nbsp;
								<input id="by_zip_code" class="field" type="text" size="7" name="b[by_zip_code]" value=""  placeholder="Zip Code" />
							</div>
						{/if}
						<div class="cntr">
							<br />
							<input class="button-large" type="submit" value="Search Now" />
							&nbsp; &nbsp; &nbsp;
							<a class="button" href="{$classifieds_file_name}?a=19">Advanced Search</a>
						</div>
					</form>
					<div class="clr"><br /><br /></div>
					<div class="center">
						<strong>Recent Listings:</strong> &nbsp;
						{module tag='newest_ads_link' buttonStyle=1}
						{module tag='newest_ads_link_1' buttonStyle=1}
						{module tag='newest_ads_link_2' buttonStyle=1}
						{module tag='newest_ads_link_3' buttonStyle=1}
					</div>
					<div class="clr"></div>
				</div>
			</div>
			<!-- FIND FORM END -->	
			<div class="clr"><br /></div>
			
			<!-- BROWSE BOX BEGIN -->						
			<div class="content_box">
				<h2 class="title">Browse Our Categories</h2>
				{body_html}
			</div>
			<!-- BROWSE BOX END -->
			<div class="clr"><br /></div>
			
			<!-- FEATURED CAROUSEL BEGIN -->
			<div class="content_box gj_simple_carousel">
				<h1 class="title">More Featured Listings</h1>
				{* 
					NOTE: In order to show the module in a way that will fit in
					the layout for this page, the {module} tag
					below includes a number of parameters that over-write the
					module settings set in the admin.  You must change those
					settings "in-line" below to change them.
					
					Or, you can remove the parameter(s) from the {module}
					tag completely, and it will use the module settings
					as set in the admin panel.
					
					See the user manual entry for the {module} tag for
					a list of all parameters that can be over-written in
					this way.
				 *}
				{module tag='module_featured_pic_1' gallery_columns=4 module_thumb_width=120}
			</div>
			<!-- FEATURED CAROUSEL END -->
			<div class="clr"><br /></div>
			
			<!-- Hottest and Recent Half Columns Begin -->
			<div class="half_column_left">
				<h2 class="title">Hottest Listings</h2>
				{module tag='module_hottest_ads'}
			</div>
			<div class="half_column_right">
				<h1 class="title">Recent Listings</h1>
				{module tag='newest_ads_1'}
			</div>
			<!-- Hottest and Recent Half Columns END -->
		</div>
		<div class="clr"></div>	
		<!-- MAIN COLUMN END -->
				
	</div>
	<div class="clr"></div>
	<!-- END CONTENT BLOCK -->
</div>

{include file='footer.tpl'}