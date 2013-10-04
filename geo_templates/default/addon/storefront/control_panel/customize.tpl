{* 7.1beta1-1197-g54888bf *}

{include file='control_panel/header.tpl'}
	{* header.tpl starts a div for main column *}
	<br />
	<form method="post" enctype="multipart/form-data" action="{$classifieds_file_name}?a=ap&amp;addon=storefront&amp;page=control_panel&amp;action=update&amp;action_type=customize">
		<div class="content_box">
			<h2 class="title">{$msgs.usercp_custom_logo_header}</h2>
			<div class="{cycle values='row_odd,row_even'}">
				<label for="storefrontLogo" class="field_label">
					{$msgs.usercp_custom_logo_upload}
				</label>
				<input class='file' type='file' name='logo' id='storefrontLogo' size='40' class="field" />
			</div>
			
			<h1 class="subtitle">{$msgs.usercp_custom_logo_currentheader}</h1>
			<img src="addons/storefront/images/{if $current_logo}{$current_logo}{else}addon_storefront_logo.gif{/if}" alt="Your Logo" {if $logo_width and $logo_height}style="width: {$logo_width}px; height: {$logo_height}px;"{/if} />
			
			<h1 class="subtitle">{$msgs.usercp_custom_logo_size_header}</h1>
			<div class="{cycle values='row_odd,row_even'}">
				<label class="field_label">
					{$msgs.usercp_custom_logo_size_mainlabel}
				</label>
				<strong class="text_highlight">{$msgs.usercp_custom_logo_size_width}</strong> <input type="text" size="4" maxlength="4" name="data[logo_width]" value="{$logo_width}" class="field" /> {$msgs.usercp_custom_logo_size_px} &nbsp;&nbsp;
				<strong class="text_highlight">{$msgs.usercp_custom_logo_size_height}</strong> <input type="text" size="4" maxlength="4" name="data[logo_height]" value="{$logo_height}" class="field" /> {$msgs.usercp_custom_logo_size_px}
			</div>
			
			<div class="{cycle values='row_odd,row_even'}">
				<label class="field_label">
					{$msgs.usercp_custom_logo_size_listlabel}
				</label>
				<strong class="text_highlight">{$msgs.usercp_custom_logo_size_width}</strong> <input type="text" size="4" maxlength="4" name="data[logo_list_width]" value="{$logo_list_width}" class="field" /> {$msgs.usercp_custom_logo_size_px} &nbsp;&nbsp;
				<strong class="text_highlight">{$msgs.usercp_custom_logo_size_height}</strong> <input type="text" size="4" maxlength="4" name="data[logo_list_height]" value="{$logo_list_height}" class="field" /> {$msgs.usercp_custom_logo_size_px}  
			</div>
		</div>
		<br />
		<div class="content_box">
			<h1 class="title">{$msgs.usercp_custom_settings_header}</h1>
			
			<div id="name_result" class="center main_text"></div>
			<div class="{cycle values='row_odd,row_even'}">
				<label class="field_label">
					{$msgs.usercp_custom_settings_name_label}
				</label>
				<input onchange="CheckStoreName(this.value)" type="text" id="storefront_name" name="data[storefront_name]" value="{$storefront_name|escape}" size="30" maxlength="50" class="field" />
				<input type="button" id="btn_check" value="{$msgs.usercp_custom_settings_name_check}" onclick="CheckStoreName($F('storefront_name'))" class="button" />
			</div>
			<script type="text/javascript">
				{literal}
				var CheckStoreName = function (name) {
							
					$('name_result').update("{/literal}{$msgs.usercp_custom_settings_name_pending}{literal}");
					$('btn_submit').disable();
					$('btn_check').disable();
				
					new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=storefront&page=check_name_ajax'{literal}, {
						method: 'post',
						parameters: {
							name_to_check: name 
						},
						onSuccess: function(returned) {
							result = returned.responseText;
							
							if(result == 'INVALID') {
								resultText = "<div class='field_error_box'>{/literal}{$msgs.usercp_custom_settings_name_invalid}{literal}</div>";
							} else if (result == 'IN_USE') {
								resultText = "<div class='field_error_box'>{/literal}{$msgs.usercp_custom_settings_name_taken}{literal}</div>";
							} else if (result == 'OK') {
								resultText = "<div class='success_box'>{/literal}{$msgs.usercp_custom_settings_name_good}{literal}</div>";
								$('btn_submit').enable();
							} else {
								//probably blank...
								resultText = "";
								$('btn_submit').enable();
							}
							$('name_result').update(resultText); 
							$('btn_check').enable();
						}
					});
				}
				{/literal}
			</script>
			
			<h1 class="subtitle">{$msgs.usercp_custom_settings_welcomenoteheader}</h1>
			<div class="{cycle values='row_odd,row_even'}">
				<a href="javascript:void(0)" onclick="geoWysiwyg.toggleTinyEditors();">{$messages.add_remove_wysiwyg}</a>
			</div>
			<div class="{cycle values='row_odd,row_even'}">
				<textarea class='editor field' name='data[welcome_note]' id='storefrontNote' cols='' rows='' style="width: 98%; height: 200px;">{$welcome_message|escape}</textarea>
			</div>
		</div>
		<br />
		{if count($template_choices) > 1}
			<div class="content_box">
				<h2 class="title">{$msgs.usercp_custom_settings_tpl_header}</h2>
				<div class="{cycle values='row_odd,row_even'}">
					<label class="field_label" for="data[storefrontTemplate]">{$msgs.usercp_custom_settings_tpl_label}</label>
					<select name="data[storefrontTemplate]" id="data[storefrontTemplate]" class="field">
						{foreach from=$template_choices item=tpl}
							<option value="{$tpl.template_id}"{if $tpl.template_id==$current_template} selected="selected"{/if}>{$tpl.name|fromDB}</option>
						{/foreach}
					</select>  
				</div>
			</div>
		{else}
			{*only one template choice -- assign it automatically*}
			<div><input type="hidden" name="data[storefrontTemplate]" value="{$single_template.template_id}" /></div>
		{/if}
		
		<div class="center">
			<input type="submit" value="{$msgs.usercp_custom_settings_save}" id="btn_submit" class="button" />
		</div>
		<div class="center">
			<a class="button" href="{$classifieds_file_name}?a=4">{$msgs.usercp_back_to_my_account}</a>
		</div>
	</form>
</div>
{* end of div started in header.tpl *}