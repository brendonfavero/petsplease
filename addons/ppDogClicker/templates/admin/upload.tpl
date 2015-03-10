
<style>
#change_detail_form div {
	margin-bottom: 7px;
}
#change_detail_form label {
	display: inline-block;
	width: 120px;
}
#change_detail_form textarea {
	vertical-align: top
}
</style>
<link rel="stylesheet" type="text/css" href="/js/jquery.datepick.css"> 
<script type="text/javascript" src="/js/jquery.datepick.js"></script>
<script type="text/javascript" src="/js/jquery.datepick.ext.js"></script>

<form method="post" enctype="multipart/form-data" action="?page=addon_photos_settings&mc=addon_cat_ppDogClicker" id="change_detail_form">
	<input type="hidden" name="d[id]" value="{$detail.id}" />
	<div>
		<label>ID</label>
		<span>
		{if $detail.id}
			{$detail.id}
		{else}
			New
		{/if}
		</span>
	</div>	

	<div>
		<label for="pet_name">Dog Name</label>
		<input type="text" name="dogname" id="pet_name" value="{$detail.dogname}" />
	</div>

	<div>
		<label for="pet_name">Trainer Name</label>
		<input type="text" name="trainer" id="trainer" value="{$detail.trainer}" />
	</div>
	
	<div>
		<label for="pet_name">Age</label>
		<input type="number" name="age" min="1" max="20" value="{$detail.age}" >
	</div>
	
	<div>
		<label for="pet_name">Comments</label>
		<input type="text" name="comments" id="comments" value="{$detail.comments}" />

	</div>
	
	<div style="margin-top:24px;">
		{if $detail.thumb_url}
			<input type="hidden" name="thumb_url" value="{$detail.thumb_url}" />
			<input type="hidden" name="full_url" value="{$detail.full_url}" />
			<img src="{$detail.thumb_url}"/>
		{/if}
		<label for="image">Upload/Change Image</label>
	
			<input type="file" name="imagefile">
			<br>
	</div>	
	

	<input type="submit" name="auto_save" value="Save" />
	
	

</form>