{* 6.0.7-61-g3a785be *}

<table style="width: 100%;">
	<tr class="{cycle values='row_color1,row_color2'}">
		<td class="stats_txt2">
			Installed Version:
		</td>
		<td class="stats_txt3">
			<span class="text_blue">{$version}</span>
		</td>
	</tr>
	<tr class="{cycle values='row_color1,row_color2'}">
		<td class="stats_txt2">
			Current Version:
		</td>
		<td class="stats_txt3">
			<span class="text_blue">{if $latestVersion}{$latestVersion}{else}Unknown{/if}</span>
		</td>
	</tr>
</table>
<br />
<div class="page_note">
	{if !$latestVersion}
		<img src="admin_images/bullet_notice.gif" alt="Notice" style="margin: 5px; vertical-align: middle;" />
		<strong style='color: red;'>Error retrieving latest version.</strong>
	{elseif $is_latest}
		<img src="admin_images/bullet_success.gif" alt="Up to Date" style="margin: 5px; vertical-align: middle;" />
		<strong style='color: green;'>Software is up to date.</strong>
	{else}
		<img src="admin_images/bullet_error.gif" alt="Old Version" style="margin: 5px; vertical-align: middle;" />
		<strong style="color: red;">There is a new version available!</strong>
		<a href="http://geodesicsolutions.com/support/updates/?product=GeoCore&amp;version={$version}" class="mini_button" onclick="window.open(this.href); return false;">Update Instructions</a>
	{/if}
	<div class="clr"></div>
</div>
<div class="clear"></div>