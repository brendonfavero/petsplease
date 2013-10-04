{* 7.1beta4-46-gbdcda59 *}
{$admin_msgs}
<fieldset>
	<legend>Generate Revenue Report</legend>
	<div>
		<p class="page_note">
			Select one or more user groups, and enter a date range to obtain a report of total revenue from members of the selected group(s) during the selected date range.<br />
			<br />
			<strong>NOTE:</strong> If you do not select at least one user group, all user groups will be reported. If you do not select a valid date range, the last 30 days will be reported.
		</p>
	
		<form action="" method="post">
	
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">User Group</div>
				<div class="rightColumn">
					<select name="d[usergroups][]" multiple="multiple">
						{foreach from=$groups item="groupName" key="groupId"}
							<option value="{$groupId}">{$groupName}</option>
						{/foreach}
					</select>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Start Date</div>
				<div class="rightColumn">
					<input type="text" name="d[start_date]" id="startDate" class="dateInput" />
					<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="startDateCalButton" />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">End Date</div>
				<div class="rightColumn">
					<input type="text" name="d[end_date]" id="endDate" class="dateInput" />
					<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="endDateCalButton" />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Output as CSV</div>
				<div class="rightColumn">
					<input type="checkbox" name="d[as_csv]" value="1" />
				</div>
				<div class="clearColumn"></div>
			</div>
		
		<div style="text-align: center;">
			<input type="submit" value="Submit" name="auto_sv" />
		</div>
		
		</form>
	</div>
</fieldset>

{if $report !== false}
	<fieldset>
		<legend>Revenue for selected group(s) from {$report_start} to {$report_end} for {$classifieds_url}</legend>
		<div>
			{foreach from=$report item="group"}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">{$group.name}</div>
					<div class="rightColumn">{$group.total} ({$group.numListings} listings)</div>
					<div class="clearColumn"></div>
				</div>
			{/foreach}
		</div>
	
	</fieldset>	
{/if}