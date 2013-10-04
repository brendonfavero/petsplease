{* 6.0.7-3-gce41f93 *}
{$adminMessages}
<fieldset>
	<legend>Auto-Load Images</legend>
	<div>
		<form action="" method="post">
			<div class="row_color1">
				<div class="leftColumn">Add images in directory:</div>
				<div class="rightColumn"><input type="text" name="autoLoadDir" size="40" value="addons/attention_getters/images/" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="row_color2">
				<div class="leftColumn">Remove ALL existing (total reset)</div>
				<div class="rightColumn"><input type="checkbox" name="clearExisting" value="1" /></div>
				<div class="clearColumn"></div>
			</div>
			<div style="text-align: center;">
				<input type="submit" name="auto_save" value="Auto-Load Images" />
			</div>
		</form>
	</div>
</fieldset>