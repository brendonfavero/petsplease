{* 7.2beta3-83-g806659c *}
{$errors}
<fieldset>
	<legend>Google Maps</legend>
	<div>
		<form action="" method="post">
			<div class="{cycle values='row_color1,row_color2'} standard">
				<div class="leftColumn">Google API Key Instructions (Requires Google Maps API v3)</div>
				<div class="rightColumn"><a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" onclick="window.open(this.href); return false;">Click here for directions.</a></div>
				<div class="clearColumn"></div>
			</div>
			{if !$googleApiKey}
				<input type="hidden" name="noApiKey" value="1" />
			{else}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Template Tag</div>
					<div class="rightColumn">
						{ldelim}listing addon='google_maps' tag='listing_map'}
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Google API key</div>
				<div class="rightColumn">
					<input type="text" name="googleApiKey" value="{$googleApiKey}" size="45" />
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $googleApiKey}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Map Width</div>
					<div class="rightColumn">
						<input type="text" name="width" value="{$width}" size="4" />
						<select name="width_type">
							<option{if $width_type=='px'} selected="selected"{/if}>px</option>
							<option{if $width_type=='%'} selected="selected"{/if}>%</option>
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Map Height</div>
					<div class="rightColumn">
						<input type="text" name="height" value="{$height}" size="4" />
						<select name="height_type">
							<option{if $height_type=='px'} selected="selected"{/if}>px</option>
							<option{if $height_type=='%'} selected="selected"{/if}>%</option>
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Google Maps Enabled</div>
					<div class="rightColumn">
						<input type="checkbox" name="on" value="1" {if !$off}checked="checked"{/if} />
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div style="text-align: center;">
				<input type="submit" name="auto_save" value="Save" />
			</div>
		</form>
	</div>
</fieldset>


{if $preview}
	<fieldset>
		<legend>Maps Preview</legend>
		<div>
			{$preview}
		</div>
	</fieldset>
{/if}
