{* 7.3.1-54-g668d448 *}

{$admin_msgs}

<div class="progress_bar" style="width: 50%; height: 4em; border: 2px solid #006699; border-radius: 2em; margin: 15px auto;">
	<div style="float: left; margin: 1.5em 0 0 1em; color: blue; font-weight: bold;">{$completion}% complete</div>
	<div style="background-color: #E0E0FF; height: 4em; border-radius: 2em; width: {$completion}%;"></div>
</div>

<fieldset>
<legend>Instructions</legend>
<div>
<p style="font-size: 14pt;">Welcome to GeoCore!</p>
<p style="margin: 5px 0px 0px 20px;">This checklist will help you review the most common configuration options for getting started with GeoCore. We recommend working through this checklist before launch to ensure that your site is fully customized to your liking.<br />
To begin, just click on one of the <strong>Select a Section</strong> buttons, below.<br /><br />
If you have already done some work customizing your site (or have just upgraded from an older version of GeoCore), the checklist can automatically detect any progress you have already made! <a href="index.php?page=checklist&mc=getting_started&sync=yes">Click here to auto-detect checklist progress.</a><br /> 

</div>
</fieldset>

<fieldset>
	<legend>Select a Section</legend>
	<div class="center" style="margin: 0px 0px 8px 0px;">
		{foreach $checks as $sectionName => $section}
			{counter name='done' assign='done' print=false start='0'}
			{counter name='total' assign='total' print=false start='0'}
			{foreach $section as $checkName => $check}
				{if $check.isChecked}{counter name='done'}{/if}
				{counter name='total'}
			{/foreach}
			<a onclick="jQuery('.sw').hide('fast'); jQuery('#{$sectionName|replace:' ':''}_wrapper').show('fast'); return false;" class="button" href="#">{$sectionName} ({$done}/{$total})</a> 
		{/foreach}
	</div>
</fieldset>
	
<form action="" method="post">
	
	{foreach $checks as $sectionName => $section}
		<div class="sw" id="{$sectionName|replace:' ':''}_wrapper" style="display: none;">
			<fieldset>
				<legend>{$sectionName}</legend>
				<div>
				
					{foreach $section as $checkName => $check}
						<div class='{cycle values="row_color1,row_color2"}' style="margin: 0 auto; padding: 2px;">
							<input type="hidden" value="0" name="checkboxes[{$sectionName}][{$checkName}]" />
							<input type="checkbox" value="1" id="{$checkName}" name="checkboxes[{$sectionName}][{$checkName}]" {if $check.isChecked}checked="checked"{/if} onclick="checklist_checkItem(this, '{$checkName}')" />
							<label for="{$checkName}" style="cursor: pointer;">
								<strong id="title_{$checkName}" {if $check.isChecked}style="color: #909090;"{/if}>({$check.percentage}%) {$check.name}</strong>
								{if $check.isChecked && !$check.isComplete}
									<span style="color: red;"> - WARNING: This item appears to be incomplete</span>
								{elseif !$check.isChecked && $check.isComplete}
									<span style="color: blue;"> - NOTICE: This item appears to be complete</span>
								{/if}
							</label> 
							<br />
							<p style="padding-left: 50px; {if $check.isChecked}display: none;{/if}" id="description_{$checkName}">{$check.description}</p>
						</div>
					{/foreach}
				
				</div>
			</fieldset>
		</div>
	{/foreach}
	<script type="text/javascript">
		checklist_checkItem = function(e, checkName) {
			if(e.checked) {
				jQuery('#description_'+checkName).hide('fast');
				jQuery('#title_'+checkName).css('color','#909090');
			} else {
				jQuery('#description_'+checkName).show('fast');
				jQuery('#title_'+checkName).css('color','#3C3C3C');
			}
		};
	</script>
	
	<div class="center"><input type="submit" class="button" value="Save" name="auto_save" /></div>
</form>