<form action="index.php" onsubmit="">
	<input type="hidden" name="a" value="19">
	<input type="hidden" name="b[subcategories_also]" value="1">
	<input type="hidden" name="c" id="category_value" value="{$search_parms.c}">

	<div>
		<label>Keyword</label>
		<input type="text" name="b[search_text]" value="{$search_parms.b.search_text}" />
	</div>

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

	<!-- <div>
		<label>Pet Breed</label>
		<select>
		<option>Affenpinscher</option>
		</select>
	</div> -->

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

	<button>Search</button>

	<div id="search_parms" style="display:none">{$search_parms|@print_r}</div>
	<div style="margin:10px; color:red">SEARCH PARMS HAVE BEEN WRITTEN TO CONSOLE.LOG</div>
	<script>jQuery(function() { console.log(jQuery('#search_parms').text())})</script>
</form>

<script>
	ppSearch = {
		// Assign these in init
		val_cat: 0,
		select_topcat: 0,
		select_subcat: 0,
		div_subcat: 0,

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

		init: function() {
			ppSearch.bindSelectors()
			ppSearch.bindEvents()

			if (ppSearch.val_cat.val() == "") { // on home page, no search info
				ppSearch.topCategoryChangedByUser()
			}
			else {
				ppSearch.topCategoryChanged()
			}
		},

		bindSelectors: function() {
			ppSearch.val_cat = jQuery("#category_value")
			ppSearch.select_topcat = jQuery("#search_category")
			ppSearch.select_subcat = jQuery("#search_subcategory")
			ppSearch.div_subcat = jQuery("#search_subcategory_container")			
		},

		bindEvents: function() {
			ppSearch.select_topcat.change(ppSearch.topCategoryChangedByUser)
			ppSearch.select_subcat.change(ppSearch.subCategoryChanged)
		}
	}

	jQuery(function() {
		ppSearch.init()
	})
</script>