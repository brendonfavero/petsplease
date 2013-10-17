<form action="#" onsubmit="alert('Searching!')">
	<div>
		<label>Category</label>
		<select id="search_category">
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

	<div>
		<label>Postcode</label>
		<input type="text" />
	</div>

	<div>
		<label>Within</label>
		<select>
			<option>20km</option>
			<option>50km</option>
			<option>100km</option>
			<option>250km</option>
			<option>500km</option>
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