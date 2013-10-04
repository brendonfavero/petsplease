{* 6.0.7-60-g4a6c66e *}
<fieldset class="admin_home_stat_box">
	<legend>Users</legend>
	<table style="width: 100%; height: 110px;" cellspacing="0" cellpadding="3">
		<tr class='row_color{cycle reset=true values="2,1"}'>
			<td class="stats_txt2">
				Registrations Awaiting Approval:
			</td>
			<td class="stats_txt3">
				{if $stats.users.registrations > 0}<a href="index.php?page=register_unapproved&amp;mc=registration_setup"><span class="admin_home_important">{/if}
					{$stats.users.registrations}
				{if $stats.users.registrations > 0}</span></a>{/if}
			</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">
				Total Registered Users:
			</td>
			<td class="stats_txt3">
				<a href="index.php?page=users_list&amp;mc=users">{$stats.users.total}</a>
			</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">
				New Registrations (Last 24 hours):
			</td>
			<td class="stats_txt3">
				{$stats.users.last1}
			</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">
				New Registrations (Last 7 days):
			</td>
			<td class="stats_txt3">
				{$stats.users.last7}
			</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">
				New Registrations (Last 30 days):
			</td>
			<td class="stats_txt3">
				{$stats.users.last30}
			</td>
		</tr>
	</table>
</fieldset>