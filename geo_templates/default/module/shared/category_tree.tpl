{* 6.0.7-3-gce41f93 *}
{* Use same template for all 3 different category tree modules *}

<ul id="breadcrumb">
	{$category_tree_pre}
	{if !$fallback_tree_display && $categories}
		<li class="element highlight">{$link_label}</li>
		<li class="element"><a href="{$base_url}">{$link_text}</a></li>
		{foreach from=$categories item=c}
			<li class="{if $c.id}element{else}active{/if}">
				{if $c.id}<a href="{$base_url}&amp;b={$c.id}">{/if}
					{$c.label}
				{if $c.id}</a>{/if}
			</li>
		{/foreach}
	{elseif $fallback_tree_display}
		<li class="element">{$fallback_tree_display}</li>
	{/if}
</ul>