{* 7.1beta3-63-g9b21c1a *}


<div class="closeBoxX"></div>
<div class="lightUpTitle" id="newConfirmTitle">Edit Multi-Level Field Group Label</div>


<form style="display:block; margin: 15px; width: 450px;" action="index.php?page=leveled_field_edit&amp;leveled_field={$leveled_field}" method="post">
	<p class="page_note">Note that the multi-level field group label is only viewed in
		the admin.</p>
	<div class="{cycle values='row_color1,row_color2'}">
		<div class="leftColumn">Multi-Level Field Group Label</div>
		<div class="rightColumn">
			<input type="text" name="label" value="{$leveled_field_label|escape}" size="30" style="width: 100%;" />
		</div>
		<div class="clearColumn"></div>
	</div>
	
	<br /><br />
	<div style="float: right;">
		<input type="submit" name="auto_save" value="Apply Changes" class="mini_button" />
		<input type="button" class="closeLightUpBox mini_cancel" value="Cancel" />
	</div>
</form>