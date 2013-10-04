{* 6.0.7-3-gce41f93 *}
{$adminMessages}
<form action="" method="post">
	<fieldset>
		<legend>Server Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">GD Library 2.0</div>
				<div class="rightColumn">
					<label><input type="radio" name="imagecreatetruecolor_switch" value="0" {if !$imagecreatetruecolor_switch}checked="checked" {/if}/>
						Use imagecreatetruecolor</label><br />
					<label><input type="radio" name="imagecreatetruecolor_switch" value="1" {if $imagecreatetruecolor_switch}checked="checked" {/if}/>
						Use older methods</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Process Image Directory (default)</div>
				<div class="rightColumn">
					<label><input type="radio" name="image_upload_type" value="0" {if !$image_upload_type}checked="checked" {/if}/>
						From Starting Temp Directory</label><br />
					<label><input type="radio" name="image_upload_type" value="1" {if $image_upload_type}checked="checked" {/if}/>
						Copy First</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Photo Directory URL</div>
				<div class="rightColumn"><input type="text" name="url_image_directory" value="{$url_image_directory|escape}" size="60" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Server Path to Root of Photos Directory<br />
					<span class="small_font">Path to this document: {$server_dir}</span></div>
				<div class="rightColumn"><input type="text" name="image_upload_path" value="{$image_upload_path|escape}" size="60" /></div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Upload Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Photo / File Upload Interface</div>
				<div class="rightColumn">
					<label><input type="radio" name="useStandardUploader" value="1" {if $useStandardUploader}checked="checked" {/if}/>
						Standard Uploader + Legacy Uploader</label><br />
					<label><input type="radio" name="useStandardUploader" value="0" {if !$useStandardUploader}checked="checked" {/if}/>
						Legacy Uploader Only</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Max Size per Photo / File</div>
				<div class="rightColumn"><input type="text" name="maximum_upload_size" value="{$maximum_upload_size}" size="10" /> Bytes each</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Image Resize Quality</div>
				<div class="rightColumn"><input type="text" name="photo_quality" value="{$photo_quality}" size="3" /> percent</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Max Length of Photo Description</div>
				<div class="rightColumn"><input type="text" name="maximum_image_description" value="{$maximum_image_description}" size="3" /> characters</div>
				<div class="clearColumn"></div>
			</div>
			<div class="col_hdr">Legacy Uploader Settings</div>
			<p class="page_note" style="text-align: center;">These settings only affect the display/usage when using the legacy uploader.</p>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Allow Photo / File Uploads</div>
				<div class="rightColumn"><input type="checkbox" name="allow_upload_images" value="1"{if $allow_upload_images} checked="checked"{/if} /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Allow URL-Referenced Photos</div>
				<div class="rightColumn"><input type="checkbox" name="allow_url_referenced" value="1"{if $allow_url_referenced} checked="checked"{/if} /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Uploading Image Icon URL</div>
				<div class="rightColumn"><input type="text" name="uploading_image" value="{$uploading_image|escape}" size="60" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Uploading Image Placeholder URL</div>
				<div class="rightColumn"><input type="text" name="uploading_image_placeholder" value="{$uploading_image_placeholder|escape}" size="60" /></div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Image Block Settings (On Listing Details page)</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"># Displayed Images</div>
				<div class="rightColumn"><input type="text" name="number_of_photos_in_detail" value="{$number_of_photos_in_detail}" size="2" /> (0 to display all)</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"># Columns</div>
				<div class="rightColumn"><input type="text" name="photo_columns" value="{$photo_columns}" size="2" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Image Block Layout</div>
				<div class="rightColumn">
					<label>
						<input type="radio" name="gallery_style" value="classic" {if $gallery_style=='classic'}checked="checked" {/if}/>
						Classic
					</label>
					<label>
						<input type="radio" name="gallery_style" value="gallery" {if $gallery_style=='gallery'}checked="checked" {/if}/>
						Gallery
					</label>
					<label>
						<input type="radio" name="gallery_style" value="filmstrip" {if $gallery_style=='filmstrip'}checked="checked" {/if}/>
						Filmstrip
					</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $is_ent}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Enlarge Photo View</div>
					<div class="rightColumn"><label><input type="radio" name="image_link_destination_type" value="1" {if $image_link_destination_type}checked="checked" {/if}/>
						Full Size Image Display Page</label><br />
					<label><input type="radio" name="image_link_destination_type" value="0" {if !$image_link_destination_type}checked="checked" {/if}/>
						Lightbox Slideshow</label></div>
					<div class="clearColumn"></div>
				</div>
			{/if}
		</div>
	</fieldset>
	<fieldset>
		<legend>Lightbox Slideshow Settings</legend>
		<div>
			<p class="page_note" style="text-align: center;">Text Used: <a href="index.php?page=sections_browsing_edit_text&b=157&l=1">Pages Management &gt; Browsing Listings &gt; Image Lightbox Slideshow &gt; Edit Text</a></p>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Slideshow Enabled</div>
				<div class="rightColumn"><input type="checkbox" name="useSlideshow" value="1"{if $useSlideshow} checked="checked"{/if} /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Slideshow Starts Automatically</div>
				<div class="rightColumn"><input type="checkbox" name="startSlideshow" value="1"{if $startSlideshow} checked="checked"{/if} /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Use Lightbox Animations</div>
				<div class="rightColumn"><input type="checkbox" name="useLightboxAnimations" value="1"{if $useLightboxAnimations} checked="checked"{/if} /></div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Photo Max Dimension Settings</legend>
		<div style="text-align: center;">
			<table style='width: auto; padding: 5px; margin-left: auto; margin-right: auto; text-align: left;'>
				<thead>
					<tr>
						<th class='col_hdr'>&nbsp;</th>
						<th class='col_hdr'>Max Width</th>
						<th class='col_hdr'>Max Height</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$dimensionSettings item="settings"}
						{cycle values="row_color1,row_color2" assign="row_color"}
						<tr>
							<td class="{$row_color} medium_font">{$settings.label}</td>
							<td class="{$row_color}">
								<label><input type='text' size='4' name='dim[{$settings.name}_width]' value='{$settings.width}' /> px</label>
							</td>
							<td class="{$row_color}">
								<label><input type='text' size='4' name='dim[{$settings.name}_height]' value='{$settings.height}' /> px</label>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</fieldset>
	
	<div style="text-align: center;">
		<input type="submit" name="auto_save" value="Save" />
	</div>
</form>