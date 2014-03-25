{$messages}

<style>
.selector_image {
	float:left;
	margin-left: 10px;
}
.selector_image img {
	display:block;
}
</style>
<div>
	
	<a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector">Back to list</a>
	<h2>Images for {$detail.breed}</h2>

	{foreach $images as $image}
		<div class="selector_image">
			<img src="{$image.image_url}">
			<a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector&auto_save=1&edit_id={$detail.id}&action=images&deleteimage={$image.image_id}">Remove</a>
		</div>
	{foreachelse}
		No images have been uploaded for this breed
	{/foreach}

	<div style="clear:both;height:0"></div>
</div>

<div style="margin-top:24px;">
	<b>Upload new image:</b>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="d[id]" value="{$detail.id}">
		
		<input type="file" name="imagefile">
		<br>
		<input type="submit" name="auto_save" value="Upload Image">
	</form>
</div>