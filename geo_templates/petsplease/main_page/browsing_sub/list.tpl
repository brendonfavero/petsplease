{*CODE_ONLY*}
{*
NOTE:  This template is used to display the contents of each listing "box" when
browsing the site using list view, during normal browsing.  Since this template
has a lot more "Smarty Tags" than normal, you can only edit using the code view.

If you try to use WYSIWYG editor to edit this template, there is very high chance
that it will corrupt the smarty tags!
*}

{* This next bit copied from listing display page *}
{if $listing.subcategory eq 309} {* Dogs for Sale *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_size' qid=168} {* Dog,Size = 168 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=171} {* Dog,Breed = 171 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_secondbreed' qid=172} {* Dog,SecondBreed = 172 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_males' qid=173 default=0} {* Dog,Males = 173 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_females' qid=174 default=0} {* Dog,Females = 174 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateofbirth' qid=169} {* Dog,Date of Birth = 169 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateavailable' qid=170} {* Dog,Date Available = 170 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_microchip' qid=182} {* Dog,Microchip = 182 *}
{elseif $listing.subcategory eq 310} {* Cats for Sale *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_size' qid=175} {* Cat,Size = 175 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=178} {* Cat,Breed = 178 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_secondbreed' qid=179} {* Cat,SecondBreed = 179 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_males' qid=180 default=0} {* Cat,Males = 180 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_females' qid=181 default=0} {* Cat,Females = 181 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateofbirth' qid=176} {* Cat,Date of Birth = 176 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateavailable' qid=177} {* Cat,Date Available = 177 *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_microchip' qid=183} {* Cat,Microchip = 183 *}	
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_hairlength' qid=184} {* Cat,Hair Length = 184 *}
{elseif $listing.subcategory eq 311} {* Birds for Sale *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=185} {* Bird,Breed = 185 *}
	{listing tag='extraCheckboxValue' addon='ppListingDisplay' assign='ex_handraised' qid=186} {* Bird,HandRaised = 186 *}
	{listing tag='extraCheckboxValue' addon='ppListingDisplay' assign='ex_suitedtobreeding' qid=187} {* Bird,SuitedToBreeding = 187 *}
	{* Gender *}
{elseif $listing.subcategory eq 312} {* Fish for Sale *}
	{listing tag='extraLeveledValue' addon='ppListingDisplay' assign='ex_breed' qid=5 level=2} {* Fish,Breed = Multi:5 *}
	{listing tag='extraLeveledValue' addon='ppListingDisplay' assign='ex_breedfirstlevel' qid=5 level=1} {* Fish,Breed = Multi:5 1st level *}
{elseif $listing.subcategory eq 313} {* Reptiles for Sale *}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=188} {* Reptile,Breed = 188 *}
{else}
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=189} {* Other,Breed = 189 *}
{/if}

<article class="list {$listing.css} clearfix{if $listing.featured_ad eq '1'} featured{/if}">
	<h1><a href="{$cfg.listing_url}{$id}">{$listing.title}</a></h1>
	
	<div class="image-col">
		<span class="helper"></span>
		{if $listing.full_image_tag}
			{$listing.image}
		{else}
			<a href="{$cfg.listing_url}{$id}">
				<img src="{$listing.image}" alt="{$listing.title|escape}" />
			</a>
		{/if}
	</div>

	{* Listing Data *}
	<div class="description-col">
		{if $ex_breed}
			<span class="breed">
				{$ex_breed}
				{if $ex_secondbreed}
					x {$ex_secondbreed}
				{/if}
				{if $ex_breedfirstlevel}
					<span style="color: grey">({$ex_breedfirstlevel})</span>
				{/if}
			</span>
		{/if}
		<span class="description">{$listing.description}</span>
	</div>

	{* Listing More Data *}
	<div class="details-col">
		<span class="price">{$listing.price}</span><br>
		{if $listing.city_data or $listing.state_data or $listing.zip_data}
			{if $listing.city_data}
				{$listing.city_data},
			{/if}
			{if $listing.state_data}
				{$listing.state_data}
			{/if}
			{if $listing.zip_data}
				{$listing.zip_data}
			{/if}
			<br>
		{/if}
		Seller:<br>

		{if $ex_males or $ex_females}
			{if $ex_males eq 1 and !$ex_females}
				Male
			{elseif $ex_females eq 1 and !$ex_males}
				Female
			{else}
				{$ex_males} males, {$ex_females} females
			{/if}
			<br>
		{/if}

		Available
	</div>
</article>

