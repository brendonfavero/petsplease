<div style="float:right;">
	{listing tag='previous_ad_link'}
	{listing tag='next_ad_link'}
</div>

<div class="icon_link_img">
	<img alt="Print Friendly" src="{external file='images/icons/printer.png'}" title="Print Friendly" />
</div>
<div class="icon_link">
	{listing tag='print_friendly_link'}
</div>
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

<ul id="breadcrumb">
	<li class="element highlight">{$additional_text_1}</li>
	<li class="element">{listing tag='category_tree'}</li>
</ul>

<h1 class="listing_title" style="display: inline;">
	{$title}
	{if $price}&nbsp;-&nbsp;<span class="value price">{$price}</span>{/if}
	<span class="id">{$classified_id_label} {$classified_id}</span>
</h1>

<div class="action_buttons" style="display: inline;">
	{if $can_edit}
		<a href="{$classifieds_file_name}?a=cart&amp;action=new&amp;main_type=listing_edit&amp;listing_id={$classified_id}"><img src="{external file='images/buttons/listing_edit.gif'}" alt="" /></a>
	{/if}
	{if $can_delete}
		<a onclick="if (!confirm('Are you sure you want to delete this?')) return false;" href="{$classifieds_file_name}?a=99&amp;b={$classified_id}"><img src="{external file='images/buttons/listing_delete.gif'}" alt="" /></a>
	{/if}
	{listing tag='listing_action_buttons' addon='core'}
</div>
<div class="clr"></div>
<br />

<div class="listing_rightcol">
	<h3 class="title rounded_top">{$additional_text_3}</h3>
	<div class="content_box_3 cntr">
		<a href="#"><img src="{external file='../images/banners/banner1_160w.jpg'}" alt="" /></a>
	</div>
</div>
<!-- # END BANNER AD COLUMN -->

<!-- # START LEFT COLUMN -->
<div class="listing_leftcol">
	<!-- SELLER INFO BEGIN -->						
	<h2 class="title rounded_top">{$seller_label}</h2>
	<div class="content_box_2">
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
		<h2 class="title rounded_top">
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

	<!-- LISTING POPULARITY BEGIN -->
	<h2 class="title rounded_top">
		{$additional_text_6}
	</h2>
	<div class="content_box_2">
		{* Assign vote total to $vote_total so can use it to determine whether
			to show the current vote info section *}
		{listing tag='voteSummary_total' assign='vote_total'}
		{if $vote_total gt 0}
			{* only show if there are already votes on the listing *}
			<div class="cntr" style="font-size: 12px;">
				Out of <span style="color: #4076B1; font-size: 18px; font-weight: bold;">{$vote_total}</span>  Vote(s) <span style="color: #4076B1; font-size: 18px; font-weight: bold;">{listing tag='voteSummary_percent'}%</span>  of Customers Say:
			</div>
			<div class="cntr">{listing tag='voteSummary_text'}</div>
		{/if}
		<div class="cntr">
			<!-- Space the links apart -->
			<div style="display: inline-block; padding: 8px;">
				{listing tag='vote_on_ad_link'}
			</div>
			<div style="display: inline-block; padding: 8px;">
				{listing tag='show_ad_vote_comments_link'}
			</div>
		</div>
	</div>
	<!-- LISTING POPULARITY END -->
	
	<!-- FIND SIMILAR BEGIN -->
	{if $listing_tags_array}
		{* only show section if there are listing tags on this listing *}
		<h2 class="title rounded_top">
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
	
	<!-- FEATURED LISTINGS BEGIN -->						
	<h2 class="title rounded_top">
		{$additional_text_2}
	</h2>
	<div class="content_box_1">
		{* 
			NOTE: In order to show featured listings in a single column, the {module} tag
			below includes a number of parameters that over-write the
			module settings set in the admin.  You must change those
			settings "in-line" below to change them.
			
			Or, you can remove the parameter(s) from the {module}
			tag completely, and it will use the module settings
			as set in the admin panel.
			
			See the user manual entry for the {module} tag for
			a list of all parameters that can be over-written in
			this way.
		 *}
		{module tag='module_featured_pic_2' gallery_columns=1 module_thumb_width=168 module_thumb_height=200}
	</div>
	<!-- FEATURED LISTINGS END -->
