{* 6.0.7-3-gce41f93 *}
{if !$brief}
<fieldset>
	<legend>Get Order{if $ent} or Recurring Billing{/if}</legend>
	<div>
		<div class="page_note"> Find an order according to ID #:</div>{/if}
		<form action="index.php?page=orders_list_order_details" method="post">
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Order ID #
				</div>
				<div class="rightColumn">
					<input type='text' size='8' name='orderId' style="font-size: 8pt;" /> <input type="submit" value="Get Order" style="font-size: 8pt;" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Invoice ID #
				</div>
				<div class="rightColumn">
					<input type='text' size='8' name='invoiceId' style="font-size: 8pt;" /> <input type="submit" value="Get Order" style="font-size: 8pt;" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Invoice Transaction ID #
				</div>
				<div class="rightColumn">
					<input type='text' size='8' name='transactionId' style="font-size: 8pt;" /> <input type="submit" value="Get Order" style="font-size: 8pt;" />
				</div>
				<div class="clearColumn"></div>
			</div>
		</form>
		{if $ent}
			<form action="index.php?page=recurring_billing_details" method="post">
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">
						Recurring Billing Internal ID #
					</div>
					<div class="rightColumn">
						<input type='text' size='8' name='id' style="font-size: 8pt;" /> <input type="submit" value="Get Recurring Billing" style="font-size: 8pt;" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">
						Recurring Billing Gateway ID #
					</div>
					<div class="rightColumn">
						<input type='text' size='8' name='altId' style="font-size: 8pt;" /> <input type="submit" value="Get Recurring Billing" style="font-size: 8pt;" />
					</div>
					<div class="clearColumn"></div>
				</div>
			</form>
		{/if}
{if !$brief}
	</div>
</fieldset>{/if}