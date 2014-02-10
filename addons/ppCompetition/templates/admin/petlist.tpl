{$messages}

<a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector&edit_id=new">New Breed</a><br>
<br>

<style>
	#breedlist th {
		text-align:left;
	}
	#breedlist td {
		padding: 4px;
	}
</style>

<table id="breedlist">
	<tr>
		<th>Pet Type</th>
		<th>Breed</th>
		<th>Action</th>
	</tr>

	{foreach $breeds as $breed}
		<tr>
			<td>
				{if $breed.pettype_id eq 1}Dog
				{elseif $breed.pettype_id eq 2}Cat
				{else}??
				{/if}
			</td>
			<td>{$breed.breed}</td>
			<td>
				<a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector&edit_id={$breed.id}">Edit</a> |
				<a href="?page=addon_petselector_settings&mc=addon_cat_ppPetSelector&edit_id={$breed.id}&action=images">Images</a>
			</td>
		</tr>
	{/foreach}
</table>
