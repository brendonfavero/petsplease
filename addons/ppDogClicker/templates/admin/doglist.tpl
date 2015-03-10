{$messages}

<a href="?page=addon_photos_settings&mc=addon_cat_ppDogClicker&edit_id=new">Add New Dog</a><br>
<br>

<style>
	#petlist th {
		text-align:center;
	}
	#petlist td {
		padding: 30px;
	}
	#petlist a {
		padding: 0 10px;
	}
</style>

<table id="petlist">
	<tr>
		<th>Dog Name</th>
		<th>Trainer</th>
		<th>Age</th>
		<th>Comments</th>
		<th>Image</th>
		<th>Actions</th>
	</tr>

	{foreach $pets as $pet}
		<tr>
			<td>{$pet.dogname}</td>
			<td>{$pet.trainer}</td>
			<td>{$pet.age}</td>
			<td>{$pet.comments}</td>
			<td>
			<img src="{$pet.thumb_url}"/>
			</td>
			<td><a href="?page=addon_photos_settings&mc=addon_cat_ppDogClicker&edit_id={$pet.id}">Edit</a>|
			<a href="?page=addon_photos_settings&mc=addon_cat_ppDogClicker&auto_save=1&d[id]={$pet.id}&dodelete=true">Delete</a></td>
		</tr>
	{/foreach}
</table>
