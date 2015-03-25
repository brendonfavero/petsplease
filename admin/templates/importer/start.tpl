{* 7.3.2-119-gc584f6b *}
{$adminMsgs}
<form action="" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>File Upload</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Import Users or Listings?
				</div>
				<div class="rightColumn">
					<select name="import_type">
						<option value="user">Users</option>
						<option value="listing" disabled="disabled">Listings (Not Implemented Yet)</option>
					</select>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Import Source File
				</div>
				<div class="rightColumn">
					<input type="file" name="source" />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Filetype
				</div>
				<div class="rightColumn">
					<select name="filetype" id="filetype_ddl">
						<option value="csv">CSV</option>
						<option value="xml" disabled="disabled">XML (Not Implemented Yet)</option>
					</select>
				</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>

	<fieldset id="csv_settings" class="type_settings" style="display: none;">
		<legend>CSV Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					First row contains column headers?
				</div>
				<div class="rightColumn">
					<input type="radio" value="1" name="csv_skipfirst" /> Yes<br />
					<input type="radio" value="0" name="csv_skipfirst" checked="checked" /> No<br />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Field Delimiter Character
				</div>
				<div class="rightColumn">
					<input type="text" name="csv_delimiter" value="," size="1" /> default: comma (,)
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Field Encapsulation Character
				</div>
				<div class="rightColumn">
					<input type="text" name="csv_encapsulation" value='"' size="1" /> default: double-quote (")
				</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>

	<fieldset id="xml_settings" class="type_settings" style="display: none;">
		<legend>XML Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Root element name (NOTE: XML not implemented yet!)
				</div>
				<div class="rightColumn">
					<input type="text" name="root" />
				</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	
	<fieldset id="general_settings" class="type_settings" style="display: none;">
		<legend>General Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Base Image Path {$base_image_tooltip}
				</div>
				<div class="rightColumn">
					<input type="text" name="base_image_path" />
				</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	
	<div class="center"><input type="submit" value="Continue" name="auto_save" id="continue_btn" style="display: none;" /></div>
</form>

<script>
	jQuery('#filetype_ddl').change(function() {
		var type = jQuery('#filetype_ddl').val();
		
		jQuery('.type_settings').hide(); //hide any choices showing from before
		jQuery('#continue_btn').hide(); //only reveal the continue button when a valid set of settings is also shown
		jQuery('#general_settings').hide();
		if(type == 'csv') {
			jQuery('#csv_settings').show();
			jQuery('#continue_btn').show();
			jQuery('#general_settings').show();
		} else if(type == 'xml') {
			jQuery('#xml_settings').show();
			jQuery('#continue_btn').show();
			jQuery('#general_settings').show();
		}
	});
	//fire the event manually to make sure everything's showing right as the page (re)loads
	jQuery('#filetype_ddl').change();
</script>