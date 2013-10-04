{* 6.0.7-3-gce41f93 *}
{if $page.type != 'sub_page'}
<div class="menu_page{if $page.current}_current{/if}">
	<a href="index.php?page={$page.index}&amp;mc={$mc}">{$page.title}</a>
</div>
{/if}