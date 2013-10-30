<div class="gj_simple_carousel listing_set gallery listing_bannerbox">
	<div class="gallery_inner">
		{foreach from=$images item=image}
			<div class="gallery_row">
				<img src="{$image.image_url}" />
			</div>
		{/foreach}
	</div>
</div>

<script>
	jQuery(function() {
		jQuery('.gj_simple_carousel').gjSimpleCarousel();
	})
</script>
