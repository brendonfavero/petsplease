{* 7.2beta3-4-gb2b3265 *}

{$adminMessages}

<form action="" method="post">

	<fieldset>
		<legend>Good Neighbor Badge</legend>
		<div>
		
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Enable Good Neighbor Badge</div>
				<div class="rightColumn"><input type="checkbox" value="1" name="settings[use_neighborly]" {if $use_neighborly}checked="checked"{/if} /></div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Good Neighbor Badge Image: </div>
				<div class="rightColumn">
					geo_templates/[Template Set]/external/images/addon/charity_tools/<input type="text" name="settings[neighborly_image]" value="{$neighborly_image}" /> 
					{if $neighborly_image}<img src="../{$neighborly_preview}" alt="" />{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Good Neighbor Badge expires after </div>
				<div class="rightColumn"><input type="text" value="{$neighborly_duration}" name="settings[neighborly_duration]" size="2" /> months</div>
				<div class="clearColumn"></div>
			</div>
		
		</div>
	</fieldset>
	
	<div class="center"><input type="submit" value="Save" class="button" name="auto_save" /></div>
	
	{if $charitables}
		<fieldset>
			<legend>Charitable Badges</legend>
			<div>
			
				<table style="width:100%;">
					
					<tr class="{cycle values='row_color1,row_color2'}">
						<th>Charity Name</th>
						<th>Badge Image</th>
						<th>Region</th>
						<th>Delete</th>
					</tr>
			
					{foreach $charitables as $id => $c}
						<tr class="{cycle values='row_color1,row_color2'}">
							<td style="text-align: center;">{$c.name}</td>
							<td style="text-align: center;"><img src="../{$c.image}" alt="{$c.name}" /><br />{$c.image}</td>
							<td style="text-align: center;">{$c.region}</td>
							<td style="text-align: center;"><a href="index.php?page=addon_charity_tools_settings&deleteCharitable={$id}&auto_save=1" class="mini_cancel lightUpLink">Delete</a></td>
						</tr>
					{/foreach}
			
					
					
				</table>
					
			</div>
		</fieldset>
	{/if}
	
	<fieldset>
		<legend>Add New Charitable Badge</legend>
		<div>
			<table style="width: 100%;">
				<tr class="row_color1">
					<th>Charity Name</th>
					<th>Badge Image</th>
					<th>Region</th>
				</tr>
				<tr class="row_color2">
					<td style="text-align: center;"><input type="text" name="nc[name]" /></td>
					<td style="text-align: center;">geo_templates/[Template Set]/external/images/addon/charity_tools/<input type="text" name="nc[image]" value="charitable.png" /></td>
					<td>{$newRegion}</td>
				</tr>
			</table>
		</div>
	</fieldset>
	
	
<div class="center"><input type="submit" value="Save" class="button" name="auto_save" /></div>
</form>