{* f99c9e7 *}

<div class="closeBoxX"></div>
<div class="lightUpTitle">
	Register GeoMobile Site Key
</div>
<br />
<form action="index.php?page=addon_mobile_api_get_site_key&amp;device={$device}" method="post">
	<strong>Site Name (displayed in app):</strong>
	<input type="text" name="site_name" value="{$site_name|escape}" maxlength="13" size="10" />
	<br /><br />
	
	<br />
	<div style="text-align: right;">
		<input type="submit" name="auto_save" value="{if $site_key}Refresh{else}Register{/if} Site Key!" class="mini_button" />
		<input type="button" value="Cancel" class="closeLightUpBox mini_cancel" />
	</div>
</form>