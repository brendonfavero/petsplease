{* 7.0.2-183-gfa134f3 *}

<h1 class="title">Featured Pets and Products</h1>
<div class="content_box_1 gj_simple_carousel">
	{module tag='module_featured_pic_1' gallery_columns=4 module_thumb_width=80}
</div>

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