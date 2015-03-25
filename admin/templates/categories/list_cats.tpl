{* 7.4beta1-11-gc801b44 *}

{$adminMsgs}

<fieldset>
	<legend>Categories</legend>
	<div>
		{include file='categories/levelInfo.tpl'}
		
		<form action="index.php?parent={$parent}&amp;p={$page}" method="post" id="massForm">
			<table style="border: 2px solid">
				<thead>
					<tr class="col_hdr_top">
						<th style="width: 21px;"><input type="checkbox" id="checkAllValues" /></th>
						<th style="text-align: left;"><div style="float: right;">Has Category Specific ...</div>Category (ID#)</th>
						<th style="width: 60px;">Listings</th>
						<th style="width: 60px;">Enabled?</th>
						<th>Display Order</th>
						<th style="width: 90px;"></th>
					</tr>
					<tr class="col_ftr">
						<td colspan="6" style="padding-left: 20px;">With Selected:
							<a href="#" class="mini_button massEditButton">Mass Edit</a>
							<a href="#" class="mini_button copyButton">Copy</a>
							<a href="#" class="mini_button moveButton">Move</a>
							&nbsp; &nbsp; &nbsp; &nbsp;
							<a href="#" class="mini_cancel massDeleteButton">Delete</a>
							{$pagination}
						</td>
					</tr>
				</thead>
				<tfoot>
					<tr class="col_ftr">
						<td colspan="6" style="padding-left: 20px;">With Selected:
							<a href="#" class="mini_button massEditButton">Mass Edit</a>
							<a href="#" class="mini_button copyButton">Copy</a>
							<a href="#" class="mini_button moveButton">Move</a>
							&nbsp; &nbsp; &nbsp; &nbsp;
							<a href="#" class="mini_cancel massDeleteButton">Delete</a>
							{$pagination}
						</td>
					</tr>
				</tfoot>
				<tbody>
					{if $parent}
						{foreach $parents as $category}
							{if $category.enabled=='no'}
								<tr class="{cycle values='row_color1,row_color2'}" id="row_{$category.id}">
									<td class="center"></td>
									<td colspan="5">
										<div class="disabledSection">
											<strong style="color: red;">Warning:</strong> Parent Category <strong class="text_blue">{$category.name}</strong>  is <strong>Disabled</strong>!  Sub-Categories below are not currently usable on the site.
										</div>
									</td>
								</tr>
							{/if}
						{/foreach}
					{/if}
					{if !$categories}
						<tr><td colspan="10"><p class="page_note_error">No categories were found at this level!  You can create some new categories at this level using the "Add Category" or "Bulk Add" buttons at the bottom...</p></td></tr>
					{else}
						{foreach $categories as $category}
							<tr class="{cycle values='row_color1,row_color2'}" id="row_{$category.id}">
								<td class="center"><input type="checkbox" name="values[]" class="valueCheckbox" value="{$category.id}" /></td>
								<td{if $category.enabled=='no'} class="disabled"{/if}>
									<div class="cat-attached-icons">
										<img src="admin_images/icons/field.png" alt="Fields to Use" title="Fields to Use"
											{if $category.what_fields_to_use!='own'}class="cat-icon-disabled"{/if} />
										<img src="admin_images/icons/pricing.gif" alt="Category Specific Pricing" title="Category Specific Pricing"
											{if $category.price_plans==0}class="cat-icon-disabled"{/if} />
										<img src="admin_images/icons/question.png" alt="Category Questions" title="Category Questions"
											{if $category.questions==0}class="cat-icon-disabled"{/if} />
										{foreach $category.addon_extras as $icons}
											{foreach $icons as $icon}
												<img src="{$icon.src}" alt="{$icon.title|escape}" title="{$icon.title|escape}"
													{if !$icon.active}class="cat-icon-disabled"{/if} />
											{/foreach}
										{/foreach}
										{if $listing_types}
											&nbsp;
											{foreach $listing_types as $type => $type_info}
												<img src="{$type_info.src}" alt="{$type_info.label}" title="{$type_info.label} Allowed"
													{if $category.excluded_list_types.$type}class="cat-icon-disabled"{/if} />
											{/foreach}
										{/if}
									</div>
									<a href="index.php?page=category_config&amp;parent={$category.id}" class="cat-name">{$category.name|fromDB} ({$category.id})</a>
								</td>
								<td class="center">{$category.listing_count}</td>
								<td class="center">
									{include file='categories/enabled.tpl'}
								</td>
								<td class="center">{$category.display_order}</td>
								<td class="center" style="white-space: nowrap;">
									<a href="index.php?page=category_config&amp;parent={$category.id}" class="mini_button">Enter</a>
									<a href="index.php?page=category_edit&amp;category={$category.id}&amp;p={$page}" class="mini_button editCatLink">Edit</a>
									<a href="index.php?page=category_manage&amp;category={$category.id}&amp;p={$page}" class="mini_button lightUpLink">Manage</a>
								</td>
							</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
		</form>
		<br />
		<div class="center">
			<a href="index.php?page=category_create&amp;parent={$parent}" class="mini_button editCatLink">Add Category</a>
			<a href="index.php?page=category_create_bulk&amp;parent={$parent}" class="mini_button lightUpLink">Bulk Add</a>
			<br /><br />
			<a href="index.php?page=category_rescan_listings&amp;parent={$parent}" class="mini_button lightUpLink">Reset Listing Breadcrumbs</a>
		</div>
	</div>
</fieldset>
