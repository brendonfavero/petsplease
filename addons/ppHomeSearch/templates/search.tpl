<form action="#" onsubmit="alert('Searching!')">
	<div>
		<label>Category</label>
		<select id="search_category">
			<option>Pets for Sale</option>
			<option>Products</option>
			<option>Shops</option>
			<option>Holiday with your Pet</option>
			<option>Breeders</option>
			<option>Services</option>
			<option>Clubs</option>

			{foreach from=$categories item=category}
				<option value="{$category.id}">{$category.name}</option>
			{/foreach}
		</select>
	</div>

	<div>
		<label>Pet Type</label>
		<select>
		<option>All Pets</option>
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