
{* The HTML at the top of each page used for menu, logo, top navigation, and user bar *}

{* Add/remove the CSS class "compact" to switch between the original (without compact),
	and the newer header style (with compact) *}
<header class="page compact">
	<!-- START LOGO / BANNER TOP -->
	<div class="logo_box" title="Website Name">
		<a href="index.php" class="logo" title="Website Name">
			<!-- Logo image OR Logo text goes here!  To use text, remove the
				image tag below and replace it with text -->
			<img src="{external file='images/logo.jpg'}" alt="Website Name" title="Website Name" />
		</a>
		<a href="index.php" class="slogan" title="Website Name">
			<!-- Slogan text goes here, if you want to add a slogan that shows
				under the logo text or logo image. -->
		</a>
	</div>
	<div id="top_banner">
		<!-- EDIT THE FOLLOWING LINE OF CODE WITH YOUR BANNER OR ADSENSE CODE -->
		{* NOTE: This does NOT display when using the CSS class "compact" on the
			header tag near the top of this template. *}
		<a href="http://geodesicsolutions.com/support/geocore-wiki/doku.php/id,tutorials;using_a_banner_system;adsense/" onclick="window.open(this.href); return false;"><img src="{external file='images/banners/banner1_adsense_468x60.jpg'}" alt="Banner Example" title="Banner Example" width="468" height="60" /></a>
	    <!-- EDIT THE ABOVE LINE OF CODE WITH YOUR BANNER OR ADSENSE CODE -->
    </div>
	
	
	<!-- END LOGO / BANNER TOP -->

	<!-- START NAVIGATION -->
	<nav class="page">
		<ul id="extra_links">
			<li><a href="/aboutus">About Us</a></li>
			<li>|</li>
			<li><a href="/contactus">Contact Us</a></li>
		</ul>
		<ul id="nav_bar">
			<li><a href="index.php">Home</a></li>
			<li><a href="index.php?a=1">Sell</a></li>
			<li><a href="index.php?a=19">Search</a></li>
			{addon author='geo_addons' addon='storefront' tag='list_stores_link'}
			<li><a href="index.php?a=28&amp;b=135">Features</a></li>
			<li><a href="index.php?a=28&amp;b=143">Pricing</a></li>
			<li><a href="index.php?a=28&amp;b=141">Help</a></li>
		</ul>
		<div id="search_bar">
			<div id="search_inner">
				<div class="expanded_search">
					{module tag='category_dropdown'}
					{module tag='module_search_box_1'}
				</div>
			</div>
		</div>
	</nav>
	<!-- END NAVIGATION -->
	
	<div class="clr abs_clr"></div>
	
	<!-- START USER BAR -->
	<div id="user_bar">
		Welcome, {module tag='display_username'}
		( 
		{if not $logged_in}
			{*Logged out code*}
			<a href="register.php">Register</a> | <a href="index.php?a=10">Login</a>
		{else}
			{*Logged in code*}
			<a href="index.php?a=4">My Account</a> | <a href="index.php?a=17">Logout</a>
		{/if}
		 )
		 
		 {addon author='geo_addons' addon='social_connect' tag='facebook_login_button'}
		 
		<!-- START SOCIAL LINKS -->
		<div id="social_links">
			{* social hover buttons from http://www.marcofolio.net/css/display_social_icons_in_a_beautiful_way_using_css3.html *}
			
			{* Change each of the links below to link to your site's page for 
				that social site, or remove any that you do not wish to use *}
			<!-- Social Media Buttons - jQuery -->
			<ul class="social" id="social_hovers">
				<li class="facebook">
					<a href="http://www.facebook.com/"><strong>Facebook</strong></a>
				</li>
				<li class="twitter">
					<a href="http://twitter.com/"><strong>Twitter</strong></a>
				</li>
				<li class="delicious">
					<a href="http://www.delicious.com/"><strong>Delicious</strong></a>
				</li>
				<li class="flickr">
					<a href="http://www.flickr.com/"><strong>Flickr</strong></a>
				</li>
				<li class="linkedin">
					<a href="http://www.linkedin.com/"><strong>LinkedIn</strong></a>
				</li>
				<li class="reddit">
					<a href="http://www.reddit.com/"><strong>Reddit</strong></a>
				</li>
				<li class="rss">
					<a href="http://feeds2.feedburner.com/marcofolio"><strong>RSS</strong></a>
				</li>
			</ul>
		</div>
		<!-- END SOCIAL LINKS -->
		<div class="clr"></div>
	</div>
	<!-- END USER BAR -->
</header>


