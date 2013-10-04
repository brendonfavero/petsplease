{* 7.0.2-173-g65f1ba6 *}
{* Allow built in main browsing template to be used... *}
{if $module.module_display_header_row && $header_title}
	<h1 class="title">{$header_title}</h1>
{/if}
{include file=$browse_tpl g_type='system' g_resource='browsing'}