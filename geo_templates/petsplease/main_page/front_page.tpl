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
				<li><a href="?a=10">Register</a></li>
				<li><a href="?a=10">Login</a></li>
				<li class="last"><a href="?a=10">Create Your Ad</a></li>
			</ul>
		</div>

		<div id="homesearch">
			<h2>Find your pet</h2>
			<form action="#" onsubmit="alert('Searching!')">
				<div>
					<label>Pet Category</label>
					<select>
						<option>Pets for Sale</option>
					</select>
				</div>

				<div>
					<label>Pet Type</label>
					<select>
					<option>All Pets</option>
					</select>
				</div>

				<div>
					<label>Pet Breed</label>
					<select>
					<option>Affenpinscher</option>
					</select>
				</div>

				<div>
					<label>Location</label>
					<input type="text" placeholder="State, Town or Postcode" />
				</div>

				<div>
					<label>In order of</label>
					<select>
					<option>Latest</option>
					</select>
				</div>

				<button>Find My Pet</button>
			</form>
		</div>
	</div>

	<div class="content_shell">
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
	</div>
</div>
{include file='footer.tpl'}
