{* 7.0.3-289-g5175606 *}

{$messages.2} <a href="{$classifieds_file_name}" class="display_ad_category_tree">{$messages.5}</a> &gt; 
{foreach $category_tree as $cat}
	<a href="{$classifieds_file_name}?a=5&amp;b={$cat.category_id}" class="display_ad_category_tree">
		{$cat.category_name}
	</a>
	{if !$cat@last} &gt; {/if}
{/foreach}
