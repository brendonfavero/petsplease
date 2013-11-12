{* 7.0.2-183-gfa134f3 *}

<h1 class="title">Featured Pets and Products</h1>
<div class="content_box_1 gj_simple_carousel">
	{module tag='module_featured_pic_1' gallery_columns=6 module_thumb_width=120}
</div>

<div class="col_left">
	<div id="browsing_search">
		{addon author='pp_addons' addon='ppSearch' tag='searchSidebar' queryurl=$cfg.browse_url}
	</div>

	<div class="adspot300x250" style="margin-bottom: 24px;">
		<img src="{external file='images/ad_example_160x600.jpg'}" width="300" height="250" style="display:block;" />
	</div>

	<div class="adspot300x250">
		<img src="{external file='images/ad_example_160x600.jpg'}" width="300" height="250" style="display:block;" />
	</div>
</div>

<div class="col_right">
	{include file=$browse_tpl}
</div>

{if $pagination}
	{$pagination}
{/if}