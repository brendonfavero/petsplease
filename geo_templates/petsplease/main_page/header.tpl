
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
		<li><a href="http://facebook.com/" class="icon-link right-divider"><div class="icon-facebook" title="Facebook"></div></a></li>
		<li class="last"><a href="http://instagram.com/" class="icon-link"><div class="icon-instagram" title="Instagram"></div></a></li>
	</ul>

	<div id="header-bytext">
		Free Classifieds
	</div>

	<div id="header-bytext2">
		Pets and Products for Sale
	</div>

	<ul id="header-petnav" class="buttonset clearfix">
		<li class="nav-dog">
			<a href="?a=5&b=304"><span>Dogs &amp;<br>Puppies</span></a>
		</li>

		<li class="nav-cat">
			<a href="?a=5&b=305"><span>Cats &amp;<br>Kittens</span></a>
		</li>

		<li class="nav-bird">
			<a href="?a=5&b=306"><span>Birds</span></a>
		</li>

		<li class="nav-fish">
			<a href="?a=5&b=307"><span>Fish</span></a>
		</li>

		<li class="nav-other last">
			<a href="?a=5&b="><span>Other Pets</span></a>
		</li>
	</ul>

	<ul id="header-nav" class="buttonset clearfix">
		<li>
			<a href="#">Products</a>
		</li>

		<li>
			<a href="#">Shops</a>
		</li>

		<li>
			<a href="#">Holiday with your pet</a>
		</li>

		<li>
			<a href="#">Breeders</a>
		</li>

		<li>
			<a href="#">Services</a>
		</li>

		<li>
			<a href="#">Clubs</a>
		</li>

		<li>
			<a href="#">News and Advice</a>
		</li>

		<li class="last">
			<a href="#">Sold Pets</a>
		</li>	
	</ul>
</header>
