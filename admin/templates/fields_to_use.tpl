{* 7.0.0-61-g96a80e5 *}

{$admin_msgs}

{if $is_ent}
	<div class="page_note">
		<strong>Category-Specific Fields:</strong>  To edit fields for a {if $categoryId}different{/if} Category, browse to that specific category under <a href="index.php?page=categories_setup" style="white-space: nowrap;">Categories &gt; Category Setup</a>, click 
		<strong>edit</strong> for the category, then click <strong>fields</strong>.
		<br /><br />
		<strong>Hidden Fields:</strong> On the listing details page, if it does not display all the listing's fields according to the fields to use settings, remember to also check the 
		<a style="font-weight: bold;" href="index.php?page=listing_hide_fields">Listing Setup > Hide Fields</a> page for the fields that are hidden to logged-out users.
	</div>
{/if}
<form action="index.php?page=fields_to_use&amp;categoryId={$categoryId}&amp;groupId={$groupId}" method="post" id="fieldsToUseForm">
	<input type="hidden" name="settings_posted" id="settings_posted" value="1" />
	{if $categoryId||$groupId}
		<fieldset>
			<legend>
				Use {if $groupId} User Group{if $categoryId} &amp;{/if}{/if}
				{if $categoryId}Category{/if}
				Specific Fields to Use
			</legend>
			<div>
				{if $groupId}
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">For Users in User Group <span class="text_blue">{$groupName}</span>:</div>
						<div class="rightColumn">
							<select name="what_fields_to_use[group]" id="what_fields_to_use_group" class="whatFields_select">
								<option value="site"{if $groupWhat=='site'} selected="selected"{/if}>Use Site Default Settings</option>
								<option value="own"{if $groupWhat=='own'} selected="selected"{/if}>Use User Group Specific Settings</option>
							</select>
						</div>
						<div class="clearColumn"></div>
					</div>
				{/if}
				{if $categoryId}
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Fields in Category <span class="text_blue">{$categoryName}</span>:</div>
						<div class="rightColumn">
							<select name="what_fields_to_use[category]" id="what_fields_to_use_category" class="whatFields_select">
								<option value="site"{if $categoryWhat=='site'} selected="selected"{/if}>Site-Wide Settings</option>
								<option value="parent"{if $categoryWhat=='parent'} selected="selected"{/if}>Parent's Category-Specific Settings</option>
								<option value="own"{if $categoryWhat=='own'} selected="selected"{/if}>Category-Specific Settings</option>
							</select>
						</div>
						<div class="clearColumn"></div>
					</div>
				{/if}
				<br />
				<div class="center">
					<a href="index.php?page=fields_to_use&amp;change=1&amp;categoryId={$categoryId}&amp;groupId={$groupId}" class="mini_button lightUpLink">
						{* Note: Same wording should be used on fields_to_use_change.tpl title text *}
						{if $groupId}
							Edit a Different User Group's
							{if $categoryId}
								Fields for {$categoryName} Category 
							{else}
								Default Fields
							{/if}
						{else}
							Edit User Group Specific
							{if $categoryId}
								 Fields for {$categoryName} Category
							{else}
								Default Fields
							{/if}
						{/if}
					</a><br /><br />
					<input type="submit" value="Save" class="mini_button saveFieldsButton" />
				</div>
			</div>
		</fieldset>
	{else}
		<h2>
			Currently Editing:  <span class="text_blue">Site-Wide Fields to Use</span>
			{if $is_ent}<a href="index.php?page=fields_to_use&amp;change=1&amp;categoryId={$categoryId}&amp;groupId={$groupId}" class="mini_button lightUpLink">Change User Group</a>{/if}
		</h2>
	{/if}
	<div id="fieldsToUse_off" style="display: none;">
		<h2>
			<span class="explainSpan"></span>
		</h2>
	</div>
	<div id="fieldsToUse_settings">
		{foreach $default_fields as $fieldSection => $sectionFields}
			<fieldset style="background: white;">
				<legend>{$sectionFields.legend}</legend>
				<div>
					<ul class="tabList{if $forceTab} ignoreActiveCookie{/if}">
						<li id="main_{$fieldSection}"{if $activeTab=='main'} class="activeTab"{/if}>Main Settings</li>
						{foreach $default_locations as $name => $locations}
							<li id="locations_{$name}_{$fieldSection}"{if $activeTab==$name} class="activeTab"{/if}>Display in {$name|replace:'_':' '|capitalize}</li>
						{/foreach}
					</ul>
					<div id="main_{$fieldSection}Contents" class="tabContents">
						<table>
							<thead>
								<tr class="col_hdr_top">
									<th style="width: 10px; text-align: center; white-space: nowrap;">
										<label style="white-space: nowrap;">
											<input type="checkbox" class="checkAll enabled" id="{$fieldSection}_is_enabled" />
											Enabled
										</label>
									</th>
									<th>Field</th>
									<th style="width: 10px; text-align: center; white-space: nowrap;">
										<label style="white-space: nowrap;">
											<input type="checkbox" class="checkAll" id="{$fieldSection}_is_required" />
											Required
										</label>
									</th>
									<th style="width: 10px; text-align: center; white-space: nowrap;">
										<label style="white-space: nowrap;">
											<input type="checkbox" class="checkAll" id="{$fieldSection}_is_editable" />
											Editable
										</label>
									</th>
									<th style="white-space: nowrap;">
										Field Type
									</th>
									<th style="white-space: nowrap;">Field Length</th>
								</tr>
							</thead>
							<tbody>
								{foreach from=$sectionFields.fields item=field key=fieldName}
									<tr class="{cycle values='row_color1,row_color2'}">
										<td class="center enabledCheckboxColumn">
											<input type="checkbox" name="fields[{$fieldSection}][{$fieldName}][is_enabled]" value="1" class="{$fieldSection}_is_enabled_input enableCheckbox"
												{if $fields.$fieldName.is_enabled}checked="checked"{/if} id="fields_{$fieldSection}_{$fieldName}_is_enabled"
												{if $field.dependencies.enabled}onclick="if(this.checked){ {foreach $field.dependencies.enabled as $checkMe}$('{$checkMe}').checked=true; FieldsManage.enableCheckboxClick($('{$checkMe}')); {/foreach} }"{/if}
												 />
										</td>
										{strip}
											<td style="white-space: nowrap;">
												{if $field.opt_name_set&&!$categoryId&&!$groupId}
													<input type="text" name="fields[{$fieldSection}][{$fieldName}][label]" value="{$field.label|escape}" style="width: 150px;" />
												{else}
													{$field.label}
												{/if}
												{if $field.opt_num}&nbsp;({$field.opt_num}){/if}
											</td>
										{/strip}
										<td class="center">
											{if !in_array('is_required',$field.skipData)}
												<input type="checkbox" name="fields[{$fieldSection}][{$fieldName}][is_required]" value="1" class="{$fieldSection}_is_required_input"
													id="fields_{$fieldSection}_{$fieldName}_is_required" {if $fields.$fieldName.is_required}checked="checked"{/if}
													{if $field.dependencies.required}onclick="if(this.checked){ {foreach $field.dependencies.required as $checkMe}$('{$checkMe}').checked=true; {/foreach} }"{/if}
													 />
											{/if}
										</td>
										<td class="center">
											{if !in_array('is_editable',$field.skipData)}
												<input type="checkbox" name="fields[{$fieldSection}][{$fieldName}][can_edit]" value="1" class="{$fieldSection}_is_editable_input"
													id="fields_{$fieldSection}_{$fieldName}_is_editable" {if $fields.$fieldName.can_edit}checked="checked"{/if} />
											{/if}
										</td>
										<td class="center">
											{if $field.type_select}
												<select name="fields[{$fieldSection}][{$fieldName}][field_type]" class="{$fieldSection}_type_input typeSelector" id="{$fieldSection}_type_select_{$fieldName}">
													<option value="text"{if $fields.$fieldName.field_type==text} selected="selected"{/if}>Text (Single Line)</option>
													<option value="textarea"{if $fields.$fieldName.field_type==textarea} selected="selected"{/if}>Text (Multiple Lines)</option>
													<option value="number"{if $fields.$fieldName.field_type==number} selected="selected"{/if}>Numeric</option>
													<option value="cost"{if $fields.$fieldName.field_type==cost} selected="selected"{/if}>Adds Cost</option>
													<option value="date"{if $fields.$fieldName.field_type==date} selected="selected"{/if}>Date</option>
													<option disabled="disabled" class="center">--Drop Down Type--</option>
													{foreach from=$sell_question_types item=qData key=type_id}
														<option value="{$type_id}"{if $fields.$fieldName.field_type=='dropdown'&&$fields.$fieldName.type_data==$type_id} selected="selected"{/if}>{$qData.type_name}</option>
													{/foreach}
												</select>
												<label id="{$fieldSection}_type_select_{$fieldName}_otherLabel">Use "Other" Box: <input name="fields[{$fieldSection}][{$fieldName}][use_other]" value="1" type="checkbox"
													{if $fields.$fieldName.use_other}checked="checked"{/if} /></label>
											{elseif $field.type_label}
												{$field.type_label}
											{elseif $field.type==text}
												Text
											{elseif $field.type==textarea}
												Text (Multi-Line)
											{elseif $field.type==url}
												URL Link
											{elseif $field.type==email}
												E-Mail Address<br />
												Show in Listing Details:
												<input type="hidden" name="fields[{$fieldSection}][{$fieldName}][type_data]" value="" /> 
												<input type="checkbox" name="fields[{$fieldSection}][{$fieldName}][type_data]" {if $fields.$fieldName.type_data=='reveal'}checked="checked" {/if}value="reveal" />
											{elseif $field.type==number}
												Numeric
											{elseif $field.type==cost}
												Adds Cost
											{elseif $field.type=='date'}
												Date
											{elseif $field.type==dropdown}
												Drop-down Selection
											{else}
												--Other--
											{/if}
										</td>
										<td class="center">
											{if $field.type_extra=='tags'}
												<label>Tag Length:  <input type="text" name="fields[{$fieldSection}][{$fieldName}][text_length]" size="4" id="{$fieldSection}_type_select_{$fieldName}_fieldLength"
													value="{$fields.$fieldName.text_length}" /></label><br />
												<label>Max Tags: <input type="text" name="fields[{$fieldSection}][{$fieldName}][field_max_tags]" value="{if $fields.$fieldName.type_data}{$fields.$fieldName.type_data|escape}{else}0{/if}" size="4" /></label>
											{elseif $field.type_extra=='on_off'}
												<label>{$field.type_extra_label}: <input type="checkbox" name="fields[{$fieldSection}][{$fieldName}][type_data]" value="1"{if $fields.$fieldName.type_data} checked="checked"{/if} /></label>
											{elseif $field.type!=='other'&&$field.type!=='dropdown'}
												<input type="text" name="fields[{$fieldSection}][{$fieldName}][text_length]" size="4" id="{$fieldSection}_type_select_{$fieldName}_fieldLength"
													value="{$fields.$fieldName.text_length}" />
												{if $field.type_select}
													<span id="{$fieldSection}_type_select_{$fieldName}_fieldLengthBlank">-</span>
												{/if}
											{else}
												-
											{/if}
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
					{foreach $default_locations as $locationSection => $locations}
						<div id="locations_{$locationSection}_{$fieldSection}Contents" class="tabContents">
							<table>
								<thead>
									<tr class="col_hdr_top">
										<th>Field</th>
										{foreach $locations as $location => $label}
											<th style="width: 20px; white-space: nowrap;">
												<label style="white-space: nowrap;">
													<input type="checkbox" class="checkAll" id="{$fieldSection}_location_{$location}" />
													{if is_array($label)}
														<span title="{$label.long|escape}">{$label.short}</span>
													{else}
														{$label}
													{/if}
												</label>
											</th>
										{/foreach}
									</tr>
								</thead>
								<tbody>
									{foreach from=$sectionFields.fields item=field key=fieldName}
										<tr class="{cycle values='row_color1,row_color2'}">
											{strip}
												<td style="white-space: nowrap;">
													<input type="checkbox" style="display: none;" class="fields_{$fieldSection}_{$fieldName}_is_enabled_displayLocations" />
													{$field.label}
													{if $field.opt_num}&nbsp;({$field.opt_num}){/if}
												</td>
											{/strip}
											{foreach from=$locations item=label key=location}
												<td class="center">
													{if $field.skipLocations === true || in_array($location, $field.skipLocations)}
													-
													{else}
														<input type="checkbox" 
															name="fields[{$fieldSection}][{$fieldName}][display_locations][{$location}]" 
															value="1"
															class="{$fieldSection}_location_{$location}_input"
															{if in_array($location, $fields.$fieldName.display_locations)} checked="checked"{/if}
															 />
													{/if}
												</td>	
											{/foreach}
										</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
					{/foreach}
					<div class="center">
						<br />
						<input type="submit" name="auto_save" value="Save" class="mini_button saveFieldsButton" />
						<input type="reset" value="Reset" class="mini_cancel resetButton" />
					</div>
				</div>
			</fieldset>
		{/foreach}
		{if !$groupId}
			<fieldset>
				<legend>Miscellaneous {if $categoryId}Category-Specific{else}Site-Wide{/if} Field Settings</legend>
				<div>
					{if !$categoryId}
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">
								<input type="checkbox" name="misc[textarea_wrap]" id="textarea_wrap" value="1" {if $misc.textarea_wrap}checked="checked" {/if}/>
							</div>
							<div class="rightColumn">
								<label for="textarea_wrap">Automatic Line Breaks on Text Areas?</label>
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">
								<input type="checkbox" name="misc[editable_category_specific]" id="editable_category_specific" value="1" {if $misc.editable_category_specific}checked="checked" {/if}/>
							</div>
							<div class="rightColumn">
								<label for="editable_category_specific">Editable Category Questions?</label>
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">
								<input type="checkbox" name="misc[allow_html_description_browsing]" id="allow_html_description_browsing" value="1" {if $misc.allow_html_description_browsing}checked="checked" {/if}/>
							</div>
							<div class="rightColumn">
								<label for="allow_html_description_browsing">
									Keep full description HTML when browsing<br />
									<span class="small_font"><strong>Warning:</strong> If enabled, a badly formatted listing can cause browsing problems!</span>
								</label>
							</div>
							<div class="clearColumn"></div>
						</div>
					{/if}
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Display the Description below the Title</div>
						<div class="rightColumn">
							<label>
								<input type="radio" name="misc[display_ad_description_where]" value="1"{if $misc.display_ad_description_where} checked="checked"{/if} />
								below title
							</label>
							<label>
								<input type="radio" name="misc[display_ad_description_where]" value="0"{if !$misc.display_ad_description_where} checked="checked"{/if} />
								own column
							</label>
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Length of Description to Display</div>
						<div class="rightColumn">
							<label>
								<input type="radio" name="misc[display_all_of_description]" value="1"{if $misc.display_all_of_description} checked="checked"{/if} />
								all of description
							</label>
							<label>
								<input type="radio" name="misc[display_all_of_description]" value="0"{if !$misc.display_all_of_description} checked="checked"{/if} />
								display this many characters:&nbsp;&nbsp;
							</label>
							<input type="text" name="misc[length_of_description]" value="{$misc.length_of_description}" size="4" />
						</div>
						<div class="clearColumn"></div>
					</div>
					{if $categoryId}
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">Default Order of Listings</div>
							<div class="rightColumn">
								<select name="misc[default_display_order_while_browsing_category]">
									{foreach from=$order_by_array item=label key=id}
										<option value="{$id}"{if $misc.default_display_order_while_browsing_category==$id} selected="selected"{/if}>{$label}</option>
									{/foreach}
								</select>
							</div>
							<div class="clearColumn"></div>
						</div>
					{else}
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">General Date/Time Display Format</div>
							<div class="rightColumn">
								{include file='dateDisplayFormatDropdown.tpl' fieldName='misc[entry_date_configuration]' fieldValue=$misc.entry_date_configuration}
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">'Member Since' Date Display Format</div>
							<div class="rightColumn">
								{include file='dateDisplayFormatDropdown.tpl' fieldName='misc[member_since_date_configuration]' fieldValue=$misc.member_since_date_configuration}
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">'Date' field type long display format</div>
							<div class="rightColumn">
								{include file='dateDisplayFormatDropdown.tpl' fieldName='misc[date_field_format]' fieldValue=$misc.date_field_format noTimes=1}
							</div>
							<div class="clearColumn"></div>
						</div>
						<div class="{cycle values='row_color1,row_color2'}">
							<div class="leftColumn">'Date' field type short display format</div>
							<div class="rightColumn">
								{include file='dateDisplayFormatDropdown.tpl' fieldName='misc[date_field_format_short]' fieldValue=$misc.date_field_format_short noTimes=1}
							</div>
							<div class="clearColumn"></div>
						</div>
						{if $is_ent}
							<div class="{cycle values='row_color1,row_color2'}">
								<div class="leftColumn">Display 'use as cost' optional fields</div>
								<div class="rightColumn">
									<label>
									<input type="radio" name="misc[add_cost_at_top]" value="0"{if !$misc.add_cost_at_top} checked="checked"{/if} />
										With other optional site-wide fields
									</label>
									<label>
										<input type="radio" name="misc[add_cost_at_top]" value="1"{if $misc.add_cost_at_top} checked="checked"{/if} />
										With 'pricing' fields
									</label>
								</div>
								<div class="clearColumn"></div>
							</div>
						{/if}
					{/if}
					<div class="center">
						<br />
						<input type="submit" name="auto_save" value="Save" class="mini_button saveFieldsButton" />
						<input type="reset" value="Reset" class="mini_cancel resetButton" />
					</div>
				</div>
			</fieldset>
			
			{if $is_ent&&!$categoryId&&!$groupId}
				<fieldset>
					<legend>Automatic Listing Titles (Site Wide Settings)</legend>
					<div>
						<div class="center">
							<label><input type="checkbox" {if $misc.use_sitewide_auto_title}checked="checked"{/if}
								onclick="$('title_stuff')[this.checked? 'show' : 'hide']();" 
								name="misc[use_sitewide_auto_title]" value="1" />
							<strong class="medium_font">Automatically generate listing titles from Optional Site Wide Fields</strong>
							</label>
						</div>
						<div id="title_stuff" class="medium_font" style="padding-bottom: 30px; text-align: center; width: 100%;{if !$misc.use_sitewide_auto_title} display: none;{/if}">
							{foreach from=$misc.sitewide_auto_titles key=auto_num item=auto_title}
								<select name="misc[sitewide_auto_titles][]">
									<option value="0"{if $auto_title==0} selected="selected"{/if}>
									{foreach from=$default_fields.optional_fields.fields item=field}
										<option value="oswf{$field.opt_num}" {if $field.opt_num==$auto_title|replace:'oswf':''}selected="selected"{/if}>{$field.label}({$field.opt_num})</option>
									{/foreach}
								</select>
							{/foreach}
						</div>
						<div class="center">
							<br />
							<input type="submit" name="auto_save" value="Save" class="mini_button saveFieldsButton" />
							<input type="reset" value="Reset" class="mini_cancel resetButton" />
						</div>
					</div>
				</fieldset>
			{/if}
		{/if}
	</div>
	<input type="hidden" name="auto_save" id="auto_save" value="1" />
</form>