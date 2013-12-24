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

<form method="post" action="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector" id="change_detail_form">
	<input type="hidden" name="d[id]" value="{$detail.id}" />
	{if $detail.pettype_id}
		<input type="hidden" name="d[pettype_id]" value="{$detail.pettype_id}" />
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
		<label>Pet Type</label>
		<span>
		{if $detail.pettype_id}
			
		{else}
			<select name="d[pettype_id]">
				<option value="1">Dog</option>
				<option value="2">Cat</option>
			</select>
		{/if}
		</span>
	</div>

	<div>
		<label for="breed_name">Breed</label>
		<input type="text" name="d[breed]" id="breed_name" value="{$detail.breed}" />
	</div>

	<div>
		<label for="breed_description">Description</label>
		<textarea name="d[description]" id="breed_description" style="width: 550px; height: 200px;">{$detail.description|utf8_decode}</textarea>
	</div>

	<div>
		<label for="breed_height">Height</label>
		<input type="text" name="d[height]" id="breed_height" value="{$detail.height}" />
	</div>

	<div>
		<label for="breed_weight">Weight</label>
		<input type="text" name="d[weight]" id="breed_weight" value="{$detail.weight}" />
	</div>

	<div>
		<label for="breed_size">Size</label>
		<input type="text" name="d[size]" id="breed_size" value="{$detail.size}" />
	</div>

	<div>
		<label for="breed_lifespan">Life span</label>
		<input type="text" name="d[lifespan]" id="breed_lifespan" value="{$detail.lifespan}" />
	</div>

	<div> 
		<label for="breed_hypoallergenic">Hypoallergenic</label>
		<input type="checkbox" name="d[hypoallergenic]" id="breed_hypoallergenic"{if $detail.hypoallergenic eq 1} checked="checked"{/if} />
	</div>

	<div>
		<label for="breed_colours">Colours</label>
		<textarea name="d[colours]" id="breed_colours" style="width: 550px; height: 100px;">{$detail.colours}</textarea>
	</div>

	<div>
		<label for="breed_coatlength">Coat length</label>
		<input type="text" name="d[coatlength]" id="breed_coatlength" value="{$detail.coatlength}" />
	</div>

	<div>
		<label for="breed_housing">Housing</label>
		<input type="text" name="d[housing]" id="breed_housing" value="{$detail.housing}" />
	</div>
		
	<div>
		<label for="breed_familyfriendly">Family Friendly</label>
		<select name="d[familyfriendly]" id="breed_familyfriendly">
			{foreach array(1,2,3,4,5) as $i}
				<option{if $detail.familyfriendly eq $i} selected="selected"{/if}>{$i}</option>
			{/foreach}
		</select>
	</div>

	<div>
		<label for="breed_trainability">Trainability</label>
		<select name="d[trainability]" id="breed_trainability">
			{foreach array(1,2,3,4,5) as $i}
				<option{if $detail.trainability eq $i} selected="selected"{/if}>{$i}</option>
			{/foreach}
		</select>
	</div>

	<div>
		<label for="breed_energy">Energy</label>
		<select name="d[energy]" id="breed_energy">
			{foreach array(1,2,3,4,5) as $i}
				<option{if $detail.energy eq $i} selected="selected"{/if}>{$i}</option>
			{/foreach}
		</select>
	</div>

	<div>
		<label for="breed_grooming">Grooming</label>
		<select name="d[grooming]" id="breed_grooming">
			{foreach array(1,2,3,4,5) as $i}
				<option{if $detail.grooming eq $i} selected="selected"{/if}>{$i}</option>
			{/foreach}
		</select>
	</div>

	<div>
		<label for="breed_shedding">Shedding</label>
		<select name="d[shedding]" id="breed_shedding">
			{foreach array(1,2,3,4,5) as $i}
				<option{if $detail.shedding eq $i} selected="selected"{/if}>{$i}</option>
			{/foreach}
		</select>
	</div>

	<input type="submit" name="auto_save" value="Save" />
	| <a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector&auto_save=1&d[id]={$detail.id}&dodelete=true">Delete this breed</a>

</form>
