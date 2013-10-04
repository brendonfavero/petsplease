{* 7.0.3-309-gc725738 *}
{if $score_image}
	<a href="{$listing.classifieds_file_name}?a=1030&amp;b={$listing_id}&amp;d={$seller.id}" class="display_auction_value">{$seller.feedback_score}</a>
	<img src="{external file=$score_image}" style="vertical-align: text-bottom;" alt="" />
{else}
	{$messages.102716}
{/if}
