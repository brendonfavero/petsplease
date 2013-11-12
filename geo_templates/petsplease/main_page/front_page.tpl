{include file='head.tpl'}

<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell home">
		<div id="homewelcome">
			<p class="large">Advertise your Pet or Product for free</p>
			<p>Place your add today it is easy</p>
			<ul>
				<li>Up to 20 photos</li>
				<li>Upload your Youtube video</li>
				<li>Manage your own ad, update as often as you like</li>
			</ul>

			<ul id="homewelcome-buttons" class="buttonset clearfix">
				<li><a href="/?a=10">Register</a></li>
				<li><a href="/?a=10">Login</a></li>
				<li class="last"><a href="/?a=10">Create Your Ad</a></li>
			</ul>
		</div>

		<div id="homesearch">
			<h2>Search</h2>

			{addon author='pp_addons' addon='ppSearch' tag='searchSidebar' simple=true}
		</div>
	</div>

	<div class="content_shell">
		<!-- FEATURED CAROUSEL BEGIN -->
		<h1 class="title">Featured Pets and Products</h1>
		<div class="content_box_1 gj_simple_carousel">
			{module tag='module_featured_pic_1' gallery_columns=6 module_thumb_width=120}
		</div>
		<!-- FEATURED CAROUSEL END -->

		<div class="adspot728x90"></div>
	</div>
</div>
{include file='footer.tpl'}
