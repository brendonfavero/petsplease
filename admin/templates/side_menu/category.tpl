{* 6.0.7-3-gce41f93 *}
{if $category.title}
	{* Special case: Don't show categories without a title, specifically made 
		for "top level" pages such as home or release notes *}
	<div class="menu_category{if $category.current}_current{/if} cat_level{$level}" id="category_{$category.index}">
		{$category.title}
	</div>
	<div id="category_{$category.index}_contents" class="menu_category_contents">
		{if count($category.children_pages) > 0}
			{foreach from=$category.children_pages item="_page"}
				{include file="side_menu/page" page=$_page mc=$category.index}
			{/foreach}
		{/if}
		{if count($category.children_categories) > 0}
			{foreach from=$category.children_categories item="sub_category"}
				{include file="side_menu/category" category=$sub_category level={$level+1}}
			{/foreach}
		{/if}
	</div>
{/if}