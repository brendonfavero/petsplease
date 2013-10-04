{* 6.0.7-3-gce41f93 *}
<div class="{cycle values='row_color1,row_color2'}">
	<div class="leftColumn">Starting Tokens Issued</div>
	<div class="rightColumn">
		<input type="text" name="tokens[group_starting_tokens_count]" value="{$group_starting_tokens_count}" size="3" />
	</div>
	<div class="clearColumn"></div>
</div>
<div class="{cycle values='row_color1,row_color2'}">
	<div class="leftColumn">Expire Starting Tokens</div>
	<div class="rightColumn">
		<input type="text" name="tokens[group_starting_tokens_expire_period]" value="{$group_starting_tokens_expire_period}" size="3" />
		<select name="tokens[group_starting_tokens_expire_period_units]">
			<option value="{$day}"{if $group_starting_tokens_expire_period_units==$day} selected="selected"{/if}>Days</option>
			<option value="{$year}"{if $group_starting_tokens_expire_period_units==$year} selected="selected"{/if}>Years</option>
		</select>
		After Registration
	</div>
	<div class="clearColumn"></div>
</div>