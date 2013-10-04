{* 6.0.7-3-gce41f93 *}
{literal}
<script type="text/javascript">
	//<![CDATA[
	Event.observe(window, 'load', function () {
		$('rotateEnabled').observe('click', toggleRotateSettings);
		toggleRotateSettings();
	});
	var toggleRotateSettings = function () {
		//show/hide the settings depending on if rotation is enabled/disabled.
		$('rotateSettings')[($('rotateEnabled').checked? 'show' : 'hide')]();
	}
	//]]>
</script>
{/literal}
<fieldset>
	<legend>Better Placement Extra Settings</legend>
	<div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class="leftColumn"><input type="checkbox" name="b[use_better_placement_feature]" id="use_better_placement_feature" value="1" {if $use_better_placement_feature} checked="checked"{/if} /></div>
			<div class="rightColumn">
				<label for="use_better_placement_feature">Enable Better Placement</label>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class="leftColumn"><input type="checkbox" name="b[{$prefix}rotate]" value="1" {if $rotate} checked="checked"{/if} id="rotateEnabled" /></div>
			<div class="rightColumn">
				<label for="rotateEnabled">Enable Better Placement Rotation</label>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div id="rotateSettings">
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Rotate 1 listing per</div>
				<div class="rightColumn">
					<label><input type="radio" name="b[{$prefix}perCategory]" value="1" {if $perCategory} checked="checked"{/if} /> Terminal Category</label><br />
					<label><input type="radio" name="b[{$prefix}perCategory]" value="0" {if !$perCategory} checked="checked"{/if} /> Entire Site</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Interval - Rotate listings every</div>
				<div class="rightColumn">
					<input type='text' name='b[rotationInterval]' size='5' value='{$adjustedInterval}' /> 
					<select name='b[rotationIntervalUnit]'>
						<option value='{$day}'{if $rotateUnit==$day} selected="selected"{/if}>Days</option>
						<option value='{$hour}'{if $rotateUnit==$hour} selected="selected"{/if}>Hours</option>
						<option value='{$minute}'{if $rotateUnit==$minute} selected="selected"{/if}>Minutes</option>
						<option value='1'{if $rotateUnit==1} selected="selected"{/if}>Seconds</option>
					</select>
					{if $rotate}
						{* Only show link if already turned on *}
						<a href="../cron.php?action=cron&amp;cron_key={$cronKey|escape}&amp;verbose=1&amp;tasks=better_placement_rotation" class="mini_button" onclick="window.open(this.href); return false;">
							Rotate Listings Now
						</a>
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</div>
</fieldset>