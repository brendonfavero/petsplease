{* 4eb7314 *}
{$adminMessages}
<fieldset>
	<legend>Pedigree Tree Settings</legend>
	<div>
		<div class="page_note">
			<strong>Fields to Use Settings:</strong>  Note that there are more settings specific to Pedigree Tree fields on the page <a href="index.php?page=fields_to_use">Listing Setup > Fields to Use</a> in the admin panel.
			If the Pedigree Tree does not show when placing or editing a listing, check there to make sure it is enabled site-wide or for that specific category/user group.
			<br /><br />
			<strong>Display in Listing:</strong>  Don't forget to add the addon tag to your listing details template(s) so that the pedigree tree information displays for each listing.  The tag to add will be:<br />
			<br />
			<div class="center">{ldelim}addon author='geo_addons' addon='pedigree_tree' tag='listing_tree'}</div>
		</div>
		<form action="" method="post">
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn"><input type="checkbox" id="allowUppercase" name="allowUppercase"{if $allowUppercase} checked="checked"{/if} /></div>
				<div class="rightColumn">
					<label for="allowUppercase">
						Preserve Uppercase in Names?<br />
						<span class="small_font">(May affect searches)</span>
					</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Max # Generations</div>
				<div class="rightColumn"><input type="text" size="2" name="maxGens" value="{$maxGens}" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Required # Generations</div>
				<div class="rightColumn"><input type="text" size="2" name="maxReqGens" value="{$maxReqGens}" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Image Icons to Use</div>
				<div class="rightColumn">
					{foreach from=$icon_sets item=set_url key=set_value}
						<label>
							<input type="radio" name="iconSet" value="{$set_value}"{if $iconSet==$set_value} checked="checked"{/if} /> 
							<image src="../{external file=$set_url.sire}" alt="" style="vertical-align: middle; margin: 1px;" /> 
							<image src="../{external file=$set_url.dam}" alt="" style="vertical-align: middle; margin: 1px;" />
						</label><br />
					{/foreach}
					<label>
						<input type="radio" name="iconSet" value="none"{if $iconSet==none} checked="checked"{/if} /> None
					</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div style="text-align: center;"><input type="submit" name="auto_save" value="Save" /></div>
		</form>
	</div>
</fieldset>