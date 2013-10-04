{* 7.1.2-110-ga8cccef *}
{if $showPreviewBox}
	<div class="previewBox" style="display: none;">
		<div class="closeBoxX"></div>
		<h1 class="title lightUpTitle">{$messages.502088}</h1>
		<br />
		<div class="cntr">
			<a href="#" class="button confirmPreview" onclick="jQuery('.mainSubmit').click(); return false;">{$messages.502089}</a>
			<a href="#" class="cancel closeLightUpBox">{$messages.502090}</a>
			<br /><br />
		</div>
		<iframe src="{$classifieds_file_name}?a=cart&amp;action=forcePreview&amp;item={$preview_item_id}"
			class="listing_preview"></iframe>
	</div>
	<script>
	//<![CDATA[
	jQuery(document).ready(function () {
		//open the preview box when document is done loading
		jQuery(document).gjLightbox('open',jQuery('.previewBox').html());
	});
	//]]>
	</script>
{elseif $error_msgs.preview_error}
	<div class="field_error_box">
		{$error_msgs.preview_error}
	</div>
{/if}