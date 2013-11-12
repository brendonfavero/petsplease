<div class="action_buttons">
	{if $can_edit}
		<a href="{$classifieds_file_name}?a=cart&amp;action=new&amp;main_type=listing_edit&amp;listing_id={$classified_id}"><img src="{external file='images/buttons/listing_edit.gif'}" alt="" /></a>
	{/if}
	{if $can_delete}
		<a onclick="if (!confirm('Are you sure you want to delete this?')) return false;" href="{$classifieds_file_name}?a=99&amp;b={$classified_id}"><img src="{external file='images/buttons/listing_delete.gif'}" alt="" /></a>
	{/if}
	{listing tag='listing_action_buttons' addon='core'}
</div>

<div class="nav_breadcrumb">
	{listing tag='category_tree'}
</div>

<div class="clear"></div>

<!-- # START LEFT COLUMN -->
<div class="listing_leftcol">
	<!-- SELLER INFO BEGIN -->						
	<h1 class="title">{$seller_label}</h1>
	<div class="content_box_1">
		User: {listing tag='seller'}<br>
		Member Since: {listing field='member_since'}<br>
		<br>
		Ph: {$phone_data} <br>
		{if $phone2_data}
			{$phone2_label} {$phone2_data}<br />
		{/if}
		{if $fax_data}
			{$fax_label} {$fax_data}<br />
		{/if}
		{if $city_data}
			{$city_data},
		{/if}
		{if $state_data}
			{$state_data}
		{/if}
		{if $zip_data}
			{$zip_data}
		{/if}
		<br><br>

		{*{if $city_data or $state_data or $zip_data}
			<p class="content_section">
				{if $city_data}
					{$city_data},
				{/if}
				{if $state_data}
					{$state_data}
				{/if}
				{if $zip_data}
					{$zip_data}
				{/if}
			</p>
		{/if}
		{if $phone_data or $phone2_data or $fax_data}
			<p class="content_section cntr">
				{if $phone_data}
					<span class="sec_color" style="font-size:1.6em;">{$phone_data}</span><br />
				{/if}
				{if $phone2_data}
					{$phone2_label} {$phone2_data}<br />
				{/if}
				{if $fax_data}
					{$fax_label} {$fax_data}<br />
				{/if}
			</p>
		{/if}*}
		{listing tag='url_link_1' assign='url_link_1'}
		{listing tag='url_link_2' assign='url_link_2'}
		{listing tag='url_link_3' assign='url_link_3'}
		
		{if $url_link_1 or $url_link_2 or $url_link_3 or $public_email}
			{* Only show section if there is at least one URL link or if there
				is public e-mail to show *}
			<p class="content_section cntr">
				{if $url_link_1}
					{$url_link_1}<br />
				{/if}
				{if $url_link_2}
					{$url_link_2}<br />
				{/if}
				{if $url_link_3}
					{$url_link_3}<br />
				{/if}
				{if $public_email}
					<a href="mailto:{$public_email}">{$public_email}</a>
				{/if}
			</p>
		{/if}
		{listing tag='storefront_link' addon='storefront' assign='storefront_link'}
		<ul class="option_list">
			{if $storefront_link}
				{* The storefront link exists so show it! *}
				<li>{$storefront_link}</li>
			{/if}
			<li>{listing tag='message_to_seller_link'}</li>
		</ul>
	</div>
	<!-- SELLER INFO END -->	
	
	{if $payment_options or $optional_field_14 or $optional_field_15}
		{* Only show section if there is payment_options, or if either optional
			field 14 or 15 is turned on *}
		<!-- SELLER NOTES BEGIN -->
		<h2 class="title">
			{$additional_text_19}
		</h2>
		<div class="content_box_2">
			{if $payment_options}
				<p class="content_section">
					<strong>{$payment_options_label}</strong><br />
					{$payment_options}
				</p>
			{/if}
			{if $optional_field_14}
				<p class="content_section">
					<strong>{$optional_field_14_label}</strong><br />
					{$optional_field_14}
				</p>
			{/if}
			{if $optional_field_15}
				<p class="content_section">
					<strong>{$optional_field_15_label}</strong><br />
					{$optional_field_15}
				</p>
			{/if}
		</div>
		<!-- SELLER NOTES END -->
	{/if}
	
	<!-- FIND SIMILAR BEGIN -->
	{if $listing_tags_array}
		{* only show section if there are listing tags on this listing *}
		<h2 class="title">
			{$additional_text_20}
		</h2>
		<div class="content_box_2">
			<p class="content_section">
				<strong>{$listing_tags_label}:</strong><br />
				{listing tag='listing_tags_links'}
			</p>
		</div>
	{/if}
	<!-- FIND SIMILAR END -->

	<!-- Aditional listing info BEGIN -->
	<div class="content_box_1">
		<ul class="option_list">
			<li>{listing tag='notify_friend_link'}</li>
			<li>{listing tag='favorites_link'}</li>
			{if $enabledAddons.contact_us}
				<li>
					<a href="{$classifieds_file_name}?a=ap&amp;addon=contact_us&amp;page=main&amp;reportAbuse={$classified_id}" class="lightUpLink">{$additional_text_10}</a>
				</li>
			{/if}
		</ul>

		<br>
		<strong>{$classified_id_label}</strong> {$classified_id}<br>
		<strong>{$date_started_label}</strong> {$date_started}<br>
		<strong>{$viewed_count_label}</strong> {$viewed_count}
	</div> 
	<!-- END -->
