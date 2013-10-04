{* 9c85b30 *}
{$admin_messages}
<form action="index.php?page=addon_multi_admin_groups" method="post">
<fieldset>
	<legend>Admin Groups</legend>
	<div>
		<table>
			<thead>
				<tr>
					<th class="col_hdr_left">Group Name</th>
					<th class="col_hdr"># Users</th>
					<th class="col_hdr_left">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
{foreach from=$groups item="group"}
	{cycle values="row_color1,row_color2" assign="row_color"}
				<tr class="{$row_color}">
					<td>
						<a href="index.php?page=addon_multi_admin_group_edit&amp;group_id={$group.group_id}">{$group.name}</a>
					</td>
					<td class="center">
						{$group.user_count}
					</td>
					<td class="center">
						{$delete_button|replace:"(GROUP)":$group.group_id}
					</td>
				</tr>
{foreachelse}
	<tr><td colspan="3" align="center"><div class="page_note_error">No Existing Groups</div></td></tr>
{/foreach}
				<tr>
					<td class="col_ftr center" colspan="3">
						<input type="text" name="group_add" />
						<input type="submit" name="auto_save" value="Create New Admin Group"  />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</fieldset>
</form>