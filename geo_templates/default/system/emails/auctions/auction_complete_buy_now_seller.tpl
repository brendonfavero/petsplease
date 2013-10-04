{* 7.2beta1-100-g9440421 *}
{if $salutation}{$salutation},<br />{/if}
{$messageBody}<br />
<br />
{if $price_applies=='item'}
	<strong>{$messages.502114}</strong> {$quantity} @ {$finalBid} {$messages.502115}
{else}
	<strong>{$finalBidLabel}</strong> {$finalBid}
{/if}
<br /><br />
{if $additionalFees}
	<strong>{if $price_applies=='item'}{$messages.502116}{else}{$messages.500033}{/if}</strong><br />
	{foreach $additionalFees.formatted as $key => $fee}
		{if $key!=='total'}
			{$fee}<br />
		{/if}
	{/foreach}
	{if $price_applies=='item'}
		<strong>{$messages.502117}</strong> {$additionalFees.formatted.total}
		<br /><br />
		<strong>{$messages.502118}</strong> {$additionalFees.grandTotal}
		<br /><br />
		<strong>{$messages.500036}</strong> {$additionalFees.grandGrandTotal}
	{else}
		<strong>{$messages.500035}</strong> {$additionalFees.formatted.total}
		<br /><br />
		<strong>{$messages.500036}</strong> {$additionalFees.grandTotal}
	{/if}
	<br /><br />
	{$messages.500034}
	<br /><br />
{elseif $price_applies=='item'}
	<strong>{$messages.500036}</strong> {$grandTotal}
{/if}
{$highBidderInfo.firstname} {$highBidderInfo.lastname}<br />
{$highBidderInfo.email}<br />
<br />
{$listingTitle}<br />
<a href="{$listingLink}">{$listingLink}</a>
{if $price_applies=='item'}
	<br /><br />
	{if $quantity_remaining}
		<strong>{$messages.502119}</strong> {$quantity_remaining} {$messages.502120} {$quantity_starting}
	{else}
		{$messages.502121}
	{/if}
{/if}