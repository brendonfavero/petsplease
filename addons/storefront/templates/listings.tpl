{* 6.0.7-3-gce41f93 *}

{foreach from=$all_listings key="browse_type" item='listings'}
	<div class="content_box">
		<h2 class="title">{if $display_classifieds && $browse_type == 1}{$messages.200109}{else}{$messages.200110}{/if} {$storefront_name} {if $category_name}({$category_name}){/if}</h2>
		{if $listings}
			<table cellpadding='0' cellspacing='0' border='0' width="100%">
				<tr class='results_column_header'>
					{if $storefront.display_business_type}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == 43}44{elseif $sort_type == 44}0{else}43{/if}">
								{$messages.1262}
							</a>
						</td>
					{/if}
					{if $storefront.display_photo_icon}
						<td class="nowrap center">
							{$messages.23}
						</td>
					{/if}
					{if $storefront.display_ad_title}
						<td class="title">
							<a href="{$sort_url}{if $sort_type == 5}6{elseif $sort_type == 6}0{else}5{/if}">
								{$messages.19}
							</a>
							{if $storefront.display_ad_description && $storefront.display_ad_description_where}
								/ {$messages.21}
							{/if}
						</td>
					{/if}
					{if $storefront.display_ad_description && !$storefront.display_ad_description_where}
						<td>
							{$messages[21]}
						</td>
					{/if}
					{foreach from=$optional_vars item="optional"}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == $optional.sortA}{$optional.sortB}{elseif $sort_type == $optional.sortB}0{else}{$optional.sortA}{/if}">
								{$optional.header_text}
							</a>
						</td>
					{/foreach}
					{if $storefront.display_browsing_city_field}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == 7}8{elseif $sort_type == 8}0{else}7{/if}">
								{$messages.1199}
							</a>
						</td>
					{/if}
					{if $storefront.display_browsing_state_field}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == 37}38{elseif $sort_type == 38}0{else}37{/if}">
								{$messages.1200}
							</a>
						</td>
					{/if}
					{if $storefront.display_browsing_country_field}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == 39}40{elseif $sort_type == 40}0{else}39{/if}">
								{$messages.1201}
							</a>
						</td>
					{/if}
					{if $storefront.display_browsing_zip_field}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == 41}42{elseif $sort_type == 42}0{else}41{/if}">
								{$messages.1202}
							</a>
						</td>
					{/if}
					{if $browse_type == 2 && $storefront.display_number_bids}
						<td class="nowrap center">
							{$messages[103041]}
						</td>
					{/if}
					{if $storefront.display_price}
						<td class="nowrap center">
							<a href="{$sort_url}{if $sort_type == 1}2{elseif $sort_type == 2}0{else}1{/if}">
								{$messages.27}
							</a>
						</td>
					{/if}
					{if ($storefront.display_entry_date && $browse_type == 1) || ($storefront.auction_entry_date && $browse_type == 2)}
						<td class="nowrap center">{$messages[22]}</td>
					{/if}
					{if $storefront.display_time_left && ($browse_type == 2 || $storefront.classified_time_left)}
						<td class="nowrap center">{$messages[103008]}</td>
					{/if}
					{if $auth_edit}
						<td class="nowrap center">Edit</td>
					{/if}
					{if $auth_delete}
						<td class="nowrap center">Delete</td>
					{/if}
				</tr>
				{foreach from=$listings item="listing"}
					<tr class="{cycle values="row_even,row_odd"}{$row_class2}{if $listing.bolding}_highlight{/if}">
						{if $storefront.display_business_type}
							<td class="nowrap center">
								{if $listing.business_type == 1}{$messages.1263}{elseif $listing.business_type == 2}{$messages.1264}{else}&nbsp;{/if}
							</td>
						{/if}
						{if $storefront.display_photo_icon}
							{$listing.image_icon}
						{/if}
						{if $storefront.display_ad_title}
							<td>
								{if $listing.sold_displayed}<img src="{$storefront.sold_image}" alt="" />{/if}
								<a href="{$listing_link|replace:'(!ID!)':$listing.id}">
									{$listing.title|fromDB}
								{$listing.title_extra_txt}
								{if $listing.attention_getter}
									<img src="{$listing.attention_getter_url}" alt="" />
								{/if}</a>
								{if $storefront.display_ad_description && $storefront.display_ad_description_where}
									<p><a href="{$listing_link|replace:'(!ID!)':$listing.id}">{$listing.clean_desc}</a></p>
								{/if}
							</td>
						{/if}
						{if $storefront.display_ad_description && !$storefront.display_ad_description_where}
							<td>
								{$listing.clean_desc}&nbsp;
							</td>
						{/if}
						{foreach from=$optional_vars item="optional"}
							{assign var='i' value=$optional.i}
							<td class="nowrap center">
								{if $listing.$i}{$listing.$i|fromDB}{else}-{/if}
							</td>
						{/foreach}
						{if $storefront.display_browsing_city_field}
							<td class="nowrap center">
								{$listing.location_city|fromDB}
							</td>
						{/if}
						{if $storefront.display_browsing_state_field}
							<td class="nowrap center">
								{$listing.location_state|fromDB}
							</td>
						{/if}
						{if $storefront.display_browsing_country_field}
							<td class="nowrap center">
								{$listing.location_country|fromDB}
							</td class="nowrap center">
						{/if}
						{if $storefront.display_browsing_zip_field}
							<td class="nowrap center">
								{$listing.location_zip|fromDB}
							</td>
						{/if}
						{if $browse_type == 2 && $storefront.display_number_bids}
							<td class="nowrap center">
								{$listing.number_bids} {$messages.103042}
							</td>
						{/if}
						{if $storefront.display_price}
							<td class="nowrap center price">
								{$listing.price}
							</td>
						{/if}
						{if ($storefront.display_entry_date && $browse_type == 1) || ($storefront.auction_entry_date && $browse_type == 2)}
							<td class="nowrap center">{$listing.entry_date}</td>
						{/if}
						{if $storefront.display_time_left && ($browse_type == 2 || $storefront.classified_time_left)}
							<td class="nowrap center">{$listing.time_left}</td>
						{/if}
						{if $auth_edit}
							<td class="nowrap center"><a href="{$classifieds_file_name}?a=cart&action=new&main_type=listing_edit&listing_id={$listing.id}" class="edit"><img src='{external file="images/buttons/listing_edit.gif"}' alt='edit' /></a></td>
						{/if}
						{if $auth_delete}
							<td class="nowrap center"><a href="{$classifieds_file_name}?a=99&b={$listing.id}&amp;c={$listing.category}" class="delete"><img src='{external file="images/buttons/listing_delete.gif"}' alt='delete' /></a></td>
						{/if}
					</tr>
				{/foreach}
			</table>
		{else}
			<div class="no_results_box">{if $display_classifieds && $browse_type == 1}{$messages.17}{else}{$messages.100017}{/if}</div>
		{/if}
	</div>
	<br />
{/foreach}

{if $pagination}{$pagination}{/if}
