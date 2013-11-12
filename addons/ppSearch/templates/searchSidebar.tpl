<form action="index.php" onsubmit="" id="search_form">
	<input type="hidden" name="a" value="19">
	<input type="hidden" name="b[subcategories_also]" value="1">
	<input type="hidden" name="c" id="category_value" value="{$search_parms.c}">

	<!-- <div>
		<label>Keyword</label>
		<input type="text" name="b[search_text]" value="{$search_parms.b.search_text}" />
	</div> -->

	<div>
		<label>Category</label>
		<select id="search_category">
			{foreach from=$categories item=category}
				<option value="{$category.category_id}"{if $category.category_id eq $topcat} selected="selected"{/if}>{$category.category_name}</option>
			{/foreach}
		</select>
	</div>

	<div id="search_subcategory_container" style="display:none">
		<label>Pet Type</label>
		<select id="search_subcategory">
			<option value="" class="showalways">All Pets</option>

			{foreach from=$categories item=category}
				{foreach from=$category.subcategories item=subcategory}
					<option value="{$subcategory.category_id}" data-parent="{$category.category_id}" {if $subcategory.category_id eq $subcat}selected="selected"{/if}>{$subcategory.category_name}</option>
				{/foreach}
			{/foreach}
		</select>
	</div>


	<div id="search_pettype_container" style="display:none">
		<label>Pet Type</label>
		<select id="search_pettype" name="b[specpettype]">
			<option value="" class="showalways">All Pets</option>
			{foreach from=$pettypes item=label key=key}
				<option value="{$key}"{if $key eq $search_parms.b.specpettype} selected="selected"{/if}>{$label}</option>
			{/foreach}
		</select>
	</div>

	<div id="search_breed_container" style="display:none">
		<label>Breed</label>
		<select id="search_breed" name="b[breed]">
			<option value="" class="showalways">All Breeds</option>

			{foreach from=$breeds item=petbreed key=breeds_key}
				{foreach from=$petbreed item=breed}
					{if is_array($breed)}
						{* Multi select *}
						<option value="{$breed.value}" data-parent="{$breeds_key}"{if $breed.value eq $search_parms.b.breed} selected="selected"{/if}>{$breed.value}</option>

						{foreach from=$breed.values item=subbreed}
							<option value="{$subbreed.value}" data-parent="{$breeds_key}"{if $subbreed.value eq $search_parms.b.breed} selected="selected"{/if}>{$subbreed.value|indent:4:"&nbsp;"}</option>
						{/foreach}
					{else}
						<option value="{$breed}" data-parent="{$breeds_key}"{if $breed eq $search_parms.b.breed} selected="selected"{/if}>{$breed}</option>
					{/if}
				{/foreach}
			{/foreach}
		</select>
	</div>


	<div id="search_services_container" style="display: none">
		<label>Service</label>
		<select id="search_services" name="b[service]">
			<option value="" class="showalways">All Services</option>

			{foreach from=$services item=service}
				<option{if $service.value eq $search_parms.b.service} selected="selected"{/if}>{$service.value}</option>
			{/foreach}
		</select>
	</div>


	<div class="postcode">
		<label>Postcode</label>
		<input type="text" name="b[by_zip_code]" value="{$search_parms.b.by_zip_code}"/>
		<select name="b[by_zip_code_distance]">
			{foreach from=$zip_distances item=distance}
				<option value="{$distance}"{if $distance eq $search_parms.b.by_zip_code_distance} selected="selected"{/if}>
					{$distance}km
				</option>
			{/foreach}
		</select>
	</div>

	{if !$simplesearch}
		<div class="sort">
			<label>Sort by</label>
			<select name="order">
				{foreach from=$sort_options item=opt key=id}
					<option value="{$id}"{if $browse_sort_c==$id} selected="selected"{/if}>{$opt}</option>
				{/foreach}
			</select>
		</div>
	{/if}

	<button>Search</button>
</form>

