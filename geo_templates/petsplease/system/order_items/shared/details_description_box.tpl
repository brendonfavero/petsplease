{* 7.1.2-62-g19dfb6e *}
{if $fields->description->is_enabled}
	<div class="{if $error_msgs.description}field_error_row {/if}{cycle values='row_odd,row_even'}">
		<label for="main_description" class="field_label">
			{$messages.114}
			{if !$use_rte}
				<br />
				<span class="sub_note">{$messages.500173} <span id="chars_remaining">{$max_length_description}</span></span>
			{/if}
		</label>
		
		{if $error_msgs.description}
			<span class="error_message">{$messages.120}</span>
		{/if}
		
		<div class="clr"><br /></div>
		
		{if $use_rte && $messages.500235|strip:'' != ''}
			<a href="javascript:void(0)" onclick="geoWysiwyg.toggleTinyEditors();">{$messages.500235}</a>
			<br />
		{/if}
		<textarea id="main_description" name="b[description]" style="width: {$desc_wysiwyg_width}px; height: {$desc_wysiwyg_height}px;{if $field_config.textarea_wrap} white-space: pre;{/if}" class="editor field"
			onkeypress="return geoListing.checkLength(event,this)" onkeyup="return geoListing.getLength(event,this)">{$desc_clean}</textarea>
	</div>
{/if}