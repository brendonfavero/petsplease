{* 7.0.3-293-g3c11adb *}

{$admin_msgs}

<form action="" method="post" onsubmit="if(document.getElementById('tmce').checked)return confirm('WARNING!\nThe editor attempts to correct HTML that is invalid. This could cause problems with your templates depending on your design. Contact support for details.\n\nDo you want to activate the editor?');">
	<fieldset>
		<legend>WYSIWYG Editor Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Admin textareas{$tooltip|replace:'EGAD':'1'}</div>
				<div class="rightColumn">
					<label><input type="radio" name="use_admin_wysiwyg" value="0"{if !$use_admin_wysiwyg} checked="checked"{/if} /> None</label><br />
					<label><input type="radio" name="use_admin_wysiwyg" value="TinyMCE"{if $use_admin_wysiwyg=='TinyMCE'} checked="checked"{/if} id="tmce" /> TinyMCE</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">CSS stylesheets{$tooltip|replace:'EGAD':'2'}<br />(comma separated)</div>
				<div class="rightColumn">
					<textarea name="wysiwyg_css_uri" cols="50">{$wysiwyg_css_uri}</textarea>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Use GZip Compression{$tooltip|replace:'EGAD':'3'}</div>
				<div class="rightColumn">
					<label><input type="radio" name="use_wysiwyg_compression" value="1"{if $use_wysiwyg_compression} checked="checked"{/if} /> On</label><br />
					<label><input type="radio" name="use_wysiwyg_compression" value="0"{if !$use_wysiwyg_compression} checked="checked"{/if} /> Off</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">IE Blank Screen Fix</div>
				<div class="rightColumn">
					<input type="checkbox" name="wysiwyg_blank_screen_fix" value="1"{if $wysiwyg_blank_screen_fix} checked="checked"{/if} />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<input type="submit" name="auto_save" value="Save" />
			</div>
	
		</div>
	</fieldset>
	<fieldset>
		<legend>Template Code Editor Codemirror Settings</legend>
		<div>
			<p class="page_note">When editing a template, the <strong>&lt;..&gt; Source Code Editor</strong> uses a 3rd party library called CodeMirror
				to make the contents easier to edit, providing line numbers and syntax highlighting and such.  Below are a few different
				settings to allow you to change the look and behavior of Codemirror.</p>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Editor Theme</div>
				<div class="rightColumn">
					<select name="codemirrorTheme">
						<option value="0">Default</option>
						{foreach $codemirrorThemes as $theme}
							<option{if $codemirrorTheme==$theme} selected="selected"{/if}>{$theme}</option>
						{/foreach}
					</select>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"><input type="checkbox" name="codemirrorAutotab" value="1"{if $codemirrorAutotab} checked="checked"{/if} /></div>
				<div class="rightColumn">Enable Auto-Tab</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"><input type="checkbox" name="codemirrorSearch" value="1"{if $codemirrorSearch} checked="checked"{/if} /></div>
				<div class="rightColumn">Enable Simple Search/Replace within editor</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<input type="submit" name="auto_save" value="Save" />
			</div>
		</div>
	</fieldset>
</form>