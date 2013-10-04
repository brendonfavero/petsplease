{* 6.0.7-60-g4a6c66e *}

<fieldset class="admin_home_stat_box2">
	<legend>Order Status</legend>
	<div>
	<table style="width: 100%; height: 80px;" cellspacing="0" cellpadding="0">
		<tr>
			<td style="width: 48%;">
				<table cellspacing="0" cellpadding="3">
					<tr class='row_color{cycle reset=true values="2,1"}'>
						<td class="stats_txt2" style="width: 90%;">
							Total Orders:
						</td>
						<td class="stats_txt3" style="width: 10%; min-width: 15px;">
							<div style="width: 40px;">
								{if $stats.orders.total > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=all&amp;narrow_gateway_type=all">{/if}
									{$stats.orders.total}
								{if $stats.orders.total > 0}</a>{/if}
							</div>
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>
						<td class="stats_txt2">
							Active Orders:
						</td>
						<td class="stats_txt3">
							{if $stats.orders.active > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=active&amp;narrow_gateway_type=all">{/if}
								{$stats.orders.active}
							{if $stats.orders.active > 0}</a>{/if}	
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>			
						<td class="stats_txt2">
							Orders Awaiting Payment:
						</td>
						<td class="stats_txt3">
							{if $stats.orders.pending > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=pending&amp;narrow_gateway_type=all">{/if}
								{$stats.orders.pending}
							{if $stats.orders.pending > 0}</a>{/if}
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>
						<td class="stats_txt2">
							Orders Awaiting Approval:
						</td>
						<td class="stats_txt3">
							{if $stats.orders.pending_admin > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=pending_admin&amp;narrow_gateway_type=all">{/if}
								{$stats.orders.pending_admin}
							{if $stats.orders.pending_admin > 0}</a>{/if}
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>
						<td class="stats_txt2">
						Incomplete Orders:
						</td>
						<td class="stats_txt3">
							{if $stats.orders.incomplete > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=incomplete&amp;narrow_gateway_type=all">{/if}
								{$stats.orders.incomplete}
							{if $stats.orders.incomplete > 0}</a>{/if}	
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 4%">&nbsp;</td>	
			<td style="width: 48%">	
				<table cellspacing="0" cellpadding="3">
					<tr class='row_color{cycle reset=true values="2,1"}'>
						<td class="stats_txt2" style="width: 90%;">
							Total Items:
						</td>
						<td class="stats_txt3" style="width: 10%; min-width: 15px;">
							<div style="width: 40px;">
								{if $stats.orders.total_items > 0}<a href="index.php?page=orders_list_items&amp;narrow_item_status=all&amp;narrow_item_type=all">{/if}				
									{$stats.orders.total_items}
								{if $stats.orders.total_items > 0}</a>{/if}
							</div>				
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>
						<td class="stats_txt2">
							Items Awaiting Approval:
						</td>
						<td class="stats_txt3" style="width: 10%; min-width: 15px;">
							<div style="width: 40px;">
								{if $stats.orders.waiting_items > 0}<a href="index.php?page=orders_list_items&amp;narrow_item_status=pending&amp;narrow_item_type=all">{/if}				
									{$stats.orders.waiting_items}
								{if $stats.orders.waiting_items > 0}</a>{/if}
							</div>				
						</td>
					</tr>
					<tr class='row_color{cycle reset=true values="2,1"}'>
						<td class="stats_txt2">
							Suspended Orders:
						</td>
						<td class="stats_txt3" style="width: 10%; min-width: 15px;">
							<div style="width: 40px;">
								{if $stats.orders.suspended > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=suspended&amp;narrow_gateway_type=all">{/if}				
									{$stats.orders.suspended}
								{if $stats.orders.suspended > 0}</a>{/if}
							</div>				
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>
						<td class="stats_txt2">
							Canceled Orders:
						</td>
						<td class="stats_txt3">
							{if $stats.orders.canceled > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=canceled&amp;narrow_gateway_type=all">{/if}
								{$stats.orders.canceled}
							{if $stats.orders.canceled > 0}</a>{/if}
						</td>
					</tr>
					<tr class='row_color{cycle values="2,1"}'>			
						<td class="stats_txt2">
							Fraud Orders:
						</td>
						<td class="stats_txt3">
							{if $stats.orders.fraud > 0}<a href="index.php?page=orders_list&amp;narrow_order_status=fraud&amp;narrow_gateway_type=all">{/if}
								{$stats.orders.fraud}
							{if $stats.orders.fraud > 0}</a>{/if}
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</div>
</fieldset>