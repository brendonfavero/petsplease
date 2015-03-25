{* 6.0.7-3-gce41f93 *}
{if $licenseError}<div class="licenseError">{$licenseError}</div>{/if}
<p><br></p>
<h1 class="heading1">Enter your License Key below for verification.</h1>
<p><br></p>
<form action='' method='post'>
	<label>License Key: <input type="text" name="licenseKey" value="{$licenseKey}" size="40" /></label>
	{if $must_agree}
		{$must_agree}
	{/if}
	<input type="hidden" name="licenseKeyEntered" value="1" />
	<input type="hidden" name="license" value="on" />
	<input type="hidden" name="backup_agree" value="on" />
	<input type="submit" value="Continue &gt;&gt;" />
	<div id="license_data">Install Domain: {$install.domain}<br />
		Install Folder: {$install.path}</div>
</form>