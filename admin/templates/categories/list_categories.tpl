{* 6.0.7-3-gce41f93 *}

{$admin_msgs}

<div class="breadcrumbBorder">
	<ul id="breadcrumb">
		<li class="current">Currently Viewing</li>
		<li><a href="index.php?mc=categories&page=categories_setup">Main</a></li>
		{foreach $cat_tree as $cat}
			<li>
				<a href="index.php?page=categories_setup&amp;b={$cat.category_id}">
					{$cat.category_name}
				</a>
			</li>
		{/foreach}
	</ul>
</div>
<br />
<fieldset>
	<legend>Current Categories</legend>
	<div>
		<div class="center">
			<a href="index.php?page=categories_add&amp;b={$category_id}" class="misc_controls">
				<img src="admin_images/design/icon_add.gif" alt='' class='misc_controls'>Add a New Category to this Level
			</a>
			<br /><br />
		</div>
		<table>
			<thead>
				<tr class="col_hdr">
					<th>{if $is_class_auctions}<span style="text-decoration: underline;">{/if}Category Name (id#){if $is_class_auctions}</span> (type){/if}</th>
					<th>Display Order</th>
					<th>Edit Category</th>
					<th>Delete Category</th>
					<th>Enter Category</th>
					<th>Edit Category Questions</th>
				</tr>
			</thead>
			<tbody>
				{foreach $categories as $cat}
					<tr class="{cycle values='row_color1,row_color2'}">
						<td>
							<a href="index.php?page=categories_setup&amp;b={$cat.category_id}">{$cat.category_name} ({$cat@key})</span></a>
							{if $is_class_auctions}
								{if $cat.listing_types_allowed==1}
									(C)
								{elseif $cat.listing_types_allowed==2}
									(A)
								{elseif $cat.listing_types_allowed==4}
									(R)
								{else}
									(C / A)
								{/if}
							{/if}
						</td>
						<td class="center">{$cat.display_order}</td>
						<td class="center">
							<a href="index.php?page=categories_edit&amp;b={$cat@key}" class="mini_button">Edit</a>
						</td>
						<td class="center">
							<a href="index.php?page=categories_delete&amp;b={$cat@key}" class="mini_cancel">Delete</a>
						</td>
						<td class="center">
							{if $cat.subcats}
								<a href="index.php?page=categories_setup&amp;b={$cat@key}" class="mini_button">Enter</a>
							{else}
								---
							{/if}
						</td>
						<td class="center">
							<a href="index.php?page=categories_questions&amp;b={$cat@key}" class="mini_button">Edit Questions</a>
						</td>
					</tr>
				{foreachelse}
					<tr><td colspan="6"><div class="page_note_error">There are currently no subcategories to display for this Category.</div></td></tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</fieldset>
<fieldset>
	<legend>Miscellaneous Category Controls</legend>
	<div style="padding: 10px;">
		<div style='padding: 5px;'>
			<a href="index.php?page=categories_add&amp;b={$category_id}" class='large_font'><img src='admin_images/design/icon_add.gif' alt='' class='misc_controls'>Add a New Category to this Level</a>
		</div>
		<div style='padding: 5px;'>
			<a href="index.php?page=categories_reset_count" class='large_font'><img src='admin_images/design/icon_reset.gif' alt='' class='misc_controls'>Reset all Category Counts</a>
		</div>
		<div style='padding: 5px;'>
			<a href="index.php?page=categories_copy_subcats" class='large_font'><img src='admin_images/design/icon_copy_cat.gif' alt='' class='misc_controls'>Copy Subcategories</a>
		</div>
		<div style='padding: 5px;'>
			<a href="index.php?page=categories_copy_questions" class='large_font'><img src='admin_images/design/icon_copy_questions.gif' alt='' class='misc_controls' border='none' align='absmiddle'>Copy Category Specific Questions to another Category</a>
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>Default Category Settings</legend>
	<div>
		<form action="" method="post">
			{foreach $languages as $language}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Default Contents to add to {ldelim}header_html} for <em>{$language.language}</em>{$header_html_tooltip}</div>
					<div class="rightColumn">
						<textarea name="header_html[{$language.language_id}]" rows="4" cols="75">{$language.header_html|escape}</textarea>
					</div>
					<div class="clearColumn"></div>
				</div>
			{/foreach}
			<div class="center">
				<input type="submit" name="auto_save" value="Save Default Settings" class="mini_button" />
			</div>
		</form>
	</div>
</fieldset>