{* 6.0.7-117-ge897589 *}

{if !$is_ajax}<input type="hidden" value="{$region.id}" /><div class="enabledButton">{/if}
{if $region.enabled=='yes'}
	<img src="admin_images/bullet_success.gif" alt="Enabled" style="width: 18px; height: 18px;" />
{else}
	<img src="admin_images/bullet_error.gif" alt="Disabled" style="width: 18px; height: 18px; opacity:0.4; filter:alpha(opacity=40);" />
{/if}
{if !$is_ajax}</div>{/if}

{if $is_ajax}

<script type="text/javascript">
//<![CDATA[
	{if $region.enabled=='yes'}
		$('row_{$region.id}').down().next().removeClassName('disabled')
			.select('.disabledSection').each(function(elem){ elem.hide(); });
	{else}
		$('row_{$region.id}').down().next().addClassName('disabled')
			.select('.disabledSection').each(function(elem){ elem.show(); });
	{/if}
//]]>
</script>

{/if}