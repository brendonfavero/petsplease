{* 7.1beta3-63-g9b21c1a *}

{$adminMsgs}

<fieldset>
	<legend>Multi-Level Field Groups</legend>
	<div>
		<table class="leveled_fields">
			<thead>
				<tr class="col_hdr_top">
					<th>Group Admin Label (ID#)</th>
					<th style="white-space: nowrap;"># of Levels</th>
					<th style="white-space: nowrap;">Total # Values</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{foreach $fields as $field}
					<tr class="{cycle values='row_color1,row_color2'}">
						<td style="text-align: right;">
							{$field.label} ({$field.id})
							<a href="index.php?page=leveled_field_edit&amp;leveled_field={$field.id}" class="mini_button lightUpLink">Edit</a>
						</td>
						<td style="text-align: right;">
							{$field.max_level}
							<a href="index.php?page=leveled_field_levels&amp;leveled_field={$field.id}" class="mini_button">Show Levels</a>
						</td>
						<td style="text-align: right;">
							{$field.value_count}
							<a href="index.php?page=leveled_field_values&amp;leveled_field={$field.id}" class="mini_button">Show Values</a>
						</td>
						<td class="center" style="white-space: nowrap;">
							<a href="index.php?page=leveled_fields_delete&amp;leveled_field={$field.id}" class="mini_cancel lightUpLink">Delete</a>
						</td>
					</tr>
				{foreachelse}
					<tr><td colspan="4">
						<p class="page_note_error">There were no leveled fields found!  You can add one below.</p>
					</td></tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</fieldset>

<fieldset>
	<legend>Add New Multi-Level Field Group</legend>
	<div>
		<form method="post" action="index.php?page=leveled_fields_add">
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Group Label (Only Used in Admin Panel)
				</div>
				<div class="rightColumn">
					<input type="text" name="new_label" placeholder="e.g. Vehicle Type" size="30" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<input type="submit" name="auto_save" value="Create New Group" />
			</div>
		</form>
	</div>
</fieldset>