{* 6.0.7-3-gce41f93 *}

{$admin_msgs}

<fieldset>
	<legend>Form Messages</legend>
	<div>
		<table>
			<tr class="col_hdr_top">
				<td>Message Name</td>
				<td>Content Type</td>
				<td style="width: 10%;"></td>
			</tr>
			{foreach $messages_list as $message}
				<tr class="{cycle values='row_color1,row_color2'}">
					<td>{$message.message_name}</td>
					<td>{$message.content_type}</td>
					<td style="white-space: nowrap;" class="center">
						<a href="index.php?page=admin_messaging_form_edit&amp;message_id={$message.message_id}" class="mini_button">Edit</a>
						<a href="index.php?page=admin_messaging_form_delete&amp;message_id={$message.message_id}&amp;auto_save=1" class="mini_cancel lightUpLink">Delete</a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="3">
						<div class="page_note_error">There are currently no form messages to display.</div>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
</fieldset>
<form action="index.php?page=admin_messaging_form_new" method="post">
	<fieldset>
		<legend>Create a New Form Message</legend>
		<div>
			{include file="messaging/forms/edit_form.tpl"}
		</div>
	</fieldset>
</form>