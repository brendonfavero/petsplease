{* 6.0.7-3-gce41f93 *}


<div id="menuTop" style="display:none;">
	<div id="category_home" class="menu_category_current cat_level0">
		<div id="menuControlButtons">
		</div>
		Admin Menu
	</div>
	<div id="category_home_contents" class="menu_category_contents">
{foreach from=$page_structure item="top_category"}
	{foreach from=$top_category.children_categories item="category"}
		{include file="side_menu/category" level='1'}
	{/foreach}
{/foreach}
	</div>
</div>