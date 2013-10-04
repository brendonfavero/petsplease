{* 7.2beta3-75-g2182f15 *}

{$admin_msgs}

<fieldset>
	<legend>Payment Gateway Settings for 
{if $group_name ne ""}
	Group {$group_name}
{else}
	Site-Wide
{/if}</legend>
	<form method='post' action='' id='frm_all_settings'>
		<input type="hidden" name="group" value="{$group}" id="payGroup" />
		<div id='table_settings'>
		{include file='payment_gateways/gateway_table.tpl'}
		</div>
		<div style="text-align: center">
			<input name='auto_save_ajax' value='Save' type='submit' class="saveAll" />
		</div>
	</form>
</fieldset>