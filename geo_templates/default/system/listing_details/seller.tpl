{* 7.1beta1-1095-g2c0999d *}{strip}
	{if $anon}
		{$anon_username}
	{else}
		<a href="{$classifieds_file_name}?a=13&amp;b={$listing_id}" class="display_ad_value">{$seller_data.username}</a>
	{/if}
{/strip}