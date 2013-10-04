{* 6.0.7-3-gce41f93 *}
<div class="{cycle values="row_color1,row_color2"}">
	<div class="leftColumn">Enabled{$tooltip_allowed}</div>
	<div class="rightColumn">
		<input type="checkbox" name="storefront_subscription[enabled]"
			id="storefront_subscription[enabled]" value="1"
			{if $enabled}checked="checked"{/if}
			{if !$choices OR count($choices) == 0}disabled="disabled"{/if}
			onclick="toggleSubscriptionDisplay();" />
		{if $choices}
			<span class='medium_error_font' id="needsPeriodError">Please attach subscription periods</span>
		{else}
			<a class='medium_error_font' href="?page=storefront_subscription_choices">Please Add Subscription Periods</a>
		{/if}
	</div>
	<div class="clearColumn"></div>
</div>
<div class="{cycle values="row_color1,row_color2"}" id="storefrontChoices">
	<div class="leftColumn">Attach Storefront Subscription Periods{$tooltip_subscriptions}</div>
	<div class="rightColumn">
		{foreach from=$choices item="choice"}
		<label><input type="checkbox" class="storefrontPeriod" onclick="checkSubscriptionPeriods()"
			name="storefront_subscription[storefront_periods][{$choice.period_id}]"
			id="storefront_subscription[storefront_periods][{$choice.period_id}]" value="{$choice.period_id}"
			{if $periods[$choice.period_id]}checked="checked" {/if}/>{$choice.display_value} - {$choice.amount|displayPrice}</label>
			<br />
		{/foreach}
	</div>
	<div class="clearColumn"></div>
</div>