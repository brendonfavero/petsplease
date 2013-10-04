{* 9c85b30 *}
{cycle values='row_color1,row_color2' assign='row_color'}
<tr class="small_font" style="height: 20px;" name="{$page.index}">
	<td class="{$row_color}" style='text-align: left;'>
		<div style="white-space:nowrap; padding-left: 30px;">
			{if $page.type != 'sub_page'}<a href="index.php?mc={$page.parent}&amp;page={$page.index}">{/if}{$page.breadcrumb}{if $page.type eq 'sub_page'} (hidden){else}</a>{/if}
			<a href="http://geodesicsolutions.com/support/wiki/admin_menu/{$page.wiki_uri}" onclick="window.open(this.href); return false;">?</a>
		</div>
	</td>
	<td style="text-align: center;" class="{$row_color}">
		<input type="checkbox" onclick="javascript: updateChecks(this)" 
			class="displayBox" name="display[{$index|escape}]" value="1"{if $display_permissions.$index} checked="checked"{/if} />
	</td>
	<td style="text-align: center;" class="{$row_color}">
		<input type="checkbox" class="updateBox" name="update[{$index|escape}]" value="1"{if $update_permissions.$index} checked="checked"{/if} />
	</td>
</tr>
{if count($page.children_pages) > 0}
	{foreach from=$page.children_pages item="_page"}
		{include file="page" page=$_page index=$_page.index}
	{/foreach}
{/if}