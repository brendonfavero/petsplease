
{* The HTML at the top of each page used for menu, logo, top navigation, and user bar *}
<header class="page">
	<a href="/">
		<img src="{external file='images/logo.png'}" width="261" height="170" id="logo" />
	</a>
	
	<ul id="header-buttons" class="buttonset">
		<li class="first"><span class="right-divider">Welcome to Pets Please</span></li>
		{if not $logged_in}
			<li><a href="?a=10" class="right-divider">Register</a></li>
			<li><a href="?a=10" class="right-divider">Login</a></li>
		{else}
			<li><a href="?a=4" class="right-divider">My PetsPlease</a></li>
			<li><a href="?a=17" class="right-divider">Logout</a></li>
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

	<ul id="header-petnav" class="buttonset clearfix">
		<li class="nav-dog">
			<a href="?a=5&b=309"><span>Dogs &amp;<br>Puppies</span></a>
		</li>

		<li class="nav-cat">
			<a href="?a=5&b=310"><span>Cats &amp;<br>Kittens</span></a>
		</li>

		<li class="nav-bird">
			<a href="?a=5&b=311"><span>Birds</span></a>
		</li>

		<li class="nav-fish">
			<a href="?a=5&b=312"><span>Fish</span></a>
		</li>

		<li class="nav-reptile">
			<a href="?a=5&b=313"><span>Reptiles</span></a>
		</li>

		<li class="nav-other last">
			<a href="?a=5&b=314"><span>Other Pets</span></a>
		</li>
	</ul>

	<ul id="header-nav" class="buttonset clearfix">
		<li>
			<a href="/">Home</a>
		</li>

		<li>
			<a href="?a=5&b=315">Products</a>
		</li>

		<li>
			<a href="#">Shops</a>
		</li>

		<li>
			<a href="#">Holiday with your pet</a>
		</li>

		<li>
			<a href="?a=5&b=316">Breeders</a>
		</li>

		<li>
			<a href="?a=5&b=318">Services</a>
		</li>

		<li>
			<a href="?a=5&b=319">Clubs</a>
		</li>

		<li>
			<a href="#">News and Advice</a>
		</li>

		<li class="last">
			<a href="#">Sold Pets</a>
		</li>	
	</ul>
</header>
