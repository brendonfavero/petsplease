{* 7.4.3-121-ge792f6d *}
{$adminMsgs}
{include file='design/parts/designModeBox.tpl'}

<script>
jQuery(function () {
	var googleLibCheck = function () {
		if (jQuery('#minifyEnabled').prop('checked') && jQuery('#minifyLibs').prop('checked')) {
			//minify libs enabled, hide option to use google API libs
			jQuery('#googleApiBox').hide('fast');
		} else {
			//minify libs not enabled, show option to use google API libs
			jQuery('#googleApiBox').show('fast');
		}
	};
	
	var minifyShowHide = function () {
		if (jQuery('#minifyEnabled').prop('checked')) {
			jQuery('.minifyOn').show('fast');
			jQuery('.minifyOff').hide('fast');
		} else {
			jQuery('.minifyOn').hide('fast');
			jQuery('.minifyOff').show('fast');
		}
		googleLibCheck();
	};
	minifyShowHide();
	jQuery('#minifyEnabled').click(minifyShowHide);
	//same for htaccess
	var minifyHtShowHide = function () {
		if (jQuery('#tplHtaccess').prop('checked')) {
			jQuery('.htaccessOn').show('fast');
			jQuery('.htaccessOff').hide('fast');
			if (!jQuery('.htaccessOn input[type=checkbox]:checked').length) {
				//if none are checked, check them all
				jQuery('.htaccessOn input[type=checkbox]').prop('checked',true);
			}
		} else {
			jQuery('.htaccessOn').hide('fast');
			jQuery('.htaccessOff').show('fast');
		}
	};
	minifyHtShowHide();
	jQuery('#tplHtaccess').click(minifyHtShowHide);
	
	var changeExtBase = function () {
		var ext_url = jQuery('#external_url_base').val();
		jQuery('.external_url_base').text(ext_url);
	}
	changeExtBase();
	
	jQuery('#external_url_base').change(changeExtBase);
	
	googleLibCheck();
	jQuery('#minifyLibs').click(googleLibCheck);
});
</script>

