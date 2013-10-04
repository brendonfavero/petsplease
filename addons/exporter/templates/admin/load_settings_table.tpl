{* 7c79407 *}

<table>
	<thead>
		<tr class="col_hdr">
			<th><input type="checkbox" id="checkAllLoad" /></th>
			<th>Name</th>
			<th>File Exported</th>
			<th>Created</th>
			<th>Last Updated</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$loadSettings item=row}
			<tr class="{cycle values='row_color1,row_color2'}">
				<th><input type="checkbox" class="deleteLoadCheckbox" name="delete_settings[]" value="{$row.name|fromDB|escape}" /></th>
				<td>{$row.name|fromDB|escape}</td>
				<td>{$row.filename|fromDB|escape}.{$row.export_type}</td>
				<td>{$row.created|format_date}</td>
				<td>{$row.last_updated|format_date}</td>
				<td>
					<input type="hidden" value="{$row.name|fromDB|escape}" />
					<button class="mini_button loadButtons">Load</button>
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="5"><div class="page_note_error">No saved export settings found.</div></td>
			</tr>
		{/foreach}
	</tbody>
</table>
{if $loadSettings}
	<input type="submit" value="Delete Selected" class="mini_cancel" id="submitDelete" />
{/if}