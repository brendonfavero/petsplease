{* 7.2beta1-77-g92294f8 *}


<div class="content_box">
	<h1 class="title">{$messages.102437}</h1>
	<h1 class="subtitle">{$title}</h1>
	<form action="{$formTarget}" method="post">
		{if $auction_type == 'buy_now'}
			<p class="page_instructions">{$messages.102442}</p>
			
			<div class="{cycle values='row_even,row_odd'}">
				<label class="field_label">{if $verify}{$messages.500237}{else}{$messages.102443}{/if}</label>
				<span class="price">{$price}</span>
				{if $price_applies=='item' && $max_quantity>1}
					{$messages.502105}
				{elseif $price_applies=='lot' && $max_quantity>1}
					{$messages.502106}
				{/if}
			</div>
			<div class="{cycle values='row_even,row_odd'}">
				<label class="field_label">
					{if $verify}{$messages.502108}{else}{$messages.502107}{/if}
				</label>
				{if !$verify && $price_applies=='item'&&$max_quantity > 1}
					<input type="text" name="c[bid_quantity]" value="{$quantity}" size="7" class="field" />
				{else}
					{if $price_applies=='lot' && $quantity>1}{$messages.502109}{/if}
					{$quantity}
					<input type="hidden" name="c[bid_quantity]" value="{$quantity}" />
				{/if}
			</div>
			
			<br />
			<div class="center">
				<input type="hidden" name="c[bid_amount]" value="{$hidden_price}" />
				<input type="hidden" name="d" value="1" />
				<input type="submit" name="c[buy_now_bid]" class="button" value="{if $verify}{$messages.500238}{else}{$messages.102444}{/if}" />
			</div>
		{elseif $auction_type == 'dutch'}
			<p class="page_instructions">{$messages.102446}</p>
			
			<div class="{cycle values='row_even,row_odd'}">
				<label for="c[bid_quantity]" class="field_label">{if $verify}{$messages.500240}{else}{$messages.102445}{/if}</label>
				{if $verify}
					{$quantity}<input type="hidden" name="c[bid_quantity]" value="{$quantity}" />
				{else}
					<input type="text" size="7" maxsize="7" name="c[bid_quantity]" id="c[bid_quantity]" value="1" class="field" />
				{/if}
			</div>
			<div class="{cycle values='row_even,row_odd'}">
				<label for="c[bid_amount]" class="field_label">{if $verify}{$messages.500239}{else}{$messages.102440}{/if}</label>
				{if $verify}
					{$price}<input type="hidden" name="c[bid_amount]" value="{$hidden_price}" />
				{else}
					{$precurrency} <input type="text" name="c[bid_amount]" id="c[bid_amount]" value="{$bid_to_show}" class="field" /> {$postcurrency}
				{/if}	
			</div>
			
			<br />
			<div class="center">
				<input type="submit" value="{if $verify}{$messages.500241}{else}{$messages.102439}{/if}" class="button" />
			</div>
		{elseif $auction_type == 'reverse'}
			<p class="page_instructions">{$messages.500987}</p>
			
			<div class="{cycle values='row_even,row_odd'}">
				<label for="c[bid_amount]" class="field_label">{if $verify}{$messages.500989}{else}{$messages.500988}{/if}</label>
				{if $verify}
					{$price}<input type="hidden" name="c[bid_amount]" value="{$hidden_price}" />
				{else}
					{$precurrency} <input type="text" name="c[bid_amount]" id="c[bid_amount]" value="{$bid_to_show}" class="field" /> {$postcurrency}
				{/if}	
			</div>
			
			<br />
			<div class="center">
				<input type="submit" value="{if $verify}{$messages.500991}{else}{$messages.500990}{/if}" class="button" />
			</div>
		{elseif $auction_type == 'standard'}
			<p class="page_instructions">{$messages.102438}</p>
			
			<div class="{cycle values='row_even,row_odd'}">
				<label for="c[bid_amount]" class="field_label">{if $verify}{$messages.500242}{else}{$messages.102440}{/if}</label>
				{if $verify}
					{$price}<input type="hidden" name="c[bid_amount]" value="{$hidden_price}" />
				{else}
					{$precurrency} <input type="text" name="c[bid_amount]" id="c[bid_amount]" value="{$bid_to_show}" class="field" /> {$postcurrency}
				{/if}	
			</div>
			
			<br />
			<div class="center">
				<input type="submit" value="{if $verify}{$messages.500236}{else}{$messages.102439}{/if}" class="button" />
			</div>
		{/if}
	</form>
</div>

<br />

<div class="center"><a href="{$auctionLink}" class="button">{$messages.103055}</a></div>