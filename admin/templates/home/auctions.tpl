{* 6.0.7-60-g4a6c66e *}

<fieldset class="admin_home_stat_box">
	<legend>Auction Stats</legend>
	<table style="width: 100%; height: 80px;" cellspacing="0" cellpadding="3">
		<tr class='row_color{cycle reset=true values="2,1"}'>
			<td class="stats_txt2">Total Auction Count:</td>
			<td class="stats_txt3">{$stats.auctions.count}</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">Total Users with Auctions:</td>
			<td class="stats_txt3">{$stats.auctions.users}</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">Total Viewed Count:</td>
			<td class="stats_txt3">{$stats.auctions.viewed}</td>
			</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">
				Total Awaiting Approval:
			</td>					
			<td class="stats_txt3">
				{if $stats.auctions.unapproved > 0}<a href="index.php?page=orders_list_items&amp;narrow_item_status=pending&amp;narrow_item_type=auction"><span class="admin_home_important">{/if}
					{$stats.auctions.unapproved}
				{if $stats.auctions.unapproved > 0}</span></a>{/if}
			</td>
		</tr>
	</table>
</fieldset>