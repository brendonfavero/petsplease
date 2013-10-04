{* 7.2.3-6-g175a8e1 *}
{$admin_messages}
<form action="index.php?mc=email_config&page=email_notify_config" method="post">
<fieldset>
	<legend>Admin Notifications</legend>
	<div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Register Complete</div>
			<div class="rightColumn">
				<input type="checkbox" name="send_register_complete_email_admin" value="1" {if $send_register_complete_email_admin}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Registration Verification System<br />
			<a href="index.php?page=sections_registration_edit_text&b=20&l=1">Verify E-Mail Text</a></div>
			<div class="rightColumn">
				<select name="email_verify_system">
					<option value="disabled|disabled" {if !$use_email_verification_at_registration && !$admin_approves_all_registration}selected="selected"{/if}>Disable Verification System</option>
					<option value="enabled|disabled" {if $use_email_verification_at_registration && !$send_register_attempt_email_admin}selected="selected"{/if}>Verify E-Mail, with Notify ADMIN on Attempt OFF</option>
					<option value="enabled|enabled" {if $use_email_verification_at_registration && $send_register_attempt_email_admin}selected="selected"{/if}>Verify E-Mail, with Notify ADMIN on Attempt ON</option>
					<option value="admin_approve" {if $admin_approves_all_registration}selected="selected"{/if}>Admin Approves All Registrations</option>
				</select>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Successful Listing</div>
			<div class="rightColumn">
				<input type="checkbox" name="send_admin_placement_email" value="1" {if $send_admin_placement_email}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Notify When Manual Payment Chosen</div>
			<div class="rightColumn">
				<input type="checkbox" name="user_set_hold_email" value="1" {if $user_set_hold_email}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Order Item Awaiting Approval</div>
			<div class="rightColumn">
				<input type="checkbox" name="admin_notice_item_approval" value="1" {if $admin_notice_item_approval}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Notify Before Listing or Subscription Expires
			</div>
			<div class="rightColumn">
				<input type="checkbox" name="send_admin_end_email" value="1" {if $send_admin_end_email}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Notify When Listing is Edited
			</div>
			<div class="rightColumn">
				<input type="checkbox" name="admin_email_edit" value="1" {if $admin_email_edit}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
	</div>
</fieldset>
<fieldset>
	<legend>User Notifications</legend>
	<div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				<a href="index.php?page=sections_registration_edit_text&b=21&l=1">Register Complete</a>
			</div>
			<div class="rightColumn">
				<input type="checkbox" name="send_register_complete_email_client" value="1" {if $send_register_complete_email_client}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		{if $is_order_notify}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					<a href="index.php?page=sections_listing_process_edit_text&b=10207&l=1">Order Approved</a>{$tooltips.order}
				</div>
				<div class="rightColumn">
					<input type="checkbox" name="notify_user_order_approved" value="1" {if $notify_user_order_approved}checked="checked"{/if} />
				</div>
				<div class="clearColumn"></div>
			</div>
		{/if}
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				<a href="index.php?page=sections_listing_process_edit_text&b=51&l=1">New Listing Approved &amp; Live</a>
			</div>
			<div class="rightColumn">
				<input type="checkbox" name="send_successful_placement_email" value="1" {if $send_successful_placement_email}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn"><a href="index.php?page=sections_listing_process_edit_text&b=10206&l=1">Edit Listing Approved &amp; Live</a></div>
			<div class="rightColumn">
				<input type="checkbox" name="notify_user_edit_approved" value="1" {if $notify_user_edit_approved}checked="checked"{/if} />
			</div>
			<div class="clearColumn"></div>
		</div>
		{foreach $exp_settings as $setting => $info}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					{if $info.page_id}<a href="index.php?page=sections_listing_process_edit_text&b={$info.page_id}&l=1">{/if}
						{$info.label}
					{if $info.page_id}</a>{/if}
				</div>
				<div class="rightColumn">
					<input type="text" name="{$setting}" size="5" value="{$info.adjustedExpire}" /> 
					<select name="{$setting}_unit">
						<option value="{$day}"{if $info.timeUnit == $day} selected="selected"{/if}>Days</option>
						<option value="{$hour}"{if $info.timeUnit == $hour} selected="selected"{/if}>Hours</option>
						<option value="{$minute}"{if $info.timeUnit == $minute} selected="selected"{/if}>Minutes</option>
						<option value="1"{if $info.timeUnit == 1} selected="selected"{/if}>Seconds</option>
					</select>
					Warning (0 to disable)
				</div>
				<div class="clearColumn"></div>
			</div>
		{/foreach}
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Re-send Listing Expiration Warnings</div>
			<div class="rightColumn">
				<select name="send_ad_expire_frequency">
					{foreach from=$email_expire_frequencies item="period" key="secs"}
						<option value="{$secs}" {if $send_ad_expire_frequency == $secs}selected="selected"{/if}>{$period}</option>
					{/foreach}
				</select>
			</div>
			<div class="clearColumn"></div>
		</div>

		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn"><a href="index.php?page=sections_user_mgmt_edit_text&b=87&l=1">Subscription Expires Soon</a></div>
			<div class="rightColumn">
				<label><input name='subscription_expire_period_notice' size='4' value='{$subscription_expire_period_notice|string_format:"%d"}' /> Day Warning (0 to disable)</label>
			</div>
			<div class="clearColumn"></div>
		</div>
		{if $send_balance_reminder_button}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					<a href="index.php?page=sections_user_mgmt_edit_text&b=177&l=1">Negative account balance reminder</a>
				</div>
				<div class="rightColumn">
					<label>
						Every
						<input name="negative_balance_reminder" value="{$negative_balance_reminder}" size="3" /> Days (0 to disable)
					</label>
					{$send_balance_reminder_button}
				</div>
				<div class="clearColumn"></div>
			</div>
		{/if}
		
		{if $is_a}	
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Seller Notification: Auction Unsuccessful</div>
				<div class="rightColumn">
					<input type="checkbox" name="notify_seller_unsuccessful_auction" value="1" {if $notify_seller_unsuccessful_auction}checked="checked"{/if} />
				</div>
				<div class="clearColumn"></div>
			</div>
		{/if}
		
	</div>
</fieldset>
<fieldset>
	<legend>Prevent e-mail Flooding</legend>
	<div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Contact Seller{$tooltips.flood_contact_seller}</div>
			<div class="rightColumn">
				<label><input type='text' name='contact_seller_limit' size='4' value='{$contact_seller_limit|string_format:"%d"}' /> messages per sender, per hour (0 to disable)</label>
			</div>
			<div class="clearColumn"></div>
		</div>
	</div>
</fieldset>
<div style="text-align:center"><input type="submit" name="auto_save" value="Save" /></div>
</form>