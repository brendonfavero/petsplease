{* 7.3beta4-113-gc39b917 *}
<nav class="breadcrumb">
	<div class="highlight">{$messages.2}</div>
	<a href="{$classifieds_file_name}">{$messages.5}</a> 
	{foreach $category_tree as $cat}
		<a href="{$classifieds_file_name}?a=5&amp;b={$cat.category_id}"{if $cat@last} class="active"{/if}>
			{$cat.category_name}
		</a>
	{/foreach}
</nav>