</div>

<!-- END LEFT COLUMN -->

<!-- # START CENTER COLUMN -->

<div class="listing_maincol">
	<h2 class="title" style="margin-bottom: 5px;">{$additional_text_1} {$additional_text_13}</h2>
	
	{* Assign social buttons to $social so we can check if there are any before
		showing the section *}
	{listing tag='listing_social_buttons' addon='core' assign='social'}
	{if $social}
		{* There are social buttons to display, use a rounded top box *}
		<div class="rounded_top">
			{$social}
		</div>
	{/if}
	
	<div class="content_box_3">
		<div style="width:95%; margin: 0 auto; padding: 3px 0;">
			<div style="float:left; width:50%; text-align: left;"><strong>{$date_started_label}</strong> {$date_started}</div>
			<div style="float:left; width:50%; text-align: right;"><strong>{$viewed_count_label}</strong> {$viewed_count}</div>
		</div>
		
		{listing tag='image_block'}
		
		{listing tag='offsite_videos_block' assign='offsite_videos_block'}
		{if $offsite_videos_block}
			<div class="clr"></div>
			<h1 class="title">{$offsite_videos_title}</h1>
			{$offsite_videos_block}
			<div class="clr"><br /></div>
		{/if}
	</div>
	
	{* START OPTIONAL FIELDS *}
	{if $optional_field_1 or $optional_field_2 or $optional_field_3 or $optional_field_4
		or $optional_field_5 or $optional_field_6 or $optional_field_7 or $optional_field_8
		or $optional_field_9 or $optional_field_10 or $optional_field_11 or $optional_field_12
		or $optional_field_13 or $optional_field_16 or $optional_field_17 or $optional_field_18
		or $optional_field_19 or $optional_field_20}
		{* Only show section if at least one optional field 1-20 is used,
			skipping 14 and 15 as they are already displayed further up in template. *}
		<h1 class="title rounded_top">
			{$additional_text_11}
		</h1>
		<div class="content_box_1" style="padding: 4px;">
			<ul class="optional_fields">
				{* Keep track of how many fields are actually displayed. *}
				{$optional_list_count=0}
				{if $optional_field_1}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_1_label}</label>
						{$optional_field_1}
					</li>
				{/if}
				{if $optional_field_2}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_2_label}</label>
						{$optional_field_2}
					</li>
				{/if}
				{if $optional_field_3}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_3_label}</label>
						{$optional_field_3}
					</li>
				{/if}
				{if $optional_field_4}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_4_label}</label>
						{$optional_field_4}
					</li>
				{/if}
				{if $optional_field_5}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_5_label}</label>
						{$optional_field_5}
					</li>
				{/if}
				{if $optional_field_6}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_6_label}</label>
						{$optional_field_6}
					</li>
				{/if}
				{if $optional_field_7}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_7_label}</label>
						{$optional_field_7}
					</li>
				{/if}
				{if $optional_field_8}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_8_label}</label>
						{$optional_field_8}
					</li>
				{/if}
				{if $optional_field_9}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_9_label}</label>
						{$optional_field_9}
					</li>
				{/if}
				{if $optional_field_10}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_10_label}</label>
						{$optional_field_10}
					</li>
				{/if}
				{if $optional_field_11}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_11_label}</label>
						{$optional_field_11}
					</li>
				{/if}
				{if $optional_field_12}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_12_label}</label>
						{$optional_field_12}
					</li>
				{/if}
				{if $optional_field_13}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_13_label}</label>
						{$optional_field_13}
					</li>
				{/if}
				{* NOTE: optional fields 14 and 15 already displayed in seller
					note section.  If you add them here, be sure to update
					the {if ...} to include it. *}
				
				{if $optional_field_16}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_16_label}</label>
						{$optional_field_16}
					</li>
				{/if}
				{if $optional_field_17}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_17_label}</label>
						{$optional_field_17}
					</li>
				{/if}
				{if $optional_field_18}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_18_label}</label>
						{$optional_field_18}
					</li>
				{/if}
				{if $optional_field_19}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_19_label}</label>
						{$optional_field_19}
					</li>
				{/if}
				{if $optional_field_20}
					{$optional_list_count=$optional_list_count+1}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}">
						<label>{$optional_field_20_label}</label>
						{$optional_field_20}
					</li>
				{/if}
				{if $optional_list_count is odd by 1}
					{* If an odd number is displayed, insert a blank value to show on left side *}
					<li class="{cycle values='row_odd,row_even,row_even,row_odd'}"><label></label></li>
				{/if}
			</ul>
			<div class="clr"></div>
		</div>
	{/if}
	
	{* END OPTIONAL FIELDS *}

	{* START DESCRIPTION *}
		
	<h1 class="title rounded_top">{$description_label}</h1>
	<div class="content_box_1">
		{listing tag='extra_checkbox_name' assign='extra_checkbox_name'}
		{if $extra_checkbox_name}
			<br />
			<h3>{$additional_text_11}</h3>
			<div id="checkbox" style="margin:5px;">
				{$extra_checkbox_name}
			</div>
			<div class="clr"></div>
			<br />
		{/if}
		<h3>{$additional_text_7}</h3>
		<p>{$description}</p>
		{listing tag='multi_level_field_ul' assign='multi_level'}
		{listing tag='extra_question_value' assign='extra_question_value'}
		{if $extra_question_value or $multi_level}
			<br />
			<h3>{$additional_text_5}</h3>
			{if $extra_question_value}
				<div id="extra_questions">
					<div class="label">
						{listing tag='extra_question_name'}
					</div>
					<div class="data">
						{$extra_question_value}
					</div>
				</div>
				<br />
			{/if}
			{if $multi_level}
				{$multi_level}
				<div class="clr"></div>
			{/if}
		{/if}
	</div>
	
	<br />
	
	{* END DESCRIPTION *}	

	{addon author='geo_addons' addon='twitter_feed' tag='show_feed'}
	<br />
		
	{* START PUBLIC QUESTIONS *}
	<h1 class="title rounded_top">
		{$publicQuestionsLabel}{if $logged_in} - <a href="{$classifieds_file_name}?a=13&amp;b={$classified_id}">{$askAQuestionText}</a>{/if}
	</h1>
	<div class="content_box_1">
		{if $publicQuestions}
			{foreach from=$publicQuestions key='question_id' item='q'}
				{if $q.answer !== false}
					<div class="publicQuestions {cycle values='row_odd,row_even'}">
						<div class="question">
							<span class="public_question_asker_username"><a href="{$classifieds_file_name}?a=6&amp;b={$q.asker_id}">{$q.asker}</a></span> 
							<span class="public_question_asker_timestamp">({$q.time})</span>
							{if $can_delete}<a onclick="if (!confirm('Are you sure you want to remove this question and its answer?')) return false;" href="{$classifieds_file_name}?a=4&amp;b=8&amp;c=2&amp;d={$question_id}&amp;public=1"><img src="{external file='images/buttons/listing_delete.gif'}" alt="" /></a> {/if}
							<br /> 
							{$q.question}
						</div>
						<div class="answer">
							{$q.answer}
						</div>
					</div>
				{/if}
			{/foreach}
		{else}
			<div class="box_pad">{$noPublicQuestions}</div>
		{/if}
	</div>
	<br />
	
	{* END PUBLIC QUESTIONS *}

	{* START GOOGLE MAPS *}
	{addon author='geo_addons' addon='google_maps' tag='listing_map' assign='map'}
	{if $map}
		<h1 class="title rounded_top">{$additional_text_18}</h1>
		<div class="content_box_1 cntr">
			{* Make sure map is centered in the box *}
			<div style="display: inline-block;">{$map}</div>
			<div class="clr"></div>
		</div>
	{/if}
	<br />
	
	{* END GOOGLE MAPS *}
</div>

<!-- END CENTER COLUMN -->

<div class="center">
	{listing tag='previous_ad_link'}
	{listing tag='next_ad_link'}
</div>
