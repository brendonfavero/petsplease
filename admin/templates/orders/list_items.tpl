{* 6.0.7-3-gce41f93 *}

<script type="text/javascript">

Event.observe(window,'load',function () {
	$$('input.dateInput').each(function (elem) {
		Calendar.setup({
			dateField : elem.identify(),
			triggerElement : elem.identify()+'CalButton'
		});
		$(elem.identify()+'CalButton').setStyle({ cursor: 'pointer' });
	});
	//make check all box work
	if ($('checkAllOrders')) {
		$('checkAllOrders').observe('click',function () {
			var checkAll = this.checked;
			$$('input.orderCheckbox').each(function (elem){
				elem.checked = checkAll;
			});
		});
	}
});

</script>

<fieldset>
	<legend>{if $itemLegend}{$itemLegend}{else}Items{/if}</legend>
	<div>
		{if !$hideNarrow}
			<div id="narrowShowBox">
				<strong>Showing items with: </strong>
				Status: <span class="text_blue">
					{$narrow_status|capitalize}
				</span>
				Item Type: <span class="text_blue">{$types[$narrow_type]|capitalize}</span>
				{if $date.low||$date.high}
					Starting From: <span class="text_blue">{if !$date.low}Beginning{else}{$date.low|escape}{/if}</span>
					To: <span class="text_blue">{if !$date.high}Now{else}{$date.high|escape}{/if}</span>
				{/if}
				{if $narrow_username}
					For Username: <span class="text_blue">{$narrow_username}</span>
				{/if}
				<a href="#" class="mini_button" onclick="$('narrowShowBox').hide(); $('narrowChangeBox').appear(); return false;">Change Filters</a>
			</div>
			<div class="medium_font" style="display: none;" id="narrowChangeBox">
				<form method="get" action="index.php">
					<input type="hidden" name="page" value="orders_list_items" />
					<input type="hidden" name="sortBy" value="{$sortBy}" />
					<input type="hidden" name="sortOrder" value="{$sortOrder}" />
					
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Current Item Status</div>
						<div class="rightColumn">
							<select name="narrow_item_status" id="narrow_item_status">
								<option value="all"{if $narrow_status == 'all'} selected="selected"{/if}>Any Status{if $narrow_status == "all"} &#42;{/if}</option>
								<option value="active"{if $narrow_status == 'active'} selected="selected"{/if}>Active{if $narrow_status == "active"} &#42;{/if}</option>
								<option value="pending"{if $narrow_status == 'pending'} selected="selected"{/if}>Pending{if $narrow_status == "pending"} &#42;{/if}</option>
								<!-- <option value="pending_alter"{if $narrow_status == 'pending_alter'} selected="selected"{/if}>Needs Alteration{if $narrow_status == "pending_alter"} &#42;{/if}</option> -->
								<option value="declined"{if $narrow_status == 'declined'} selected="selected"{/if}>Declined{if $narrow_status == "declined"} &#42;{/if}</option>
							</select>
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Item Type</div>
						<div class="rightColumn">
							<select name="narrow_item_type" id="narrow_item_type">
								{foreach from=$types item="title" key="type"}
									<option value="{$type}"{if $narrow_type == $type} selected="selected"{/if}>{$title}{if $narrow_type == $type} &#42;{/if}</option>
								{/foreach}
							</select>
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">From Date</div>
						<div class="rightColumn">
							<input type="text" name="date[low]" id="dateLow" class="dateInput" value="{$date.low|escape}" style="width: 110px;" />
							<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="dateLowCalButton" />
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">To Date</div>
						<div class="rightColumn">
							<input type="text" name="date[high]" id="dateHigh" class="dateInput" value="{$date.high|escape}" style="width: 110px;" />
							<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="dateHighCalButton" />
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">For Username</div>
						<div class="rightColumn">
							<input type="text" name="narrow_username" value="{$narrow_username|escape}" />
						</div>
						<div class="clearColumn"></div>
					</div>
					
					<div class="center">
						<br />
						<input type="submit" value="Narrow Selection" class="mini_button" style="margin-top: 10px; margin-left: 10px;" />
					</div>
				</form>
			</div>
		{/if}
		<div id="items_parent">
		<table style="border: 2px solid #DDD; margin: 10px 0;">
			<thead>
				<tr class="col_hdr_top">
					<th style="width: 10px;">{if !$hideStatChange}<input type="checkbox" id="checkAllOrders" />{/if}</th>
					<th>Item</th>
					<th>
						{if !$hideNarrow}<a href="{$sort_link}&amp;sortBy=created&amp;sortOrder={if $sortBy=='created'&&$sortOrder=='up'}down{else}up{/if}">{/if}
							Date
						{if !$hideNarrow}</a>{/if}
						{if !$hideNarrow && $sortBy=='created'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						{if !$hideNarrow}<a href="{$sort_link}&amp;sortBy=username&amp;sortOrder={if $sortBy=='username'&&$sortOrder=='up'}down{else}up{/if}">{/if}
							Username
						{if !$hideNarrow}</a>{/if}
						{if !$hideNarrow && $sortBy=='username'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						{if !$hideNarrow}<a href="{$sort_link}&amp;sortBy=oi.type&amp;sortOrder={if $sortBy=='oi.type'&&$sortOrder=='up'}down{else}up{/if}">{/if}
							Type
						{if !$hideNarrow}</a>{/if}
						{if !$hideNarrow && $sortBy=='oi.type'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						{if !$hideNarrow}<a href="{$sort_link}&amp;sortBy=oi.status&amp;sortOrder={if $sortBy=='oi.status'&&$sortOrder=='up'}down{else}up{/if}">{/if}
							Status
						{if !$hideNarrow}</a>{/if}
						{if !$hideNarrow && $sortBy=='oi.status'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$items item="plan_item" key="index"}
					<tr class="{cycle values='row_color1,row_color2'}">
						<td class="medium_font center">{if !$hideStatChange && count($items) > 1}<input id="batch_item[{$index}]" name="batch_item[{$index}]" value="1" class="orderCheckbox" type="checkbox" />{/if}</td>
						<td class="medium_font" style="white-space: nowrap;">
							<a href="index.php?page=orders_list_items_item_details&item_id={$index}">{if $plan_item.title}{$plan_item.title}{else}[{$index}]{/if}</a>
						</td>
						<td class="medium_font" style="white-space: nowrap;">{$plan_item.date|date_format}</td>
						<td class="medium_font" style="white-space: nowrap;">
							{if $plan_item.user_id != 0}<a href="index.php?mc=users&amp;page=users_view&amp;b={$plan_item.user_id}">{/if}
							{$plan_item.username}
							{if $plan_item.user_id != 0}</a>{/if}
						</td>
						<td class="medium_font">{$plan_item.type}</td>
						<td class="medium_font" style="white-space: nowrap;">
							{if !$hideStatChange}
								<span id="item_status{$index}">
									<select name="item_status" id="item_status_val{$index}">
										<option value="active"{if $plan_item.status == 'active'} selected="selected"{/if}>Active{if $plan_item.status == "active"} &#42;{/if}</option>
										<option value="pending"{if $plan_item.status == 'pending'} selected="selected"{/if}>Pending{if $plan_item.status == "pending"} &#42;{/if}</option>
										<!-- <option value="edit">Edit Details</option>
										<option value="pending_alter"{if $plan_item.status == 'pending_alter'} selected="selected"{/if}>Pending Alter{if $plan_item.status == "pending_alter"} &#42;{/if}</option> -->
										<option value="declined"{if $plan_item.status == 'declined'} selected="selected"{/if}>Declined{if $plan_item.status == "declined"} &#42;{/if}</option>
										<option disabled="disabled">---------</option>
										<option value="delete">Delete</option>
									</select>
								</span>
								{$set_status_link|replace:'##':$plan_item.id}
							{else}
								{$plan_item.status|ucfirst}
							{/if}
						</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="10">
						<div class="page_note_error">No Items match Selection above.</div>
						</td>
					</tr>
				{/foreach}
				{if !$hidePages}
					<tr>
						<td colspan="10" class="medium_font" style="text-align: center;">
							{$pagination}
						</td>
					</tr>
				{/if}
				{if !$hideStatChange && $items && count($items) > 1}
					<tr>
						<td colspan="10" class="medium_font" style="text-align: left;">
							With selected:
							<select id="batch_status">
								<option>--Choose--</option>
								<option value='active'>Active</option>
								<option value='pending'>Pending</option>
								<!--  <option value='pending_alter'>Needs Alteration</option> -->
								<option value='declined'>Declined</option>
							</select>
							{include file="HTML/add_button" label="Apply" link=$apply_url link_is_really_javascript="1"}
						</td>
					</tr>
				{/if}
			</tbody>
		</table>
		</div>
	</div>
</fieldset>
{if $legacy}
	{include file="orders/unapproved_legacy_listings.tpl"}
{/if}