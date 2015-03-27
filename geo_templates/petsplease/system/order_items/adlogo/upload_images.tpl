{* 7.1.2-64-gbe87c06 *}
{if $main_type == 'listing_edit'}
	{* Template designers: if you want to use a totally different template file for edit
	   listings, you would surround it with these if smarty tags.  The same goes for classified,
	   auction, and reverse_auction, just change 'listing_edit' as appropriate in the if stmt *}
	
{/if}

<div id="adlogostartingButtonContainer">
	<span id="adlogospanButtonPlaceHolder"></span>
</div>

<h2 class="title">
	Listing Logo
	
	{*{if $adlogo.useStandardUploader && $messages.500916}
		{if $adlogo.description}
			<a href="#" class="show_instructions_button" id="adlogo_upload_instructions" style="display: none;">{$messages.500916}</a>
		{/if}
		{if $adlogo.legacy_description}
			<a href="#" class="show_instructions_button" id="adlogo_upload_instructions_legacy" style="display: none;">{$messages.500916}</a>
		{/if}
	{elseif $messages.500916}
		{if $adlogo.legacy_description}
			<a href="#" class="show_instructions_button" id="adlogo_upload_instructions_legacy">{$messages.500916}</a>
		{/if}
	{/if}*}
</h2>

{if $adlogo.useStandardUploader}
	<div id="adlogoloadingBox" style="display: none;">
		<img src="{if $in_admin}../{/if}{external file=$adlogo.uploading_image}" alt="Loading..." />{$messages.500704}<br /><br />
		{$messages.500705}<a href="http://get.adobe.com/flashplayer/" onclick="window.open(this.href); return false;">{$messages.500706}</a>
		{$messages.500707}<span class="normalUploaderShowLink" onclick="$('adlogoloadingBox').hide(); $('legacyUploadContainer').show(); $('image_upload_instructions_legacy').show(); return false;">{$messages.500708}</span>{$messages.500709}
	</div>
	
	<div id="adlogostandardUploadBox" style="display: none; position: relative;">
		{* This is the "drop the image here" box that will show up while moving images around,
			the in-line styles need to stay in-line for the JS to work *}
		<div id="adlogoplopDropImageHere" style="display: none;">
			{$messages.500710}
		</div>
		<div id="adlogo_upload_instructions_box"><p class="page_note">Use this section to upload your logo to be shown in your listing. 
		Upload by selecting the file to upload, enter the file title (if one is desired), then click the upload button. </p>
		</div>
		<div class="clr"></div>
		
		<div id="adlogoCapturedBox">
			{include file="adlogo/images_captured_box.tpl"}
		</div>
		
		{* This is the new image/edit image box that will be put into place by JS,
			or hidden if no empty slots *}
		<div class="imageBox" id="adlogonewImageBox" style="display: none;">
			<div class="imageBoxTitleNew" id="adlogoUploadTitle">{$messages.500711}</div>
			<div class="imagePreview emptyPreview" id="adlogoPreview">{$messages.500712}</div>
			<div class="progressContainer">
				<div id="adlogouploadBar" style="width: 1%;"><img id="adlogobarAnimation" src="{if $in_admin}../{/if}{external file='images/animation_bar.gif'}" alt="Processing Image" style="display: none;" /></div>
				<div id="adlogonewImageProgress">{$messages.500675}</div>
			</div>
			<div id="adlogoselectFileButtonBox"></div>
			<div class="imageBoxClear"></div>
			{if $adlogo.imgMaxTitleLength}
				<label>
					{$messages.500371}<br />
					<input type="text" name="fileTitle" id="adlogofileTitle" size="20" maxlength="{$adlogo.imgMaxTitleLength}" />
				</label>
				<br />
			{else}
				<input type="hidden" name="fileTitle" id="adlogofileTitle" />
				<br /><br />
			{/if}

			<div class="uploadButtonsContainer">
				<input type="button" value="{$messages.500713}" style="display: none;" id="adlogocancelUploadButton" class="mini_cancel" />
				<input type="button" value="Upload Image"  disabled="disabled" id="adlogouploadButton" class="mini_button" />
			</div>
		</div>
		<div style="clear:both;"></div>
		
		{* This is needed to "find" the parent form and add a listener to it *}
		
		<input type='hidden' name='imageUploadNextFormElement' id='adlogoUploadNextFormElement' value='1' />
		<input type='hidden' name='c[no_images]' value='1' />
	</div>
	
	<div id="adlogolegacyUploadContainer" style="display: none;">
		{* And put it once for people that do have javascript, but no flash... *}
		{include file="adlogo/legacy_form.tpl" noscript=''}
	</div>
	<noscript>
		{* Put it in here once for the noscript tag for people without javascript *}
		{include file="adlogo/legacy_form.tpl" noscript='Noscript'}
	</noscript>
	{if $steps_combined&&$is_ajax_combined}
		{* Loaded as part of combined steps, need to 're-initialize' stuff... *}
		<script type="text/javascript">
		//<![CDATA[
			geoUHLogo.initSwfu();
			$('adlogoloadingBox').show();
		//]]>
		</script>
	{/if}
{else}
	{include file="adlogo/legacy_form.tpl" noscript=''}
{/if}


