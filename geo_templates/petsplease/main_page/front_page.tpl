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

			{addon author='pp_addons' addon='ppHomeSearch' tag='search'}
		</div>
	</div>

	<div class="content_shell">
		<!-- FEATURED CAROUSEL BEGIN -->
		<h1 class="title">Featured Pets and Products</h1>
		<div class="content_box_1 gj_simple_carousel">
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

		<div class="adspot728x90"></div>
	</div>
</div>
{include file='footer.tpl'}
