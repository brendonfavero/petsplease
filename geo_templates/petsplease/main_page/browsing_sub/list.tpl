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
	<div class="list-listing-heading" style="position:relative">

		{if $cfg.cols.edit or $cfg.cols.delete}
			<div style="float:right; padding-left: 10px;">
				{if $cfg.cols.edit}
					<p class="edit">
						<a href="{$classifieds_file_name}?a=cart&amp;action=new&amp;main_type=listing_edit&amp;listing_id={$id}"><img src="{external file='images/buttons/listing_edit.gif'}" alt="" /></a>
					</p>
				{/if}
				
				{if $cfg.cols.delete}
					<p class="delete">
						<a onclick="if (!confirm('Are you sure you want to delete this?')) return false;" href="{$classifieds_file_name}?a=99&amp;b={$id}"><img src="{external file='images/buttons/listing_delete.gif'}" alt="" /></a>
					</p>
				{/if}
			</div>
		{/if}

		<h2>
			{if $listing.icons.sold && $cfg.icons.sold}<img src="{$cfg.icons.sold}" alt="" />{/if}
			<a href="{$cfg.listing_url}{$id}">{$listing.title}</a>
		</h2>
		{if $listing.featured_ad eq '1'}
		<p style="
		    color: white;
		    font-weight: bold;
		    position: absolute;
		    top: 0;
		    right: 0;
		    font-size: 16px;
		">Featured</p>
		{/if}
		{if $listing.shelter eq '1'}
			<p style="color:#EC008C; font-size:14px; font-weight:bold">Shelter Pet</p>
		{/if}
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

		{if $cfg.cols.price and $listing.price}
			<span>
				{if $listing.topcategory eq 411}
					{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_charge' qid=206}
					Prices from {$listing.from_price}  to {$listing.to_price} {$ex_charge}
				{else}
					{$listing.price}
					{if $ex_males + $ex_females gt 1}
						each
					{/if}
				{/if}
			</span>
		{/if}

		{if $listing.topcategory eq 318}
			{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$listing.optional_field_1 assign='services'}
			{if $services}
				<span class="services">
					{$services}
				</span>
			{/if}
		{/if}
	</div>
	
	<div class="image-col">
		{listing tag='listingLogoThumb' addon='ppListingImagesExtra' assign='logo'}
		{if $logo}
			{$logo}
		{elseif $listing.topcategory eq 413}
			{* Wanted Pets have no images so suppress "No Image Available" *}
		{elseif $listing.full_image_tag}
			{$listing.image}
		{else}
			<a href="{$cfg.listing_url}{$id}">
				<img src="{$listing.image}" alt="{$listing.title|escape}" />
			</a>
		{/if}
	</div>

	{* Listing Data *}
	<div class="description-col">
		<span class="description">{$listing.description}</span>
	</div>

	{* Listing More Data *}
	{strip}
		{if $listing.url_link_1_href}
			{capture append="details" nocache}
				<a href="{$listing.url_link_1_href}" target="_blank">Website</a>
			{/capture}
		{/if}

		{if $listing.city_data or $listing.state_data}
			{capture append="details" nocache}
				{$listing.city_data}{if $listing.city_data and $listing.state_data}, {/if}
				{$listing.state_data}
			{/capture}
		{/if}

		{if $ex_males or $ex_females}
			{capture append="details"}
				{if $ex_males eq 1 and !$ex_females}
					Male
				{elseif $ex_females eq 1 and !$ex_males}
					Female
				{elseif $ex_males or $ex_females}
					{$ex_males} males, {$ex_females} females
				{/if}
			{/capture}
		{/if}

		{if count($details) gt 0}
			<div class="details-col">
				{foreach $details as $detail}
					<div class="detail{if $detail@last} last{/if}">
						{$detail}
					</div>
				{/foreach}
			</div>
		{/if}
	{/strip}
</article>

