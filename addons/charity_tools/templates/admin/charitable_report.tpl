{* 7.2beta3-4-gb2b3265 *}
{$adminMsgs}
<fieldset>
	<legend>Report Timeframe</legend>
	<div>
		<form action="" method="post">
			Show Charitable Badge purchases for times 
			from: <input type="text" name="d[start_date]" id="startDate" class="dateInput" />
					<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="startDateCalButton" />
			to: <input type="text" name="d[end_date]" id="endDate" class="dateInput" />
					<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="endDateCalButton" />
			<div class="center"><input type="submit" value="Run Report" /></div>
		</form>
	</div>
</fieldset>

{foreach $badgeData as $id => $badge}
	<fieldset>
		<legend>{$badge.name} - {if $badge.region}{$badge.region}{else}All Regions{/if}</legend>
		<div>
			<table style="width: 100%;">
				<tr>
					<th>Listing ID</th>
					<th>Purchase Time</th>
					<th>Price</th>
				</tr>
				{foreach $purchases.$id as $p}
					<tr class='{cycle values="row_color1,row_color2"}'>
						<td style="text-align: center;">{$p.listing}</td>
						<td style="text-align: center;">{$p.time}</td>
						<td style="text-align: center;">{$p.price}</td>
					</tr>
				{/foreach}
			</table>
			<div class="center" style="font-weight: bold;">Total Collected: {$badge.total|displayPrice}</div>
		</div>
	</fieldset>
{/foreach}