{include file="cart_steps.tpl" g_resource="cart"}

<div class="content_box">
	<h1 class="title">Store Product</h1>

<!-- 	<p class="page_instructions">
	</p>
 -->	
	{if $error_msgs.cart_error}
		<div class="field_error_box">
			{$error_msgs.cart_error}
		</div>
	{/if}
	
<!-- 	<p class="page_instructions">{$desc2}</p>
 -->	

 	Is this listing a store product or a classified?<br>
 	<a href="{$process_form_url}&type=storeproduct" class="button">Store Product</a>
 	<a href="{$process_form_url}&type=classified" class="button">Classified</a>

	<div class="clr"><br /></div>
</div>

<br />

<div class="center">
	<a href="{$cart_url}&amp;action=cancel" class="cancel">Cancel</a>
</div>
