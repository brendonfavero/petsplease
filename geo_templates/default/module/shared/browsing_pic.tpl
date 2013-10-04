{* 7.0.2-173-g65f1ba6 *}
{if $module.module_display_header_row}
	<div class="content_box">
		<h1 class="title">{$header_title}</h1>
	</div>
{/if}
<div class="featured_items">
	{include file=$browse_tpl g_type='system' g_resource='browsing'}
</div>
