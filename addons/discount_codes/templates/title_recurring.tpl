{* 6.0.7-3-gce41f93 *}
{if $inCart}
	<label>
		{$msgs.inputLabel}
		&nbsp;<input type='text' size='10' name='discount_code' value='{$discount_code}' onclick="$('checkout_clicked').value='click';" />
	</label>
	<input type='submit' value='{$msgs.updateLabel}' />
	<div class="clr"></div>
{elseif $discount_code}
	{$msgs.inputLabelAlt} ({$discount_code})
{/if}
{if $error}
<span class="error">
	{$msgs.errorMsg}
</span>
{/if}