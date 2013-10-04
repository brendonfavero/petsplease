{* 9c85b30 *}
		<tr>
			<th class="col_ftr" colspan="4" style="text-align:left; padding-left:10px; white-space:nowrap; vertical-align:middle;">
				<img src="{$category.image}" alt="" width="20" height="18" style="vertical-align:middle;" />&nbsp;
				{if $category.breadcrumb}{$category.breadcrumb}{else}[ NO NAME ]{/if}
				{if $category.wiki_uri}<a href="http://geodesicsolutions.com/support/wiki/admin_menu/{$category.wiki_uri}" onclick="window.open(this.href); return false;">?</a>{/if}
			</th>
		</tr>
{foreach from=$category.children_pages item="_page"}
	{include file="page" page=$_page index=$_page.index}
{/foreach}
{foreach from=$category.children_categories item="sub_category" key="sub_index"}
	{include file="category.tpl" category=$sub_category index=$sub_index}
{/foreach}
