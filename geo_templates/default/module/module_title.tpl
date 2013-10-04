{* 7.0.2-47-gd8a897c *}
{strip}
	{* Strip extra white-space from title... *}
	{if $preview_text}{$preview_text} : {/if}
	{$addonTextPre}
	{if $page_id==1}
		{* Viewing a listing, display Listing Title : Category Name 
		NOTE: anecdotal evidence suggests having the listing title before the category name is better for SEO
				To have the category name appear first, instead, simply swap their locations below
		*}
		{$titleOnly|fromDB} : {$category_name|strip_tags:false} 
	{elseif $page_id == 2}
		{$messages.2462}
	{elseif $page_id == 3}
		{* Viewing a category, display just the category name *}
		{$category_title|strip_tags:false}
	{elseif $page_id==84}
		{* Full sized images display *}
		{$messages.500767} {$category_name|strip_tags:false} : {$body_vars.title}
	{elseif $page_id==10210}
		{* Listing tags browse page *}
		{$messages.500874} {$listing_tag|replace:'-':' '|capitalize|escape}
	{else}
		{* Some unknown page *}
		{$text}
	{/if}
	{if $addonText}
		{$addonText}
	{/if}
	{if $page_number > 1}
		{* Display the page number *}
		{$messages.500573}{$page_number}
	{/if}
{/strip}