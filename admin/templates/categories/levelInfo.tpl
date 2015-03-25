{* 7.4beta1-13-gb32528a *}

<nav class="breadcrumb">
	<div class="highlight">Subcategories Of:</div>
	<a href="index.php?page=category_config&amp;parent={$last_id}" class="highlight">Top Level</a>
	{foreach $parents as $p}
		<a href="index.php?page=category_config&amp;parent={$p.id}"{if $p@last} class="active"{/if}>{$p.name}{if $p.enabled=='no'} [Disabled!]{/if}</a>
	{/foreach}
</nav>

<div style="text-align: right;">
	{if $parents}
		<a href="index.php?page=category_edit&amp;category={$parent}&amp;p={$page}" class="mini_button editCatLink">Edit</a>
		<a href="index.php?page=category_manage&amp;category={$parent}&amp;p={$page}" class="mini_button lightUpLink">Manage</a>
	{else}
		<form action="index.php" method="get">
			<input type="hidden" name="page" value="category_config" />
			<label>Navigate to Category #<input type="text" name="parent" placeholder="123" size="4" /></label>
			<input type="submit" value="Go &gt;" />
		</form>
	{/if}
</div>

<br /><br />