{* 7.3.1-69-g0d1cea8 *}

{if !$is_ajax}<input type="hidden" value="{$category.id}" /><div class="enabledButton">{/if}
{if $category.enabled=='yes'}
	<img src="admin_images/bullet_success.gif" alt="Enabled" style="width: 18px; height: 18px;" />
{else}
	<img src="admin_images/bullet_error.gif" alt="Disabled" style="width: 18px; height: 18px; opacity:0.4; filter:alpha(opacity=40);" />
{/if}
{if !$is_ajax}</div>{/if}

{if $is_ajax}

<script type="text/javascript">
//<![CDATA[
	{if $category.enabled=='yes'}
		$('row_{$category.id}').down().next().removeClassName('disabled')
			.select('.disabledSection').each(function(elem){ elem.hide(); });
	{else}
		$('row_{$category.id}').down().next().addClassName('disabled')
			.select('.disabledSection').each(function(elem){ elem.show(); });
	{/if}
//]]>
</script>

{/if}