{* 6.0.7-3-gce41f93 *}
{$introduction}{if $salutation} {$salutation}{/if},<br />
{$messageBody}<br />
<br />
{$orderIdLabel} {$orderId}<br />
{$orderStatusActive}<br />
<br />
{$line}<br />
{$infoHeader}<br />
{foreach $itemInfos as $info}
	{$info}<br />
{/foreach}
{$line}<br />
<br />
{$orderTotalLabel} {$orderTotal} (price is GST inclusive)<br />
{$fullPaymentReceived}
{if $invoiceLink}
	<br />
	<br />
	<a href="{$invoiceLink}">{$invoiceLink}</a>
{/if}