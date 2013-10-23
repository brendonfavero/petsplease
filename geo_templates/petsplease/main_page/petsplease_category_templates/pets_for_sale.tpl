<div class="icon_link_img">
	<img alt="Tell-a-Friend" src="{external file='images/icons/friend.png'}" title="Tell-a-Friend" />
</div>
<div class="icon_link">
	{listing tag='notify_friend_link'}
</div>
<div class="icon_link_img">
	<img alt="Add-to-Favorites" src="{external file='images/icons/favorite.png'}" title="Add-to-Favorites" />
</div>
<div class="icon_link">
	{listing tag='favorites_link'}
</div>
{if $enabledAddons.contact_us}
	<div class="icon_link_img">
		<img alt="Report Abuse" src="{external file='images/icons/flag.png'}" title="Report Abuse" />
	</div>
	<div class="icon_link">
		<a href="{$classifieds_file_name}?a=ap&amp;addon=contact_us&amp;page=main&amp;reportAbuse={$classified_id}" class="lightUpLink">{$additional_text_10}</a>
	</div>
{/if}
<div class="clr"></div>

<div class="nav_breadcrumb">
	{listing tag='category_tree'}
</div>


<!-- # START LEFT COLUMN -->
<div class="listing_leftcol">
	<!-- SELLER INFO BEGIN -->						
	<h1 class="title">{$seller_label}</h1>
	<div class="content_box_2">
		Username<br>
		Contact phone<br>
		Member since<br>
		Location of Pet<br>
		Postcode<br>
		City<br>
		State<br>
		Country<br>
		Ask seller a question<br>
		Breeder profile page<br>
		<br>
		Skype<br>
		Watch<br>
		Share with a friend<br>
		Print<br>
		GMail<br>
		Facebook<br>

		<br><br>


		<h1 class="seller_username">{listing tag='seller'}</h1>
		<p class="content_section">
			<strong>{$additional_text_17}</strong><br />
			{listing field='member_since'}
		</p>
		{if $city_data or $state_data or $zip_data}
			{* Only show this section if the city, state, or zip is set for the listing.
				This prevents an "empty section" when all of location information is empty *}
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
			{* Only show this section if one of the phones or fax is set for
				the listing.  This prevents an "empty section" when all of the
				phone numbers are empty. *}
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
		{/if}
		{* "Assign" contents of each url link to a smarty variable, so we can see
			if the link exists before adding the section *}
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
		{* Assign the storefront link to $storefront_link so we can check if it is
			"empty" or not before showing it...  To prevent an "empty" item in the list
			if there is no storefront link. *}
		{listing tag='storefront_link' addon='storefront' assign='storefront_link'}
		<ul class="option_list">
			{if $storefront_link}
				{* The storefront link exists so show it! *}
				<li>{$storefront_link}</li>
			{/if}
			<li>{listing tag='sellers_other_ads_link'}</li>
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
	<div>
		<strong>{$date_started_label}</strong> {$date_started}<br>
		<strong>{$viewed_count_label}</strong> {$viewed_count}<br>
		<strong>{$classified_id_label}</strong> {$classified_id}
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
		<div class="action_buttons" style="float: right">
			{if $can_edit}
				<a href="{$classifieds_file_name}?a=cart&amp;action=new&amp;main_type=listing_edit&amp;listing_id={$classified_id}"><img src="{external file='images/buttons/listing_edit.gif'}" alt="" /></a>
			{/if}
			{if $can_delete}
				<a onclick="if (!confirm('Are you sure you want to delete this?')) return false;" href="{$classifieds_file_name}?a=99&amp;b={$classified_id}"><img src="{external file='images/buttons/listing_delete.gif'}" alt="" /></a>
			{/if}
			{listing tag='listing_action_buttons' addon='core'}
		</div>

		<h1>{$title}</h1>

		{if $ex_breed}
			<span class="breed">{$ex_breed}</span>
		{/if}

		{if $price}
			<span>{$price}</span>
		{/if}
	</div>
	
	<div class="content_box_1">
		{listing tag='image_block'}
	</div>

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

		{if $ex_microchip}
			<div class="field_set">
				<span class="field_name">Microchip Numbers:</span>
				<span class="field_value">{$ex_microchip|regex_replace:"/\r\n?|\n/":'<br>'}</span>
			</div>
		{/if}

		<div class="field_set">
			<span class="field_name">Description:</span>
			<span class="field_value">{$description|regex_replace:"/\r\n?|\n/":'<br>'}</span>
		</div>
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
