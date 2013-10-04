{* 6.0.7-3-gce41f93 *}
{if $salutation}{$salutation},<br />{/if}
{$messageBody}<br />
<br />
{if $auctionSuccess}
	{$lowBidderInfo.firstname} {$lowBidderInfo.lastname}<br />
	{$lowBidderInfo.email}<br />
	<br />
	{$listingTitle}<br />
	<br />
	{$finalBidLabel} {$finalBid}<br />
	<br />
	{$additionalFeeInfo}<br />
{/if}
<br />
<a href="{$listingURL}">{$listingURL}</a>