{* The HTML at the top of each page used for menu, logo, top navigation, and user bar *}
<header class="page {addon addon='ppListingDisplay' tag='headerImageClass'}">
	<a href="/"> <img alt="Pets Please" src="{external file='images/logo.png'}" width="261" alt="Pets Please" height="170" id="logo" /> </a>

	<ul class="buttonset header-buttons">
		<li class="first">
			<a href="/" class="right-divider">Home</a>
		</li>
		<li>
			<a href="/createyourlisting" class="right-divider">Create a Listing</a>
		</li>
		<li>
			<a href="/help" class="right-divider">Help</a>
		</li>
		<li class='has-sub'><a class="right-divider" href='#'>Advertising</span></a>
			<ul>
				<li><a class="right-divider" href='/petsandproducts'>Advertising for Pet or Pet Products</a></li>
				<li><a href='/businesses'>Advertising For Businesses</a></li>
			</ul>
		</li> 
		<li>
			<a href="/testimonials" class="right-divider">Testimonials</a>
		</li>
		<li>
			<a href="/mypetsplease" class="right-divider">My PetsPlease</a>
		</li>
		{if not $logged_in}
		<li>
			<a href="/register" class="right-divider">Register</a>
		</li>
		<li>
			<a href="/login" class="right-divider">Login</a>
		</li>
		{else}
		<li>
			<a href="/?a=17" class="right-divider">Logout</a>
		</li>		
		{/if}
		<li>
			<a href="http://facebook.com/Petsplease" target="_blank" class="icon-link right-divider"><div class="icon-facebook" title="Facebook"></div></a>
		</li>
		<li>
			<a href="http://instagram.com/petspleaseau" target="_blank" class="icon-link right-divider"><div class="icon-instagram" title="Instagram"></div></a>
		</li>

		<li>
			<a href="https://plus.google.com/105165938008909986946/posts" target="_blank" class="icon-link right-divider"><div class="icon-google" title="Google"></div></a>
		</li>	
		<li style="margin-top:7px; margin-left:5px">
			<a target="_blank" class="icon-link right-divider" href="//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.petsplease.com.au&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="above"> <img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
			<!-- Please call pinit.js only once per page -->
			<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
		</li>

	</ul>

	<div id="header-bytext">
		Free Classifieds
	</div>

	<div id="header-search">
		<form action="index.php">
			<input type="hidden" name="a" value="19">
			<input type="hidden" name="b[subcategories_also]" value="1">

			<input type="text" name="b[search_text]" placeholder="Breed/Product/Keyword" />
			<input type="submit" value="Search" />
		</form>
	</div>

	<div id="header-bytext2">
		{addon addon='ppListingDisplay' tag='headerTextClass'}
	</div>

	<div class="header-navpre">
		<div style="float: right">
			<a href="/dogclickertraining" class="curveleft pink">Dog Clicker Training</a>
			<a href="/mypetsplease">My Account</a>
			<a href="/competition">Pet Competition</a>
			<a href="/petselector">Pet Selector</a>
			<a href="/favourites">My Favourites</a>
			<a href="/cart">Shopping Cart</a>
		</div>

		<div>
			<a href="petsforsale" class="curveright">Pets for Sale</a>
		</div>

	</div>

	<!-- <div class="petnav-header-icon">
	Pets for Sale
	</div> -->

	<ul id="header-petnav" class="buttonset clearfix">
		<li class="nav-dog">
			<a href="/dogs"><span>Dogs &amp;
				<br>
				Puppies</span></a>
		</li>

		<li class="nav-cat">
			<a href="/cats"><span>Cats &amp;
				<br>
				Kittens</span></a>
		</li>

		<li class="nav-bird">
			<a href="/birds"><span>Birds</span></a>
		</li>

		<li class="nav-fish">
			<a href="/fish"><span>Fish</span></a>
		</li>

		<li class="nav-reptile">
			<a href="/reptiles"><span>Reptiles</span></a>
		</li>

		<li class="nav-other last">
			<a href="/otherpets"><span>Other Pets</span></a>
		</li>
	</ul>

	<ul id="header-nav" class="buttonset clearfix">
		<li>
			<a href="/">Home</a>
		</li>

		<li>
			<a href="/products">Pet Products</a>
		</li>

		<li>
			<a href="/shops">Pet Shops</a>
		</li>

		<li>
			<a href="/accommodation" class="large">Pet Friendly Accommodation</a>
		</li>

		<li>
			<a href="/breeders">Pet Breeders</a>
		</li>

		<li>
			<a href="/services">Pet Services</a>
		</li>

		<li>
			<a href="/shelters">Pet Shelters</a>
		</li>

		<li>
			<a href="/clubs">Pet Clubs</a>
		</li>
		<li class="last">
			<a href="/news">Pet News</a>
		</li>

		<li>
			<!--
			<a href="#">Pet News and Advice</a>
			</li>

			<li class="last">
			<a href="#">Pet Selector</a>
			</li> -->

			<!-- <li class="last">
			<a href="/index.php?a=19&c=308&b[subcategories_also]=1&b[sold_displayed]=1">Sold Pets</a>
			</li> -->
	</ul>
</header>

<script>
	jQuery(function() {
		jQuery("#header-petnav").hover(function() {
			jQuery(this).addClass("hovering")
		}, function() {
			jQuery(this).removeClass("hovering")
		})
	})
</script>
