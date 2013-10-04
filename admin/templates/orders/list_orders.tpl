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
	<legend>Managing Orders</legend>
	<div>
		<div class="page_note">
			<strong>Note:</strong> Order approval is only for whether payment has been received.
			To allow/deny a specific item in an order, that has to be done after the order as 
			a whole has been approved.
		</div>
		<div id="narrowShowBox">
			<strong>Showing orders with: </strong>
			Status: <span class="text_blue">
				{if $narrow_order_status=='pending'}
					Pending Payment
				{elseif $narrow_order_status=='pending_admin'}
					Pending
				{else}
					{$narrow_order_status|capitalize}
				{/if}
			</span>
			Gateway: <span class="text_blue">{$narrow_gateway_type|capitalize}</span>
			{if $date.low||$date.high}
				Starting From: <span class="text_blue">{if !$date.low}Beginning{else}{$date.low|escape}{/if}</span>
				To: <span class="text_blue">{if !$date.high}Now{else}{$date.high|escape}{/if}</span>
			{/if}
			{if $narrow_username}
				For Username: <span class="text_blue">{$narrow_username}</span>
			{/if}
			{if $narrow_admin_text}
				Created by Admin: <span class="text_blue">{$narrow_admin_text}</span>
			{/if}
			<a href="#" class="mini_button" onclick="$('narrowShowBox').hide(); $('narrowChangeBox').appear(); return false;">Change Filters</a>
		</div>
		<div class="medium_font" style="display: none;" id="narrowChangeBox">
			<form method="get" action="index.php">
				<input type="hidden" name="page" value="orders_list" />
				<input type="hidden" name="sortBy" value="{$sortBy}" />
				<input type="hidden" name="sortOrder" value="{$sortOrder}" />
				
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Current Order Status</div>
					<div class="rightColumn">
						<select name="narrow_order_status" id="narrow_order_status">
							<option value="all"{if $narrow_order_status == 'all'} selected="selected"{/if}>Any Status{if $narrow_order_status == "all"} &#42;{/if}</option>
							<option value="active"{if $narrow_order_status == 'active'} selected="selected"{/if}>Active{if $narrow_order_status == "active"} &#42;{/if}</option>
							<option value="pending"{if $narrow_order_status == 'pending'} selected="selected"{/if}>Pending Payment{if $narrow_order_status == "pending"} &#42;{/if}</option>
							<option value="pending_admin"{if $narrow_order_status == 'pending_admin'} selected="selected"{/if}>Pending{if $narrow_order_status == "pending_admin"} &#42;{/if}</option>
							<option value="incomplete"{if $narrow_order_status == 'incomplete'} selected="selected"{/if}>Incomplete{if $narrow_order_status == "incomplete"} &#42;{/if}</option>
							<option value="canceled"{if $narrow_order_status == 'canceled'} selected="selected"{/if}>Canceled{if $narrow_order_status == "canceled"} &#42;{/if}</option>
							<option value="suspended"{if $narrow_order_status == 'suspended'} selected="selected"{/if}>Suspended{if $narrow_order_status == "suspended"} &#42;{/if}</option>
							<option value="fraud"{if $narrow_order_status == 'fraud'} selected="selected"{/if}>Fraud{if $narrow_order_status == "fraud"} &#42;{/if}</option>
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Gateway Used</div>
					<div class="rightColumn">
						<select name="narrow_gateway_type" id="narrow_gateway_type">
							{foreach from=$types item="title" key="type"}
								<option value="{$type}"{if $narrow_gateway_type == $type} selected="selected"{/if}>{$title}{if $narrow_gateway_type == $type} &#42;{/if}</option>
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
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						Admin Creator ID# or Username
						<br /><span class="small_font">(0 for user-created orders)</span>
					</div>
					<div class="rightColumn">
						<input type="text" name="narrow_admin" value="{$narrow_admin|escape}" />
					</div>
					<div class="clearColumn"></div>
				</div>
				
				<div class="center">
					<br />
					<input type="submit" value="Narrow Selection" class="mini_button" style="margin-top: 10px; margin-left: 10px;" />
				</div>
			</form>
		</div>
		<table style="border: 2px solid #DDD; margin: 10px 0;">
			<thead>
				<tr class="col_hdr_top">
					<th style="width: 10px;">
						{if !$hideStatChange}<input type="checkbox" id="checkAllOrders" />{/if}
					</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=order_id&amp;sortOrder={if $sortBy=='order_id'&&$sortOrder=='up'}down{else}up{/if}">Order</a>
						{if $sortBy=='order_id'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=username&amp;sortOrder={if $sortBy=='username'&&$sortOrder=='up'}down{else}up{/if}">User</a>
						{if $sortBy=='username'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=admin&amp;sortOrder={if $sortBy=='admin'&&$sortOrder=='up'}down{else}up{/if}">Admin Creator</a>
						{if $sortBy=='admin'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=created&amp;sortOrder={if $sortBy=='created'&&$sortOrder=='up'}down{else}up{/if}">Date</a>
						{if $sortBy=='created'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=invoice_id&amp;sortOrder={if $sortBy=='invoice_id'&&$sortOrder=='up'}down{else}up{/if}">Invoice</a>
						{if $sortBy=='invoice_id'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=gateway_type&amp;sortOrder={if $sortBy=='gateway_type'&&$sortOrder=='up'}down{else}up{/if}">Gateway</a>
						{if $sortBy=='gateway_type'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>Order Total</th>
					<th>Still Due</th>
					<th>
						<a href="{$sort_link}&amp;sortBy=status&amp;sortOrder={if $sortBy=='status'&&$sortOrder=='up'}down{else}up{/if}">Status</a>
						{if $sortBy=='status'}
							<img src="admin_images/admin_arrow_{$sortOrder}.gif" alt="" />
						{/if}
					</th>
					<th>Approve</th>
				</tr>
			</thead>
			<tbody id="orders_parent">
				{foreach from=$orders item="order"}
					<tr class="{cycle values='row_color1,row_color2'}" id="order{$order.order_id}">
						<td class="medium_font center">{if count($orders) > 1}<input type="checkbox" id='batch_order[{$order.order_id}]' class="orderCheckbox" name="batch_order[{$order.order_id}]" value="1" />{/if}</td>
						<td class="medium_font" style="white-space: nowrap;">
							<a href={$display_order_link|replace:'##':$order.order_id}>{$order.order_id}</a>
						</td>
						<td class="medium_font" style="white-space: nowrap;">
							{if $order.user_id != 0}<a href="index.php?mc=users&amp;page=users_view&amp;b={$order.user_id}">{/if}{$order.username}{if $order.user_id != 0}</a>{/if}
						</td>
						<td class="medium_font" style="white-space: nowrap;">
							{if $order.admin_username}
								{$order.admin_username} (#{$order.admin})
							{elseif $order.admin}
								#{$order.admin}
							{else}
								User Created
							{/if}
						</td>
						<td class="medium_font" style="white-space: nowrap;">{$order.created|date_format}</td>
						<td class="medium_font" style="white-space: nowrap;">
							<a href="{$invoice_link}{$order.invoice_id}" class="lightUpLink">{$order.invoice_id}</a>
						</td>
						<td class="medium_font">{$order.gateway}</td>
						<td class="medium_font">{$order.order_total|displayPrice}</td>
						<td class="medium_font" id="order_due_amount{$order.order_id}"><span style="color: {if $order.due > 0}red{elseif $order.due < 0}green{else}black{/if}">{$order.due|displayPrice}</span></td>
						<td class="medium_font" style="white-space: nowrap;">
							<span id="order_status{$order.order_id}">
								<select name="order_status" id="order_status_val{$order.order_id}">
									<option value="active"{if $order.status == "active"} selected="selected"{/if}>Active{if $order.status == "active"} &#42;{/if}</option>
									<option value="pending"{if $order.status == "pending"} selected="selected"{/if}>Pending Payment{if $order.status == "pending"} &#42;{/if}</option>
									<option value="pending_admin"{if $order.status == "pending_admin"} selected="selected"{/if}>Pending{if $order.status == "pending_admin"} &#42;{/if}</option>
									<option value="incomplete"{if $order.status == "incomplete"} selected="selected"{/if}>Incomplete{if $order.status == "incomplete"} &#42;{else}&nbsp;&nbsp;&nbsp;{/if}</option>
									<option value="canceled"{if $order.status == "canceled"} selected="selected"{/if}>Canceled{if $order.status == "canceled"} &#42;{/if}</option>
									<option value="suspended"{if $order.status == "suspended"} selected="selected"{/if}>Suspended{if $order.status == "suspended"} &#42;{/if}</option>
									<option value="fraud"{if $order.status == "fraud"} selected="selected"{/if}>Fraud{if $order.status == "fraud"} &#42;{/if}</option>
									<option disabled="disabled">---------</option>
									<option value="delete">Delete</option>
								</select>
							</span>
							{$set_status_link|replace:'##':$order.order_id}</td>
						<td class="medium_font">{$approve_link|replace:'##':$order.order_id}</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="10">
							<div class="page_note_error">No Orders match Selection above.</div>
						</td>
					</tr>
				{/foreach}
				<tr>
					<td colspan="10" class="medium_font" style="text-align: center;">
						{$pagination}
					</td>
				</tr>
				{if $orders && count($orders) > 1}
					<tr>
						<td colspan="10" class="medium_font" style="text-align: left;">
							With selected:
							<select name="batch_status" id="batch_status">
								<option>--Choose--</option>
								<option value='active'>Active</option>
								<option value='pending'>Pending</option>
								<option value='incomplete'>Incomplete</option>
								<option value='canceled'>Canceled</option>
								<option value='suspended'>Suspended</option>
								<option value='fraud'>Fraud</option>
							</select>
							{include file="HTML/add_button" label="Apply" link=$apply_url link_is_really_javascript="1"}
						</td>
					</tr>
				{/if}
			</tbody>
		</table>
		{include file="HTML/add_button" label="Refresh Orders" link="onclick='window.location.reload(true)'" link_is_really_javascript="1"}
	</div>
</fieldset>
{include file='orders/get_order_form.tpl'}