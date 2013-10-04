{* 6.0.7-3-gce41f93 *}
{* NOTE: this template is used for the internals of the current_high_bidder and outbid emails *}
{$titleLabel} {$title}<br />
<br />
{$currentBidLabel} {$currentBid}<br />
{if $maxBid}
	<br />
	{$maxBidLabel} {$maxBid}<br />
{/if}
<br />
{$endDateLabel} {$endDate}<br />
<br />
{$listingLinkLabel} <a href="{$listingLink}">{$listingLink}</a><br />