{* 6.0.7-3-gce41f93 *}

<ul class="listing_images">
	{foreach from=$images.images_data key='position' item="image" name="imgs"}
		<li>
			{if $image.icon}
				<img src="{$image.icon}" alt='' />
			{else}
				{$image.tag}<br />
				{if $image.image_text}
					<span class="image_text">{$image.image_text}</span><br />
				{/if}
				{if $image.resized}
					<a href="javascript:winimage('{$image.image_url}','{$image.original_image_width+40}','{$image.original_image_height+40}')" class="preview">{$messages.500370}</a>
				{/if}
			{/if}
			
			{if $images.show_delete}
				<a href="{$process_form_url}&amp;f={$image.image_id}&amp;g={$position}" class="delete">{$messages.173}</a>
			{/if}
		</li>
	{/foreach}
</ul>
