{* 612208c *}
<div class="listing_extra_item">
	<div class="listing_extra_cost">
		<input name="c[twitter_feed]" class="field" value="{$value}" />
	</div>
	<br />
	{$addon_text.input_username_instructions}
	<a href="show_help.php?addon=twitter_feed&amp;auth=geo_addons&amp;textName=listing_help_box" class="lightUpLink" onclick="return false;"><img src="{external file=$helpIcon}" alt="" /></a>
	<br />
	{if $error}<span class="error_message">{$error}</span>{/if}
	<br />
	<div class="clr"></div>
</div>
