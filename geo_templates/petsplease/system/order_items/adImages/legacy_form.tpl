{* 7.0.2-66-g28e6e7b *}

{if !$noscript}<div id="adimage_upload_instructions_legacy_box">{/if}
	<p class="page_note">{$images.legacy_description}</p>
{if !$noscript}</div>{/if}

<div id="adlegacyUploadBox{$noscript}">
	{if $error_msgs.images_error}
		<div class="field_error_box">
			{$error_msgs.images_error}
		</div>
	{/if}

	{if count($images.images_captured) > 0}
		{include file="images/display_images.tpl"}
	{/if}

	{if count($images.not_keys_yet) > 0}
		<table style="margin: 0 auto; border: none;" class="content_box">
			<tr>
				<td colspan="4">
					{if $images.old_config.allow_upload_images}
						<div class="note_box">
							<strong>{$messages.643}</strong> {$images.old_config.maximum_upload_size}
						</div>
					{/if}
				</td>
			</tr>

			<tr class="column_header">
				<td style="width: 25px;"></td>
				{if $images.old_config.allow_url_referenced}
					<td>
						{$messages.166}
					</td>
				{/if}
				{if $images.old_config.allow_upload_images}
					<td>
						{$messages.169}
					</td>
				{/if}
				{if $images.imgMaxTitleLength}
					<td>{$messages.500371}</td>
				{/if}
			</tr>
			{foreach from=$images.not_keys_yet item='img_key'}
				<tr>
					<td class="center">
						<strong>&nbsp;{$img_key})&nbsp;</strong>
					</td>
					{if $images.old_config.allow_url_referenced}
						<td>
							<input type="text" name="c[{$img_key}][url][location]" size="25" maxlength="100" class="field" />{if $img_key == 1}&nbsp;*{/if}
						</td>
					{/if}
					{if $images.old_config.allow_upload_images}
						<td>
							<input type="file" name="d[{$img_key}]" />{if $img_key == 1}&nbsp;*{/if}
						</td>
					{/if}
					{if $images.imgMaxTitleLength}
						<td>
							<input type="text" name="c[{$img_key}][text]" size="25" maxlength="100" class="field" />&nbsp;
						</td>
					{/if}
				</tr>
			{/foreach}
		</table>
	{/if}
</div>
