{* 6.0.7-3-gce41f93 *}

{if $results}
	<table style="width: 100%;">
		<thead>
			<tr class="col_hdr_top">
				<td></td>
				<td>Full File Name</td>
			</tr>
		</thead>
		<tbody>
			{foreach from=$results item=row}
				<tr class="{cycle values='row_color1,row_color2'}">
					<td style="text-align: center; width: 10px;">
						<a href="index.php?page=design_manage&amp;location={$row.containingFolder|escape}" class="mini_button">View Containing Folder</a>
					</td>
					<td>
						{$row.filename}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{else}
	<div class="page_note_error">No template filenames matching search criteria.</div>
{/if}