{* 7.3.1-97-g9414759 *}

{$admin_msgs}

<br />
<strong>Category:</strong> {$info.name}
<br /><br />

<form action="index.php?page=category_durations&amp;c={$category_id}" method="post">
	<fieldset>
		<legend>Category Listing Durations</legend>
		<div>
			<table>
				<tr class="col_hdr_top">
					<th>Listing Duration (Displayed)</th>
					<th>Listing Duration (# Days)</th>
					<th></th>
				</tr>
				{foreach $lengths as $length}
					<tr class="{cycle values='row_color1,row_color2'}">
						<td>{$length.display_length_of_ad}</td>
						<td>{$length.length_of_ad}</td>
						<td>
							<a href="index.php?page=category_durations_delete&amp;c={$category_id}&amp;length_id={$length.length_id}&amp;auto_save=1" class="mini_cancel lightUpLink">Delete</a>
						</td>
					</tr>
				{/foreach}
				<tr class="col_ftr">
					<th>Displayed: <input type="text" name="display_length_of_ad" /></th>
					<th>Days: <input type="text" name="length_of_ad" /></th>
					<th><input type="submit" name="auto_save" value="Add Duration" /></th>
				</tr>
			</table>
		</div>
	</fieldset>
</form>

<div class="center">
	<a href="index.php?page=category_config&amp;parent={$info.parent_id}" class="back_to">
		<img src='admin_images/design/icon_back.gif' alt="Back to.." class="back_to" />
		Back to Categories
	</a>
</div>