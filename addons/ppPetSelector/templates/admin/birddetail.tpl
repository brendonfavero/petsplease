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
			{if $detail.pettype_id eq 1}Dog
			{elseif $detail.pettype_id eq 2}Cat
			{else}??
			{/if}
		{else}
			<select name="d[pettype_id]">
				<option value="1">Dog</option>
				<option value="2">Cat</option>
				<option value="3">Bird</option>
				<option value="4">Fish</option>
				<option value="5">Reptile</option>
				<option value="6">Other</option>
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
		<label for="breed_size">Size</label>
		<input type="text" name="d[size]" id="breed_size" value="{$detail.size}" />
	</div>

	<div>
		<label for="breed_lifespan">Life span</label>
		<input type="text" name="d[lifespan]" id="breed_lifespan" value="{$detail.lifespan}" />
	</div>

	<div>
		<label for="breed_colours">Colours</label>
		<textarea name="d[colours]" id="breed_colours" style="width: 550px; height: 100px;">{$detail.colours}</textarea>
	</div>

	<div>
		<label for="breed_scientific_name">Scientific Name</label>
		<input type="text" name="d[scientific_name]" id="breed_scientific_name" value="{$detail.scientific_name}" />
	</div>

	<div>
		<label for="breed_origin">Origin</label>
		<input type="text" name="d[origin]" id="breed_origin" value="{$detail.origin}" />
	</div>
		
	<div>
		<label for="breed_bird_friendlyness">Family Friendly</label>
		<input type="text" name="d[bird_friendlyness]" id="breed_bird_friendlyness" value="{$detail.bird_friendlyness}" />
	</div>

	<div>
		<label for="breed_bird_trainability">Bird Trainability</label>
		<input type="text" name="d[bird_trainability]" id="breed_bird_trainability" value="{$detail.bird_trainability}" />
	</div>

	<div>
		<label for="breed_loudness">Loudness</label>
		<input type="text" name="d[loudness]" id="breed_loudness" value="{$detail.loudness}" />
	</div>

	<div>
		<label for="breed_sexing">Sexing</label>
		<input type="text" name="d[sexing]" style="width: 550px;" id="breed_sexing" value="{$detail.sexing}" />
	</div>

	<div>
		<label for="breed_stimulation">Stimulation</label>
		<input type="text" name="d[stimulation]" style="width: 550px;" id="breed_stimulation" value="{$detail.stimulation}" />
	</div>
	
	<div>
		<label for="breed_time_outside">Time Outside</label>
		<input type="text" name="d[time_outside]" id="breed_time_outside" value="{$detail.time_outside}" />
	</div>

	<div>
		<label for="breed_cage">Cage Size</label>
		<input type="text" name="d[cage]" id="breed_cage" value="{$detail.cage}" />
	</div>

	<div>
		<label for="breed_talks">Talks</label>
		<input type="text" name="d[talks]" style="width: 550px;" id="breed_talks" value="{$detail.talks}" />
	</div>

	<input type="submit" name="auto_save" value="Save" />
	| <a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector&auto_save=1&d[id]={$detail.id}&dodelete=true">Delete this breed</a>

</form>
