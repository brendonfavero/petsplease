{* 9c85b30 *}
{$admin_messages}
<form action="index.php?page=addon_multi_admin_users" method="post">
<fieldset>
	<legend>Admin Users</legend>
	<div>
		<table>
			<thead>
				<tr><th class="col_hdr_left">Admin User</th><th class="col_hdr_left">Admin Group</th><th class="col_hdr_left">&nbsp;</th></tr>
			</thead>
			<tbody>
{foreach from=$users item="user"}
	{cycle values="row_color1,row_color2" assign="row_color"}
				<tr>
					<td class="{$row_color}">
						<a href="index.php?page=addon_multi_admin_user_edit&amp;user_id={$user.user_id}">{$user.username}</a>
					</td>
					<td class="{$row_color}">
						{if $user.group_id}<a href="index.php?page=addon_multi_admin_group_edit&group_id={$user.group_id}">{$user.group_name}</a>{else}None{/if}
					</td>
					<td class="{$row_color}" style="text-align:center;">
						{$delete_button|replace:"(USER)":$user.user_id}
					</td>
				</tr>
{foreachelse}
	<tr><td colspan="3" class="medium_font" align="center"><div class="page_note_error">No Admin Users</div></td></tr>
{/foreach}
				<tr>
					<td class="col_ftr">
						<input type="text" name="user_add" />
					</td>
					<td class="col_ftr">
						{$group_dropdown}
					</td>
					<td class="col_ftr center">
						<input type="submit" name="auto_save" value="Create New Admin User"  />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</fieldset>
</form>