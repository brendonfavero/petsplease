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
	{listing tag='listingLogoImage' addon='ppListingImagesExtra' assign='logo'}
	{if $logo}
	<div class="listing_logo">	
		{$logo}
	</div>
	{/if}

	<!-- SHOP CATEGORIES BEGIN -->
	<h1 class="title">Categories</h1>
	<div class="content_box_1">
		{listing tag='storeCategories' addon='ppListingDisplay'}
	</div>
	<!-- SHOP CATEOGRIES END -->

	<!-- SHOP NEWS BEGIN -->
	{listing tag='extraQuestionValue' addon='ppListingDisplay' assign='ex_news' qid=190} {* Shop,News = 190 *}
	{if $ex_news}
		<h1 class="title">News</h1>
		<div class="content_box_1">{$ex_news}</div>
	{/if}
	<!-- SHOP NEWS END -->


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
	<div class="listing_heading">
		<h1>{$title}</h1>
	</div>

	{listing tag='listingBannerImages' addon='ppListingImagesExtra'}

	{listing tag='listingsEmbed' addon='ppListingDisplay' category=315}

	{* START DESCRIPTION *}
		
	<h1 class="title">Details</h1>
	<div class="content_box_1">
		<div class="field_set">
			<span class="field_name">Description:</span>
			<span class="field_value">{$description|regex_replace:"/\r\n?|\n/":'<br>'}</span>
		</div>
	</div>
	
	{* END DESCRIPTION *}	

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
