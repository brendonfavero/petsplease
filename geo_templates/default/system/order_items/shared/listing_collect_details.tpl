{* 7.2.1-6-g63ad625 *}

{include file="cart_steps.tpl" g_resource="cart"}
{include file='inline_preview_box.tpl' g_resource='cart'}

<script type="text/javascript">
	//<![CDATA[
	geoListing.inAdmin={if $in_admin}true{else}false{/if};
	//]]>
</script>

{if !$steps_combined}<form action="{$process_form_url}" method="post">{/if}
	<div class="content_box">
		{if $steps_combined}
			<h1 class="title">{$title1}</h1>
		{else}		
			<h1 class="title">{$txt1}</h1>
			<h1 class="subtitle">{$title1}</h1>
		{/if}
		<p class="page_instructions">{$desc1}</p>
		
		{if $error_msgs.cart_error}
			<div class="field_error_box">
				{$error_msgs.cart_error}
			</div>
		{/if}
		
		<div class="note_box">
			<strong>
				{$txt2} {$txt3}
				{if is_array($category_tree)}
					{foreach from=$category_tree item="cat" name="cat_tree"}
						&gt; {$cat.category_name}
					{/foreach}
				{elseif $category_tree}
					&gt; {$category_tree}
				{/if}
			</strong>
		</div>
		
		{if $sell_type == 1 && $editCheck}
			<div class="{cycle values='row_odd,row_even'} combined_update_fields">
				<label for="classified_length" class="field_label">
					{$messages.125}<br />{$messages.126}
				</label>
				<select class="field" id="classified_length" name="b[classified_length]">
					{$duration_dropdown}
				</select>
			</div>
		{/if}
		{if $fields->title->is_enabled}
			{if $use_auto_title}
				<input type="hidden" id="classified_title" name="b[classified_title]" value="{$messages.500110}" />
			{else}
				<div class="{if $error_msgs.classified_title}field_error_row {/if}{cycle values='row_odd,row_even'}">
					<label for="classified_title" class="field_label">{$messages.123}</label>
					
					{if !$editCheck && !$fields->title->can_edit}
						{$session_variables.classified_title}
					{elseif !$use_textarea_in_title}
						<input type="text" id="classified_title" class="field" name="b[classified_title]"
							value="{$session_variables.classified_title}" size="{if $fields->title->text_length > 50}50{else}{$fields->title->text_length}{/if}"
							maxlength="{$fields->title->text_length}" />
					{else}
						<textarea id="classified_title" class="field" name="b[classified_title]" rows="1"
							cols="{if $fields->title->text_length > 50}50{else}{$fields->title->text_length}{/if}">{$session_variables.classified_title}</textarea>
					{/if}
					
					{if $error_msgs.classified_title}
						<span class="error_message">{$messages.116}</span>
					{/if}
				</div>
			{/if}
		{/if}
		
		{if !$display_description_last_in_form && ($editCheck || $fields->description->can_edit)}
			{include file="shared/details_description_box.tpl"}
		{/if}
		{if $fields->tags->is_enabled}
			<div class="{if $error_msgs.tags}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="classified_title" class="field_label">{$messages.500863}</label>
				
				{if !$editCheck && !$fields->tags->can_edit}
					{$session_variables.tags}
				{else}
					<input type="text" class="field" name="b[tags]" id="listingTags" value="{$session_variables.tags|escape}" size="50" />
					<div id="listingTags_choices" class="autocomplete_choices"></div>
				{/if}
				{$tags_help_link}
				{if $error_msgs.tags}
					<span class="error_message">{$messages.500865}</span>
				{/if}
			</div>
		{/if}
		{if $moreDetails}
			{include file='shared/listing_collect_details.tpl' more=$moreDetails}
		{/if}
		
		{if $sell_type == 1 && $fields->price->is_enabled}
			<div class="{if $error_msgs.price}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="price" class="field_label">{$messages.134}</label>
				
				{if $editCheck || $fields->price->can_edit}
					<span class="precurrency">{$session_variables.precurrency}</span>
					<input type="text" id="price" class="field" name="b[price]"
						size="{if $fields->price->text_length > 12}12{else}{$fields->price->text_length}{/if}"
						maxlength="{$fields->price->text_length}"
						value="{$session_variables.price|displayPrice:"":""}" />
					{include file="shared/postcurrency_dropdown.tpl"}
				{else}
					{$session_variables.price|displayPrice:$session_variables.precurrency:$session_variables.postcurrency}
				{/if}
				
				{if $error_msgs.price}
					<span class="error_message">{$messages.642}</span>
				{/if}
			</div>
		{/if}
		
		{if $sell_type == 2 && $editAuctionPrices}
			<div class="{if $error_msgs.auction_type}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="auction_type" class="field_label">{$messages.102689}</label>
				
				{if $editCheck}
					{if $field_config.allow_standard && $pricePlan.buy_now_only}
						{* Buy now only!  Can only be "standard" auction! *}
						<input id="auction_type" type="hidden" class="field" name="b[auction_type]" value="1" />{$messages.102837}
					{elseif $auction_choices_count>1}
						{* We know there is more than one choice, so show selection *}
						<select id="auction_type" class="field" name="b[auction_type]">
							{if $field_config.allow_standard}<option value="1"{if $session_variables.auction_type == 1} selected="selected"{/if}>{$messages.102837}</option>{/if}
							{if $field_config.allow_dutch}<option value="2"{if $session_variables.auction_type == 2} selected="selected"{/if}>{$messages.102838}</option>{/if}
							{if $allow_reverse}<option value="3"{if $session_variables.auction_type == 3} selected="selected"{/if}>{$messages.500977}</option>{/if}
						</select>
					{elseif $field_config.allow_standard}
						<input id="auction_type" type="hidden" name="b[auction_type]" value="1" />{$messages.102837}
					{elseif $field_config.allow_dutch}
						<input id="auction_type" type="hidden" name="b[auction_type]" value="2" />{$messages.102838}
					{elseif $allow_reverse}
						<input id="auction_type" type="hidden" name="b[auction_type]" value="3" />{$messages.500977}
					{else}
						{* Needs to always have auction type set... this is "probably" buy now only *}
						<input id="auction_type" type="hidden" name="b[auction_type]" value="1" />
					{/if}
					{$auction_type_help_link}
				{else}
					{if $session_variables.auction_type==1}
						{$messages.102837}
					{elseif $session_variables.auction_type==2}
						{$messages.102838}
					{elseif $session_variables.auction_type==3}
						{$messages.500977}
					{/if}
				{/if}
			
				{if $error_msgs.auction_type}
					<span class="error_message">{$error_msgs.auction_type}</span>
				{/if}
			</div>
			
			<div class="{if $error_msgs.auction_quantity}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="auction_quantity" class="field_label">{$messages.102690}</label>
				
				{if $editCheck || $fields->price->can_edit}
					<input type="text" id="auction_quantity" name="b[auction_quantity]" value="{if $session_variables.auction_quantity <= 0}1{else}{$session_variables.auction_quantity}{/if}" class="field" />
				{else}
					{if $session_variables.auction_quantity <= 0}1{else}{$session_variables.auction_quantity}{/if}
				{/if}
				
				{if $error_msgs.auction_quantity}
					<span class="error_message">{$messages.500217}</span>
				{/if}
			</div>	
			
			{if $field_config.user_set_auction_start_times && $editCheck}
				<div class="{if $error_msgs.start_time}field_error_row {/if}{cycle values='row_odd,row_even'}">
					<label class="field_label">{$messages.102816}</label>
					{$date_select_start_time}
					{if $error_msgs.start_time}
						<span class="error_message">{$messages.103356}</span>
					{/if}
				</div>
			{/if}
			
			{if $field_config.user_set_auction_end_times && $pricePlan.charge_per_ad_type != 2 && $editCheck}
				<div class="{if $error_msgs.end_time}field_error_row {/if}{cycle values='row_odd,row_even'}">
					<label class="field_label">{$messages.102820}</label>
					<input type="radio" id="endTimeRadio" value="1" name="b[end_mode]"{if $session_variables.end_mode == 1} checked="checked"{/if} onclick="SetEndMode(1);" />
					{$date_select_end_time}
					{if $error_msgs.end_time}
						<span class="error_message">{$messages.103357}</span>
					{/if}
				</div>
			{/if}
			{if $editCheck}
				<div class="{if $error_msgs.duration||$error_msgs.classified_length}field_error_row {/if}{cycle values='row_odd,row_even'} combined_update_fields">
					<label class="field_label">{$messages.125}<br />{$messages.100126}</label>
					{if $field_config.user_set_auction_end_times && $pricePlan.charge_per_ad_type != 2}
						<input type="radio" id="durationRadio" value="2" name="b[end_mode]"{if $session_variables.end_mode != 1} checked="checked"{/if} onclick="SetEndMode(2);" />
					{/if}
					<select class="field" id="classified_length" name="b[classified_length]">
						{$auction_duration_dropdown}
					</select>
					{if $field_config.user_set_auction_end_times && $pricePlan.charge_per_ad_type != 2}
									
						<script type="text/javascript">
							//TODO: Move this function to a JS file or something, and
							//only leave the SetEndMode call, as that part is dynamic.
							function SetEndMode(mode)
							{ldelim}
								if(mode == 1) {ldelim}
									document.getElementById('classified_length').disabled = true;
									document.getElementById('endtime_month').disabled = false;
									document.getElementById('endtime_day').disabled = false;
									document.getElementById('endtime_year').disabled = false;
									document.getElementById('endtime_hour').disabled = false;
									document.getElementById('endtime_minute').disabled = false;
								} else if (mode == 2) {ldelim}
									document.getElementById('classified_length').disabled = false;
									document.getElementById('endtime_month').disabled = true;
									document.getElementById('endtime_day').disabled = true;
									document.getElementById('endtime_year').disabled = true;
									document.getElementById('endtime_hour').disabled = true;
									document.getElementById('endtime_minute').disabled = true;
								}
							}
							SetEndMode({if $session_variables.end_mode}{$session_variables.end_mode}{else}2{/if});
						</script>
					{/if}
					{if $error_msgs.duration || $error_msgs.classified_length}
							<span class="error_message">{$messages.103358}</span>
					{/if}
				</div>
			{/if}
			
			{if $editCheck || $fields->price->can_edit}
				{if $currency_count>1}
				<div class="{cycle values='row_odd,row_even'}">
					<label class="field_label">{$messages.100134}</label>
				{/if}
					{include file="shared/postcurrency_dropdown.tpl"}
				{if $currency_count>1}</div>{/if}
			{/if}
		
			{if !$pricePlan.buy_now_only}
				<div class="{if $error_msgs.auction_minimum}field_error_row {/if}{cycle values='row_odd,row_even'}"{if $bno} style="display:none;"{/if} id="min_row">
					<label for="minimum" class="field_label" id="minimum_label">{$messages.102691}</label>
					{if $allow_reverse}<label for="minimum" class="field_label" id="maximum_label">{$messages.500978}</label>{/if}
					<span class="main_text precurrency">{$session_variables.precurrency}</span>
					{if $editCheck || $fields->price->can_edit}
						<input type="text" id="minimum" class="field" name="b[auction_minimum]"
							value='{if !$bno}{$session_variables.auction_minimum|displayPrice:"":""}{/if}'
							{if $fields->price->text_length}maxlength='{$fields->price->text_length}'{/if} />
					{elseif !$bno}
						{$session_variables.auction_minimum|displayPrice:"":""}
					{/if}
					
					{if $error_msgs.auction_minimum}
						<span class="error_message">{$error_msgs.auction_minimum}</span>
					{/if}
				</div>
				
				<div class="{if $error_msgs.auction_reserve}field_error_row {/if}{cycle values='row_odd,row_even'}" id="res_row"{if $bno} style="display:none;"{/if}>
					<label for="reserve" class="field_label">{$messages.102692}</label>
					<span class="main_text precurrency">{$session_variables.precurrency}</span>
					{if $editCheck || $fields->price->can_edit}
						<input type="text" id="reserve" class="field" name="b[auction_reserve]"
							value="{if !$bno}{$session_variables.auction_reserve|displayPrice:'':''}{/if}"
							{if $fields->price->text_length}maxlength="{$fields->price->text_length}"{/if} />
					{elseif !$bno}
						{$session_variables.auction_reserve|displayPrice:"":""}
					{/if}
					{if $error_msgs.auction_reserve}
						<span class="error_message">{if $session_variables.auction_type==3}{$messages.500979}{else}{$messages.102731}{/if}</span>
					{/if}
				</div>
			{/if}
			{if $allow_buy_now}
				<div class="{if $error_msgs.auction_buy_now}field_error_row {/if}{cycle values='row_odd,row_even'}{if $allow_reverse_buy_now} reverse_buy_now{/if}"
					id="buy_now_row"{if $is_dutch} style="display:none;"{/if}>
					
					<label for="auction_buy_now" class="field_label">{$messages.102693}</label>
					<span class="main_text precurrency">{$session_variables.precurrency}</span>
					
					{if $editCheck || $fields->price->can_edit}
						<input type="text" class="field" id="auction_buy_now" name="b[auction_buy_now]" value='{$session_variables.auction_buy_now|displayPrice:"":""}' {if $fields->price->text_length}maxlength='{$fields->price->text_length}'{/if} />
						<input type="hidden" name="b[bno_submitted]" value="1" />
					{else}
						{$session_variables.auction_buy_now|displayPrice:"":""}
					{/if}
			
					{if $is_ent && $allow_buy_now_only}
						{if $pricePlan.buy_now_only}
							<input type="hidden" name="b[buy_now_only]" value="1" id="buy_now_only" />
						{elseif !$editCheck && !$fields->price->can_edit}
							<input type="hidden" name="b[buy_now_only]" id="buy_now_only" value="{if $bno || $session_variables.buy_now_only}1{else}0{/if}" />
						{else}
							<label id="buy_now_only_row"><input id="buy_now_only" type="checkbox"
							name="b[buy_now_only]" {if $bno || $session_variables.buy_now_only}checked="checked" {/if}/>
							&nbsp;{$messages.500029}</label>
						{/if}
					{/if}
					
					{if $error_msgs.auction_buy_now}
						<span class="error_message">
							{if !$pricePlan.buy_now_only && !$session_variables.buy_now_only}
								{if $session_variables.auction_type==3}{$messages.500980}{else}{$messages.102732}{/if}
							{else}
								{$messages.103373}
							{/if}
						</span>
					{/if}
				</div>
			{/if}
		{/if}
		{if $is_ent && $add_cost_at_top && ($sell_type != 2 || $editAuctionPrices)}
			{foreach from=$opt_field_info item='opt_info' key='i'}
				{if $opt_info.field->field_type=='cost'}
					<div class="{if $opt_info.error}field_error_row {/if}{cycle values='row_odd,row_even'}">
						<label class="field_label">{$opt_info.label}</label>
						<span class="precurrency">{$precurrency}</span>
						{if $editCheck || $opt_info.field->can_edit}
							<input type="text" name="b[optional_field_{$i}]" id="optional_field_{$i}"
								size="{if $opt_info.field->text_length>12}12{else}{$opt_info.field->text_length}{/if}"
								maxlength="{$opt_info.field->text_length}" class="field"
								value="{$opt_info.value|displayPrice:'':''}" />
						{else}
							{$opt_info.value|displayPrice:'':''}
						{/if}
						{if $opt_info.error}
							<span class="error_message">{$opt_info.error}</span>
						{/if}
					</div>
				{/if}
			{/foreach}
		{/if}
		{if $sell_type == 2 && $editAuctionPrices}
			<div class="{cycle values='row_odd,row_even'}" id="price_applies_box">
				<label class="field_label">{$messages.502099}</label>
				<select name="b[price_applies]" id="price_applies"{if !$bno && !$pricePlan.buy_now_only} disabled="disabled"{/if}>
					<option value="item"{if $session_variables.price_applies=='item'} selected="selected"{/if}>{$messages.502100}</option>
					<option value="lot"{if $session_variables.price_applies!='item'} selected="selected"{/if}>{$messages.502101}</option>
				</select>
			</div>
		{/if}
		
		{if $on_site_html}
			<div class="{cycle values='row_odd,row_even'} highlight_links">
				<label class="field_label">{$messages.500208}</label>
				{$on_site_html}
			</div>
		{/if}
		
		{if $fields->payment_types->is_enabled}
			<div class="{if $error_msgs.payment_options}field_error_row {/if}{cycle values='row_odd,row_even'}">
				
				<label class="field_label" style="width: 400px;">{$messages.102850}<br /><span class="mini_note">{$messages.102867}</span></label>
				
				<ul class="payment_options_list inline">
					{foreach from=$payment_options item="show_payment" key="key" name="payment_type_loop"}
						<li>
							<label>
								<input type="checkbox" name="b[payment_options_from_form][]" value="{$show_payment.type_name}"
								{if in_array($show_payment.type_name, $session_variables.payment_options)}checked="checked"{/if} />
								{$show_payment.type_name}
							</label>
						</li>
					{foreachelse}
						<li>No Choices to Display</li>
					{/foreach}
				</ul>
				
				{if $error_msgs.payment_options}
					<span class="error_message">{$messages.102851}</span>
				{/if}
			</div>
		{/if}
		
		{if $field_config.use_buy_now}
			<div class="{if $error_msgs.paypal_id}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="paypal_id" class="field_label">{$messages.3279}</label>
				<span class="sub_note">{$messages.3280}</span>
				{if $editCheck || $field_config.editable_buy_now}
					<input type="text" class="field" id="paypal_id" name="b[paypal_id]" value='{$session_variables.paypal_id}' />
				{else}
					{$session_variables.paypal_id}
				{/if}
			
				{if $error_msgs.paypal_id}
					<span class="error_message">{$messages.3281}</span>
				{/if}
			</div>
		{/if}
		
		{if $leveled_fields}
			{foreach $leveled_fields as $lev_id => $lev_field}
				{* Note: already checks for "enabled" in PHP *}
				<div class="{if $error_msgs.{$lev_field.error}}field_error_row {/if}{cycle values='row_odd,row_even'}">
					{* Note: edit checks done in code, if cannot edit it only shows
						the value already selected *}
					{include file='shared/leveled_fields/main.tpl'}
					{if $error_msgs.{$lev_field.error}}
						<span class="error_message">{$messages.502064}</span>
					{/if}
				</div>
			{/foreach}
		{/if}
		
		{if $security_image}
			<div class="{if $error_msgs.securityCode}field_error_row {/if}{cycle values='row_odd,row_even'}">
				{$security_image}
			</div>
		{/if}
		
	</div>
	<br />
	
	<div class="content_box">
		<h2 class="title">{$messages.500804}</h2>
		
		{if $fields->email->is_enabled}
			<div class="{if $error_msgs.email_option}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="email_option" class="field_label">{$messages.1339}</label>
		
				{if $fields->email->can_edit || $editCheck}
					<input type="text" id="email_option" name="b[email_option]" value="{if $session_variables.email_option}{$session_variables.email_option}{else}{$user_data.email}{/if}"
						class="field" />
				{else}
					{$session_variables.email_option}
					<input type="hidden" name="b[email_option]" value="{$session_variables.email_option}" />
				{/if}
				{if $fields->email->type_data == 'reveal'}
					{$messages.1340}
					<label>{$messages.1341} <input type="radio" name="b[expose_email]" value="1"{if $session_variables.expose_email == 1} checked="checked"{/if} /></label>
					<label>{$messages.1342} <input type="radio" name="b[expose_email]" value="0"{if $session_variables.expose_email != 1} checked="checked"{/if} /></label>
				{/if}
				{if $error_msgs.email_option}
					<span class="error_message">{$messages.1343}</span>
				{/if}
			</div>
		{else}
			<input type="hidden" name="b[email_option]" value="{$session_variables.email_option}" />
		{/if}
		
		{if $fields->phone_1->is_enabled}
			<div class="{if $error_msgs.phone_1_option}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="phone_1_option" class="field_label">{$messages.1345}</label>
				{if $editCheck || $fields->phone_1->can_edit}
					<input type="text" id="phone_1_option" name="b[phone_1_option]" value="{$session_variables.phone_1_option}"
						size="{if $fields->phone_1->text_length > 18}18{else}{$fields->phone_1->text_length}{/if}"
						maxlength="{$fields->phone_1->text_length}" class="field" />
				{else}
					{$session_variables.phone_1_option}
					<input type="hidden" name="b[phone_1_option]" value="{$session_variables.phone_1_option}" />
				{/if}
			
				{if $error_msgs.phone_1_option}
					<span class="error_message">{$messages.500097}</span>
				{/if}
			</div>
		{/if}
		
		{if $fields->phone_2->is_enabled}
			<div class="{if $error_msgs.phone_2_option}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="phone_2_option" class="field_label">{$messages.1346}</label>
			
				{if $editCheck || $fields->phone_2->can_edit}
					<input type="text" id="phone_2_option" name="b[phone_2_option]" value="{$session_variables.phone_2_option}"
						size="{if $fields->phone_2->text_length > 18}18{else}{$fields->phone_2->text_length}{/if}"
						maxlength="{$fields->phone_2->text_length}" class="field" />
				{else}
					{$session_variables.phone_2_option}
					<input type="hidden" name="b[phone_2_option]" value="{$session_variables.phone_2_option}" />
				{/if}
			
				{if $error_msgs.phone_2_option}
					<span class="error_message">{$messages.500098}</span>
				{/if}
			</div>
		{/if}
		
		{if $fields->fax->is_enabled}
			<div class="{if $error_msgs.fax_option}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="fax_option" class="field_label">{$messages.1355}</label>
			
				{if $editCheck || $fields->fax->can_edit}
					<input type="text" id="fax_option" name="b[fax_option]" value="{$session_variables.fax_option}"
						size="{if $fields->fax->text_length > 18}18{else}{$fields->fax->text_length}{/if}"
						maxlength="{$fields->fax->text_length}" class="field" />
				{else}
					{$session_variables.fax_option}
					<input type="hidden" name="b[fax_option]" value="{$session_variables.fax_option}" />
				{/if}
			
				{if $error_msgs.fax_option}
					<span class="error_message">{$messages.500099}</span>
				{/if}
			</div>
		{/if}
		
		{if $fields->address->is_enabled && ($editCheck || $fields->address->can_edit)}
			<div class="{if $error_msgs.address}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="address" class="field_label">{$messages.500161}</label>
			
				{if $error_msgs.address}
					<span class="error_message">{$messages.500162}</span>
				{/if}
			
				<input type="text" id="address" name="b[address]" size="{if $fields->address->text_length > 20}20{else}{$fields->address->text_length}{/if}"
					maxlength="{$fields->address->text_length}"
					value="{$session_variables.address}" class="field" />
			</div>
		{/if}
		
		{if $region_selector && ($editCheck || $fields->region_level_1->can_edit)} {* allow editing based on "level 1" switch...for now... *}
			<div class="{if $error_msgs.location}field_error_row {/if}{cycle values='row_odd,row_even'}">
				{if $error_msgs.location}
					<span class="error_message">{$messages.501631}</span>
				{/if}
				{$region_selector}
			</div>
		{/if}
		
		{if $fields->city->is_enabled && ($editCheck || $fields->city->can_edit) && !$geographicOverrides.city}
			<div class="{if $error_msgs.city}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="city" class="field_label">{$messages.1129}</label>
				{if $error_msgs.city}
					<span class="error_message">{$messages.1130}</span>
				{/if}
			
				{if $editCheck || $fields->city->can_edit}
					<input type="text" id="city" name="b[city]" size="{if $fields->city->text_length > 20}20{else}{$fields->city->text_length}{/if}"
						maxlength="{$fields->city->text_length}"
						value="{$session_variables.city}" class="field" />
				{else}
					{$session_variables.city}
				{/if}
			</div>
		{/if}
		
		
		{if $fields->zip->is_enabled}
			<div class="{if $error_msgs.zip_code}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="zip_code" class="field_label">{$messages.121}</label>
				{if $error_msgs.zip_code}
					<span class="error_message">{$messages.118}</span>
				{/if}
				{if $messages.119}
					{$messages.119}
				{/if}
			
				{if $editCheck || $fields->zip->can_edit}
					<input type="text" id="zip_code" name="b[zip_code]" value="{$session_variables.zip_code}"
						size="{if $fields->zip->text_length > 10}10{else}{$fields->zip->text_length}{/if}"
						maxlength="{$fields->zip->text_length}" class="field" />
				{else}
					{$session_variables.zip_code}
				{/if}
			</div>
		{/if}
		
		{if $moreDetailsLocation}
			{include file='shared/listing_collect_more.tpl' more=$moreDetailsLocation}
		{/if}
	</div>
	<br />

