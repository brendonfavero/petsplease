<form action="index.php" onsubmit="">
	<input type="hidden" name="a" value="19" />
	<input type="hidden" name="b[subcategories_also]" value="1">

	<div>
		<label>Keyword</label>
		<input type="text" name="b[search_text]" />
	</div>

	<div>
		<label>Category</label>
		<select id="search_category" name="c">
			{foreach from=$categories item=category}
				<option value="{$category.category_id}">{$category.category_name}</option>
			{/foreach}
		</select>
	</div>

	<div>
		<label>Pet Type</label>
		<select>
		<option>All Pets</option>

		{foreach from=$categories item=category}
			{foreach from=$category.subcategories item=subcategory}
				<option value="{$subcategory.category_id}">{$subcategory.category_name}</option>
			{/foreach}
		{/foreach}
		</select>
	</div>

	<!-- <div>
		<label>Pet Breed</label>
		<select>
		<option>Affenpinscher</option>
		</select>
	</div> -->

	<div class="postcode">
		<label>Postcode</label>
		<input type="text" name="b[by_zip_code]" />
		<select name="b[by_zip_code_distance]">
			<option value="5">5km</option>
			<option value="10">10km</option>
			<option value="15">15km</option>
			<option value="20">20km</option>
			<option value="25">25km</option>
			<option value="30">30km</option>
			<option value="40">40km</option>
			<option value="50">50km</option>
			<option value="75">75km</option>
			<option value="100">100km</option>
			<option value="200">200km</option>
			<option value="300">300km</option>
			<option value="400">400km</option>
			<option value="500">500km</option>
		</select>
	</div>

	<button>Search</button>
</form>

<script>
	$(function() {
		$("#search_category").change(function() {
			var cat = $("#search_category").val()

			if (cat == "") {}
		})
	})
</script>