<script>
	ppSearch = {
		// Assign these in init
		form: 0,
		val_cat: 0,
		select_topcat: 0,
		select_subcat: 0,
		div_subcat: 0,
		select_pettype: 0,
		div_pettype: 0,
		select_breed: 0,
		div_breed: 0,
		select_service: 0,
		div_service: 0,

		topCategoryChanged: function() {
			// if category has any children show child cat select
			var selectedTopCat = ppSearch.select_topcat.val()
			var subcat_options = jQuery("option", ppSearch.select_subcat).filter(function() {
				return jQuery(this).data("parent") == selectedTopCat
			})

			if (subcat_options.length == 0) {
				ppSearch.div_subcat.hide()
				ppSearch.select_subcat.val("")
			}
			else {
				jQuery("option", ppSearch.select_subcat).not(".showalways").hide()
				subcat_options.show()

				ppSearch.div_subcat.show()

				var selectedCat = ppSearch.val_cat.val()
				var valIfInGroup = subcat_options.filter(function() {
					return jQuery(this).val() == selectedCat
				}).eq(0)
				if (!valIfInGroup) {
					ppSearch.select_subcat.val("")
				}
				else {
					ppSearch.select_subcat.val(valIfInGroup.val())
				}
			}
		},

		topCategoryChangedByUser: function() {
			var catid = ppSearch.select_topcat.val()
			ppSearch.val_cat.val(catid)

			ppSearch.topCategoryChanged()
		},

		subCategoryChanged: function() {
			var subcatval = ppSearch.select_subcat.val()
			if (subcatval != "") {
				ppSearch.val_cat.val(subcatval)
			}
			else {
				var topcatval = ppSearch.select_topcat.val()
				ppSearch.val_cat.val(topcatval)
			}
		},

		refreshControls: function() {
			// Pet Type (not subcategory, but for Breeders etc)
			if (ppSearch.val_cat.val() == "316") { // Breeders
				if (ppSearch.div_pettype.is(":hidden")) {
					ppSearch.div_pettype.show()
				}
			}
			else {
				ppSearch.div_pettype.hide()
				ppSearch.select_pettype.val("")
			}

			// Pet Breed
			// Show if subCat.parent is "Pets for Sale" or Pet Type (breeder) is not nothing
			var selectedSubCat = jQuery(":selected", ppSearch.select_subcat)
			if (selectedSubCat.data("parent") == 308 || ppSearch.select_pettype.val() != "") {
				var petType = ppSearch.select_pettype.val()
				if (selectedSubCat.data("parent") == 308) {
					petType = ppSearch.mapPetCategoryIDToPetType(selectedSubCat.val())
				}

				if (ppSearch.div_breed.is(":hidden") || jQuery("option:visible", ppSearch.select_breed).eq(2).data("parent") != petType) {
					var breed_options = jQuery("option", ppSearch.select_breed).filter(function() {
						return jQuery(this).data("parent") == petType
					})
					jQuery("option", ppSearch.select_breed).not(".showalways").hide()
					breed_options.show()

					ppSearch.div_breed.show()
				}
			}
			else {
				ppSearch.select_breed.val("")
				ppSearch.div_breed.hide()
			}

			// Services
			if (ppSearch.val_cat.val() == "318") { // Breeders
				if (ppSearch.div_service.is(":hidden")) {
					ppSearch.div_service.show()
				}
			}
			else {
				ppSearch.div_service.hide()
				ppSearch.select_service.val("")
			}
		},

		mapPetCategoryIDToPetType: function(catID) {
			if (catID == 309) return "dog"
			if (catID == 310) return "cat"
			if (catID == 311) return "bird"
			if (catID == 312) return "fish"
			if (catID == 313) return "reptile"
			if (catID == 314) return "other"
		},

		init: function() {
			ppSearch.bindSelectors()
			ppSearch.bindEvents()

			if (ppSearch.val_cat.val() == "") { // on home page, no search info
				ppSearch.topCategoryChangedByUser()
			}
			else {
				ppSearch.topCategoryChanged()
			}

			ppSearch.refreshControls()
		},

		bindSelectors: function() {
			ppSearch.form = jQuery("#search_form")
			ppSearch.val_cat = jQuery("#category_value")
			ppSearch.select_topcat = jQuery("#search_category")
			ppSearch.select_subcat = jQuery("#search_subcategory")
			ppSearch.div_subcat = jQuery("#search_subcategory_container")	
			ppSearch.select_pettype = jQuery("#search_pettype")
			ppSearch.div_pettype = jQuery("#search_pettype_container")
			ppSearch.select_breed = jQuery("#search_breed")
			ppSearch.div_breed = jQuery("#search_breed_container")
			ppSearch.select_service = jQuery("#search_services")
			ppSearch.div_service = jQuery("#search_services_container")
		},

		bindEvents: function() {
			ppSearch.form.on("change", ppSearch.refreshControls)

			ppSearch.select_topcat.change(ppSearch.topCategoryChangedByUser)
			ppSearch.select_subcat.change(ppSearch.subCategoryChanged)
		}
	}

	jQuery(function() {
		ppSearch.init()
	})
</script>