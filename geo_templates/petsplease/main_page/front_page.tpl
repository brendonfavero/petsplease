{include file='head.tpl'}

<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell home">
		<div id="homewelcome">
			<p class="large">Advertise your Pet or Product for free</p>
			<p>Place your ad today it is easy</p>
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

		<div style="width:728px;margin:auto;">
			{addon author='pp_addons' addon='ppAds' tag='adspot' aid=0}
		</div>
	</div>
</div>

<div class="homepage-footer">
	{assign "search_url" "/index.php?a=19&b[subcategories_also]=1"}

	<div class="footer-quicklinks clearfix">
		<h2>Quick Links</h2>
		<ul>
			<li class="title"><a href="{$search_url}&c=320">Pet Products</a></li>
			<li><a href="{$search_url}&c=320">Dog Products</a></li>
			<li><a href="{$search_url}&c=321">Cat Products</a></li>
			<li><a href="{$search_url}&c=322">Bird Products</a></li>
			<li><a href="{$search_url}&c=323">Fish Products</a></li>
			<li><a href="{$search_url}&c=324">Reptile Products</a></li>
			<li><a href="{$search_url}&c=326">Small Pets Products</a></li>
		</ul>

		<ul>
			{assign "breeder_url" "{$search_url}&c=316"}
			<li class="title"><a href="{$breeder_url}">Pet Breeders</a></li>
			<li><a href="{$breeder_url}&specpettype=dog">Dog Breeders</a></li>
			<li><a href="{$breeder_url}&specpettype=cat">Cat Breeders</a></li>
			<li><a href="{$breeder_url}&specpettype=bird">Bird Breeders</a></li>
			<li><a href="{$breeder_url}&specpettype=fish">Fish Breeders</a></li>
			<li><a href="{$breeder_url}&specpettype=reptile">Reptile Breeders</a></li>
			<li><a href="{$breeder_url}&specpettype=other">Small Pets Breeders</a></li>
		</ul>

		<ul>
			{assign "club_url" "{$search_url}&c=319"}
			<li class="title"><a href="{$club_url}">Pet Clubs</a></li>
			<li>Dog Clubs</li>
			<li>Cat Clubs</li>
			<li>Bird Clubs</li>
			<li>Fish Clubs</li>
			<li>Reptile Clubs</li>
			<li>Small Pets Clubs</li>
		</ul>

		<ul>
			{assign "news_url" "index.php?a=ap&addon=ppNews&page=news"}
			<li class="title"><a href="{$news_url}">Pets News and Advice</a></li>
			<li><a href="/news?category=news-and-advice-for-dogs-and-puppies">Dog News and Advice</a></li>
			<li><a href="/news?category=news-and-advice-for-cats-and-kittens">Cat News and Advice</a></li>
			<li><a href="/news?category=news-and-advice-for-birds">Bird News and Advice</a></li>
			<li><a href="/news?category=news-and-advice-for-fish">Fish News and Advice</a></li>
			<li><a href="/news?category=news-and-advice-for-reptiles">Reptile News and Advice</a></li>
			<li><a href="/news?category=news-and-advice-for-other-pets">Small Pets News and Advice</a></li>
		</ul>

		<ul>
			<li class="title"><a href="/petselector">Pet Selector - Choose a Cat/Dog</a></li>
			<li class="title"><a href="{$search_url}&c=309&adoptable_only=1">Shelter Dogs and Puppies</a></li>
			<li class="title"><a href="{$search_url}&c=310&adoptable_only=1">Shelter Cats and Kittens</a></li>
			<li class="title"><a href="{$search_url}&c=413">Wanted Pets</a></li>
		</ul>
	</div>

	<div class="footer-quicklinks clearfix">
		<h2>Pet Services</h2>
		<ul class="float-li-service">
			{assign "servicetype_url" "{$search_url}&c=318"}
			<li class="title"><a href="{$servicetype_url}">All Services</a></li>
			<li><a href="{$servicetype_url}&service=Aquarium+Maintenance">Aquarium Maintenance</a></li>
			<li><a href="{$servicetype_url}&service=Boarding+for+Cats">Boarding for Cats</a></li>
			<li><a href="{$servicetype_url}&service=Boarding+for+Other+Pets">Boarding for Other Pets</a></li>
			<li><a href="{$servicetype_url}&service=Boarding+Kennels+Dog">Boarding Kennels Dog</a></li>
			<li><a href="{$servicetype_url}&service=Dog+and+Cat+Washing,+Clipping+and+Grooming">Dog and Cat Washing, Clipping and Grooming</a></li>
			<li><a href="{$servicetype_url}&service=Dog+Swimming+Lessons">Dog Swimming Lessons</a></li>
			<li><a href="{$servicetype_url}&service=Dog+Training">Dog Training</a></li>
			<li><a href="{$servicetype_url}&service=Natural+Therapies">Natural Therapies</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Cemeteries+and+Crematoriums">Pet Cemeteries and Crematoriums</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Day+Care">Pet Day Care</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Insurance">Pet Insurance</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Minding+in+the+Home">Pet Minding in the Home</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Nutrition+Advise">Pet Nutrition Advise</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Photography">Pet Photography</a></li>
			<li><a href="{$servicetype_url}&service=Pet+Transport+Services">Pet Transport Services</a></li>
			<li><a href="{$servicetype_url}&service=Puppy+Socialising">Puppy Socialising</a></li>
			<li><a href="{$servicetype_url}&service=Veterinary+Surgeries+and+Hospotals">Veterinary Surgeries and Hospotals</a></li>
			<li><a href="{$servicetype_url}&service=Walking">Walking</a></li>
			<li><a href="{$servicetype_url}&service=Other">Other</a></li>
		</ul>
	</div>

	<div class="footer-quicklinks clearfix">
		{assign "location_url" "{$search_url}&c=308&b[location_distance]=50"}

		<h2>Quick Pet Location</h2>
		<ul>
			<li class="title">By State</li>
			<li><a href="{$location_url}&location=NSW">New South Wales</a></li>
			<li><a href="{$location_url}&location=QLD">Queensland</a></li>
			<li><a href="{$location_url}&location=VIC">Victoria</a></li>
			<li><a href="{$location_url}&location=SA">South Australia</a></li>
			<li><a href="{$location_url}&location=WA">Western Australia</a></li>
			<li><a href="{$location_url}&location=TAS">Tasmania</a></li>
			<li><a href="{$location_url}&location=NT">Northern Territory</a></li>
			<li><a href="{$location_url}&location=ACT">Australian Capital Territory</a></li>
		</ul>

		<ul>
			<li class="title">By Capital City</li>
			<li><a href="{$location_url}&location=Sydney,+NSW+2000">Sydney</a></li>
			<li><a href="{$location_url}&location=Brisbane,+QLD+4000">Brisbane</a></li>
			<li><a href="{$location_url}&location=Melbourne,+VIC+3000">Melbourne</a></li>
			<li><a href="{$location_url}&location=Adelaide,+SA+5000">Adelaide</a></li>
			<li><a href="{$location_url}&location=Perth,+WA+6000">Perth</a></li>
			<li><a href="{$location_url}&location=Hobart,+TAS+7000">Hobart</a></li>
			<li><a href="{$location_url}&location=Darwin,+NT+800">Darwin</a></li>
			<li><a href="{$location_url}&location=Canberra,+ACT+2600">Canberra</a></li>
		</ul>

		<ul class="float-li">
			<li class="title" style="float:none">Other Major Towns</li>
			<li><a href="{$location_url}&location=Orange, NSW 2800">Orange</a></li>
			<li><a href="{$location_url}&location=Townsville, QLD 4810">Townsville</a></li>
			<li><a href="{$location_url}&location=Rockingham">Rockingham</a></li>
			<li><a href="{$location_url}&location=Newcastle,+NSW+2300">Newcastle</a></li>
			<li><a href="{$location_url}&location=Dubbo,+NSW+2830">Dubbo</a></li>
			<li><a href="{$location_url}&location=Cairns,+QLD+4870">Cairns</a></li>
			<li><a href="{$location_url}&location=Mandurah,+WA+6210">Mandurah</a></li>
			<li><a href="{$location_url}&location=Lismore">Lismore</a></li>
			<li><a href="{$location_url}&location=Toowoomba,+QLD+4350">Toowoomba</a></li>
			<li><a href="{$location_url}&location=Bunbury,+SA+5266">Bunbury</a></li>
			<li><a href="{$location_url}&location=Wollongong,+NSW+2500">Wollongong</a></li>
			<li><a href="{$location_url}&location=Bathurst,+NSW+2795">Bathurst</a></li>
			<li><a href="{$location_url}&location=Rockhampton,+QLD+4700">Rockhampton</a></li>
			<li><a href="{$location_url}&location=Kalgoorlie,+WA+6430">Kalgoorlie</a></li>
			<li><a href="{$location_url}&location=Albury,+NSW+2640">Albury</a></li>
			<li><a href="{$location_url}&location=Coffs+Harbour,+NSW+2450">Coffs Harbour</a></li>
			<li><a href="{$location_url}&location=Mackay,+QLD+4740">Mackay</a></li>
			<li><a href="{$location_url}&location=Geraldton,+WA+6530">Geraldton</a></li>
			<li><a href="{$location_url}&location=Maitland">Maitland</a></li>
			<li><a href="{$location_url}&location=Richmond">Richmond</a></li>
			<li><a href="{$location_url}&location=Bundaberg,+QLD+4670">Bundaberg</a></li>
			<li><a href="{$location_url}&location=Albany,+WA+6330">Albany</a></li>
			<li><a href="{$location_url}&location=Wagga+Wagga,+NSW+2650">Wagga Wagga</a></li>
			<li><a href="{$location_url}&location=Nowra,+NSW+2541">Nowra</a></li>
			<li><a href="{$location_url}&location=Hervey+Bay,+QLD+4655">Hervey Bay</a></li>
			<li><a href="{$location_url}&location=Launceston,+TAS+7250">Launceston</a></li>
			<li><a href="{$location_url}&location=Port+Macquarie,+NSW+2444">Port Macquarie</a></li>
			<li><a href="{$location_url}&location=Gladstone">Gladstone</a></li>
			<li><a href="{$location_url}&location=Alice+Springs,+NT+870">Alice Springs</a></li>
			<li><a href="{$location_url}&location=Tamworth,+NSW+2340">Tamworth</a></li>
			<li><a href="{$location_url}&location=Mount+Gambier,+SA+5290">Mount Gambier</a></li>
		</ul>
	</div>


	<div class="footer-quicklinks clearfix fivecol">
		<h2>Popular Pet Breeds</h2>
		<ul class="double">
			{assign "dogs_url" "{$search_url}&c=309"}
			<li class="title"><a href="{$dogs_url}">Dogs and Puppies for Sale</a></li>
			<li><a href="{$dogs_url}&breed=Australian+Cattle+dog">Australian Cattle Dog puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Australian+Shepherd+Dog">Australian Shepherd Dog puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Border+Collie">Border Collie puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Cavalier+King+Charles+Spaniel">Cavalier King Charles Spaniel puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Cocker+Spaniel">Cocker Spaniel puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Dalmatian">Dalmatian puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=German+Shepherd+Dog">German Shepherd puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Golden+Retriever">Golden Retriever puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Great+Dane">Great Dane puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Jack+Russell+Terrier">Jack Russell Terrier puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Labrador+Retriever">Labrador Retriever puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Poodle+(Toy)">Toy Poodle puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Rottweiler">Rottweiler puppies for sale</a></li>
			<li><a href="{$dogs_url}&breed=Staffordshire+Bull+Terrier">Staffordshire Bull Terrier puppies for sale</a></li> 
		</ul>

		<ul>
			{assign "cats_url" "{$search_url}&c=310"}
			<li class="title"><a href="{$cats_url}">Cat and Kittens for Sale</a></li>
			<li><a href="{$cats_url}&breed=Australian+Mist">Australian Mist kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Bengal">Bengal kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Burmese">Burmese kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Maine+Coon">Maine Coon kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Manx">Manx kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Persian">Persian kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Ragdoll">Ragdoll kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Scottish+Fold">Scottish Fold kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Siamese">Siamese kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Sphynx">Sphynx kittens for sale</a></li>
			<li><a href="{$cats_url}&breed=Tonkinese">Tonkinese kittens for sale</a></li>
		</ul>

		<ul>
			{assign "birds_url" "{$search_url}&c=311"}
			<li class="title"><a href="{$birds_url}">Birds for Sale</a></li>
			<li><a href="{$birds_url}&b[question_value][186]=Hand+Raised">Hand Raised Birds for sale</a></li>
			<li><a href="{$birds_url}&breed=Cockatoos%2C+Cockatiels+and+Budgies">Cockatoos, Cockatiels and Budgies for sale</a></li>
			<li><a href="{$birds_url}&breed=Finches+and+Canaries">Finches and Canaries for sale</a></li>
			<li><a href="{$birds_url}&breed=Exotic+Parrots">Exotic Parrots for sale</a></li>
			<li><a href="{$birds_url}&breed=Lories+and+Lorikeets">Lories and Lorikeets for sale</a></li>
			<li><a href="{$birds_url}&breed=Native+Parrots">Native Parrots for sale</a></li>
			<li><a href="{$birds_url}&breed=Pigeons%2C+Doves+and+Soft-bills">Pigeons, Doves and Soft-bills for sale</a></li>
			<li><a href="{$birds_url}&breed=Pheasants+and+Quail">Pheasants and Quail for sale</a></li>
			<li><a href="{$birds_url}&breed=Poultry">Poultry for sale</a></li>
		</ul>

		<ul>
			{assign "fish_url" "{$search_url}&c=312"}
			<li class="title"><a href="{$fish_url}">Fish for Sale</a></li>
			<li><a href="{$fish_url}&breed=Goldfish+(Cold+Water)">Goldfish for sale</a></li>
			<li><a href="{$fish_url}&breed=Riftlake+Cichlid+Fish">Riftlake Cichlid fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Tropical+Fish">Tropical Fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Angels">Angels fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Catfish">Catfish for sale</a></li>
			<li><a href="{$fish_url}&breed=Danio">Danio fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Discus">Discus fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Fighter+(Beta)">Fighter fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Guppies">Guppies for sale</a></li>
			<li><a href="{$fish_url}&breed=Marine+Fish+(Salt+Water)">Marine fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Australian+and+New+Guinea+Natives">Australian and New Guinea Native fish for sale</a></li>
			<li><a href="{$fish_url}&breed=Yabby">Yabby for sale</a></li>
		</ul>

		<ul>
			{assign "reptile_url" "{$search_url}&c=313"}
			<li class="title"><a href="{$reptile_url}">Reptiles for Sale</a></li>
			<li><a href="{$reptile_url}&breed=Dragon">Dragons for sale</a></li>
			<li><a href="{$reptile_url}&breed=Gecko">Geckos for sale</a></li>
			<li><a href="{$reptile_url}&breed=Legless+Lizard">Legless Lizards for sale</a></li>
			<li><a href="{$reptile_url}&breed=Python">Pythons for sale</a></li>
			<li><a href="{$reptile_url}&breed=Skink">Skinks for sale</a></li>
		</ul>

		<ul style="margin-top: 7px;">
			{assign "other_url" "{$search_url}&c=314"}
			<li class="title"><a href="{$other_url}">Other Small Pets for Sale</a></li>
			<li><a href="{$other_url}&breed=Guinea+Pig">Guinea Pigs for sale</a></li>
			<li><a href="{$other_url}&breed=Leaf+Insect">Leaf Insects for sale</a></li>
			<li><a href="{$other_url}&breed=Rabbit">Rabbits for sale</a></li>
		</ul>
	</div>

</div>

{include file='footer.tpl'}
