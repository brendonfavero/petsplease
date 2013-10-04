{* 6.0.7-3-gce41f93 *}
{if $email != ''}
	<input type="hidden" name="paypal_allow_sb" value="0" />
	<label><input type="checkbox" name="paypal_allow_sb" value="1"{if $checked} checked="checked"{/if} /> {$messages.500185} ({$email})</label>
{elseif $messages.500243 != ''}
	<a href="{$myInfoLink}" onclick="window.open(this.href); return false;">{$messages.500243}</a>
{else}{*return nothing*}{/if}