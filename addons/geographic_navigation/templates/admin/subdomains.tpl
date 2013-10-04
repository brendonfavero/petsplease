{* 6.0.7-217-g6ee936f *}

{$adminMessages}

{literal}
<script type="text/javascript">
	//<![CDATA[
	//simple JS to show/hide fields
	Event.observe(window,'load', function () {
		var toggleDomains = function (action) {
			$('subdomainSettingsLink')[($('subdomainOff').checked)? 'hide' : 'show']();
			$('subdomainSettingsAdd')[($('subdomainOff').checked)? 'hide' : 'show']();
		};
		$('subdomainOff').observe('change', toggleDomains);
		$('subdomainConfig').observe('change', toggleDomains);
		$('subdomainOn').observe('change', toggleDomains);
		toggleDomains(false);
	});
	
	//]]>
</script>

{/literal}
<form action="" method="post" id="subdomainsForm">
<fieldset>
	<legend>Sub Domains</legend>
	<div>
		<p class="page_note_error"><strong>Warning:</strong> The sub-domains listed at bottom of this page must be configured in your hosting control panel to "point" to this installation location prior to enabling the sub-domain feature.  If you do not configure your host properly, enabling sub domains will result in broken links or even a non-functioning site.</p>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class="leftColumn">Sub Domain Usage</div>
			<div class="rightColumn">
				<label><input type="radio" name="subdomains" id="subdomainOff" value="0"{if !$subdomains} checked="checked"{/if} /> Not Used</label><br />
				<label><input type="radio" name="subdomains" id="subdomainConfig" value="configure"{if $subdomains=='configure'} checked="checked"{/if} /> Configure, but Not Enabled</label><br />
				<label><input type="radio" name="subdomains" id="subdomainOn" value="on"{if $subdomains=='on'} checked="checked"{/if} /> Enabled</label><br />
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}" id="subdomainSettingsLink">
			<div class="leftColumn">Sub Domain Settings</div>
			<div class="rightColumn">
				Set subdomain for each region as the "Unique Name" on page:<br />
				<a href="index.php?page=regions&mc=geographic_setup">Geographic Setup > Regions</a>
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class="center">
			<br /><br />
			<input type="submit" name="auto_save" value="Save Settings" class="mini_button" />
			<div id="subdomainSettingsAdd">
				<br /><br />
				<input type="hidden" name="autoAdd" id="autoAdd" value="0" />
				<input type="submit" name="auto_save" value="Auto-Set Subdomains" class="mini_button" onclick="$('autoAdd').value='add'; return true;" />
				<br /><br />
				<input type="submit" name="auto_save" value="Clear All Subdomains" class="mini_button" onclick="$('autoAdd').value='clear'; return true;" />
			</div>
		</div>
	</div>
</fieldset>
</form>