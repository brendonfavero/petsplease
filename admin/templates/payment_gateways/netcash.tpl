{* 6.0.7-3-gce41f93 *}
	{$commonAdminOptions}
	
	<div class='row_color{cycle values="1,2"}'>
		<div class='leftColumn'>
			Username {$tooltips.username}
		</div>
		<div class='rightColumn'>
			<input type='text' name="{$payment_type}[username]" value="{$values.username}" />
		</div>
		<div class='clearColumn'></div>
	</div>
	<div class='row_color{cycle values="1,2"}'>
		<div class='leftColumn'>
			Password {$tooltips.password}
		</div>
		<div class='rightColumn'>
			<div class='rightColumn'>
				<input type='text' name="{$payment_type}[password]" value="{$values.password}" />
			</div>
		</div>
		<div class='clearColumn'></div>
	</div>
	<div class='row_color{cycle values="1,2"}'>
		<div class='leftColumn'>
			PIN {$tooltips.pin}
		</div>
		<div class='rightColumn'>
			<input type='text' name="{$payment_type}[pin]" value="{$values.pin}" />
		</div>
		<div class='clearColumn'></div>
	</div>
	<div class='row_color{cycle values="1,2"}'>
		<div class='leftColumn'>
			Terminal Number {$tooltips.terminal}
		</div>
		<div class='rightColumn'>
			<input type='text' name="{$payment_type}[terminal]" value="{$values.terminal}" />
		</div>
		<div class='clearColumn'></div>
	</div>
	
	<table width=100% align=center>
		<tr>
			<td colspan=2 class=col_hdr_left>Additional Setup Instructions
			</td>
		</tr>
		<tr>
			<td colspan=2 valign=top class=medium_font><b>Return URLs:</b><Br>
				You must specify the URL for users to be returned to after completeing their transaction on Netcash's site.<br />
				This is done in your Netcash back office, at the location: "credit cards > Credit Service Administration > Adjust Gateway Defaults."<br />
				 You must set BOTH the accept and reject pages to: <strong>{$responseURL}</strong>
			</td>
		</tr>
		<tr>
			<td colspan=2 valign=top class=medium_font><b>Testing Mode</b><Br>
				For the Netcash gateway, "testing mode" must be enabled in your Netcash back office control panel.<br />
				The "Account Status" switch above will have no effect.
			</td>
		</tr>
	</table>