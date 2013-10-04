{* 7.1beta4-103-gdc8149c *}


<div class="galleryContainer">
	<div class="galleryBigImage">
		<div class="bigLeadImage">
			{foreach $images as $image}
				{if $image.icon}
					<a href="{$image.url}" onclick="window.open(this.href); return false;" class="big_link_{$classified_id}_{$image.id}"{if !$image@first} style="display: none;"{/if}>
						<img src="{external file=$image.icon}" alt="" />
					</a>
				{else}
					<a href="get_image.php?id={$image.id}" class="big_link_{$classified_id}_{$image.id} lightUpLink" onclick="return false;"{if !$image@first} style="display: none;"{/if}>
						<img src="{if $image.thumb_url}{$image.thumb_url}{else}{$image.url}{/if}"{if $image.scaled.image} style="width: {$image.scaled.image.width}px; height: {$image.scaled.image.height}px;"{/if} alt="" />
					</a>
				{/if}
			{/foreach}
		</div>
		<p class="imageTitle">{$images.1.image_text}</p>
	</div>
	<div class="galleryThumbs">
		<ul>
			{foreach $images as $image}
				<li>
					{if $image.icon}
						<img class="thumb" src="{external file=$image.icon}" alt="" />
					{else}
						<img class="thumb" src="{if $image.thumb_url}{$image.thumb_url}{else}{$image.url}{/if}"{if $image.scaled.thumb} style="width: {$image.scaled.thumb.width}px; height: {$image.scaled.thumb.height}px;"{/if} alt="" />
					{/if}
					<label style="display: none;" id="big_link_{$classified_id}_{$image.id}">{$image.image_text}</label>
				</li>
			{/foreach}
			
		</ul>
	</div>
	
	
	<div class="clr"></div>
</div>