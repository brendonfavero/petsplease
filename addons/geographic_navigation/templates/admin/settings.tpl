{* 7.1beta4-30-gcf40ea2 *}

{$adminMessages}
<form method='post' action=''>

	<fieldset>
		<legend>General Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="settings[combineTree]" value="1" {if $combineTree}checked="checked" {/if}/>
				</div>
				<div class="rightColumn">Combine with Category Breadcrumb</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="settings[showInSearchBox]" value="1" {if $showInSearchBox}checked="checked" {/if}/>
				</div>
				<div class="rightColumn">Show in Module Search Box 1</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="settings[showInTitleListing]" value="1" {if $showInTitleListing}checked="checked" {/if}/>
				</div>
				<div class="rightColumn">Show listing's region in Title Module</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="settings[showInTitle]" value="1" {if $showInTitle}checked="checked" {/if}/>
				</div>
				<div class="rightColumn">Show current selected region in Title Module</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="settings[terminalSiblings]" value="1" {if $terminalSiblings}checked="checked" {/if}/>
				</div>
				<div class="rightColumn">Show siblings in navigation when there are no children</div>
				<div class="clearColumn"></div>
			</div>
			{if showLegacyUrlSetting}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="settings[useLegacyUrls]" value="1" {if $useLegacyUrls}checked="checked" {/if}/>
					</div>
					<div class="rightColumn">Allow Legacy (Geographic Navigation 4.x) URLs</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="settings[geo_ip]" value="1" {if $geo_ip}checked="checked" {/if} onchange="if(this.checked)jQuery('#geoapi').show();else jQuery('#geoapi').hide();" />
				</div>
				<div class="rightColumn">
					<strong>BETA / Experimental</strong>: Automatically assign visitors' regions based on their IPs
					<div id="geoapi" {if !$geo_ip}style="display: none;"{/if}>
						<a href="http://www.ipinfodb.com/register.php">IpInfoDB API key</a> (required): <input type="text" name="settings[geo_ip_apikey]" value="{$geo_ip_apikey}" size="70" />
					</div>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="center">
				<input type="submit" name="auto_save" value="save" />
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Navigation Tag Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Show Listing Counts</div>
				<div class="rightColumn">
					<select name='settings[countFormat]'>
						{html_options options=$countOptions selected=$countFormat}
					</select>
					<br />
					<strong>use_cat_counts=1</strong><br />(if added to navigation tag, will reduce listing counts specific to current category)
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="col_hdr">Settings below can be changed via Tag Parameters listed under each value</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Number of Columns
				</div>
				<div class="rightColumn">
					<input type='text' name='settings[columns]' value='{$columns}' style='width:40px' id="columns"
						onchange="$('columnsValue').update(this.value);" />
					<br />
					<strong>columns=<span id="columnsValue"><script type="text/javascript">document.write($('columns').value);</script></span></strong>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Breadcrumb on Top:</div>
				<div class="rightColumn">
					<select name="settings[tree]" id="tree" onchange="$('treeValue').update(this.value);">
						<option value="0"{if !$tree}selected="selected"{/if}>None</option>
						<option value="compact"{if $tree==compact}selected="selected"{/if}>Compact</option>
						<option value="full"{if $tree==full}selected="selected"{/if}>Full Breadcrumb</option>
					</select>
					<br />
					<strong>tree='<span id="treeValue"><script type="text/javascript">document.write($('tree').value);</script></span>'</strong>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"><input type='checkbox' name='settings[showSubs]' id="showSubs" value="1" {if $showSubs}checked="checked" {/if}
					onclick="$('showSubsValue')[((this.checked)? 'show':'hide')]();"/></div>
				<div class="rightColumn">
					Show Sub-Regions
					<br />
					<span id="showSubsValue" style="display: none;">To Hide Subregions: <strong>showSubs=0</strong></span>
					<script type="text/javascript">if ($('showSubs').checked) { $('showSubsValue').show(); }</script>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			
			
			<div class="center">
				<input type="submit" name="auto_save" value="save" />
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Template Tags</legend>
		<div>
			<div class="page_note">Be sure to place these tags in your template where you want the information to be displayed on the page.
				See the user manual for more information.</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Geographic Navigation
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='navigation'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Top Level Geographic Navigation
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='navigation_top'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Link to Change Selected Region
					<br />
					<span class="small_font">(Alternative space-saving option to normal navigation)</span>
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='change_region_link'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Listing's Geographic Location
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='listing_regions'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Currently Selected Location's Full Breadcrumb
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='breadcrumb'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Currently Selected Location Label
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='current_region'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Inserts JS/CSS into Head<br />
					<span class="small_font">Allows for Advanced Customization</span>
				</div>
				<div class="rightColumn" style="white-space: nowrap;">
					{ldelim}addon author='geo_addons' addon='geographic_navigation' tag='insert_head'}
				</div>
				<div class="clearColumn"></div>
			</div>
			<p class="page_note">
				<strong>Tip:</strong> Stick that last tag in a template, then use the CSS class <strong>geographic_navigation_changeLink</strong> on any element
				to turn it into a "choose location" link.  This works on images or input buttons too!
			</p>
		</div>
	</fieldset>
</form>