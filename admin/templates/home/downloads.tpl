{* 7.2beta3-47-g8cdc8f8 *}

{* so we don't have to have super long thing for each link *}
{capture assign='extrnLink'}class="mini_button" style="white-space: normal;" onclick="window.open(this.href); return false;"{/capture}


<fieldset>
	<legend>Software Downloads</legend>
	<div class="medium_font">
		<table style="width: 100%;">
			<tr class="{cycle values='row_color1,row_color2'}">
				<td class="stats_txt2">
					Download Access Expires:
				</td>
				<td class="stats_txt3_wide">
					<span class="text_blue">{$stats.downloadExpire|date_format}</span>
				</td>
			</tr>
		</table>
		<br />
		<div class="page_note">
			{if $stats.downloadLeft||$stats.downloadExpire=='never'}
				<img src="admin_images/bullet_success.gif" alt="Download Access Active" style="margin: 5px; vertical-align: middle; float: left;" />
				{if $stats.downloadLeft}
					You have <strong class="text_blue">{$stats.downloadLeft}</strong>
					remaining download access to new software updates.
				{elseif $stats.downloadExpire=='never'}
					Your paid download never expires.
				{/if}
			{else}
				<img src="admin_images/bullet_error.gif" alt="Notice" style="margin: 5px; vertical-align: middle;float: left;" />
				Download access has <strong style="color: red;">Expired</strong>.
				You will not be able to download software versions released after <strong class="text_blue">{$stats.downloadExpire|date_format}</strong>.
				See download access renewal options below.
			{/if}
			<div class="clr"></div>
		</div>
		<a href="#" id="downloadToggle">See Options</a>
		<div id="download_Links" style="display: none;">
			<div style="margin-top: 15px;">
				{if $stats.downloadLeft||$stats.downloadExpire=='never'}
					<ul class="home_links center">
						<li><a href="https://geodesicsolutions.com/client-area/task,my_downloads/" {$extrnLink}>My Downloads</a></li>
						{if $stats.packageId}
							<li><a href="https://geodesicsolutions.com/client-area/task,my_package_details/package_id,{$stats.packageId}/category_id,370/tab,downloads/" {$extrnLink}><span class="text_blue">Latest</span> Release Downloads</a></li>
							<li><br /><a href="https://geodesicsolutions.com/client-area/task,my_package_details/package_id={$stats.packageId}/category_id,372/tab,downloads/" {$extrnLink}><span style="color: red;">_Developer BETA</span> Release Downloads</a></li>
						{/if}
					</ul>
				{else}
					<ul class="home_links">
						<li><a href="https://geodesicsolutions.com/client-area/{if $stats.packageId}task,product/product_id,73/package_id,{$stats.packageId}/{else}task,choose_parent/product_id,73/{/if}" {$extrnLink}>Extend Download Access</a></li>
						{if $stats.packageId}
							<li><a href="https://geodesicsolutions.com/client-area/task,my_package_details/package_id,{$stats.packageId}/category_id,371/tab,downloads/" {$extrnLink}><span class="text_blue">Previous</span> Release Downloads</a></li>
						{/if}
					</ul>
				{/if}
			</div>
			<div class="clr"></div>
		</div>
	</div>
</fieldset>