<form action="index.php?page=design_settings" method="post">
	<fieldset>
		<legend>Media Location Settings</legend>
		<div>
			<p class="page_note">
				<strong>Caution:</strong> Changing these settings incorrectly can result
				in a non-working website, make sure you fully understand what each
				setting does before changing these.  Check the user manual for a
				full explanation of each setting.
				<br /><br />
				Most sites should leave these settings as they are.
			</p>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Alternate External Media Base URL<br />
					<span class="small_font">
						(Leave BLANK in most cases)
					</span>
				</div>
				<div class="rightColumn">
					<input type="text" name="external_url_base" id="external_url_base" value="{$external_url_base|escape}" size="50" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Template Set Folder
				</div>
				<div class="rightColumn">
					<span class="external_url_base">{$external_url_base}</span>{$GEO_TEMPLATE_LOCAL_DIR|escape}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					JS Library Folder
				</div>
				<div class="rightColumn">
					<span class="external_url_base">{$external_url_base}</span>{$GEO_JS_LIB_LOCAL_DIR|escape}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<br />
				<input type="submit" name="auto_save" value="Save Settings" />
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Optimization Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="minifyEnabled" id="minifyEnabled" value="1" {if $minifyEnabled}checked="checked"{/if}/>
				</div>
				<div class="rightColumn">Combine, Minify, and Compress CSS and JS (Recommended for Live Sites)</div>
				<div class="clearColumn"></div>
			</div>
			<div class="minifyOn">
				{if $minifyEnabled}
					<div class="center">
						<a href="index.php?page=design_clear_combined" class="mini_cancel lightUpLink">Clear Combined CSS &amp; JS</a>
						<br /><br />
					</div>
				{/if}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="minifyLibs" id="minifyLibs" value="1" {if $minifyLibs}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">Also Combine CSS and JS libraries (such as jQuery)</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="noMinifyJs" id="noMinifyJs" value="1" {if $noMinifyJs}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">Compatibility: do NOT minify JS (combine only -- useful for older designs or server configurations that prevent minification)</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="noMinifyCss" id="noMinifyCss" value="1" {if $noMinifyCss}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">Compatibility: do NOT minify CSS (combine only -- useful for older designs or server configurations that prevent minification)</div>
					<div class="clearColumn"></div>
				</div>
				
				
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="filter_trimwhitespace" value="1" {if $filter_trimwhitespace}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					Trim repeated whitespace from final HTML output (Recommended for sites with a high percentage of mobile or low-bandwidth users) 
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}" id="googleApiBox">
				<div class="leftColumn">
					<input type="checkbox" name="useGoogleLibApi" value="1" id="useGoogleLibApi" {if $useGoogleLibApi}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					Use Google Libraries API (Allows faster loading of available JS libraries - <a href="http://code.google.com/apis/libraries/devguide.html" onclick="window.open(this.href); return false;">Info Here</a>)
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="useFooterJs" id="useFooterJs" value="1" {if $useFooterJs}checked="checked"{/if}/>
				</div>
				<div class="rightColumn">Use <strong>{ldelim}footer_html}</strong> to delay loading of certain javascript</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="tplHtaccess" id="tplHtaccess" value="1" {if $tplHtaccess}checked="checked"{/if}/>
				</div>
				<div class="rightColumn">Use <strong>.htaccess</strong> for <strong>{$GEO_TEMPLATE_LOCAL_DIR}</strong> (requires apache)</div>
				<div class="clearColumn"></div>
			</div>
			<div class="htaccessOn">
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="tplHtaccess_protect" value="1" {if $tplHtaccess_protect}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">.htaccess - <strong>Stop Prying Eyes</strong> (deny access to tpl files and folder contents)
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="tplHtaccess_compress" value="1" {if $tplHtaccess_compress}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">.htaccess - <strong>Compress Files</strong> (requires mod_deflate Apache Module)</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="tplHtaccess_expires" value="1" {if $tplHtaccess_expires}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">.htaccess - <strong>Cache Files Longer</strong> (requires mod_expires Apache Module)</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'} minifyOn">
					<div class="leftColumn">
						<input type="checkbox" name="tplHtaccess_rewrite" value="1" {if $tplHtaccess_rewrite}checked="checked"{/if}/>
					</div>
					<div class="rightColumn">.htaccess - Use mod_rewrite for Combined CSS/JS</div>
					<div class="clearColumn"></div>
				</div>
			</div>
			<div class="center">
				<br />
				<input type="submit" name="auto_save" value="Save Settings" />
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Advanced Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="noDefaultCss" value="1" {if $noDefaultCss}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					<span class="minifyOff">Do NOT Automatically Reference <strong>default.css</strong> in {literal}{head_html}{/literal}</span>
					<span class="minifyOn">Do NOT Automatically Include <strong>default.css</strong> in Combined CSS Contents</span>
					<br />
					<em><strong>Warning:</strong> May break site, and WILL require additional steps for software updates</em>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}"{if !$advMode} style="display: none;"{/if}>
				<div class="leftColumn">
					<input name="useCHMOD" id="chmod" type="checkbox" value="1" {if $useCHMOD}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					<label for="chmod">CHMOD 777 Files<br />(affects operations on files)</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $canEditSystemTemplates && $advMode}
				{include file="design/parts/editSystemWarning.tpl"}
			{/if}
			<div class="{cycle values='row_color1,row_color2'}"{if !$advMode} style="display: none;"{/if}>
				<div class="leftColumn">
					<input name="canEditSystemTemplates" id="canEditSystemTemplates" type="checkbox" value="1" {if $canEditSystemTemplates}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					<label for="canEditSystemTemplates">Allow Edit of system, module, and addon Templates</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $iamdeveloper}
				<div class="{cycle values='row_color1,row_color2'}"{if !$advMode} style="display: none;"{/if}>
					<div class="leftColumn">
						<input name="allowDefaultTsetEdit" id="allowDefaultTsetEdit" type="checkbox" value="1" {if $allowDefaultTsetEdit}checked="checked"{/if} />
					</div>
					<div class="rightColumn">
						<label for="allowDefaultTsetEdit">Allow Edit of default template set<br />(IAMDEVELOPER Setting)</label>
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			
			<div class="center">
				<br />
				<input type="submit" name="auto_save" value="Save Settings" />
			</div>
		</div>
	</fieldset>
</form>