{* capture this next bit into a smarty variable, so that it can be not shown if there's nothing to show *}
	{capture assign=additionalInfo}
		{if $fields->url_link_1->is_enabled}
			<div class="{if $error_msgs.url_link_1}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="url_link_1" class="field_label">{$messages.2434}</label>
			
				{if $editCheck || $fields->url_link_1->can_edit}
					<input type="text" id="url_link_1" name="b[url_link_1]" 
						size="{if $fields->url_link_1->text_length > 30}30{else}{$fields->url_link_1->text_length}{/if}"
						maxlength="{$fields->url_link_1->text_length}"
						value="{$session_variables.url_link_1}" class="field" />
				{else}
					{$session_variables.url_link_1}
				{/if}
			
				{if $error_msgs.url_link_1}
					<span class="error_message">{$messages.2437}</span>
				{/if}
			</div>
		{/if}
		
		{if $fields->url_link_2->is_enabled}
			<div class="{if $error_msgs.url_link_2}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="url_link_2" class="field_label">{$messages.2435}</label>
			
				{if $editCheck || $fields->url_link_2->can_edit}
					<input type="text" id="url_link_2" name="b[url_link_2]" 
						size="{if $fields->url_link_2->text_length > 30}30{else}{$fields->url_link_2->text_length}{/if}"
						maxlength="{$fields->url_link_2->text_length}"
						value="{$session_variables.url_link_2}" class="field" />
				{else}
					{$session_variables.url_link_2}
				{/if}
			
				{if $error_msgs.url_link_2}
					<span class="error_message">{$messages.2438}</span>
				{/if}
			</div>
		{/if}
		
		{if $fields->url_link_3->is_enabled}
			<div class="{if $error_msgs.url_link_3}field_error_row {/if}{cycle values='row_odd,row_even'}">
				<label for="url_link_3" class="field_label">{$messages.2436}</label>
			
				{if $editCheck || $fields->url_link_3->can_edit}
					<input type="text" id="url_link_3" name="b[url_link_3]" 
						size="{if $fields->url_link_3->text_length > 30}30{else}{$fields->url_link_3->text_length}{/if}"
						maxlength="{$fields->url_link_3->text_length}"
						value="{$session_variables.url_link_3}" class="field" />
				{else}
					{$session_variables.url_link_3}
				{/if}
			
				{if $error_msgs.url_link_3}
					<span class="error_message">{$messages.2439}</span>
				{/if}
			</div>
		{/if}
		
		
		{if $is_ent}
			{foreach from=$opt_field_info item=opt_info key=i}
				{if $opt_info.field->field_type=='cost'}
					{if !$add_cost_at_top && ($sell_type != 2 || $editAuctionPrices)}
						{* Adds cost, and does not have set to display cost 
							optional fields at "top" of page (below normal price fields)
							AND it is either classified ad, or it is auction and
							can edit auction prices *}
						<div class="{if $opt_info.error}field_error_row {/if}{cycle values='row_odd,row_even'}">
							<label for="optional_field_{$i}" class="field_label">{$opt_info.label}</label>
								
							<span class="precurrency">{$precurrency}</span>
							{if $editCheck || $opt_info.field->can_edit}
								<input type="text" name="b[optional_field_{$i}]" id="optional_field_{$i}"
									size="{if $opt_info.field->text_length>12}12{else}{$opt_info.field->text_length}{/if}"
									maxlength="{$opt_info.field->text_length}" class="field"
									value="{$opt_info.value|displayPrice:'':''}" />
							{else}
								{$opt_info.value|displayPrice:'':''}
							{/if}
							{if $opt_info.error}
								<span class="error_message">{$opt_info.error}</span>
							{/if}
						</div>
					{/if}
				{elseif $opt_info.field->is_enabled}
					<div class="{if $opt_info.error}field_error_row {/if}{cycle values='row_odd,row_even'}">
						<label for="optional_field_{$i}" class="field_label">{$opt_info.label}</label>
						
						{if $field_config.use_filters && $opt_info.filter_assoc}
							{* Using filters for this field *}
							{$opt_info.value}
						{elseif !$editCheck && !$opt_info.field->can_edit}
							{* Editing, and can edit is off, so just display *}
							{$opt_info.value}
						{elseif $opt_info.field->field_type=='text'||$opt_info.field->field_type=='url'||$opt_info.field->field_type=='number'}
							{* Text input (text, number, or url) *}
							<input type="text" name="b[optional_field_{$i}]" id="optional_field_{$i}"
								size="{if $opt_info.field->text_length > 30}30{else}{$opt_info.field->text_length}{/if}"
								maxlength="{$opt_info.field->text_length}" class="field"
								value="{$opt_info.value}" />
						{elseif $opt_info.field->field_type=='textarea'}
							{* textarea type *}
							<textarea rows="6" cols="30" name="b[optional_field_{$i}]" id="optional_field_{$i}" class="field">{$opt_info.value}</textarea>
						{elseif $opt_info.field->field_type=='dropdown'&&count($optional_types[$i])}
							{* dropdown *}
							<select name="b[optional_field_{$i}]" id="optional_field_{$i}" class="field">
								{assign var="opt_used" value="0"}
								{foreach from=$optional_types.$i item="row"}
									{* "value" parameter intentionally ommitted from this <option>, to support values containing double-quotes *}
									<option{if $opt_info.value == ($row.value|escape)} selected="selected"{assign var="opt_used" value="1"}{/if}>{$row.value}</option>
								{/foreach}
							</select>
							{if $opt_info.other_box}
								{* other box for dropdown *}
								&nbsp;{$opt_info.or}&nbsp;
								<input type="text" name="b[optional_field_{$i}_other]"
									value="{if !$opt_used}{$opt_info.value}{/if}"
									class="field" 
									size="{if $opt_info.field->text_length > 30}30{else}{$opt_info.field->text_length}{/if}"
									maxlength="{$opt_info.field->text_length}" />
							{/if}
						{elseif $opt_info.field->field_type=='date'}
							{* Date type, show date *}
							<input type="text" name="b[optional_field_{$i}]" id="optional_field_{$i}"
								size="10"
								maxlength="10" class="field dateInput"
								value="{$opt_info.value}" />
						{/if}
				
						{if $opt_info.error}
							<span class="error_message">{$opt_info.error}</span>
						{/if}
					</div>
				{/if}
			{/foreach}
		{/if}
		
		{include file="shared/questions.tpl"}
		
		{if $display_description_last_in_form && ($editCheck || $fields->description->can_edit)}
			{include file="shared/details_description_box.tpl"}
		{/if}
	{/capture}

	{if $additionalInfo|strip:'' !== ''}
		<div class="content_box">
			<h1 class="title">{$messages.500805}</h1>
			{$additionalInfo}		
		</div>
	{/if}
{********************************************}
	{if $fields->mapping_location->is_enabled}
		<br />
		<div class="content_box">
			<h2 class="title">{$messages.1622}</h2>
			<p class="page_instructions">{$messages.1623}</p>
			
				<div class="{if $error_msgs.mapping_location}field_error_row {/if}{cycle values='row_odd,row_even'}">
					<label for="mapping_location" class="field_label">{$messages.1617}</label>
					<input type="text" id="mapping_location" name="b[mapping_location]" value="{$session_variables.mapping_location}" class="field" size="70" />
					{if $error_msgs.mapping_location}
						<span class="error_message">{$messages.500100}</span>
					{/if}
				</div>
			
		</div>
	{/if}
	{if $moreDetailsEnd}
		{*  Allow addons to insert stuff at the very end. *}
		<br />
		<div class="content_box">
			{include file='shared/listing_collect_more.tpl' more=$moreDetailsEnd}
		</div>
	{/if}
	{if !$steps_combined}
		<div class="center">
			{if $showPreviewButton}
				<br /><br />
				<input type="submit" name="forcePreview" value="{$preview_button_txt}" class="button" />
			{/if}
			{if $forcePreviewButtonOnly}
				{* Use hidden main submit, that way can only be "clicked" using JS *} 
				<input type="submit" name="submit" value="1" style="display: none;" class="mainSubmit" />
			{else}
				<br /><br />
				<input type="submit" name="submit" value="{$submit_button_txt}" class="button mainSubmit" />
			{/if}
			<br /><br />
			<a href="{$cart_url}&amp;action=cancel" class="cancel">{$cancel_txt}</a>
		</div>
	{/if}
	
{if !$steps_combined}</form>{/if}

