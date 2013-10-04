{* 6.0.7-3-gce41f93 *}

{$admin_msgs}

<form action="" method="post">
	<fieldset>
		<legend>General Storefront Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type='checkbox' name='storefront[sef]' value='1'{if !$seo} disabled="disabled"{elseif $sef} checked="checked"{/if} />
				</div>
				<div class="rightColumn">Search Engine Friendly URL<br />(Requires SEO Addon)</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type='checkbox' name='storefront[geonav_filter_storefronts]' value='1'{if !$geographic_navigation} disabled="disabled"{elseif $geonav_filter_storefronts} checked="checked"{/if} />
				</div>
				<div class="rightColumn">Geographic Navigation filter applies to Storefront List<br />(Requires Geographic Navigation Addon)</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="storefront[show_traffic]" value="1"{if $show_traffic} checked="checked"{/if} />
				</div>
				<div class="rightColumn">Show Traffic Reports</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="storefront[allow_newsletter]" value="1"{if $allow_newsletter} checked="checked"{/if} />
				</div>
				<div class="rightColumn">Allow Sending Newsletters</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="storefront[default_storename_to_company]" value="1"{if $default_storename_to_company} checked="checked"{/if} />
				</div>
				<div class="rightColumn">Use "Company Name" as default store name</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Max logo width (in store)</div>
				<div class="rightColumn">
					<input type="text" name="storefront[max_logo_width_in_store]" value="{$max_logo_width_in_store}" size="3" maxlength="4" /> pixels {$size_tooltip}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Max logo height (in store)</div>
				<div class="rightColumn">
					<input type="text" name="storefront[max_logo_height_in_store]" value="{$max_logo_height_in_store}" size="3" maxlength="4" /> pixels {$size_tooltip}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center"><input type="submit" name="auto_save" value="Save" class="mini_button" /></div>
		</div>
	</fieldset>
</form>