</div>

<!-- END LEFT COLUMN -->

<!-- # START CENTER COLUMN -->

<div class="listing_maincol">
	{if $subcategory eq 309} {* Dogs for Sale *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_size' qid=168} {* Dog,Size = 168 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=171} {* Dog,Breed = 171 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_secondbreed' qid=172} {* Dog,SecondBreed = 172 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_males' qid=173 default=0} {* Dog,Males = 173 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_females' qid=174 default=0} {* Dog,Females = 174 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateofbirth' qid=169} {* Dog,Date of Birth = 169 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateavailable' qid=170} {* Dog,Date Available = 170 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_microchip' qid=182} {* Dog,Microchip = 182 *}
	{elseif $subcategory eq 310} {* Cats for Sale *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_size' qid=175} {* Cat,Size = 175 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=178} {* Cat,Breed = 178 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_secondbreed' qid=179} {* Cat,SecondBreed = 179 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_males' qid=180 default=0} {* Cat,Males = 180 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_females' qid=181 default=0} {* Cat,Females = 181 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateofbirth' qid=176} {* Cat,Date of Birth = 176 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_dateavailable' qid=177} {* Cat,Date Available = 177 *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_microchip' qid=183} {* Cat,Microchip = 183 *}	
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_hairlength' qid=184} {* Cat,Hair Length = 184 *}
	{elseif $subcategory eq 311} {* Birds for Sale *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=185} {* Bird,Breed = 185 *}
		{listing tag='extraCheckboxValue' addon='ppListingDisplay' assign='ex_handraised' qid=186} {* Bird,HandRaised = 186 *}
		{listing tag='extraCheckboxValue' addon='ppListingDisplay' assign='ex_suitedtobreeding' qid=187} {* Bird,SuitedToBreeding = 187 *}
		{* Gender *}
	{elseif $subcategory eq 312} {* Fish for Sale *}
		{listing tag='extraLeveledValue' addon='ppListingDisplay' assign='ex_breed' qid=5 level=2} {* Fish,Breed = Multi:5 *}
		{listing tag='extraLeveledValue' addon='ppListingDisplay' assign='ex_breedfirstlevel' qid=5 level=1} {* Fish,Breed = Multi:5 1st level *}
	{elseif $subcategory eq 313} {* Reptiles for Sale *}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=188} {* Reptile,Breed = 188 *}
	{else}
		{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_breed' qid=189} {* Other,Breed = 189 *}
	{/if}

	<div class="listing_heading">
		<h1>{$title}</h1>

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

		{if $price}
			<span>
				{if $topcategory eq 411}
					Starting from {$price}/night
				{else}
					{$price}
					{if $ex_males + $ex_females gt 1}
						each
					{/if}
				{/if}
			</span>
		{/if}
	</div>

	{listing tag='listingBannerImages' addon='petspleaseListingImagesExtra'}

	<div class="content_box_1">
		{listing tag='image_block'}
	</div>

	

	{* Start buyable product stuff *}
	{if $topcategory eq 315 and $optional_field_1 eq "1"}
		<div class="content_box_1">
			This product is buyable
		</div>
	{/if}
	{* End buyable product stuff}



	{* START DESCRIPTION *}
		
	<h1 class="title">Details</h1>
	<div class="content_box_1">
		{if $ex_size}
			<div class="field_set">
				<span class="field_name">Size:</span> 
				<span class="field_value">{$ex_size}</span>
			</div> 
		{/if}

		<!--{if $ex_breed}
			<div class="field_set">
				<span class="field_name">Breed:</span> 
				<span class="field_value">
					{$ex_breed}
					{if $ex_secondbreed}
						x {$ex_secondbreed}
					{/if}
					{if $ex_breedfirstlevel}
						<span style="color: grey">({$ex_breedfirstlevel})</span>
					{/if}
				</span>
			</div>
		{/if}-->

		{if $ex_hairlength}
			<div class="field_set">
				<span class="field_name">Hair Length:</span>
				<span class="field_value">{$ex_hairlength}</span>
			</div>
		{/if}

		{if $ex_males or $ex_females}
			<div class="field_set">
				<span class="field_name">Gender:</span>
				<span class="field_value"> 
					{if $ex_males eq 1 and !$ex_females}
						Male
					{elseif $ex_females eq 1 and !$ex_males}
						Female
					{else}
						{$ex_males} males, {$ex_females} females
					{/if}
				</span>
			</div>
		{/if}

		{if $ex_dateofbirth}
			<div class="field_set">
				<span class="field_name">Date of Birth:</span>
				<span class="field_value">{$ex_dateofbirth}</span>
			</div>
		{/if}

		{if $ex_dateavailable}
			<div class="field_set">
				<span class="field_name">Date Available:</span>
				<span class="field_value">{$ex_dateavailable}</span>
			</div>
		{/if}

		{if $ex_handraised}
			<div class="field_set">
				<span class="field_name">Hand Raised:</span>
				<span class="field_value">{$ex_handraised}</span>
			</div>
		{/if}

		{if $ex_suitedtobreeding}
			<div class="field_set">
				<span class="field_name">Suited to Breeding:</span>
				<span class="field_value">{$ex_suitedtobreeding}</span>
			</div>
		{/if}

		<div class="field_set">
			<span class="field_name">Description:</span>
			<span class="field_value">{$description|regex_replace:"/\r\n?|\n/":'<br>'}</span>
		</div>

		{if $ex_microchip}
			<div class="field_set">
				<span class="field_name">Microchip Numbers:</span>
				<span class="field_value">{$ex_microchip|regex_replace:"/\r\n?|\n/":'<br>'}</span>
			</div>
		{/if}

		{if $topcategory eq 318 and $optional_field_1 neq ""} {* Services *}
			<div class="field_set">
				<span class="field_name">Services:</span>
				<span class="field_value clearfix">
					{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$optional_field_1}
				</span>
			</div>
		{/if}

		{if $topcategory eq 316 or $topcategory eq 319}
			{if $optional_field_8 neq ""} {* Breeding - Dog breeds *}
				<div class="field_set">
					<span class="field_name">Dog Breeds:</span>
					<span class="field_value clearfix">
						{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$optional_field_8}
					</span>
				</div>
			{/if}

			{if $optional_field_9 neq ""} {* Breeding - Cat breeds *}
				<div class="field_set">
					<span class="field_name">Cat Breeds:</span>
					<span class="field_value clearfix">
						{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$optional_field_9}
					</span>
				</div>
			{/if}

			{if $optional_field_10 neq ""} {* Breeding - Bird breeds *}
				<div class="field_set clearfix">
					<span class="field_name">Bird Breeds:</span>
					<span class="field_value">
						{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$optional_field_10}
					</span>
				</div>
			{/if}

			{if $optional_field_11 neq ""} {* Breeding - Fish breeds *}
				<div class="field_set clearfix">
					<span class="field_name">Fish Breeds:</span>
					<span class="field_value">
						{addon author='pp_addons' addon='ppListingDisplay' tag='extraLeveledMutliCheckboxDisplay' joined=$optional_field_11}
					</span>
				</div>
			{/if}

			{if $optional_field_12 neq ""} {* Breeding - Reptile types *}
				<div class="field_set clearfix">
					<span class="field_name">Reptile Types:</span>
					<span class="field_value">
						{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$optional_field_12}
					</span>
				</div>
			{/if}

			{if $optional_field_13 neq ""} {* Breeding - Other pet types *}
				<div class="field_set clearfix">
					<span class="field_name">Other Pet Types:</span>
					<span class="field_value">
						{addon author='pp_addons' addon='ppListingDisplay' tag='extraMultiCheckboxDisplay' joined=$optional_field_13}
					</span>
				</div>
			{/if}
		{/if}
	</div>
	
	{* END DESCRIPTION *}			

	{listing tag='offsite_videos_block' assign='offsite_videos_block'}
	{if $offsite_videos_block}
		<h1 class="title">{$offsite_videos_title}</h1>
		<div class="content_box_1">		
			{$offsite_videos_block}
			<div class="clr"><br /></div>
		</div>
	{/if}

	{* START GOOGLE MAPS *}
	{addon author='geo_addons' addon='google_maps' tag='listing_map' assign='map'}
	{if $map}
		<h1 class="title">{$additional_text_18}</h1>
		<div class="content_box_1 cntr">
			{* Make sure map is centered in the box *}
			<div style="display: inline-block;">{$map}</div>
			<div class="clr"></div>
		</div>
	{/if}
	
	{* END GOOGLE MAPS *}
</div>

<!-- END CENTER COLUMN -->
