{* 7.1beta4-4-g9367fc8 *}
{$adminMsgs}

<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function () {
		var updateFiltersDep = function () {
			jQuery('.enabledCheckbox').each(function(){
				var name = jQuery(this).attr('name').slice(9,-10);
				var options = jQuery('option[value='+name+']');
				if (options.length) {
					if (jQuery(this).prop('checked')) {
						//show the option
						options.show();
					} else {
						options.hide();
					}
				}
			});
		};
		
		//go ahead and update now
		updateFiltersDep();
		
		//make check all work
		jQuery('input.checkAll').click(function () {
			var isChecked = jQuery(this).prop('checked');
			jQuery(this).closest('table').find('.enabledCheckbox').prop('checked',isChecked);
		});
		
		//make the leveled fields "enabled" depending on other features
		jQuery('input.leveled').click(function () {
			var isChecked = jQuery(this).prop('checked');
			var level = jQuery(this).siblings('input.leveled_level').val();
			var leveled_field = jQuery(this).siblings('input.leveled_field').val();
			
			//it is checked, make sure lower levels are also checked...
			jQuery('input.leveled_field[value='+leveled_field+']').each(function () {
				var thisLevel=jQuery(this).siblings('input.leveled_level').val();
				
				if (isChecked && thisLevel<level) {
					jQuery(this).siblings('input.leveled').prop('checked',true);
				} else if (!isChecked&&thisLevel>level) {
					jQuery(this).siblings('input.leveled').prop('checked',false);
				}
			});
			//once done, update filter dependency dropdowns
			updateFiltersDep();
		});
		
		jQuery('input.enabledCheckbox:not(.leveled)').click(updateFiltersDep);
	});
	//]]>
</script>

