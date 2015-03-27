{* 6.0.7-3-gce41f93 *}

{* Top title part *}

<div class="box_head_1_dyn imageBoxTitle{if $slotData.empty}Empty{/if}{if $position==1} firstUploadSlotTitle{/if}" id="imageTitle{$position}">
	{if $slotData.empty}
		{$messages.500695}
	{else}
		<div class="imageBoxTitleButtons">
			<span class="deleteImage" id="deleteImage_{$position}" onclick="geoUH.deleteImage({$position});">
				{if $in_admin}
					<img src="../{external file='images/buttons/delete.png'}" alt="Delete Image" />
				{else}
					{$messages.500715}
				{/if}
			</span>
		</div>
		<div class="imageBoxTitleButtons">
			<span class="editImage" id="editImage_{$position}" onclick="geoUH.editImage({$position});">
				{if $in_admin}
					<img src="../{external file='images/buttons/edit.png'}" alt="Edit Image Info" />
				{else}
					<img src="../{external file='images/buttons/edit.png'}" alt="Edit Image Info" />
				{/if}
			</span>
		</div>
		<div class="imageBoxTitleHandle">
			Change Order :: Click & Drag
		</div>
	{/if}
</div>

{* The actual contents of the box *}

<div class="imagePreview{if $slotData.empty} emptyPreview{/if}" id="imagePreview_{$position}">
	<div>
		{if $slotData.empty}
			{$messages.500697}
		{elseif $slotData.image.icon}
			{strip}
				<a href="javascript:winimage('{$slotData.image.image_url}','{$slotData.image.original_image_width+40}','{$slotData.image.original_image_height+40}')" class="place_an_ad_image_links">
					<img src="{if $in_admin}../{/if}{external file=$slotData.image.icon}" alt='' style="width: 100px; height: 100px;" />
				</a>
			{/strip}
		{else}
			{strip}
				{if $slotData.image.resized}
					<div class="magnifyingLink">
						<a href="{if $in_admin}../{/if}{$slotData.image.image_url}" class="lightUpImg place_an_ad_image_links"><img src="{if $in_admin}../{/if}{external file='images/magnifying_glass_small.gif'}" alt="See larger" /></a>
					</div>
				{/if}
				
				{if $slotData.image.resized}<a href="{if $in_admin}../{/if}{$slotData.image.image_url}" class="lightUpImg place_an_ad_image_links">{/if}
					{$slotData.image.tag}
				{if $slotData.image.resized}</a>{/if}
			{/strip}
		{/if}
	</div>
</div>
<span class="fileSlotLabel">{$messages.500698}</span> <span class="fileSlotValue">{if $position == 1}Lead Image{else}{$position}{/if}</span>
<br />
<div class="fileTitleBox">
	<span class="fileTitleLabel">Title</span> <span class="fileTitleValue">{if !$slotData.image.image_text}{$messages.500701}{else}{$slotData.image.image_text}{/if}</span>
</div>
<input type="hidden" id="imageTitle_{$position}" value="{$slotData.image.image_text}" />
<br />
{if $slotData.cost}
	<span class="fileCostLabel">{$messages.500702}</span> <span class="fileCostValue price">{$slotData.cost}</span>
	<br />
{/if}
