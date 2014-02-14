{$messages}

<a href="?page=addon_Competition_settings&mc=addon_cat_ppCompetition&edit_id=new">Add New Pet of the Week</a><br>
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
		<th>Week</th>
		<th>Pet</th>
		<th>Image</th>
		<th>Actions</th>
	</tr>

	{foreach $pets as $pet}
		<tr>
			<td>{if $pet.current eq 1}Current{else}{$pet.week}{/if}
			</td>
			<td>{$pet.petname}</td>
			<td>
			<img src="{$pet.thumb_url}"/>
			</td>
			<td><a href="?page=addon_Competition_settings&mc=addon_cat_ppCompetition&edit_id={$pet.id}">Edit</a>|
			<a href="?page=addon_Competition_settings&mc=addon_cat_ppCompetition&auto_save=1&d[id]={$pet.id}&dodelete=true">Delete</a></td>
		</tr>
	{/foreach}
</table>