<form action="" method="post">
	<input type="hidden" name="category" value="{$category_id}" />
	{if $no_settings}
		<p class="page_note" style="text-align: center; font-weight: bold;">
			{if $category_id}
				There are currently no saved settings for this category (<span style="color: #FF0000;">{$category_name}</span>). Settings from higher-up categories will be used until you save this form.
			{else}
				There are currently no saved <span style="color: #FF0000;">Site-Wide</span> Browsing Filter settings. Save this form to begin using Browsing Filters.
			{/if}
		</p>		
	{else}
		<div class="center" style="margin: 10px auto;">
			<a class="button" href="index.php?page=browsing_filter_settings&reset=yes&category={$category_id}">
				{if $category_id}
					Clear Browsing Filter Settings for Category: <span style="color: #FF0000;">{$category_name}</span>
				{else}
					Clear <span style="color: #FF0000;">Site-Wide</span> Browsing Filter Settings
				{/if}
			</a>
		</div>
	{/if}
	
	{if !$category_id}
		<fieldset>
			<legend>General Settings</legend>
			<div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="browsing_filters_enabled" value="1" {if $browsing_filters_enabled}checked="checked"{/if} />
					</div>
					<div class="rightColumn">Show Browsing Filters Automatically when Browsing {$browsing_filters_enabled_tooltip}</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						Use Values:{$use_listing_values_tooltip}
					</div>
					<div class="rightColumn">
						<label><input type="radio" name="use_listing_values" value="1" {if $use_listing_values}checked="checked"{/if} /> From Listings</label><br />
						<label><input type="radio" name="use_listing_values" value="0" {if !$use_listing_values}checked="checked"{/if} /> From Pre-Valued Dropdown Values</label>
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Expandable Filter Threshold {$expandable_threshold_tooltip}</div>
					<div class="rightColumn">
						<input type="text" name="expandable_threshold" value="{$expandable_threshold}" size="2" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="center">
					<input type="submit" name="auto_save" class="mini_button" value="Save" />
				</div>
			</div>
		</fieldset>		
	{/if}
		
	<fieldset>
		<legend>General Fields</legend>
		<div>
			{if $general_fields_enabled}
				<table>
					<thead>
						<tr class="col_hdr_top">
							<th>Field Name</th>
							<th style="white-space: nowrap;"><input type="checkbox" class="checkAll" /> Enabled</th>
							<th>Depends on</th>
							{foreach $languages as $l}
								<th>{$l.language} Name</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						<tr class="{cycle values='row_color1,row_color2'}">
							<td>Price</td>
							<td style="text-align: center;"><input type="checkbox" class="enabledCheckbox" name="settings[price][enabled]" value="1" {if $settings.price.enabled}checked="checked"{/if} /></td>
							<td style="text-align: center;">{include file="admin/browsing_filter_dependency/select.tpl" field='price'}</td>
							{foreach $languages as $l}
								{$id = $l.language_id}
								<td style="text-align: center;"><input type="text" name="settings[price][languages][{$id}]" value="{$settings.price.languages.$id}" /></td>
							{/foreach}
						</tr>
					</tbody>
				</table>
				<div class="center">
					<input type="submit" name="auto_save" class="mini_button" value="Save" />
				</div>
			{else}
				<p class="page_note" style="text-align: center; font-weight: bold;">Found no filterable general fields (such as "Price") enabled. See Listing Setup > Fields to Use.</p>
			{/if}
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Multi-Level Fields</legend>
		<div>
			{if $leveled_fields}
				<table>
					<thead>
						<tr class="col_hdr_top">
							<th>Multi-Level Field - Level</th>
							<th style="white-space: nowrap;"><input type="checkbox" class="checkAll" /> Enabled</th>
							<th>Depends on</th>
							<th>Sample Value</th>
							{foreach $languages as $l}
								<th>{$l.language} Name (Edit Level Settings to Change)</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach $leveled_fields as $field => $info}
							<tr class="{cycle values='row_color1,row_color2'}">
								<td>
									{if $info.level==1}
										<div style="float: right;">
											Show:
											<a href="index.php?page=leveled_field_values&amp;leveled_field={$info.leveled_field}" class="mini_button">Values</a>
											<a href="index.php?page=leveled_field_levels&amp;leveled_field={$info.leveled_field}" class="mini_button">Levels</a>
										</div>
									{/if}
									{$info.leveled_field_label} - Level {$info.level}
									<input type="hidden" name="settings[{$field}][is_leveled]" value="1" />
								</td>
								<td style="text-align: center;">
									<input type="hidden" class="leveled_level" value="{$info.level}" />
									<input type="hidden" class="leveled_field" value="{$info.leveled_field}" />
									<input type="checkbox" class="enabledCheckbox leveled" name="settings[{$field}][enabled]" value="1" {if $settings.$field.enabled}checked="checked"{/if} />
								</td>
								<td style="text-align: center;">
									{if $info.level<2}
										{include file='admin/browsing_filter_dependency/select.tpl' leveled_field=$info.leveled_field}
									{else}
										{$info.leveled_field_label} - Level {$info.level-1}
										<input type="hidden" name="settings[{$field}][dependency]" value="leveled_{$info.leveled_field}_{$info.level-1}" />
									{/if}
								</td>
								<td style="text-align: center;">
									{$info.sample}
								</td>
								{foreach $languages as $l}
									{$id = $l.language_id}
									<td style="text-align: center;">
										{if $info.labels.$id}
											{$info.labels.$id}:
										{else}
											<em style="color: red;">[Blank - No Label Set]</em>
										{/if}
									</td>
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
				<div class="center">
					<input type="submit" name="auto_save" class="mini_button" value="Save" />
				</div>
			{else}
				<p class="page_note_error">Found no multi-level fields enabled. See <strong>Listing Setup &gt; Multi-Level Fields</strong> to create new fields, and <strong>Listing Setup &gt; Fields to Use</strong> to enable/disable the new fields.</p>
			{/if}
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Optional Fields</legend>
		<div>
		{if $optionals}
			<table>
				<thead>
					<tr class="col_hdr_top">
						<th>Field Name</th>
						<th style="white-space: nowrap;"><input type="checkbox" class="checkAll" /> Enabled</th>
						<th>Depends on</th>
						{foreach $languages as $l}
							<th>{$l.language} Name</th>
						{/foreach}
					</tr>
				</thead>
				<tbody>
					{foreach $optionals as $field => $name}
						<tr class="{cycle values='row_color1,row_color2'}">
							<td>{$name}</td>
							<td style="text-align: center;"><input type="checkbox" class="enabledCheckbox" name="settings[{$field}][enabled]" value="1" {if $settings.$field.enabled}checked="checked"{/if} /></td>
							<td style="text-align: center;">{include file="admin/browsing_filter_dependency/select.tpl" field=$field}</td>
							{foreach $languages as $l}
								{$id = $l.language_id}
								<td style="text-align: center;"><input type="text" name="settings[{$field}][languages][{$id}]" value="{$settings.$field.languages.$id}" /></td>
							{/foreach}
						</tr>
					{/foreach}
				</tbody>
			</table>
			<div class="center">
				<input type="submit" name="auto_save" class="mini_button" value="Save" />
			</div>
		{else}
			<p class="page_note" style="text-align: center; font-weight: bold;">Found no optional fields enabled. See Listing Setup > Fields to Use.</p>
		{/if}
		</div>
	</fieldset>
		
	{if $category_id}
		<fieldset>
			<legend>Category-Specific Fields</legend>
			<div>
			{if $catSpec}
				<table>
					<thead>
						<tr class="col_hdr_top">
							<th>Field Name</th>
							<th style="white-space: nowrap;"><input type="checkbox" class="checkAll" /> Enabled</th>
							<th>Depends on</th>
							{foreach $languages as $l}
								<th>{$l.language} Name</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach $catSpec as $id => $name}
						{$field = "cs_"|cat:$id}
							<tr class="{cycle values='row_color1,row_color2'}">
								<td>{$name}</td>
								<td style="text-align: center;"><input type="checkbox" class="enabledCheckbox" name="settings[{$field}][enabled]" value="1" {if $settings.$field.enabled}checked="checked"{/if} /></td>
								<td style="text-align: center;">{include file="admin/browsing_filter_dependency/select.tpl" field=$field}</td>
								{foreach $languages as $l}
									{$id = $l.language_id}
									<td style="text-align: center;"><input type="text" name="settings[{$field}][languages][{$id}]" value="{$settings.$field.languages.$id}" /></td>
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
				<div class="center">
					<input type="submit" name="auto_save" class="mini_button" value="Save" />
				</div>
			{else}
				<p class="page_note" style="text-align: center; font-weight: bold;">No category-specific questions found for this category.</p>
			{/if}
			</div>
		</fieldset>
	{/if}
</form>