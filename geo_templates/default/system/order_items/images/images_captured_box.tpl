{* 6.0.7-3-gce41f93 *}

{foreach from=$images.imageSlots item='slotData' key='position'}
	<div id="imageSlot_{$position}" class="imageBox{if $slotData.empty}Empty{/if}{if $position==1} firstUploadSlot{/if}">
		<div class="innerImageBox">
			{include file="images/image_box.tpl"}
		</div>
	</div>
{/foreach}
<div class="imagesCapturedBoxClear"></div>