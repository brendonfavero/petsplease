{* 7.4.3-54-g3786c12 *}
{$adminMessages}
<form action="" method="post">
	<fieldset>
		<legend>Force Subscriptions</legend>
		<div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn"><input type="checkbox" value="1" name="require_sub_all_users" {if $require_sub_all_users}checked="checked"{/if} /></div>
				<div class="rightColumn">Require all registered users to have an active subscription before accessing any pages</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	<div style="text-align: center;"><input type="submit" name="auto_save" value="Save" /></div>
</form>