{* 6.0.7-3-gce41f93 *}
{if $salutation}{$salutation},<br />{/if}
{$messageBody}<br />
<br />
{$finalBidLabel} {$finalBid}<br />
<br />
{$additionalFeeInfo}<br />
<br />
{$sellerInfo.firstname} {$sellerInfo.lastname}<br />
{$sellerInfo.email}<br />
<br />
{$listingTitle}<br />
<a href="{$listingURL}">{$listingURL}</a><br />
{if $sellerBuyerInfo}
	<br />
	{$sellerBuyerInfo}
{/if}