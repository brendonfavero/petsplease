<table id="breedlist">
	<tr>
		<th>Week/url/save</th>
	</tr>

	{foreach $links as $link}
		<tr>
			<td>
				<form method="post" enctype="multipart/form-data" action="?page=addon_link_settings&mc=addon_cat_ppDogClicker" id="change_detail_form">
					{if $link.id}
						<input type="hidden" name="id" value="{$link.id}" />
					{/if}
					
					{$link.week}
					<input type="text" name="url" value="{$link.link}"/>
					
					<input type="submit" name="auto_save" value="Save"/>
					
				</form>
			</td>
		</tr>
	{/foreach}
</table>