{* 6.0.7-3-gce41f93 *}
{$adminMsg}
<form action=index.php?mc=site_setup&page=security_image_config method=post>
	<fieldset>
		<legend>Security Image Type</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Security Image Type</div>
				<div class="rightColumn">
					<label><input type="radio" id="imageType_system" name="security_image[imageType]" value="system" {if $reg->get('imageType','system')=='system'} checked="checked"{/if} /> System Generated</label><br />
					<label><input type="radio" id="imageType_recaptcha" name="security_image[imageType]" value="recaptcha"{if $reg->get('imageType','system')=='recaptcha'} checked="checked"{/if} /> reCAPTCHA&trade;</label> (3rd Party, <a href="http://www.google.com/recaptcha" onclick="window.open(this.href); return false;">see website</a>)
				</div>
			</div>
		</div>
	</fieldset>
	
	<fieldset class="built_in_images">
		<legend>System-Generated Image Abilities</legend>
		<div>
			{if !($abilities.imagecreatetruecolor||$abilities.imagecreate)}
				<p class="page_note_error">reCAPTCHA&trade; Only - Minimum requirement (GD Library) for System Generated Security Image is not met.</p>
				<div class="medium_font" style="text-align: left;">
					<span class="medium_error_font">Minimum requirement for System Generated Security Image is not met.</span>
					<br /><br />
					System Generated Security image requires <strong>GD library</strong> in order to be able to create the security image.
					However, it appears that this host does not have the GD library installed and enabled. 
					Technically speaking, neither of the functions imagecreate() or imagecreatetruecolor() can be used, which
					indicates that GD library is not installed.  
					<br /><br />
					Using <em>System Generated</em> security image will <strong>not display anything until GD libraries are installed and enabled on this host</strong>.
				</div>
			{else}
				{if !($abilities.imagepng || $abilities.imagegif || $abilities.imagejpeg || $abilities.imagewbmp)}
					<p class="page_note_error">reCAPTCHA&trade; Only - No library support found for GIF, JPEG, PNG, or WBMP.  The system generated security image requires GD library with at least one of those image types installed in able to generate the security image "on the fly".</p>
					<div class="medium_font" style="text-align: left;">
						<span class="medium_error_font">Minimum requirement for System Generated Security Image is not met.</span>
						<br /><br />
						The System Generated Security image requires <strong>GD library</strong> in order to be able to create the security image.
						It appears that GD library is installed, however there is no support for GIF, JPEG, PNG, or WBMP found, which
						indicates that the GD library may be mis-configured or not installed correctly. 
						Technically speaking, none of the functions imagepng(), imagegif(), imagejpeg(), or imagewbmp() can be used,
						and the System Generated Security Image needs at least one of those to be able to work.  
						<br /><br />
						The security image will <strong>not display until support for the image types listed above are installed and 
						enabled on this host</strong>.
					</div>
				{else}
					<div class='col_hdr'>General Capabilities</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class='leftColumn'>Blur Filter</div>
						<div class='rightColumn'>{if !$abilities.imagefilter}Not {/if}Supported</div>
						<div class='clearColumn'></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class='leftColumn'>Emboss Filter</div>
						<div class='rightColumn'>{if !$abilities.imagefilter}Not {/if}Supported</div>
						<div class='clearColumn'></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class='leftColumn'>"Sketchy" Filter</div>
						<div class='rightColumn'>{if !$abilities.imagefilter}Not {/if}Supported</div>
						<div class='clearColumn'></div>
					</div>
					<div class="{cycle values='row_color1,row_color2'}">
						<div class='leftColumn'>Negative Effect</div>
						<div class='rightColumn'>{if !$abilities.imagefilter}Not {/if}Supported</div>
						<div class='clearColumn'></div>
					</div>
					<div class='col_hdr'>Character/Font Capabilities</div>
					
					<div class="{cycle values='row_color1,row_color2'}">
						<div class='leftColumn'>GD True Type Fonts (TTF)</div>
						<div class='rightColumn'>
							{if $abilities.imagettftext}Installed &amp; Enabled on Host{else}TTF not supported{/if}
						</div>
						<div class='clearColumn'></div>
					</div>
					{if $abilities.imagettftext}
						<div class="{cycle values='row_color1,row_color2'}">
							<div class='leftColumn'>Fonts Directory</div>
							<div class='rightColumn'>
								{$fonts_dir}
							</div>
							<div class='clearColumn'></div>
						</div>
						<div class="{cycle values='row_color1,row_color2'}">
							<div class='leftColumn'>Font Files</div>
							<div class='rightColumn'>
								{foreach from=$fonts item=font}{$font}<br />{/foreach}
							</div>
							<div class='clearColumn'></div>
							<div class='page_note' style='text-align: left;'>
								The system generated security image will randomly select which font to use on a per-character basis.
								<br />
								You can upload additional TTF font files if you wish, just be sure they are uploaded in <strong>BINARY mode</strong> to prevent font corruption.
							</div>
						</div>
					{else}
						<div class="{cycle values='row_color1,row_color2'}">
							<div class='leftColumn'>
								Font Used
							</div>
							<div class='rightColumn'>
								Built-in default font for this host<br />
								(Without TTF support, font abilities are limited)
							</div>
							<div class='clearColumn'></div>
						</div>
					{/if}
					<div class='col_hdr'>Security Image Preview</div>
					<br />
					<div id='addon_security_image' style='text-align: center; border:0px; margin:0px; padding:0px;'>
						<a href='javascript:void(0)' onclick='changeSecurityImage();'>
							<img src="../{$classifieds_file_name}?a=ap&addon=security_image&page=image" alt='Security Image' />
						</a>
					</div>
				{/if}
			{/if}
		</div>
	</fieldset>
	
	<fieldset class="reCAPTCHA_images">
		<legend>reCAPTCHA&trade; Preview</legend>
		<div>
			{if $reg->imageType=='recaptcha'}
				<div class="page_note">If you do not see the reCAPTCHA&trade; preview below, check that the public and private keys are set correctly in the settings below.</div>
				{include file='recaptcha.tpl' recaptcha_error=$error.recaptcha}
			{else}
				<div class="page_note">Save Changes to view reCAPTCHA&trade; preview.</div>
			{/if}
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Locations</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='secure_registration'>Registration</label>
				</div>
				<div class='rightColumn'>
					<input type='hidden' name='security_image[secure_registration]' value='0' />
					<input type='checkbox' name='security_image[secure_registration]' id='secure_registration' value='1'{if $reg->secure_registration} checked="checked"{/if} />
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='secure_login'>User Login</label>
				</div>
				<div class='rightColumn'>
					<input type='hidden' name='security_image[secure_login]' value='0' />
					<input type='checkbox' name='security_image[secure_login]' id='secure_login' value='1'{if $reg->secure_login} checked="checked"{/if} />
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='secure_messaging'>Messaging</label>
				</div>
				<div class='rightColumn'>
					<input type='hidden' name='security_image[secure_messaging]' value='0' />
					<input type='checkbox' name='security_image[secure_messaging]' id='secure_messaging' value='1'{if $reg->secure_messaging} checked="checked"{/if} />
					<div>
						<input type="hidden" name="security_image[login_override]" value="0" /><input type="checkbox" name="security_image[login_override]" value="1" {if $reg->login_override}checked="checked"{/if} />
						Skip if sender is logged in
					</div>
				</div>
				<div class='clearColumn'></div>
			</div>
			{*
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='secure_messaging'>Bypass Messaging images for logged-in users</label>
				</div>
				<div class='rightColumn'>
					<input type="hidden" name="security_image[login_override]" value="0" /><input type="checkbox" name="security_image[login_override]" value="1" {if $reg->login_override}checked="checked"{/if} />
				</div>
				<div class='clearColumn'></div>
			</div>
			*}
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='secure_listing'>Listing Placement</label>
				</div>
				<div class='rightColumn'>
					<input type='hidden' name='security_image[secure_listing]' value='0' />
					<input type='checkbox' name='security_image[secure_listing]' id='secure_listing' value='1'{if $reg->secure_listing} checked="checked"{/if} />
				</div>
				<div class='clearColumn'></div>
			</div>
			{if $anonEnabled}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class='leftColumn'>
						<label for='secure_listing_anon'>Anonymous Listing Placement</label>
					</div>
					<div class='rightColumn'>
						<input type='hidden' name='security_image[secure_listing_anon]' value='0' />
						<input type='checkbox' name='security_image[secure_listing_anon]' id='secure_listing_anon' value='1'{if $reg->secure_listing_anon} checked="checked"{/if} />
					</div>
					<div class='clearColumn'></div>
				</div>
			{/if}
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='secure_forgot_pass'>Forgot Password</label>
				</div>
				<div class='rightColumn'>
					<input type='hidden' name='security_image[secure_forgot_pass]' value='0' />
					<input type='checkbox' name='security_image[secure_forgot_pass]' id='forgot_pass' value='1'{if $reg->secure_forgot_pass} checked="checked"{/if} />
				</div>
				<div class='clearColumn'></div>
			</div>
		</div>
	</fieldset>
	<fieldset class="reCAPTCHA_images">
		<legend>reCAPTCHA&trade; Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					API Public/Private Key Sign-Up (Its FREE)
				</div>
				<div class='rightColumn'>
					<a href="https://www.google.com/recaptcha/admin/create" onclick="window.open(this.href); return false;">
						Sign-Up Page
					</a>
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					Public Key
				</div>
				<div class='rightColumn'>
					<input type='text' name='security_image[recaptcha_pub_key]' value='{$recaptcha_pub_key}' size="50" />{$error.recaptcha_pub_key}
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					Private Key
				</div>
				<div class='rightColumn'>
					{* Note: We don't actually show private key to browser since it is secret. *}
					<input type='password' name='security_image[recaptcha_private_key]' size="50" value='xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' />{$error.recaptcha_private_key}
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					Theme
				</div>
				<div class='rightColumn'>
					<label><input type="radio" name="security_image[recaptcha_theme]" value="red"{if $recaptcha_theme=='red'} checked="checked"{/if} /> Red (default theme)</label><br />
					<label><input type="radio" name="security_image[recaptcha_theme]" value="white"{if $recaptcha_theme=='white'} checked="checked"{/if} /> White</label><br />
					<label><input type="radio" name="security_image[recaptcha_theme]" value="blackglass"{if $recaptcha_theme=='blackglass'} checked="checked"{/if} /> Black Glass</label><br />
					<label><input type="radio" name="security_image[recaptcha_theme]" value="clean"{if $recaptcha_theme=='clean'} checked="checked"{/if} /> Clean</label>
				</div>
				<div class='clearColumn'></div>
			</div>
		</div>
	</fieldset>
	
	<fieldset class="built_in_images">
		<legend>Image Size</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='width'>Width</label>
				</div>
				<div class='rightColumn'>
					<input type='text' id='width' name='security_image[width]' value='{$reg->width}' />{$error.width}
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='height'>Height</label>
				</div>
				<div class='rightColumn'>
					<input type='text' id='height' name='security_image[height]' value='{$reg->height}' />{$error.height}
				</div>
				<div class='clearColumn'></div>
			</div>
		</div>
	</fieldset>
	<fieldset class="built_in_images">
		<legend>Character Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='numChars'>Number of Characters</label>
				</div>
				<div class='rightColumn'>
					<input type='text' id='numChars' name='security_image[numChars]' value='{$reg->numChars}' />{$error.numChars}
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='fontSize'>Font Size {if !$abilities.imagettftext}(1-5){/if}</label>
				</div>
				<div class='rightColumn'>
					<input type='text' id='fontSize' name='security_image[fontSize]' value='{$reg->fontSize}' />
					{if $abilities.imagettftext}
						{if $abilities.gd_version.1==2}
							<label for="fontSize">Points</label>
						{else}
							<label for="fontSize">Pixels</label>
						{/if}
					{else}
						<input type="hidden" id="use_small_font_size" />
					{/if}
					{$error.fontSize}
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class='leftColumn'>
					<label for='useRandomColors'>Use Random Colors</label>
				</div>
				<div class='rightColumn'>
					<input type='hidden' name='security_image[useRandomColors]' value='0' />
					<input type='checkbox' name='security_image[useRandomColors]' id='useRandomColors' value='1'{if $reg->useRandomColors} checked="checked"{/if} />
					<a href="#" class="mini_button" id="secure_font_color_adv_link" onclick="$('secure_font_color_adv').show(); $('secure_font_color_adv_link').hide(); return false;">Advanced &gt;</a>
					<div id='secure_font_color_adv' class='adv_box'>
						<a href="#" class="mini_button" onclick="$('secure_font_color_adv').hide(); $('secure_font_color_adv_link').show(); return false;">&lt; Hide</a>
						<br />
						RGB Color Ranges (0-255):
						<br />
						<span style='color: red;'>Red:</span> <input type='text' name='security_image[rmin]' id='rmin' value='{$reg->rmin}' size='2' /> - <input type='text' name='security_image[rmax]' id='rmax' value='{$reg->rmax}' size='2' /><br />
						<span style='color: green;'>Green:</span> <input type='text' name='security_image[gmin]' id='gmin' value='{$reg->gmin}' size='2' /> - <input type='text' name='security_image[gmax]' id='gmax' value='{$reg->gmax}' size='2' /><br />
						<span style='color: blue;'>Blue:</span> <input type='text' name='security_image[bmin]' id='bmin' value='{$reg->bmin}' size='2' /> - <input type='text' name='security_image[bmax]' id='bmax' value='{$reg->bmax}' size='2' /><br />
						{$error.secure_font_color}
					</div>
				</div>
				<div class='clearColumn'></div>
			</div>
			
			<br />
			<div class='col_hdr'>
				Advanced Settings 
				<a href="#" class="mini_button" id="char_advanced_link_show" onclick="$('char_advanced_link_show').hide();$('char_advanced_link_hide').show();$('char_advanced_box').show(); return false;">Show &gt;</a>
				<a href="#" class="mini_button" id="char_advanced_link_hide" onclick="$('char_advanced_link_show').show();$('char_advanced_link_hide').hide();$('char_advanced_box').hide(); return false;">&lt; Hide</a>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}" id='char_advanced_box'>
				<div class='leftColumn'>
					<label for='allowedChars'>Characters Allowed (Case-insensitive matching)</label>
				</div>
				<div class='rightColumn'>
					<input type='text' id='allowedChars' name='security_image[allowedChars]' value="{$reg->allowedChars|escape}" />{$error.allowedChars}
				</div>
				<div class='clearColumn'></div>
			</div>
		</div>
	</fieldset>
	<fieldset class="built_in_images">
		<legend>Overall Image Effects</legend>
		<div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useDistort'>Distort</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useDistort]' value='0' />
				<input type='checkbox' name='security_image[useDistort]' id='useDistort' value='1'{if $reg->useDistort} checked="checked"{/if} />
				<a href="#" class="mini_button" id="distort_adv_link" onclick="$('distort_adv').show(); $('distort_adv_link').hide(); return false;">Advanced &gt;</a>
				
				<div id='distort_adv' class='adv_box'>
					<a href="#" class="mini_button" onclick="$('distort_adv').hide(); $('distort_adv_link').show(); return false;">&lt; Hide</a>
					<label>
						Amount (0.0 - 1.0):
						<input type='text' id='distort' name='security_image[distort]' value='{$reg->distort}' size='2' />{$error.distort}
					</label> 
				</div>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useBlur'>Blur Filter (PHP 5)</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useBlur]' value='0' />
				<input type='checkbox' name='security_image[useBlur]' id='useBlur' value='1'{if $reg->useBlur} checked="checked"{/if} />
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useEmboss'>Emboss Filter (PHP 5)</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useEmboss]' value='0' />
				<input type='checkbox' name='security_image[useEmboss]' id='useEmboss' value='1'{if $reg->useEmboss} checked="checked"{/if} />
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useSketchy'>Sketchy filter (PHP 5)</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useSketchy]' value='0' />
				<input type='checkbox' name='security_image[useSketchy]' id='useSketchy' value='1'{if $reg->useSketchy} checked="checked"{/if} />
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useNegative'>Photo Negative (PHP 5)</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useNegative]' value='0' />
				<input type='checkbox' name='security_image[useNegative]' id='useNegative' value='1'{if $reg->useNegative} checked="checked"{/if} />
			</div>
			<div class='clearColumn'></div>
		</div>
		</div>
	</fieldset>
	<fieldset class="built_in_images">
		<legend>Add to Image</legend>
		<div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useRefresh'>Refresh Image Overlay</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useRefresh]' value='0' />
				<input type='checkbox' name='security_image[useRefresh]' id='useRefresh' value='1'{if $reg->useRefresh} checked="checked"{/if} />
				<a href="#" class="mini_button" id="secure_refresh_adv_link" onclick="$('secure_refresh_adv').show(); $('secure_refresh_adv_link').hide(); return false;">Advanced &gt;</a>
				<div id='secure_refresh_adv' class='adv_box'>
					<a href="#" class="mini_button" onclick="$('secure_refresh_adv').hide(); $('secure_refresh_adv_link').show(); return false;">&lt; Hide</a>
					<label>
						Image URL:
						<input type='text' id='refreshUrl' name='security_image[refreshUrl]' value="{$reg->refreshUrl|escape}" />{$error.refreshUrl}
					</label> 
				</div>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useGrid'>Grid</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useGrid]' value='0' />
				<input type='checkbox' name='security_image[useGrid]' id='useGrid' value='1'{if $reg->useGrid} checked="checked"{/if} />
				<a href="#" class="mini_button" id="numGrid_adv_link" onclick="$('numGrid_adv').show(); $('numGrid_adv_link').hide(); return false;">Advanced &gt;</a>
				<div id='numGrid_adv' class='adv_box'>
					<a href="#" class="mini_button" onclick="$('numGrid_adv').hide(); $('numGrid_adv_link').show(); return false;">&lt; Hide</a>
					<label>
						# Grid Lines:
						<input type='text' id='numGrid' name='security_image[numGrid]' value='{$reg->numGrid}' />{$error.numGrid}
					</label> 
				</div>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useLines'>Lines</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useLines]' value='0' />
				<input type='checkbox' name='security_image[useLines]' id='useLines' value='1'{if $reg->useLines} checked="checked"{/if} />
				<a href="#" class="mini_button" id="lines_adv_link" onclick="$('lines_adv').show(); $('lines_adv_link').hide(); return false;">Advanced &gt;</a>
				<div id='lines_adv' class='adv_box'>
					<a href="#" class="mini_button" onclick="$('lines_adv').hide(); $('lines_adv_link').show(); return false;">&lt; Hide</a>
					<label>
						# Lines:
						<input type='text' id='lines' name='security_image[lines]' value='{$reg->lines}' />{$error.lines}
					</label> 
				</div>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class="{cycle values='row_color1,row_color2'}">
			<div class='leftColumn'>
				<label for='useNoise'>Noise</label>
			</div>
			<div class='rightColumn'>
				<input type='hidden' name='security_image[useNoise]' value='0' />
				<input type='checkbox' name='security_image[useNoise]' id='useNoise' value='1'{if $reg->useNoise} checked="checked"{/if} />
				<a href="#" class="mini_button" id="numNoise_adv_link" onclick="$('numNoise_adv').show(); $('numNoise_adv_link').hide(); return false;">Advanced &gt;</a>
				<div id='numNoise_adv' class='adv_box'>
					<a href="#" class="mini_button" onclick="$('numNoise_adv').hide(); $('numNoise_adv_link').show(); return false;">&lt; Hide</a>
					<label>
						Amount:
						<input type='text' id='numNoise' name='security_image[numNoise]' value='{$reg->numNoise}' />{$error.numNoise}
					</label> 
				</div>
			</div>
			<div class='clearColumn'></div>
		</div>
		</div>
	</fieldset>
	<fieldset class="built_in_images">
		<legend>Load Preset Settings</legend>
		<div>
			<span class='medium_font'>
				Note: presets may look differently from host to host, depending on what GD 
				libraries are supported, and the version of PHP.
			</span><br />
			<!-- PRESET BUTTONS - ADD HERE - PICK BEST SECTION TO ADD TO -->
			<div class='col_hdr'>
				Overall Look
			</div>
			
			<div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'>
				<a href="#" class="mini_button" onclick="loadInstallDefaults(); return false;">Fresh Install Default</a>
				<a href="#" class="mini_button" onclick="loadCleanLook(); return false;">Clean &amp; Crisp</a>
				<a href="#" class="mini_button" onclick="loadIceyBlackLook(); return false;">Icey Black</a>
				<a href="#" class="mini_button" onclick="loadPlaidLook(); return false;">Plaid</a>
				<a href="#" class="mini_button" onclick="loadAsphaltLook(); return false;">Chalk On Asphalt</a>
				<a href="#" class="mini_button" onclick="loadGrainyLook(); return false;">Grainy</a>
			</div>
			
			<div class='col_hdr'>
				Color Range Presets
			</div>
			
			<div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'>
				<a href="#" class="mini_button" onclick="loadFontColorRed(); return false;">Reds</a>
				<a href="#" class="mini_button" onclick="loadFontColorGreen(); return false;">Greens</a>
				<a href="#" class="mini_button" onclick="loadFontColorBlue(); return false;">Blues</a>
				<a href="#" class="mini_button" onclick="loadFontColorLight(); return false;">Light Colors</a>
				<a href="#" class="mini_button" onclick="loadFontColorDark(); return false;">Dark Colors</a>
				<a href="#" class="mini_button" onclick="loadFontColorBright(); return false;">Bright Colors</a>
			</div>
			
			<div class='col_hdr'>
				Misc
			</div>
			
			<div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'>
				<a href="#" class="mini_button" onclick="advFormDefault(); return false;">Reset Advanced Settings to Defaults</a>
				<a href="#" class="mini_button" onclick="loadAlphaLowercase(); return false;">Lowercase Letters</a>
			</div>
		
		</div>
	</fieldset>
	{$displayDevBox}
	<table cellpadding=15 cellspacing=0 align=center width="75%">
		<tr>
			<td align=center colspan=3>
				<input type='reset'>&nbsp; &nbsp;
				<input type='submit' name="auto_save" value='Save'>
			</td>
		</tr>
	</table>
</form>