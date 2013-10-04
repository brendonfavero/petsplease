{include file="head.tpl"}
<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell">
		<!-- START LEFT COLUMN -->
		<div id="navigation_column_left">
			<!-- Zip/postal search - commented out by default since it requires zipsearch addon -->
			<!--
			<div class="content_box center">
				<h1 class="title">
					Search by Zip/Postal:
				</h1>
				{module tag='module_zip_filter_1'}
			</div>
			<br />
			-->
			{if $enabledAddons.geographic_navigation}
				{* Only show this section if the geographic navigation addon is set up *}
				<div class="content_box">
					<h2 class="title">
						Select a Region:
					</h2>
					<br />
					{addon author='geo_addons' addon='geographic_navigation' tag='navigation'} 
				</div>
				<br />
			{/if}
		</div>
		<!-- END LEFT COLUMN -->
		
		<!-- START CONTENT BLOCK -->
		<div id="content_column_navigation">
			<div style="float: right;">
				<strong>Newest Listings:</strong> &nbsp;
				{module tag='newest_ads_link' buttonStyle=1}
				{module tag='newest_ads_link_1' buttonStyle=1}
				{module tag='newest_ads_link_2' buttonStyle=1}
				{module tag='newest_ads_link_3' buttonStyle=1}
			</div>
			<div class="clr"></div>
			
			{body_html}
			<div class="clear"><br /></div>
			<div class="content_box">
				{module tag='module_featured_pic_1'}
			</div>
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
		</div>
		<!-- END CONTENT BLOCK -->
		<div class="clr"></div>
	</div>
</div>

{include file="footer.tpl"}