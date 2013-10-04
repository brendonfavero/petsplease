{* 7.1.2-64-gbe87c06 *}
{if !$in_ajax}
	<h2 class="title">
		{$offsite_videos.section_title}
		{if $messages.500916 && $offsite_videos.description}
			<a href="#" class="show_instructions_button" id="offsite_video_instructions">{$messages.500916}</a>
		{/if}
	</h2>
	<div id="offsite_video_instructions_box"><p class="page_note">{$offsite_videos.description}</p></div>
	{if $error_msgs.offsite_videos}
		<div class="field_error_box">
			{$error_msgs.offsite_videos}
		</div>
	{/if}
	<div class="clr"></div>
{/if}
{if !$in_ajax}<div id="offsite_videos_outer">{/if}
	<div id="plopDropVideoHere" style="display: none;">
		{$messages.500917}
	</div>
	{foreach from=$offsite_videos.slots item='slot' key='slotNum' name='offsite_video_slots'}
		<div id="offsite_video_slot_{$slotNum}" class="offsite_video_slot">
			{include file='offsite_videos/upload_slot.tpl'}
		</div>
	{/foreach}
	<div class="clr"><br /></div>
{if !$in_ajax}</div>{/if}

{if $steps_combined&&$is_ajax_combined}
	{* Loaded as part of combined steps, need to 're-initialize' stuff... *}
	<script type="text/javascript">
		//<![CDATA[
			//initialize external video js
			geoVidProcess.currentSlot = {$currentSlot};
			geoVidProcess.init();
		//]]>
	</script>
{/if}