{* 6.0.7-3-gce41f93 *}


<div class="closeBoxX"></div>
<div class="lightUpTitle">Import Suggested Text Changes</div>
{if $errorMsgs}
	<div class="errorBoxMsgs">
		<br />
		<strong>Unable to perform action here:</strong><br />
		{$errorMsgs}
		<br /><br />
		<div class="templateToolButtons">
			<input type="button" class="closeLightUpBox mini_button" value="Ok" />
		</div>
		<div class="clearColumn"></div>
	</div>
{else}
	<form style="display:block; margin: 15px;" action="index.php?page=design_sets_import_text&t_set={$t_set}" method="post">
		<input type="hidden" name="auto_save" value="1" />
		
		<p style="width: 350px;" class="page_note">
			This will import suggested text changes that might be needed to make the template set
			work as it is designed.  It will import the text changes from the file:<br /> 
			<strong class="text_blue" style="white-space: nowrap;">{$geo_templatesDir}{$t_set}/text.csv</strong>
		</p>
		<strong>Suggested Text Changes for Template Set:</strong><br />
		&nbsp; &nbsp; <span class="text_blue">{$t_set}</span>
		<br /><br />
		<strong>Apply Suggested Text Changes to Language:</strong><br />
		{foreach from=$languages item=lang key=lang_id}
			&nbsp; &nbsp; <label><input type="radio" name="languageId" value="{$lang_id}"{if $lang_id==1} checked="checked"{/if} /> {$lang}</label><br />
		{/foreach}
		<br />
		<div class="templateToolButtons">
			<input type="submit" value="Import Text" class="mini_button" />
			<input type="button" class="closeLightUpBox mini_cancel" value="Cancel" />
		</div>
	</form>
{/if}
