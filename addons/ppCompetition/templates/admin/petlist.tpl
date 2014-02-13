{$messages}

<a href="?page=addon_Competition_settings&mc=addon_cat_ppCompetition&edit_id=new">Add New Pet of the Week</a><br>
<br>

<style>
	#petlist th {
		text-align:left;
	}
	#petlist td {
		padding: 30px;
	}
</style>

<table id="petlist">
	<tr>
		<th>Week</th>
		<th>Pet</th>
		<th>Image</th>
		<th>Actions</th>
	</tr>

	{foreach $pets as $pet}
		<tr>
			<td>{$pet.week}
			</td>
			<td>{$pet.petname}</td>
			<td>
			<img src="{$pet.thumb_url}"/>
			</td>
			<td><a href="#">Edit</a>|<a href="#">Delete</a></td>
		</tr>
	{/foreach}
</table>
