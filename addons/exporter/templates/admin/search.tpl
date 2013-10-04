{* 7.2.2-6-g4661b5a *}
{$notices}

{capture assign="multiSelect"}
	<strong>Multi-Select Tip:</strong> To select or un-select multiple entries, hold down CTRL in Windows 
		or CMD in OS X while left-clicking.
{/capture}
{capture assign="exportButton"}
	<a href="#" class="button exportButton">Export &amp; Download Now</a>
{/capture}
<div id="requestResponse"></div>

<form method="post" id="exportForm" action="index.php?page=addon_exporter">
	<br />
	{$exportButton}
	<input type="hidden" name="auto_save" value="1" />
	
	<br /><br /><br />
	
	<ul class="tabList">
		<li id="criteriaTab" class="activeTab">Export Criteria</li>
		<li id="dataTab">Data Exported</li>
		<li id="saveTab">Save/Load Export Settings</li>
	</ul>

	<div class="tabContents" id="criteriaTabContents">
		<fieldset style="float: left;">
			<legend class="startClosed">General</legend>
			<div style="height: 350px;">
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Max listings</div>
					<div class="rightColumn">
						<input type="text" name="maxListings" value="500" class="shortNum" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Listing Type</div>
					<div class="rightColumn">
						<label><input type="radio" name="item_type" value="indif" checked="checked" />Indifferent</label><br />
						<label><input type="radio" name="item_type" value="2" />Auction</label><br />
						<label><input type="radio" name="item_type" value="1" />Classified</label>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Status</div>
					<div class="rightColumn">
						<label><input type="radio" name="live" value="indif" />Indifferent</label><br />
						<label><input type="radio" name="live" value="1" checked="checked" />Live</label><br />
						<label><input type="radio" name="live" value="0" />Expired</label><br />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Image</div>
					<div class="rightColumn">
						<label><input type="radio" name="image" value="indif" checked="checked" />Indifferent</label><br />
						<label><input type="radio" name="image" value="1" />Yes</label><br />
						<label><input type="radio" name="image" value="0" />No</label>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Price</div>
					<div class="rightColumn">
						<input type="text" name="price[low]" class="shortNum textbox" /> to <input type="text" name="price[high]" class="shortNum textbox" />
					</div>
					<div class="clearColumn" style="width: 270px;"></div>
				</div>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Date</legend>
			<div style="height: 350px;">
				<strong>Start Date</strong>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Min</div>
					<div class="rightColumn">
						<input type="text" name="date[start][low]" id="startDateLow" class="dateInput" />
						<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="startDateLowCalButton" />
					</div>
					<div class="clearColumn" style="width: 260px;"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Max</div>
					<div class="rightColumn">
						<input type="text" name="date[start][high]" id="startDateHigh" class="dateInput" />
						<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="startDateHighCalButton" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<strong>End Date</strong>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Min</div>
					<div class="rightColumn">
						<input type="text" name="date[end][low]" id="endDateLow" class="dateInput" />
						<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="endDateLowCalButton" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Max</div>
					<div class="rightColumn">
						<input type="text" name="date[end][high]" id="endDateHigh" class="dateInput" />
						<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="endDateHighCalButton" />
					</div>
					<div class="clearColumn"></div>
				</div>
				
				<strong>Duration</strong>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Min</div>
					<div class="rightColumn">
						<input type="text" name="date[duration][low][num]" class="extraShort" />
						<select name="date[duration][low][multiplier]">
							<option value="0">Select</option>
							<option value="86400">day(s)</option>
							<option value="604800">week(s)</option>
							<option value="2419200">month(s)</option>
							<option value="31536000">year(s)</option>
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Max</div>
					<div class="rightColumn">
						<input type="text" name="date[duration][high][num]" class="extraShort" />
						<select name="date[duration][high][multiplier]">
							<option value="0">Select</option>
							<option value="86400">day(s)</option>
							<option value="604800">week(s)</option>
							<option value="2419200">month(s)</option>
							<option value="31536000">year(s)</option>
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Extras</legend>
			<div style="height: 350px;">
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Bolding</div>
					<div class="rightColumn">
						<label><input type="radio" name="bolding" value="indif" checked="checked" /> Indifferent</label><br />
						<label><input type="radio" name="bolding" value="1" /> Yes</label><br />
						<label><input type="radio" name="bolding" value="0" /> No</label>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Better placement</div>
					<div class="rightColumn">
						<label><input type="radio" name="better_placement" value="indif" checked="checked" /> Indifferent</label><br />
						<label><input type="radio" name="better_placement" value="1" /> Yes</label><br />
						<label><input type="radio" name="better_placement" value="0" /> No</label>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Attention getter</div>
					<div class="rightColumn">
						<label><input type="radio" name="attention_getter" value="indif" checked="checked" /> Indifferent</label><br />
						<label><input type="radio" name="attention_getter" value="1" /> Yes</label><br />
						<label><input type="radio" name="attention_getter" value="0" /> No</label>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Featured Levels</div>
					<div class="rightColumn">
						<label><input type="checkbox" name="featured_ad" value="1" /> One</label><br />
						<label><input type="checkbox" name="featured_ad_2" value="1" /> Two</label><br />
						<label><input type="checkbox" name="featured_ad_3" value="1" /> Three</label><br />
						<label><input type="checkbox" name="featured_ad_4" value="1" /> Four</label><br />
						<label><input type="checkbox" name="featured_ad_5" value="1" /> Five</label>
					</div>
					<div class="clearColumn" style="width: 200px;"></div>
				</div>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Categories</legend>
			<div style="height: 350px;">
				<p>Select which categories to export:</p>
				<select name="category[]" size="10" multiple="multiple" id="catMultiselect">
					{$categories}
				</select>
				<p class="page_note" style="width: 220px;">{$multiSelect}</p>
			</div>
		</fieldset>
		{* For now, disable location and optionals until we get a chance to implement
		<fieldset style="float: left;">
			<legend class="startClosed">Location</legend>
			<div style="height: 350px;">
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">City</div>
					<div class="rightColumn">
						<input type="text" name="location_city" class="textField" value="" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">State</div>
					<div class="rightColumn">
						<select name="location_state[]" multiple="multiple" size="5">
							{$states}
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Country</div>
					<div class="rightColumn">
						<select name="location_country[]" multiple="multiple" size="5">
							{$countries}
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Zip/Postal Code</div>
					<div class="rightColumn">
						<input type="text" name="location_zip" class="textField" value="" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<p class="page_note" style="width: 350px;">
					{$multiSelect}
					<br /><br />
					For <strong>City</strong> or <strong>Zip/Postal Codes</strong>:
					Use commas to separate multiple values.
				</p>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Optionals</legend>
			<div>
				<p class="page_note">Enter text to search for in any of the optional fields.</p>
				{foreach from=$optionals key=i item=label}
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">{$label}&nbsp;({$i})</div>
						<div class="rightColumn">
							<input type="text" name="optional_field_{$i}" class="textField" />
						</div>
						<div class="clearColumn"></div>
					</div>
				{/foreach}
			</div>
		</fieldset>
		*}
		<div class="clear"></div>
	</div>
	
	<div class="tabContents" id="dataTabContents">
		<div class="{cycle values='row_color1,row_color2'}">
			<div class="leftColumn">Export Type</div>
			<div class="rightColumn">
				<label><input type="radio" name="exportType" value="xml" class="exportTypeRadio" checked="checked" /> Generic XML</label><br />
				<label><input type="radio" name="exportType" value="csv" class="exportTypeRadio" /> Generic CSV</label><br />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class="leftColumn">Export To</div>
			<div class="rightColumn">
				addons/exporter/exports/<input type="text" name="filename" value="export" size="10" /><span id="filenameExtension">.xml</span>
			</div>
			<div class="clearColumn"></div>
		</div>
		<fieldset style="float: left;">
			<legend class="startClosed">Main Fields</legend>
			<div>
				<p class="page_note" style="width: 210px;">Select which fields from the main listing table you would like the export to contain.</p>
				<select name="show[]" size="5" multiple="multiple">
					<option value="id" selected="selected">ID</option>
					<option value="title" selected="selected">Title</option>
					<option value="description" selected="selected">Description</option>
					<option value="category">Category ID</option>
					<option value="seller">Seller ID</option>
					<option value="email">E-Mail</option>
					<option value="date">Date</option>
					<option value="duration">Duration (Days)</option>
					<option value="item_type">Item type</option>
					<option value="live">Status</option>
					<option value="image">Image Count</option>
					<option value="price">Price</option>
					<option value="high_bidder">High Bidder ID</option>
					<option value="reserve_price">Reserve Price</option>
					<option value="location_city">City</option>
					<option value="location_state">State</option>
					<option value="location_country">Country</option>
					<option value="location_zip">Zip code</option>
					<option value="phone">Phone 1</option>
					<option value="phone2">Phone 2</option>
					<option value="fax">Fax</option>
					<option value="url_link_1">URL Link 1</option>
					<option value="url_link_2">URL Link 2</option>
					<option value="url_link_3">URL Link 3</option>
					<option value="email">E-Mail</option>
					<option value="mapping_location">Mapping Location</option>				
					<option value="better_placement">Better placement</option>
					<option value="optional_field_1">Optional field 1</option>
					<option value="optional_field_2">Optional field 2</option>
					<option value="optional_field_3">Optional field 3</option>
					<option value="optional_field_4">Optional field 4</option>
					<option value="optional_field_5">Optional field 5</option>
					<option value="optional_field_6">Optional field 6</option>
					<option value="optional_field_7">Optional field 7</option>
					<option value="optional_field_8">Optional field 8</option>
					<option value="optional_field_9">Optional field 9</option>
					<option value="optional_field_10">Optional field 10</option>
					<option value="optional_field_11">Optional field 11</option>
					<option value="optional_field_12">Optional field 12</option>
					<option value="optional_field_13">Optional field 13</option>
					<option value="optional_field_14">Optional field 14</option>
					<option value="optional_field_15">Optional field 15</option>
					<option value="optional_field_16">Optional field 16</option>
					<option value="optional_field_17">Optional field 17</option>
					<option value="optional_field_18">Optional field 18</option>
					<option value="optional_field_19">Optional field 19</option>
					<option value="optional_field_20">Optional field 20</option>
					<option value="featured_ad">Featured level 1</option>
					<option value="featured_ad_2">Featured level 2</option>
					<option value="featured_ad_3">Featured level 3</option>
					<option value="featured_ad_4">Featured level 4</option>
					<option value="featured_ad_5">Featured level 5</option>
					<option value="bolding">Bolding</option>
				</select>
				<p class="page_note" style="width: 210px;">{$multiSelect}</p>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Additional Fields</legend>
			<div>
				<p class="page_note" style="width: 210px;">Select which information pulled from additional tables in the database you would
					like the export to contain.</p>
				<select name="show_extra[]" size="3" multiple="multiple" id="dataExtra">
					<option value="img_url_1">Main Image URL</option>
					<option value="img_url_all">All Image URLs</option>
					<option value="extra_questions">Extra Questions</option>
				</select>
				<p class="page_note" style="width: 210px;">{$multiSelect}</p>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Field Formatting</legend>
			<div>
				<p class="page_note" style="width: 350px;">These settings affect how certain fields are displayed in the exported data.  They will only
					affect applicable fields as selected in <strong>Main Fields</strong> or <strong>Additional Fields</strong>.</p>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Date/Time Fields</div>
					<div class="rightColumn">
						<label><input type="radio" name="fieldFormat[date]" value="unix" checked="checked" /> 1184618389 <em>(Unix timestamp)</em></label><br />
						<label><input type="radio" name="fieldFormat[date]" value="date_time" /> 07/16/2007 - 20:39:49</label><br />
						<label><input type="radio" name="fieldFormat[date]" value="date" /> 07/16/2007</label><br />
						<label>
							<input type="radio" name="fieldFormat[date]" value="custom" /> 
							Custom (see <a href="http://php.net/date" target="_new">date</a>):<br />
							<input type="text" style="margin-left: 24px;" name="fieldFormat[date_custom]" value="m/d/Y - H:i:s" size="10" />
						</label><br />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Category ID Fields</div>
					<div class="rightColumn">
						<label><input type="radio" name="fieldFormat[category]" value="id" checked="checked" /> ID Only</em></label><br />
						<label><input type="radio" name="fieldFormat[category]" value="name_id" /> Name &amp; ID</em></label><br />
						<label><input type="radio" name="fieldFormat[category]" value="name" /> Name Only</em></label><br />
					</div>
					<div class="clearColumn"></div>
				</div>
			</div>
		</fieldset>
		<div class="clear"></div>
	</div>
	<div class="tabContents" id="saveTabContents">
		<fieldset style="float: left;">
			<legend class="startClosed">Save</legend>
			<div>
				<p class="page_note">Save export settings currently set for use later.</p>
				<div class="{cycle values='row_color1,row_color2'}" style="width: 350px;">
					<div class="leftColumn">Save Name</div>
					<div class="rightColumn">
						<input type="text" name="save_name" value="" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="center"><a href="#" id="saveButton" class="mini_button">Save Settings</a></div>
			</div>
		</fieldset>
		<fieldset style="float: left;">
			<legend class="startClosed">Load</legend>
			<div>
				<p class="page_note">Load any previously saved export settings.</p>
				<div id="loadTable">
					{include file='admin/load_settings_table.tpl'}
				</div>
			</div>
		</fieldset>
		<div class="clear"></div>
	</div>
</form>
<div class="clear"></div>
<br /><br />
{$exportButton}