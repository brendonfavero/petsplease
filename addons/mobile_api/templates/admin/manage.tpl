{* f99c9e7 *}

{$admin_msgs}

<form action="index.php?page=addon_mobile_api_manage" method="post">
	{foreach $devices as $device=>$data}
		<fieldset>
			<legend>{$device|capitalize}</legend>
			<div>
				{if $data.errors}
					<p class="page_note_error">{$data.errors}</p>
				{/if}
				{if !$data.valid}
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">{$device|capitalize} License Key</div>
						<div class="rightColumn"><input type="text" name="license_keys[{$device}]" value="{$data.license_key|escape}" size="20" /></div>
						<div class="clearColumn"></div>
					</div>
					{if $data.mustAgree}
						{$data.mustAgree}
					{/if}
				{else}
					<div class="center col_hdr_top">Site Info</div>
					<p class="page_note">
						<strong>Site Info:</strong>  This information is used by the app
						to connect to your site.  This information is automatically submitted
						any time you refresh or register for a GeoMobile site key.
					</p>
					
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">GeoMobile Site Key</div>
						<div class="rightColumn">
							{if $data.site_key}
								{$data.site_key}
								<a href="index.php?page=addon_mobile_api_get_site_key&device={$device}" class="mini_cancel lightUpLink">Refresh GeoMobile Key</a>
							{else}
								N/A
								<a href="index.php?page=addon_mobile_api_get_site_key&device={$device}" class="mini_button lightUpLink">Register/Request GeoMobile Site Key</a>
							{/if}
						</div>
						<div class="clearColumn"></div>
					</div>
					
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">API URL</div>
						<div class="rightColumn">
							{$base_api_url}?transport=iphone
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">API Key</div>
						<div class="rightColumn">
							{$data.api_key}
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Site URL</div>
						<div class="rightColumn">
							{$classifieds_url}
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">Site Name</div>
						<div class="rightColumn">
							{if $data.site_name}{$data.site_name}{else}N/A{/if}
						</div>
						<div class="clearColumn"></div>
					</div>
					
					<div class="center col_hdr_top">Device License Info</div>
					<p class="page_note">
						<strong>Device License Info:</strong>  Information provided below is only meant as
						a brief summary, it is not a legal contract or binding in any way.  See the
						user agreement and/or license agreement for specific details.
					</p>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">{$device|capitalize} License Key</div>
						<div class="rightColumn">
							{$data.license_key}
							<a href="index.php?page=addon_mobile_api_clear_key&amp;device={$device}&auto_save=1" class="mini_cancel lightUpLink">Clear Key</a>
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">
							License/Device Info
						</div>
						<div class="rightColumn">
							- Leased License<br />
							- All PHP files fully encoded using Ioncube<br />
							- Support and Software Updates included for duration the software is leased<br />
							- License expires only if/when lease is canceled or payments lapse<br />
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">
							License Key Expires
						</div>
						<div class="rightColumn">
							{$data.licenseExp}
							{if $data.leased}
								(Or until Lease is Canceled)
							{/if}
						</div>
						<div class="clearColumn"></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">
							Local data expires
						</div>
						<div class="rightColumn">
							{$data.localExpire}
							{if $data.leased}
								<br />
								(License key will automatically revalidate after this time)
							{/if}
						</div>
						<div class="clearColumn"></div>
					</div>
				{/if}
			</div>
		</fieldset>
	{/foreach}
	{if $keysToSave}
		<div class="center">
			<input type="submit" name="auto_save" value="Save Keys" />
		</div>
	{/if}
</form>