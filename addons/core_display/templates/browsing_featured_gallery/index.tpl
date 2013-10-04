{* 7.1beta1-1139-g3d5df08 *}

<div class="content_box">
	{if $msgs.featured_title}
		<h1 class="title">{$msgs.featured_title} {$current_category_name}</h1>
	{/if}
	<div class="featured_browsing{if $featured_carousel} gj_simple_carousel{/if}">
		{include file=$browse_tpl g_type='system' g_resource='browsing'}
	</div>
</div>
<div class="clr"><br /></div>