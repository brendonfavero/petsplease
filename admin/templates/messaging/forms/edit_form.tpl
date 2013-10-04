{* 6.0.7-3-gce41f93 *}

<div class="{cycle values='row_color1,row_color2'}">
	<div class="leftColumn">Content Type</div>
	<div class="rightColumn">
		<select name="content_type">
			<option value="text/plain"{if $content_type=='text/plain'} selected="selected"{/if}>Plain Text</option>
			<option value="text/html"{if $content_type=='text/html'} selected="selected"{/if}>HTML</option>
		</select>
	</div>
	<div class="clearColumn"></div>
</div>
<div class="{cycle values='row_color1,row_color2'}">
	<div class="leftColumn">Form Message Name</div>
	<div class="rightColumn">
		<input type="text" name="message_name" size="30" maxsize="50" value="{$message_name|escape}" />
	</div>
	<div class="clearColumn"></div>
</div>
<div class="{cycle values='row_color1,row_color2'}">
	<div class="leftColumn">Form Subject</div>
	<div class="rightColumn">
		<input type="text" name="subject" size="30" maxsize="50" value="{$subject|escape}" />
	</div>
	<div class="clearColumn"></div>
</div>
<div class="center">
	<strong>Form Message:</strong><br />
	<textarea name="message" cols="50" rows="20" style="width: 100%;">{$message|fromDB|escape}</textarea>
	<br /><br />
	<input type="submit" name="auto_save" value="Save" class="mini_button" />
</div>