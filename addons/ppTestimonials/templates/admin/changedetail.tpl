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

<form method="post" action="?page=addon_testimonials_settings&mc=addon_cat_ppTestimonials" id="change_detail_form">
	<input type="hidden" name="d[id]" value="{$detail.id}" />
	{if $detail.id}
		<input type="hidden" name="d[id]" value="{$detail.id}" />
	{/if}

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
		<label for="breed_description">Description</label>
		<textarea name="d[description]" id="description" style="width: 550px; height: 200px;">{$detail.description|utf8_decode}</textarea>
	</div>

	<input type="submit" name="auto_save" value="Save" />
	| <a href="?page=addon_testimonials_settings&mc=addon_cat_ppTestimonials&auto_save=1&d[id]={$detail.id}&dodelete=true">Delete this Testimonial</a>

</form>
