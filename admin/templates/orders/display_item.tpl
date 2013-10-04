{* 6.0.7-3-gce41f93 *}
{$adminMessages}
<fieldset>
	<legend>General Item Info</legend>
	<div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Item ID
			</div>
			<div class="rightColumn">
				{$item.id}
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Attached to Order
			</div>
			<div class="rightColumn">
				{if $item.order_id}
					<a href="index.php?page=orders_list_order_details&amp;order_id={$item.order_id}"># {$item.order_id}</a>
					{if $item.orderStatus != 'active'}
						<br />(order status: {$item.orderStatus})
					{/if}
				{else}
					Unknown (no data)
				{/if}
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Price Plan Settings
			</div>
			<div class="rightColumn">
				<a href="{$item.pricePlanUrl}">{$item.pricePlan}</a>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Item last modified
			</div>
			<div class="rightColumn">
				{$item.date|date_format}
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				User
			</div>
			<div class="rightColumn">
				{if $item.user_id > 0}
					<a href="index.php?page=users_view&amp;b={$item.user_id}">{$item.username}</a>
				{elseif $item.order_id}
					Anonymous
				{else}
					Unknown (No data)
				{/if}
				
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Item Type
			</div>
			<div class="rightColumn">
				{$item.type}
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values="row_color1,row_color2"}">
			<div class="leftColumn">
				Status
			</div>
			{if $item.orderStatus === 'active'}
				<div class="rightColumn">
					<span id="item_status{$item.id}">
						<select name="item_status" id="item_status_val{$item.id}">
							<option value="active"{if $item.status == 'active'} selected="selected"{/if}>Active{if $item.status == "active"} &#42;{/if}</option>
							<option value="pending"{if $item.status == 'pending'} selected="selected"{/if}>Pending{if $item.status == "pending"} &#42;{/if}</option>
							<!-- <option value="edit">Edit Details</option> 
							<option value="pending_alter"{if $item.status == 'pending_alter'} selected="selected"{/if}>Needs Alteration{if $item.status == "pending_alter"} &#42;{/if}</option> -->
							<option value="declined"{if $item.status == 'declined'} selected="selected"{/if}>Declined{if $item.status == "declined"} &#42;{/if}</option>
							<option disabled="disabled"></option>
							<option value="delete">Delete</option>
						</select>
					</span>
					{$item.set_status_link}
					<br />
					<label class="small_font"><input type="checkbox" checked="checked" id="send_email" value="1" /> Send E-Mail Notifications</label>
				</div>
			{else}
				<div class="rightColumn">
					Order Not Active, Cannot Change Item's Status.
				</div>
			{/if}
			<div class="clearColumn"></div>
		</div>
	</div>
</fieldset>
{if $itemDetails}
<fieldset>
	<legend>More Details</legend>
	<div>
		{$itemDetails}
	</div>
</fieldset>
{/if}