{*CODE_ONLY*}
{*
NOTE:  This template is used to display the contents of each listing "box" when
browsing the site using list view, during normal browsing.  Since this template
has a lot more "Smarty Tags" than normal, you can only edit using the code view.

If you try to use WYSIWYG editor to edit this template, there is very high chance
that it will corrupt the smarty tags!
*}
<article class="listing {$listing.css}">
	<div style="float: right; text-align: right;">
		{if $cfg.cols.price&&$listing.price}
			<p class="price">
				<!-- {if $headers.price.label}<em>{$headers.price.label}</em>{/if} -->
				<span class="price">{$listing.price}</span>
			</p>
		{/if}
		{* Lets rip the storefront icon out of the normal "addon data" displayed
			further down, to display as part of the box floated to the right *}
		{if $listing.addonData.storefront.0}
			{$listing.addonData.storefront.0}
			{* This is a trick to keep it from showing further down *}
			{$listing.addonData.storefront.0=''}
		{/if}
	</div>
	{if $cfg.cols.image}
		<div class="image">
			{if $listing.full_image_tag}
				{$listing.image}
			{else}
				<a href="{$cfg.listing_url}{$id}">
					<img src="{$listing.image}" alt="{$listing.title|escape}" />
				</a>
			{/if}
			<div style="text-align:center; margin-top: 3px;">
				<img src="{external file='images/icons/camera_sml.png'}" alt="" />&nbsp;:&nbsp;<a href="{$cfg.listing_url}{$id}">{listing tag='number_images'}</a>&nbsp;|&nbsp;
				<img src="{external file='../images/icons/camera_video_sml.png'}" alt="" />&nbsp;:&nbsp;<a href="{$cfg.listing_url}{$id}">{listing tag='number_videos'}</a>
				<!-- &nbsp;|&nbsp;
				<img src="{external file='../images/icons/heart_sml.png'}" alt="" />&nbsp;:&nbsp;{listing tag='favourites_link'}
				-->
			</div>
		</div>
	{/if}
	{if $cfg.cols.type&&$listing.type}
		<p class="type">
			{if $headers.type.label}<em>{$headers.type.label}</em>{/if}
			{$listing.type}
		</p>
	{/if}
	
	{if $cfg.cols.title}
		<div class="title">
			{if $headers.title.label}<em>{$headers.title.label}</em>{/if}
			{if $listing.icons.sold && $cfg.icons.sold}<img src="{$cfg.icons.sold}" alt="" />{/if}
			<h2><a href="{$cfg.listing_url}{$id}">{$listing.title}</a></h2>
			{if $listing.icons.verified && $cfg.icons.verified}<img src="{$cfg.icons.verified}" class="verified_icon" alt="" />{/if}
			{if $listing.icons.buy_now && $cfg.icons.buy_now}<img src="{$cfg.icons.buy_now}" class="buy_now_icon" alt="" />{/if}
			{if $listing.icons.reserve_met && $cfg.icons.reserve_met}<img src="{$cfg.icons.reserve_met}" class="reserve_met_icon" alt="" />{/if}
			{if $listing.icons.reserve_not_met && $cfg.icons.reserve_not_met}<img src="{$cfg.icons.reserve_not_met}" class="reserve_not_met_icon" alt="" />{/if}
			{if $listing.icons.no_reserve && $cfg.icons.no_reserve}<img src="{$cfg.icons.no_reserve}" class="no_reserve_icon" alt="" />{/if}
			
			{if $listing.icons.attention_getter}<img src="{$listing.attention_getter_url}" class="attention_getter_icon" alt="" />{/if}
			
			{if $listing.icons.addon_icons}
				{foreach $listing.icons.addon_icons as $addon => $icon}
					{$icon}
				{/foreach}
			{/if}
		</div>
	{elseif $cfg.cols.icons && $listing.icons}
		{if $listing.icons.sold && $cfg.icons.sold}<img src="{$cfg.icons.sold}" alt="" />{/if}
		{if $listing.icons.verified && $cfg.icons.verified}<img src="{$cfg.icons.verified}" class="verified_icon" alt="" />{/if}
		{if $listing.icons.buy_now && $cfg.icons.buy_now}<img src="{$cfg.icons.buy_now}" class="buy_now_icon" alt="" />{/if}
		{if $listing.icons.reserve_met && $cfg.icons.reserve_met}<img src="{$cfg.icons.reserve_met}" class="reserve_met_icon" alt="" />{/if}
		{if $listing.icons.reserve_not_met && $cfg.icons.reserve_not_met}<img src="{$cfg.icons.reserve_not_met}" class="reserve_not_met_icon" alt="" />{/if}
		{if $listing.icons.no_reserve && $cfg.icons.no_reserve}<img src="{$cfg.icons.no_reserve}" class="no_reserve_icon" alt="" />{/if}
		
		{if $listing.icons.attention_getter}<img src="{$listing.attention_getter_url}" class="attention_getter_icon" alt="" />{/if}
		
		{if $listing.icons.addon_icons}
			{foreach $listing.icons.addon_icons as $addon => $icon}
				{$icon}
			{/foreach}
		{/if}
	{/if}
	
	{if $cfg.cols.description||$cfg.description_under_title}
	 	<p class="description">
			{if $headers.description.label}<em>{$headers.description.label}</em>{/if}
			{$listing.description}
		</p>
	{/if}
	
	{if $cfg.cols.time_left&&$listing.time_left}
		<p class="time_left">
			{if $headers.time_left.label}<em>{$headers.time_left.label}</em>{/if}
			{$listing.time_left}
		</p>
	{/if}
	
	{if $listing.addonData}
		{* let addons add columns if they want to *}
		{foreach $listing.addonData as $addonRows}
			{foreach $addonRows as $addonText}
				<p class="addon_data {$addonHeaders[$addon][$aKey].css}">
					{if $addonHeaders[$addon][$aKey].label}<em>{$addonHeaders[$addon][$aKey].label}:</em>{/if}
					{$addonText}
				</p>
			{/foreach}
		{/foreach}
	{/if}
	
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
	<div class="clear"></div>
</article>