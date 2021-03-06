You have received a new order through <a href="{$baseUrl}?a=2&b={$shoplisting.id}">your shop</a> on Petsplease.com.au<br>
<br>
Below are the details of the order:<br>
<br>

<table style="width:100%" >
	<tr>
		<th align="left">Product</th>
		<th align="left">Price</th>
		<th align="left">Qty</th>
		<th align="left">Subtotal</th>
	</tr>
	
	{foreach $listings as $listing}
		<tr>
			<td>
				<a href="{$baseUrl}?a=2&b={$listing.id}">{$listing.title|urldecode}</a>
			</td>
			<td>{$listing.price_display}</td>
			<td>{$listing.cartqty}</td>
			<td>{$listing.subtotal_display}</td>
		</tr>
	{/foreach}
	
	<tr>
		<td colspan="3"><b>Shipping & Handling</b></td>
		<td><b>{$shipping_display}</b></td>
	</tr>

	<tr>
		<td colspan="3"><b>Grand Total</b></td>
		<td><b>{$grand_total_display}</b></td>
	</tr>
</table><br>

{if $paypalPaid}
	<b>PAID FOR IN FULL THROUGH PAYPAL</b><br>
	Please log in to your Paypal account and check that the payment has been received<br>
	<br>
{/if}

<b>Billing address:</b><br>
First name: {$fielddata.billing.firstname}<br>
Last name: {$fielddata.billing.lastname}<br>
Address: {$fielddata.billing.address}<br>
Address 2: {$fielddata.billing.address2}<br>
City: {$fielddata.billing.city}<br>
Country: {$fielddata.billing.country}<br>
State: {$fielddata.billing.state}<br>
Postcode: {$fielddata.billing.zip}<br>
Phone: {$fielddata.billing.phone}<br>
Email: {$fielddata.billing.email}<br>
<br>

<b>Shipping address:</b><br>
First name: {$fielddata.shipping.firstname}<br>
Last name: {$fielddata.shipping.lastname}<br>
Address: {$fielddata.shipping.address}<br>
Address 2: {$fielddata.shipping.address2}<br>
City: {$fielddata.shipping.city}<br>
Country: {$fielddata.shipping.country}<br>
State: {$fielddata.shipping.state}<br>
Postcode: {$fielddata.shipping.zip}<br>
<br>
<b>Additional info:</b><br>
{if $fielddata.additional_info}
	{$fielddata.additional_info}
{else}
	<i>No additional info entered</i>
{/if}<br>
<br>
<b>Selected payment method:</b><br>
{$fielddata.payment_type}<br>
<br>
We ask that you please notify the buyer of any updates to the order.<br>
<br>
Petsplease.com.au