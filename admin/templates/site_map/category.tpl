{* 6.0.7-3-gce41f93 *}
{if $category.title}
	<li>
		<div>
			<img src="{$category.image}" alt="" />&nbsp;{$category.title}
		</div>
	{if count($category.children_pages) > 0}
		<ul>
			{foreach from=$category.children_pages item="_page"}
				{include file="site_map/page" page=$_page mc=$category.index}
			{/foreach}
		</ul>
	{/if}
	{if count($category.children_categories) > 0}
		<ul>
			{foreach from=$category.children_categories item="sub_category"}
				{include file="site_map/category" category=$sub_category}
			{/foreach}
		</ul>
	{/if}
		
	</li>
{/if}