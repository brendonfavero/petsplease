{foreach from=$error_msgs item=err_msg}
	<div class="field_error_box">
		{$err_msg}
	</div>
{/foreach}
<form action="{$process_form_url}" method="post">
	<div class="clearfix" style="width:100%; margin-bottom: 30px">
		<div id="cart_left_column">
			<div class="content_box">
				<h2 class="title">Billing Contact Info</h2>
				<p class="page_instructions">
					This is the information that will be used for billing purposes. Please ensure that all of the information here is accurate.
				</p>
				
				{if $error_msgs.billing_email}
					<div class='field_error_box'>{$error_msgs.billing_email}</div>
				{/if}
				
				<div class="row_even">
					<label for="billing_firstname" class="field_label_tight">First Name</label>
					<input id="billing_firstname" name='billing[firstname]' value='{if $buyer.firstname}{$buyer.firstname}{elseif $populate_billing_info}{$cart.firstname}{/if}' class="field" />
				</div>
				
				<div class="row_odd">
					<label for="billing_lastname" class="field_label_tight">Last Name</label>
					<input id="billing_lastname" name='billing[lastname]' value='{if $buyer.lastname}{$cart.billing_info.lastname}{elseif $populate_billing_info}{$cart.lastname}{/if}' class="field" />
				</div>
				
				<div class="row_even">
					<label for="billing_address" class="field_label_tight">Address</label>
					<input id="billing_address" name='billing[address]' value='{if $cart.billing_info.address}{$cart.billing_info.address}{elseif $populate_billing_info}{$cart.address}{/if}' class="field" />
				</div>
				
				<div class="row_odd">
					<label for="billing_address_2" class="field_label_tight">Address 2</label>
					<input id="billing_address_2" name='billing[address_2]' value='{if $cart.billing_info.address_2}{$cart.billing_info.address_2}{elseif $populate_billing_info}{$cart.address_2}{/if}' class="field" />
				</div>
				
				<div class="row_even">
					<label for="billing_city" class="field_label_tight">City</label>
					<input id="billing_city" name='billing[city]' value='{if $cart.billing_info.city}{$cart.billing_info.city}{elseif $populate_billing_info}{$cart.city}{/if}' class="field" />
				</div>
				
				<div class="row_odd">
					<label class="field_label_tight">Country</label>
					{$billingCountries}
				</div>
				
				<div class="row_even" id="billing_state_wrapper">
					<label class="field_label_tight">State</label>
					{$billingStates}
				</div>
				
				<div class="row_odd">
					<label for="billing_zip" class="field_label_tight">Zip Code</label>
					<input id="billing_zip" name='billing[zip]' value='{if $cart.billing_info.zip}{$cart.billing_info.zip}{elseif $populate_billing_info}{$cart.zip}{/if}' class="field" />
				</div>
				
				<div class="row_even">
					<label for="billing_phone" class="field_label_tight">Phone</label>
					<input id="billing_phone" name='billing[phone]' value='{if $cart.billing_info.phone}{$cart.billing_info.phone}{elseif $populate_billing_info}{$cart.phone}{/if}' class="field" />
				</div>
				
				<div class="row_odd">
					<label for="billing_email" class="field_label_tight">Email</label>
					<input id="billing_email" name='billing[email]' value='{if $cart.billing_info.email}{$cart.billing_info.email}{elseif $populate_billing_info}{$cart.email}{/if}' class="field" />
				</div>
			</div>
		</div>

		<div id="cart_right_column">
			<div class="content_box">
				<h2 class="title">Shipping Address</h2>

				<div>
					<input type="checkbox" id="shipping_copybilling" name="shipping[copy_billing]" value="1" checked="checked">
					<label for="shipping_copybilling">Same as billing address</label> 
				</div>

				{if $error_msgs.billing_email}
					<div class='field_error_box'>{$error_msgs.billing_email}</div>
				{/if}
				
				<div id="shipping_fields" style="display:none">
					<div class="row_even">
						<label for="shipping_firstname" class="field_label_tight">First Name</label>
						<input id="shipping_firstname" name='shipping[firstname]' value='{if $buyer.firstname}{$buyer.firstname}{elseif $populate_billing_info}{$cart.firstname}{/if}' class="field" />
					</div>
					
					<div class="row_odd">
						<label for="shipping_lastname" class="field_label_tight">Last Name</label>
						<input id="shipping_lastname" name='shipping[lastname]' value='{if $buyer.lastname}{$cart.billing_info.lastname}{elseif $populate_billing_info}{$cart.lastname}{/if}' class="field" />
					</div>
					
					<div class="row_even">
						<label for="shipping_address" class="field_label_tight">Address</label>
						<input id="shipping_address" name='shipping[address]' value='{if $cart.billing_info.address}{$cart.billing_info.address}{elseif $populate_billing_info}{$cart.address}{/if}' class="field" />
					</div>
					
					<div class="row_odd">
						<label for="shipping_address_2" class="field_label_tight">Address 2</label>
						<input id="shipping_address_2" name='shipping[address_2]' value='{if $cart.billing_info.address_2}{$cart.billing_info.address_2}{elseif $populate_billing_info}{$cart.address_2}{/if}' class="field" />
					</div>
					
					<div class="row_even">
						<label for="shipping_city" class="field_label_tight">City</label>
						<input id="shipping_city" name='shipping[city]' value='{if $cart.billing_info.city}{$cart.billing_info.city}{elseif $populate_billing_info}{$cart.city}{/if}' class="field" />
					</div>
					
					<div class="row_odd">
						<label class="field_label_tight">Country</label>
						{$shippingCountries}
					</div>
					
					<div class="row_even" id="billing_state_wrapper">
						<label class="field_label_tight">State</label>
						{$shippingStates}
					</div>
					
					<div class="row_odd">
						<label for="shipping_zip" class="field_label_tight">Zip Code</label>
						<input id="shipping_zip" name='shipping[zip]' value='{if $cart.billing_info.zip}{$cart.billing_info.zip}{elseif $populate_billing_info}{$cart.zip}{/if}' class="field" />
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		jQuery(function() {
			var shipToggle = jQuery("#copy_billing")

			shipToggle.on('change', function() {
				var isChecked = shipToggle.is(":checked")
				showShippingFields(!isChecked)
			})

			showShippingFields(!shipToggle.is(":checked"))
		})

		function showShippingFields(show) {
			var el = jQuery("#shipping_fields")
			if (show) {
				el.show()
			}
			else {
				el.hide()
			}
		}
		</script>
	</div>

	<div class="content_box">
		<h1 class="title">Order Summary</h1>
		<p class="page_instructions">This is the summary of items, if you need to make changes, go back to the cart view.</p>
		
		<div class="box_pad">
			<table style="width:100%" class="summary_table">
				<tr>
					<th>Product</th>
					<th>Price</th>
					<th>Shipping</th>
					<th>Qty</th>
					<th>Subtotal</th>
				</tr>
				
				{foreach $order.listings as $listing}
					<tr>
						<td>{$listing.title|urldecode}</td>
						<td>{$listing.price}</td>
						<td>{$listing.shipping}</td>
						<td>{$listing.cartqty}</td>
						<td>{$listing.subtotal}</td>
					</tr>
				{/foreach}

				<tr class="grandtotal">
					<td colspan="4">Grand Total</td>
					<td>{$order.total_price_display}</td>
			</table>
		</div>
	</div>
	
	<div class="clr"><br /></div>

	<h1 class="title">Message for Seller</h1>
	<div class="content_box_1" style="padding: 14px;">
		Enter any additional information/notes that should be passed along to the seller:
		<textarea name="additional_info" style="width:690px;"></textarea>
	</div>
	
	<div class="content_obetsyx">
		<h1 class="title">Payment Details</h1>
		<p class="page_instructions">Please select how you will pay for this order.</p>
		{if $no_free_cart}
			<p class="page_instructions">
				{$messages.500629}
			</p>
		{/if}
		{if $errors.choices_box}
			<div class="field_error_box">
				{$errors.choices_box}
			</div>
		{/if}
		
		<div id="payment_choices">
			<div class="payment_item">
				<div class="inline"> </div>
				<span class="inline">
					<input type="radio" value="paypal" checked="checked" name="c[payment_type]" id="paypal">
				</span>
				<label class="payment_label" for="paypal">
					<img alt="PayPal" src="geo_templates/default/external/images/paypal_logo.gif">
				</label>
			</div>

			<div class="payment_item">
				<div class="inline"> </div>
				<span class="inline">
					<input type="radio" value="money_order" name="c[payment_type]" id="money_order">
				</span>
							
				<label class="payment_label" for="money_order">Money Order</label>
			</div>
		</div>
		
		<div class="center">
			<input type="submit" value="Submit Order" class="button" /><br /><br />
			<a href="?a=ap&addon=ppStoreSeller&page=merchantCart" class="button">Back to Cart</a>
		</div>
	</div>
</form>