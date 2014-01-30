{* 7.1.2-49-g2dcab01 *}

{include file="cart_steps.tpl" g_resource="cart"}

<div class="content_box">
	<h1 class="title">{$title1}</h1>
	<h1 class="subtitle">{$title2} {$help_link}</h1>
	<p class="page_instructions">
		{$desc1}
		{if $parent_cat_id ne 0}
			<strong class="text_highlight">{$parent_cat_name}</strong> {$text1} {$num_cats}{$text2}
		{/if}
	</p>
	
	{if $error_msgs.cart_error}
		<div class="field_error_box">
			{$error_msgs.cart_error}
		</div>
	{/if}
	
	<p class="page_instructions">{$desc2}</p>
	
	<ul id="listing_categories">
		{foreach from=$cat_data key=i item=cat}
			{if $cat.category_id eq 308 or $cat.category_id eq 315 or $cat.category_id eq 413}
				<li class="element" style="width:100%; padding-right:7px">
			{else}
				<li class="element" style="width:49%; padding-right:7px">
			{/if}
					<a href="{$process_form_url}&amp;b={$cat.category_id}">
						{if $display_cat_image ne 0 AND $cat.category_image ne ""}
							<img src="{external file=$cat.category_image}" alt="" /> &nbsp;
						{/if}
						<span class="category_title">{$cat.category_name|fromDB}</span>
						{if $display_cat_description && $cat.description}
							<p class="category_description">{$cat.description|fromDB}</p>
						{/if}
					</a>
				</li>
		{/foreach}
	</ul>
	<div class="clr"><br /></div>
	
	{if $listings_only_in_terminal ne 1}
		<div class="center">
			{$text3}
			<a href="{$process_form_url}&amp;b={$parent_cat_id}&amp;c=terminal" class="button">
				{$parent_cat_name}
			</a>
		</div>
	{/if}
</div>

<br />
{if !$steps_combined}
	<div class="center">
		<a href="{$cart_url}&amp;action=cancel" class="cancel">{$cancel_txt}</a>
	</div>
{/if}
