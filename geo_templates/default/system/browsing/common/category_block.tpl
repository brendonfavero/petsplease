{* 7.1beta4-56-g3c7a61e *}

{if $text.back_to_normal_link}
	<a href="{$classifieds_file_name}?a=5&amp;b={$category}" class="button">{$text.back_to_normal_link}</a>
	<br /><br />
{/if}
	
	{* this can appear in multiple places on the page, so run the code once and capture to a smarty var *}
	{capture assign=category_breadcrumb}
		{if $string_tree or $array_tree or $category_tree_pre or $category_tree_post}
			<ul id="breadcrumb">
				{if $category_tree_pre}
					{* Allow outside sources add to category tree *}
					{$category_tree_pre}
				{/if}
				{if $array_tree}
					<li class="element highlight">{$text.tree_label}</li>
					<li class="element"><a href="{$link_top}">{$text.main_category}</a></li>
					{foreach from=$array_tree item=cat name=tree}
						{if not $smarty.foreach.tree.last}<li class="element"><a href="{$link}{$cat.category_id}">{else}<li class="element active">{/if}
						{$cat.category_name}
						{if not $smarty.foreach.tree.last}</a>{/if}
						</li>
					{/foreach}
				{elseif $string_tree}
					<li class="element">{$string_tree}</li>
					{* is that anything like string cheese? "string_treese," perhaps? *}
				{/if}
				{if $category_tree_post}
					{* Allow outside sources add to category tree *}
					{$category_tree_post}
				{/if}
			</ul>
		{/if}
	{/capture}
	
	{*
		$tree_display_mode:
			0: show tree below subcategories
			1: show tree above subcategories
			2: show tree below AND above subcategories
			3: do not show tree
	*}
	
	{if $tree_display_mode == 1 or $tree_display_mode == 2}
		{$category_breadcrumb}
	{/if}

	{if $show_no_subcats}
		<div class="center sub_note">
			{$text.no_subcats} {$current_category_name}
		</div>
	{/if}

	{if $show_subcats}
		{foreach from=$categories item=cats key=column}
			<div class="category_column" style="width: {$column_width};">
				<ul class="categories">
					{foreach from=$cats item=cat}
						<li class="element category_{$cat.category_id}">
							<div class="main_cat_title">
								{if $cat.category_image}<img src="{external file=$cat.category_image}" alt="" />{/if}
								<a href="{$link}{$cat.category_id}">{strip}
									<span class="category_title">
										{$cat.category_name}
									</span>
								{/strip}</a>
								{if $cat.category_count}<span class="listing_counts">{$cat.category_count}</span>{/if}
								{if $cat.new_ad_icon}{$cat.new_ad_icon}{/if}
							</div>
							{if $show_descriptions}
								<p class="category_description">{$cat.category_description}</p>
							{/if}
							{if $cat.sub_categories}
								<ul class="sub_categories">
									{foreach from=$cat.sub_categories item=sub_cat}
										<li class="element subcategory_{$sub_cat.category_id}">
											<a href="{$link}{$sub_cat.category_id}">
												<span class="category_title">
													{$sub_cat.category_name|fromDB}
												</span>
											</a>
										</li>
									{/foreach}
								</ul>
							{/if}
						</li>
					{/foreach}
				</ul>
			</div>
		{/foreach}
		<div class="clr"></div>
	{/if}

	{if $tree_display_mode == 0 or $tree_display_mode == 2}
		{$category_breadcrumb}
	{/if}