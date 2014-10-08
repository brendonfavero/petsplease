{* 6.0.7-3-gce41f93 *}
{$error_messages}
<form action='index.php?page=users_restart_ad&amp;b={$listing.id}' method='post'>
	<fieldset>
		<legend>{if $listing.live}Reset{else}Restart &amp; Upgrade Expired{/if} Listing Extras</legend>
		<div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Listing:</div>
				<div class="rightColumn">({$listing.id}) {$listing.title|fromDB}</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Action:</div>
				<div class="rightColumn">
					{if $listing.live}
						<input type='hidden' name='c[live]' value='1' />
						Alter Live 
						{if $listing.item_type == 2}
							{if $listing.auction_type==1}
								Auction
							{elseif $listing.auction_type==2}
								Dutch Auction
							{elseif $listing.auction_type==3}
								Reverse Auction
							{/if}
						{else}
							Classified
						{/if}
					{else}
						<input type='hidden' name='c[live]' value='0' />
						{if $listing.item_type == 2}
							Renew Expired 
							{if $listing.auction_type==1}
								Auction
							{elseif $listing.auction_type==2}
								Dutch Auction
							{elseif $listing.auction_type==3}
								Reverse Auction
							{/if} (creates copy of original)
						{else}
							Renew Expired Classified
						{/if}
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Start Day</div>
				<div class="rightColumn">
					{if $listing.live}
						{html_select_date time=$listing.date field_array='c[date]' prefix='' year_as_text=true} at {html_select_time time=$listing.ends field_array='c[date]' prefix='' use_24_hours=0}
					{else}
						{html_select_date field_array='c[date]' prefix='' year_as_text=true} at {html_select_time field_array='c[date]' prefix='' use_24_hours=0}
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $listing.item_type == 2}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Bidding Starts</div>
					<div class="rightColumn">
						{if $listing.live}
							{html_select_date time=$listing.start_time field_array='c[start_time]' prefix='' year_as_text=true} at {html_select_time time=$listing.start_time field_array='c[start_time]' prefix='' use_24_hours=0}
						{else}
							{html_select_date field_array='c[start_time]' prefix='' year_as_text=true} at {html_select_time field_array='c[start_time]' prefix='' use_24_hours=0}
						{/if}
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">New Ending Day</div>
				<div class="rightColumn">
					{if $listing.live}
						{html_select_date time=$listing.ends field_array='c[ends]' prefix='' year_as_text=true} at {html_select_time time=$listing.ends field_array='c[ends]' prefix='' use_24_hours=0}
					{else}
						{html_select_date field_array='c[ends]' prefix='' year_as_text=true} at {html_select_time field_array='c[ends]' prefix='' use_24_hours=0}
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $listing.item_type == 2}
				<script type='text/javascript'>{literal}
					function allow_price_change()
					{
						if ($('remove_bids')) {
							if ( $('remove_bids').checked ){
								$('bid_fields').show();
							} else {
								$('bid_fields').hide();
							}
						}
						if ($('buy_now_only')) {
							if ($('buy_now_only').checked) {
								$('not_buy_now_only_fields').hide();
							} else {
								$('not_buy_now_only_fields').show();
							}
						}
					}
					Event.observe(window,'load',function() { 
						if ($('remove_bids')) {
							$('remove_bids').observe('click', function () { allow_price_change(); });
						}
						if ($('buy_now_only')) {
							$('buy_now_only').observe('click', function () { allow_price_change(); });
						}
						allow_price_change();
					});
				{/literal}</script>
				{if $listing.live}
					<div class="{cycle values="row_color1,row_color2"}">
						<div class="leftColumn">Remove Current Bids?</div>
						<div class="rightColumn">
							<input type='checkbox' name='c[remove_current_bids]' id='remove_bids' value='1' />
						</div>
						<div class="clearColumn"></div>
					</div>
				{else}
					<input type='hidden' name='c[remove_current_bids]' value='1' />
				{/if}
				<div id="bid_fields" style="display: none;">
					<div id="not_buy_now_only_fields">
						<div class="{cycle values="row_color1,row_color2"}">
							<div class="leftColumn">Starting Bid</div>
							<div class="rightColumn">
								<input type='text' name='c[starting_bid]' value='{$listing.starting_bid}' />
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values="row_color1,row_color2"}">
							<div class="leftColumn">Reserve Price</div>
							<div class="rightColumn">
								<input type='text' name='c[reserve_price]' value='{$listing.reserve_price}' />
							</div>
							<div class="clearColumn"></div>
						</div>
					</div>
					{if $listing.auction_type != 2}
						<div class="{cycle values="row_color1,row_color2"}">
							<div class="leftColumn">Buy Now Only?</div>
							<div class="rightColumn">
								<input type='checkbox' name='c[buy_now_only]' id='buy_now_only' {if $listing.buy_now_only}checked="checked"{/if} value='1' />
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values="row_color1,row_color2"}">
							<div class="leftColumn">Buy Now Price</div>
							<div class="rightColumn">
								<input type='text' name='c[buy_now]' value='{$listing.buy_now}' />
							</div>
							<div class="clearColumn"></div>
						</div>
					{/if}
					<div class="{cycle values="row_color1,row_color2"}">
						<div class="leftColumn">Auction Quantity</div>
						<div class="rightColumn">
							<input type='text' name='c[quantity]' value='{$listing.quantity}' />
						</div>
						<div class="clearColumn"></div>
					</div>
				</div>
			{/if}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Reset Viewed Count</div>
				<div class="rightColumn">
					<input type='checkbox' name='c[reset_viewed_count]' value='1' />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Sold</div>
				<div class="rightColumn">
					<input type='checkbox' {if $listing.sold_displayed}checked="checked"{/if} name='c[sold_displayed]' value='1' />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Featured Listing</div>
				<div class="rightColumn">
					<input type='checkbox' {if $listing.featured_ad}checked="checked"{/if} name='c[featured_ad]' value='1' />
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $is_ent}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Featured Listing Level 2</div>
					<div class="rightColumn">
						<input type='checkbox' {if $listing.featured_ad_2}checked="checked"{/if} name='c[featured_ad_2]' value='1' />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Featured Listing Level 3</div>
					<div class="rightColumn">
						<input type='checkbox' {if $listing.featured_ad_3}checked="checked"{/if} name='c[featured_ad_3]' value='1' />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Featured Listing Level 4</div>
					<div class="rightColumn">
						<input type='checkbox' {if $listing.featured_ad_4}checked="checked"{/if} name='c[featured_ad_4]' value='1' />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Featured Listing Level 5</div>
					<div class="rightColumn">
						<input type='checkbox' {if $listing.featured_ad_5}checked="checked"{/if} name='c[featured_ad_5]' value='1' />
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Better Placement</div>
				<div class="rightColumn">
					<input type='checkbox' {if $listing.better_placement}checked="checked"{/if} name='c[better_placement]' value='1' />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Bolded</div>
				<div class="rightColumn">
					<input type='checkbox' {if $listing.bolding}checked="checked"{/if} name='c[bolding]' value='1' />
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $agCheck}
				<script type="text/javascript">
					{literal}
						function ag_show_hide () {
							if ($('ag_check').checked) {
								$('ag_box').show();
							} else {
								$('ag_box').hide();
							}
						}
						Event.observe(window, 'load', function () {
							$('ag_check').observe('click',function () { ag_show_hide(); });
							ag_show_hide();
						});
					{/literal}
				</script>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Use Attention Getter</div>
					<div class="rightColumn">
						<input type="hidden" name="c[attention_getter]" value="0" />
						<input type='checkbox' name='c[attention_getter]' id='ag_check' value='1' {if $listing.attention_getter}checked="checked"{/if} />
						<div id="ag_box">
							{foreach from=$agChoices item='choice'}
								<label>
									<input type='radio' name='c[attention_getter_choice]' value="{$choice.choice_id}" 
									{if $choice.value == $listing.attention_getter_url}checked="checked"{/if} />
									<img src="../{$choice.value}" alt="" />
								</label><br />
							{/foreach}
						</div>
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div style="text-align: center;"><input type='submit' name='auto_save' value="Save" /></div>
		</div>
	</fieldset>
</form>
<fieldset>
	<legend>Other Actions</legend>
	<div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">View Listing's Details</div>
			<div class="rightColumn">
				<a href="index.php?page=users_view_ad&amp;b={$listing.id}">View listing #{$listing.id} ({$listing.title|fromDB})</a>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">View User Info</div>
			<div class="rightColumn">
				<a href="index.php?page=users_view&amp;b={$listing.seller}">View {$username} ({$listing.seller})</a>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">Inform User of Changes</div>
			<div class="rightColumn">
				<a href="index.php?page=admin_messaging_send&amp;b[{$listing.seller}]={$username|escape:url}">Send Message to {$username} ({$listing.seller})</a>
			</div>
			<div class="clearColumn"></div>
		</div>
	</div>
</fieldset>
