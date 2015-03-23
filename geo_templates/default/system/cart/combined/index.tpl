{* 7.2.1-6-g63ad625 *}


<div class="combined_stepsBreadcrumb">
	{include file='cart_steps.tpl' g_resource='cart'}
</div>
{include file='inline_preview_box.tpl' g_resource='cart'}

{* Prevent cart steps from showing multiple times *}
{$cartSteps=false}
{$showPreviewBox=false}

{if $error_msgs.cart_error}
	<div class="field_error_box">
		{$error_msgs.cart_error}
	</div>
{/if}

<form method="post" action="{$process_form_url}" enctype="multipart/form-data" id="combined_form">
	<h1 class="title">{$txt1}</h1>
	{foreach $step_tpls as $step => $step_info}
		<div id="combined_{$step|replace:':':'-'}" class="combined_step_section">
			{include file='combined/step_section.tpl'}
		</div>
	{/foreach}
	<div class="center">
		{if $listing_types_allowed}
			{* Used by category changing *}
			<input type="hidden" id="listing_types_allowed" name="listing_types_allowed" value="{$listing_types_allowed}" />
		{/if}
		{if $showPreviewButton}
				<br /><br />
				<input type="submit" name="forcePreview" value="{$preview_button_txt}" class="button" />
				<br /><br />
				<input type="submit" name="submit" value="Submit Listing" class="button mainSubmit" />
			{elseif $forcePreviewButtonOnly}
				{* Use hidden main submit, that way can only be "clicked" using JS *} 
				<input type="submit" name="submit" value="1" style="display: none;" class="mainSubmit" />
			{else}
				<br /><br />
				<input type="submit" name="submit" value="{$submit_button_txt}" class="button mainSubmit" />
			{/if}
		<br /><br />
		<a href="{$cart_url}&amp;action=cancel" class="cancel">{$cancel_txt}</a>
	</div>
</form>
