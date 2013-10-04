{* 6.0.7-3-gce41f93 *}
{$admin_messages}

<script type="text/javascript">
//<![CDATA[
	Event.observe(window, 'load', function () {
		$('verify_accounts').observe('click', function () {
			$('verify_settings')[(this.checked? 'show':'hide')]();
		});
	});
//]]>
</script>

<form action="" method="post">
	<fieldset>
		<legend>Main Settings</legend>
		<div>
			{$main_settings}
		</div>
	</fieldset>
	<fieldset>
		<legend>Verify Account Settings</legend>
		<div>
			<p class="page_note">
				Note that there is a "Verify Account" order item, that charges $1 to verify the user's account.  That order item has
				additional price plan specific settings in <strong>Pricing > Price Plans Home > [edit button] > Cost Specifics</strong>,
				such as turning the item off, or changing the price.
				<br /><br />
				Those price plan item settings will only be available if <strong>Enable account verification system</strong> is enabled (checked)
				below.
			</p>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"><input type="checkbox" name="verify_accounts" id="verify_accounts" value="1" {if $verify_accounts}checked="checked"{/if} /></div>
				<div class="rightColumn">
					Enable account verification system (anti-SPAM measure designed for "mostly free" sites)
				</div>
				<div class="clearColumn"></div>
			</div>
			<div id="verify_settings"{if !$verify_accounts} style="display: none;"{/if}>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn"><input type="checkbox" name="nonverified_require_approval" value="1" {if $nonverified_require_approval}checked="checked"{/if} /></div>
					<div class="rightColumn">
						All order items "require admin approval" for Non-verified accounts (including anonymous listings)
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn"><input type="checkbox" name="auto_verify_with_payment" value="1" {if $auto_verify_with_payment}checked="checked"{/if} /></div>
					<div class="rightColumn">
						Verify account when user pays for something
					</div>
					<div class="clearColumn"></div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Boxes</legend>
		<div class="page_note">
			Below is the list of all the information "boxes" that can be shown on the My Account Home Page. Select the ones you want to show.
		</div>
		<div>
			{$boxes}
		</div>
	</fieldset>
	
	<div style="width: 100%; margin: 10px auto; text-align: center;"><input type="submit" name="auto_save" value="Save" /></div>
</form>