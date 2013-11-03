
{* The HTML at the top of each page used for menu, logo, top navigation, and user bar *}
<header class="page">
	<a href="/">
		<img src="{external file='images/logo.png'}" width="261" height="170" id="logo" />
	</a>
	
	<ul id="header-buttons" class="buttonset">
		<li class="first"><span class="right-divider">Welcome to Pets Please</span></li>
		{if not $logged_in}
			<li><a href="/?a=10" class="right-divider">Register</a></li>
			<li><a href="/?a=10" class="right-divider">Login</a></li>
		{else}
			<li><a href="/?a=4" class="right-divider">My PetsPlease</a></li>
			<li><a href="/?a=17" class="right-divider">Logout</a></li>
		{/if}
		<li><a href="http://facebook.com/Petsplease" target="_blank" class="icon-link right-divider"><div class="icon-facebook" title="Facebook"></div></a></li>
		<li class="last"><a href="http://instagram.com/" target="_blank" class="icon-link"><div class="icon-instagram" title="Instagram"></div></a></li>
	</ul>

	<div id="header-bytext">
		Free Classifieds
	</div>

	<div id="header-bytext2">
		Pets and Products for Sale
	</div>

	<div class="petnav-header-icon">
		Pets for Sale
	</div>

	<ul id="header-petnav" class="buttonset clearfix">
		<li class="nav-dog">
			<a href="/index.php?a=19&c=309&b[subcategories_also]=1"><span>Dogs &amp;<br>Puppies</span></a>
		</li>

		<li class="nav-cat">
			<a href="/index.php?a=19&c=310&b[subcategories_also]=1"><span>Cats &amp;<br>Kittens</span></a>
		</li>

		<li class="nav-bird">
			<a href="/index.php?a=19&c=311&b[subcategories_also]=1"><span>Birds</span></a>
		</li>

		<li class="nav-fish">
			<a href="/index.php?a=19&c=312&b[subcategories_also]=1"><span>Fish</span></a>
		</li>

		<li class="nav-reptile">
			<a href="/index.php?a=19&c=313&b[subcategories_also]=1"><span>Reptiles</span></a>
		</li>

		<li class="nav-other last">
			<a href="/index.php?a=19&c=314&b[subcategories_also]=1"><span>Other Pets</span></a>
		</li>
	</ul>

	<ul id="header-nav" class="buttonset clearfix">
		<li>
			<a href="/">Home</a>
		</li>

		<li>
			<a href="/index.php?a=19&c=315&b[subcategories_also]=1">Pet Products</a>
		</li>

		<li>
			<a href="#">Pet Shops</a>
		</li>

		<li>
			<a href="/index.php?a=19&c=411&b[subcategories_also]=1">Holiday with your pet</a>
		</li>

		<li>
			<a href="/index.php?a=19&c=316&b[subcategories_also]=1">Pet Breeders</a>
		</li>

		<li>
			<a href="/index.php?a=19&c=318&b[subcategories_also]=1">Pet Services</a>
		</li>

		<li>
			<a href="/index.php?a=19&c=319&b[subcategories_also]=1">Pet Clubs</a>
		</li>

		<li>
			<a href="#">Pet News and Advice</a>
		</li>

		<li class="last">
			<a href="/index.php?a=19&c=308&b[subcategories_also]=1&b[sold_displayed]=1">Sold Pets</a>
		</li>	
	</ul>
</header>

<script>
jQuery(function() {
	jQuery("#header-petnav").hover(function() 
		{ jQuery("#header-petnav").toggleClass("hovering") })
}) 
</script>
