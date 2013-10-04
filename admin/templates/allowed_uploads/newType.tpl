{* 7.2.1-26-gab39217 *}

{$adminMsgs}

<form action="index.php?page=uploads_new_type" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>New File Type Allowed in Listing</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">File Type Name:</div>
				<div class="rightColumn"><input type="text" name="b[type_name]" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">File's Mime Type: {$toolTip.mime_type}</div>
				<div class="rightColumn">
					<input type="text" name="b[mime_type]" />
					<br /><strong>OR</strong><br />
					upload a file to pull the mime-type from<br />
					<input type="file" name="c" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Icon to Use: {$toolTip.icon}<br />
					<span class="small_font">[Required if not an image]</span>
				</div>
				<div class="rightColumn">
					{$geo_templatesDir}[Template Set]/external/<input type="text" name="b[icon_to_use]" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Extension of File Type: {$toolTip.type}</div>
				<div class="rightColumn"><input type="text" name="b[extension]" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<input type="submit" name="auto_save" value="Add Type" class="mini_button" />
			</div>
		</div>
	</fieldset>
</form>