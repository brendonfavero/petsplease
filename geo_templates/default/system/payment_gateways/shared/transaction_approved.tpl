{* 6.0.7-3-gce41f93 *}

<div class="content_box">
	<h1 class="title">{$page_title}</h1>
	
	{if $success_failure_message}
		<p class="page_instructions">{$page_desc}</p>
		<div class="success_box">
			{$success_failure_message}
		</div>
	{else}
		<div class="success_box">
			{$page_desc}
		</div>
	{/if}
	
	<br />
	
	{if $cart_items}
		<div>
			<h1 class="title">{$messages.500896}</h1>
			{include file='display_cart/index.tpl' g_resource='cart' view_only=1 items=$cart_items}
			<div class="clear"></div>
		</div>
		<br />
	{/if}
	{if $invoice_url}
		<div class="center">
			<a href="{$invoice_url}" class="button{if $in_admin} lightUpLink{/if}"{if $in_admin} onclick="return false;"{/if}>
				{$messages.500949}
			</a>
		</div>
	{/if}
	{if $logged_in&&!$in_admin}
		<div class="center">
			<a href="{$my_account_url}" class="button">
				{$my_account_link}
			</a>
		</div>
	{/if}
	{if $in_admin && $user.id}
		<div class="center">
			<a href="index.php?page=orders_list&narrow_order_status=all&narrow_gateway_type=all&narrow_admin={$user.id}&sortBy=order_id&sortOrder=down" class="button">
				Recently Created Orders
			</a>
		</div>
	{/if}
</div>
