{* 6.0.7-3-gce41f93 *}

{foreach from=$adlogo.imageSlots item='slotData' key='position'}
	<div id="adlogoSlot_{$position}" class="imageBox{if $slotData.empty}Empty{/if}{if $position==1} firstUploadSlot{/if}">
		<div class="innerImageBox">
			{include file="adlogo/image_box.tpl"}
		</div>
	</div>
{/foreach}
<div class="imagesCapturedBoxClear"></div>