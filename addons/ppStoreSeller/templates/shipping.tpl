{foreach from=$error_msgs item=err_msg}
	<div class="field_error_box">
		{$err_msg}
	</div>
{/foreach}
<form action="{$process_form_url}" method="post">
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
				<label for="firstname" class="field_label">First Name</label>
				<input id="firstname" name='c[firstname]' value='{if $buyer.firstname}{$buyer.firstname}{elseif $populate_billing_info}{$cart.firstname}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label for="lastname" class="field_label">Last Name</label>
				<input id="lastname" name='c[lastname]' value='{if $buyer.lastname}{$cart.billing_info.lastname}{elseif $populate_billing_info}{$cart.lastname}{/if}' class="field" />
			</div>
			
			<div class="row_even">
				<label for="address" class="field_label">Address</label>
				<input id="address" name='c[address]' value='{if $cart.billing_info.address}{$cart.billing_info.address}{elseif $populate_billing_info}{$cart.address}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label for="address_2" class="field_label">Address 2</label>
				<input id="address_2" name='c[address_2]' value='{if $cart.billing_info.address_2}{$cart.billing_info.address_2}{elseif $populate_billing_info}{$cart.address_2}{/if}' class="field" />
			</div>
			
			<div class="row_even">
				<label for="city" class="field_label">City</label>
				<input id="city" name='c[city]' value='{if $cart.billing_info.city}{$cart.billing_info.city}{elseif $populate_billing_info}{$cart.city}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label class="field_label">Country</label>
				{$countries}
			</div>
			
			<div class="row_even" id="billing_state_wrapper">
				<label class="field_label">State</label>
				{$states}
			</div>
			
			<div class="row_odd">
				<label for="zip" class="field_label">Zip Code</label>
				<input id="zip" name='c[zip]' value='{if $cart.billing_info.zip}{$cart.billing_info.zip}{elseif $populate_billing_info}{$cart.zip}{/if}' class="field" />
			</div>
			
			<div class="row_even">
				<label for="phone" class="field_label">Phone</label>
				<input id="phone" name='c[phone]' value='{if $cart.billing_info.phone}{$cart.billing_info.phone}{elseif $populate_billing_info}{$cart.phone}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label for="email" class="field_label">Email</label>
				<input id="email" name='c[email]' value='{if $cart.billing_info.email}{$cart.billing_info.email}{elseif $populate_billing_info}{$cart.email}{/if}' class="field" />
			</div>
		</div>
	</div>
	
	<div id="cart_right_column">
		<div class="content_box">
			<h2 class="title">Shipping Address</h2>
			<p class="page_instructions">
				This is the information that will be used for billing purposes. Please ensure that all of the information here is accurate.
			</p>
			
			{if $error_msgs.billing_email}
				<div class='field_error_box'>{$error_msgs.billing_email}</div>
			{/if}
			
			<div class="row_even">
				<label for="firstname" class="field_label">First Name</label>
				<input id="firstname" name='c[firstname]' value='{if $buyer.firstname}{$buyer.firstname}{elseif $populate_billing_info}{$cart.firstname}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label for="lastname" class="field_label">Last Name</label>
				<input id="lastname" name='c[lastname]' value='{if $buyer.lastname}{$cart.billing_info.lastname}{elseif $populate_billing_info}{$cart.lastname}{/if}' class="field" />
			</div>
			
			<div class="row_even">
				<label for="address" class="field_label">Address</label>
				<input id="address" name='c[address]' value='{if $cart.billing_info.address}{$cart.billing_info.address}{elseif $populate_billing_info}{$cart.address}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label for="address_2" class="field_label">Address 2</label>
				<input id="address_2" name='c[address_2]' value='{if $cart.billing_info.address_2}{$cart.billing_info.address_2}{elseif $populate_billing_info}{$cart.address_2}{/if}' class="field" />
			</div>
			
			<div class="row_even">
				<label for="city" class="field_label">City</label>
				<input id="city" name='c[city]' value='{if $cart.billing_info.city}{$cart.billing_info.city}{elseif $populate_billing_info}{$cart.city}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label class="field_label">Country</label>
				{$countries}
			</div>
			
			<div class="row_even" id="billing_state_wrapper">
				<label class="field_label">State</label>
				{$states}
			</div>
			
			<div class="row_odd">
				<label for="zip" class="field_label">Zip Code</label>
				<input id="zip" name='c[zip]' value='{if $cart.billing_info.zip}{$cart.billing_info.zip}{elseif $populate_billing_info}{$cart.zip}{/if}' class="field" />
			</div>
			
			<div class="row_even">
				<label for="phone" class="field_label">Phone</label>
				<input id="phone" name='c[phone]' value='{if $cart.billing_info.phone}{$cart.billing_info.phone}{elseif $populate_billing_info}{$cart.phone}{/if}' class="field" />
			</div>
			
			<div class="row_odd">
				<label for="email" class="field_label">Email</label>
				<input id="email" name='c[email]' value='{if $cart.billing_info.email}{$cart.billing_info.email}{elseif $populate_billing_info}{$cart.email}{/if}' class="field" />
			</div>
		</div>
	</div>

	<div class="content_box">
		<h1 class="title">Order Summary</h1>
		<p class="page_instructions">This is the summary of items, if you need to make changes, go back to the cart view.</p>
		
		<div class="box_pad">
			ORDER SUMMARY IS HERE
			{*include file='display_cart/index.tpl' view_only=1*}
			<div class="clr"></div>
		</div>
	</div>
	
	<div class="clr"><br /></div>
	
	<div class="content_box">
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