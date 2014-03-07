
{* The HTML at the top of each page used for menu, logo, top navigation, and user bar *}
<header class="page {addon addon='ppListingDisplay' tag='headerImageClass'}">
	<a href="/">
		<img alt="Pets Please" src="{external file='images/logo.png'}" width="261" alt="Pets Please" height="170" id="logo" />
	</a>
	
	<ul id="header-buttons" class="buttonset">
		<li class="first"><span class="right-divider">Welcome to Pets Please</span></li>
		<li><a href="/?a=cart&action=new&main_type=classified" class="right-divider">Create Your Listing</a></li>
		<li><a href="/?a=4" class="right-divider">My PetsPlease</a></li>
		{if not $logged_in}
			<li><a href="/?a=10" class="right-divider">Register</a></li>
			<li><a href="/?a=10" class="right-divider">Login</a></li>
		{else}
			<li><a href="/?a=17" class="right-divider">Logout</a></li>
		{/if}
		<li><a href="http://facebook.com/Petsplease" target="_blank" class="icon-link right-divider"><div class="icon-facebook" title="Facebook"></div></a></li>
		<li class="last"><a href="http://instagram.com/petspleaseau" target="_blank" class="icon-link"><div class="icon-instagram" title="Instagram"></div></a></li>
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
			<a href="/competition" class="curveleft pink">Pet Competition</a>
			<a href="/petselector">Pet Selector</a>
			<a href="/favourites">My Favourites</a>
			<a href="/cart">Shopping Cart</a>
		</div>

		<div>
			<a href="?a=19&c=308&b[subcategories_also]=1" class="curveright">Pets for Sale</a>
		</div>

	</div>






	<!-- <div class="petnav-header-icon">
		Pets for Sale
	</div> -->

	<ul id="header-petnav" class="buttonset clearfix">
		<li class="nav-dog"> 
			<h1><a href="/index.php?a=19&c=309&b[subcategories_also]=1"><span>Dogs &amp;<br>Puppies</span></a></h1>
		</li>

		<li class="nav-cat">
			<h1><a href="/index.php?a=19&c=310&b[subcategories_also]=1"><span>Cats &amp;<br>Kittens</span></a></h1>
		</li>

		<li class="nav-bird">
			<h1><a href="/index.php?a=19&c=311&b[subcategories_also]=1"><span>Birds</span></a></h1>
		</li>

		<li class="nav-fish">
			<h1><a href="/index.php?a=19&c=312&b[subcategories_also]=1"><span>Fish</span></a></h1>
		</li>

		<li class="nav-reptile">
			<h1><a href="/index.php?a=19&c=313&b[subcategories_also]=1"><span>Reptiles</span></a></h1>
		</li>

		<li class="nav-other last">
			<h1><a href="/index.php?a=19&c=314&b[subcategories_also]=1"><span>Other Pets</span></a></h1>
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
			<h1><a href="/shops">Pet Shops</a></h1>
		</li>

		<li>
			<h1><a href="/accommodation" class="large">Pet Friendly Accommodation</a></h1>
		</li>

		<li>
			<h1><a href="/breeders">Pet Breeders</a></h1>
		</li>

		<li>
			<h1><a href="/services">Pet Services</a></h1>
		</li>
		
		<li>
			<h1><a href="/shelters">Pet Shelters</a></h1>
		</li>

		<li>
			<h1><a href="/clubs">Pet Clubs</a></h1>
		</li>
		<li class="last">
			<a href="/news">Pet News</a>
		</li>

		<li><!-- 
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
	jQuery("#header-petnav").hover(
		function() { jQuery(this).addClass("hovering") },
		function() { jQuery(this).removeClass("hovering") }
	)
}) 
</script>
