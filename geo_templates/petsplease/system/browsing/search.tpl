{* 7.0.2-183-gfa134f3 *}

{include file='common/browse_mode_buttons.tpl'}

<div class="col_left">
	<div id="browsing_search">
		{addon author='pp_addons' addon='ppSearch' tag='searchSidebar' queryurl=$cfg.browse_url}
	</div>

	<div class="adspot160x600">
		<img src="{external file='images/ad_example_160x600.jpg'}" width="160" height="600" style="display:block;" />
	</div>
</div>

<div class="col_right">
	{include file=$browse_tpl}
</div>

{if $pagination}
	{$pagination}
{/if}