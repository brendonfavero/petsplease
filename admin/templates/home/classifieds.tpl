{* 6.0.7-60-g4a6c66e *}

<fieldset class="admin_home_stat_box">
	<legend>Classified Stats</legend>
	<table style="width: 100%; height: 80px;" cellspacing="0" cellpadding="3">
		<tr class='row_color{cycle reset=true values="2,1"}'>
			<td class="stats_txt2">Total Classified Count:</td>
			<td class="stats_txt3">{$stats.classifieds.count}</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">Total Users with Classifieds:</td>
			<td class="stats_txt3">{$stats.classifieds.users}</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">Total Viewed Count:</td>
			<td class="stats_txt3">{$stats.classifieds.viewed}</td>
			</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">	
				Total Awaiting Approval:
			</td>
			<td class="stats_txt3">
				{if $stats.classifieds.unapproved > 0}<a href="index.php?page=orders_list_items&amp;narrow_item_status=pending&amp;narrow_item_type=classified"><span class="admin_home_important">{/if}
				{$stats.classifieds.unapproved}
				{if $stats.classifieds.unapproved > 0}</span></a>{/if}
			</td>
		</tr>
	</table>
</fieldset>