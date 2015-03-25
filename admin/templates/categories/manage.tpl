{* 7.3.1-103-g1c4ef69 *}

<div class="closeBoxX"></div>
<div class="lightUpTitle" id="newConfirmTitle" style="min-width: 250px;">Manage Category {$category.name} ({$category_id})</div>
<div class="center" style="padding: 20px;">
	<a href="index.php?mc=&amp;page=fields_to_use&amp;categoryId={$category_id}" class="mini_button" style="width: 120px; margin:2px;">Fields To Use</a>
	<br />
	<a href="index.php?mc=&amp;page=category_durations&amp;c={$category_id}" class="mini_button" style="width: 120px; margin:2px;">Durations</a>
	<br />
	<a href="index.php?page=category_templates&amp;b={$category_id}" class="mini_button lightUpLink" style="width: 120px; margin:2px;">Templates</a>
	<br />
	<a href="index.php?mc=&amp;page=categories_questions&amp;b={$category_id}" class="mini_button" style="width: 120px; margin:2px;">Questions</a>
	{if $addon_links}
		<br /><br />
		<strong>Addon Abilities</strong>
		
		{foreach $addon_links as $links}
			{foreach $links as $link}
				<br />
				<a href="{$link.href}" class="mini_button" style="width: 120px; margin:2px;">{$link.label}</a>
			{/foreach}
		{/foreach}
	{/if}
	<br /><br />
	<hr />
	<a href="index.php?page=category_copy_parts&amp;categoryId={$category_id}" class="mini_button lightUpLink" style="width: 120px; margin:2px;">Copy .... To ...</a>
</div>

