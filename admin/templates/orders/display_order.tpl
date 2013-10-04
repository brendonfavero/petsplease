{* 6.0.7-3-gce41f93 *}
<fieldset>
	<legend>Order Details for Order #{$order.order_id}</legend>
	<div id ='frm_order_details'>
{if $order}
	<div class='{cycle values="row_color1,row_color2"}'>
		<div class='leftColumn'>
			Username
		</div>
		<div class='rightColumn'>
			{$order.username}
		</div>
		<div class='clearColumn'></div>
	</div>
	<div class='{cycle values="row_color1,row_color2"}'>
		<div class='leftColumn'>
			Admin Creator
		</div>
		<div class='rightColumn'>
			{if $order.admin_username}
				{$order.admin_username} (#{$order.admin})
			{elseif $order.admin}
				ID #{$order.admin} (Error retrieving admin user data)
			{else}
				N/A (User Created on client side)
			{/if}
		</div>
		<div class='clearColumn'></div>
	</div>
	
	<div class='{cycle values="row_color1,row_color2"}'>
		<div class='leftColumn'>
			Date
		</div>
		<div class='rightColumn'>
			{$order.date}
		</div>
		<div class='clearColumn'></div>
	</div>
	
	<div class='{cycle values="row_color1,row_color2"}'>
		<div class='leftColumn'>
			Gateway
		</div>
		<div class='rightColumn'>
			{$order.gateway_type}
		</div>
		<div class='clearColumn'></div>
	</div>
	
	{if $order.cc_number}
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				CC Number {if $order.cvv2_code}[cvv2]{/if} {if $order.exp_date}(EXP date){/if}
			</div>
			<div class='rightColumn'>
				{$order.cc_number} {if $order.cvv2_code}[{$order.cvv2_code}]{/if} {if $order.exp_date}({$order.exp_date}){/if} {if $order.can_delete_cc}<a href="?page=orders_list_order_details&order_id={$order.order_id}&clear_cc=1&auto_save=1" class="mini_cancel lightUpLink">Clear CC Data</a>{/if}
			</div>
			<div class='clearColumn'></div>
		</div>
	{/if}
		
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Status
			</div>
			<div class='rightColumn'>
				<span id="order_status{$order.order_id}" onchange="CJAX.$('do_next').value='save_and_back';">
					<select name="order_options[status]" id="order_options[status]">
						<option value="active"{if $order.status == "active"} selected="selected"{/if}>Active{if $order.status == "active"} &#42;{/if}</option>
						<option value="pending"{if $order.status == "pending"} selected="selected"{/if}>Pending Payment{if $order.status == "pending"} &#42;{/if}</option>
						<option value="pending_admin"{if $order.status == "pending_admin"} selected="selected"{/if}>Pending{if $order.status == "pending_admin"} &#42;{/if}</option>
						<option value="incomplete"{if $order.status == "incomplete"} selected="selected"{/if}>Incomplete{if $order.status == "incomplete"} &#42;{else}&nbsp;&nbsp;&nbsp;{/if}</option>
						<option value="canceled"{if $order.status == "canceled"} selected="selected"{/if}>Canceled{if $order.status == "canceled"} &#42;{/if}</option>
						<option value="suspended"{if $order.status == "suspended"} selected="selected"{/if}>Suspended{if $order.status == "suspended"} &#42;{/if}</option>
						<option value="fraud"{if $order.status == "fraud"} selected="selected"{/if}>Fraud{if $order.status == "fraud"} &#42;{/if}</option>
						<option value="delete">Delete</option>
					</select>
				</span>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Invoice
			</div>
			<div class='rightColumn'>
				<a href="{$invoice_link}" class="lightUpLink">{$order.invoice_id}</a>
			</div>
			<div class='clearColumn'></div>
		</div>
		
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Order Total
			</div>
			<div class='rightColumn'>
				{$order.total}
			</div>
			<div class='clearColumn'></div>
		</div>
		
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Still due
			</div>
			<div class='rightColumn' style="white-space: nowrap;">
				<span style="color: {if $order.due > 0}red{elseif $order.due < 0}green{else}black{/if}">{$order.due|displayPrice}</span>
			</div>
			<div class='clearColumn'></div>
		</div>
		
			
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Summary of Order Items:
			</div>
			<div class='rightColumn'>
	{foreach from=$attached_items  item='item'}
				[{$item.type}] - {$item.title} <br />
	{foreachelse}
				None found?
	{/foreach}
			</div>
			<div class='clearColumn'></div>
		</div>
		
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Apply changes to attached items
			</div>
			<div class='rightColumn' style="white-space: nowrap;">
				<input type='checkbox' id='order_options[apply_to_all]' checked="checked" />
			</div>
			<div class='clearColumn'></div>
		</div>
		
		<div class='{cycle values="row_color1,row_color2"}'>
			<div class='leftColumn'>
				Send Email Notifications
			</div>
			<div class='rightColumn' style="white-space: nowrap;">
				<input type='checkbox' id='order_options[email_notifications]' checked="checked" />
			</div>
			<div class='clearColumn'></div>
		</div>
		{include file="HTML/separator.tpl"}
	
		<div class='centercolumn' style='position:relative; text-align:center'>
			{include file="HTML/add_button.tpl" link_is_really_javascript="1" link=$take_action label="Save"}
		</div>
		
{else}
Invalid order, or order is missing needed data.
{/if}
	</div>
</fieldset>
{if $order}
	<fieldset>
		<legend>Itemized Order Items</legend>
		<div>
			<table>
				<thead>
					<tr>
						<th class="col_hdr">Item</th>
						<th class="col_hdr">Type</th>
						{if $order.status == 'active'}
							<th class="col_hdr">Item Status</th>
						{/if}
						<th class="col_hdr">Cost</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$attached_items key='item_id' item='item'}
						{cycle values="row_color1,row_color2" assign="row_color"}
						<tr>
							<td class="{$row_color}">
								{if $order.status == 'active' && $item.displayInAdmin}
									{* Only display link if order is active, otherwise can't see much about it *}
									<a href="index.php?page=orders_list_items_item_details&item_id={$item_id}"># {$item_id}</a> - 
								{/if}
								{$item.title}
							</td>
							<td class="{$row_color}">{$item.type}</td>
							{if $order.status == 'active'}
								<td class="{$row_color}">{$item.status}</td>
							{/if}
							<td class="{$row_color}" style="text-align: right;">{$item.cost|displayPrice}</td>
						</tr>
						{foreach from=$item.children item='child'}
							<tr>
								<td class="{$row_color}"> &nbsp; &nbsp; - {$child.title}</td>
								<td class="{$row_color}">{$child.type}</td>
								{if $order.status == 'active'}<td class="{$row_color}"></td>{/if}
								<td class="{$row_color}" style="text-align: right;">{$child.cost|displayPrice}</td>
							</tr>
						{/foreach}
					{foreachelse}
						<tr><td colspan="3">No attached items.</td></tr>
					{/foreach}
						<tr>
							<td class="col_hdr" colspan="{if $order.status == 'active'}3{else}2{/if}" style="text-align: right;">Order Total:</td>
							<td class="col_hdr" style="text-align: right;">{$order.total}</td>
						</tr>
				</tbody>
			</table>
		</div>
	</fieldset>
{/if}

{if !$order}
	{include file="orders/get_order_form.tpl"}
{/if}