<form action="index.php" onsubmit="" id="search_form">
	<input type="hidden" name="a" value="19">
	<input type="hidden" name="b[subcategories_also]" value="1">
	<input type="hidden" name="c" id="category_value" value="{$search_parms.c}">

	<!-- <div>
		<label>Keyword</label>
		<input type="text" name="b[search_text]" value="{$search_parms.b.search_text}" />
	</div> -->

	<div>
		<label for="search_category">Category</label>
		<select id="search_category">
			{foreach from=$categories item=category}
				<option value="{$category.category_id}"{if $category.category_id eq $topcat} selected="selected"{/if}>{$category.category_name}</option>
			{/foreach}
		</select>
	</div>

	<div id="search_subcategory_container" data-showif="#search_category=308,315,413" style="display:none">
		<label for="search_subcategory">Pet Type</label>
		<select id="search_subcategory" data-childfilter="#search_category=?">
			<option value="" class="showalways">All Pets</option>

			{foreach from=$categories item=category}
				{foreach from=$category.subcategories item=subcategory}
					<option value="{$subcategory.category_id}" data-parent="{$category.category_id}" {if $subcategory.category_id eq $subcat}selected="selected"{/if}>{$subcategory.category_name}</option>
				{/foreach}
			{/foreach}
		</select>
	</div>


	<div id="search_pettype_container" data-showif="#search_category=316" style="display:none">
		<label for="search_pettype">Pet Type</label>
		<select id="search_pettype" name="b[specpettype]">
			<option value="" class="showalways">All Pets</option>
			{foreach from=$pettypes item=label key=key}
				<option value="{$key}"{if $key eq $search_parms.b.specpettype} selected="selected"{/if}>{$label}</option>
			{/foreach}
		</select>
	</div>

	<div id="search_breed_container" data-showif="#search_pettype=dog,cat,bird,fish,reptile,other||#search_subcategory=309,310,311,312,313,314" style="display:none">
		<label for="search_breed">Breed</label>
		<select id="search_breed" name="b[breed]" data-childfilter="#search_subcategory=?->mapPetCategoryIDToPetType||#search_pettype=?">
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

	<div id="search_services_container" data-showif="#search_category=318" style="display: none">
		<label for="search_services">Service</label>
		<select id="search_services" name="b[service]">
			<option value="" class="showalways">All Services</option>

			{foreach from=$services item=service}
				<option{if $service eq $search_parms.b.service} selected="selected"{/if}>{$service}</option>
			{/foreach}
		</select>
	</div>

	{if !$simplesearch}
		<div id="search_dog_size_container" data-showif="#search_subcategory=309" style="display:none">
			<label for="search_dog_size">Size</label>
			<select id="search_dog_size" name="b[dog_size]">
				<option value="">All Sizes</option>
				{foreach from=$dogsizes item=size}
					<option value="{$size}"{if $size eq $search_parms.b.dog_size} selected="selected"{/if}>
						{$size}
					</option>
				{/foreach}
			</select>
		</div>

		<div id="search_cat_hairlength_container" data-showif="#search_subcategory=310" style="display:none">
			<label for="search_cat_hairlength">Hair Length</label>
			<select id="search_cat_hairlength" name="b[cat_hairlength]">
				<option value="">Any Length</option>
				{foreach from=$cathairlength item=length}
					<option value="{$length}"{if $size eq $search_parms.b.cat_hairlength} selected="selected"{/if}>
						{$length}
					</option>
				{/foreach}
			</select>
		</div>

		<div id="search_purebred_container" class="check" data-showif="#search_subcategory=309,310" style="display:none">
			<input type="checkbox" value="1" id="search_purebred" name="b[purebred_only]"{if $search_parms.b.purebred_only} checked="checked"{/if}>
			<label for="search_purebred">Purebred Only</label>
		</div>

		<div id="search_adoption_container" class="check" data-showif="#search_subcategory=309,310" style="display:none">
			<input type="checkbox" value="1" id="search_adoption" name="b[adoptable_only]">
			<label for="search_adoption">Adoptable Pets Only</label>
		</div>
	{/if}

	<div class="postcode">
		<label for="search_postcode">Postcode</label>
		<input type="text" id="search_postcode" name="b[by_zip_code]" value="{$search_parms.b.by_zip_code}"/>
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
			<label for="search_order">Sort by</label>
			<select id="search_order" name="order">
				{foreach from=$sort_options item=opt key=id}
					<option value="{$id}"{if $browse_sort_c==$id} selected="selected"{/if}>{$opt}</option>
				{/foreach}
			</select>
		</div>
	{/if}

	<button>Search</button>
</form>

<script>
	jQuery(ppSearch.init)
</script>