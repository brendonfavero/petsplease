{* 6.0.7-3-gce41f93 *}
{if $edit eq 1}
	<label>
		{$messages.500313}: {$precurrency}<input type='text' name='account_balance_add' value='{$price}' size="4" class="field" />{$postcurrency}
		{* (Current Balance: <em>{$current_balance|displayPrice}</em>) *}
	</label>
	
	<input type="submit" value="{$messages.500589}" class="button" onclick="$('checkout_clicked').value='click';" />
{else}
	{$messages.500313}
{/if}