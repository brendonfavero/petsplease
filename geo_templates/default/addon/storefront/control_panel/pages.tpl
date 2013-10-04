{* 7.1.2-70-g2e86b49 *}
{include file='control_panel/header.tpl'}
	{* header.tpl starts a div for main column *}
	<form method="post" action="{$classifieds_file_name}?a=ap&amp;addon=storefront&amp;page=control_panel&amp;action=update&amp;action_type=pages">
		<br />
		<div class="content_box">
			<h2 class="title">{$msgs.usercp_pages_settings_header}</h2>
			<div class="{cycle values='row_odd,row_even'}">
				<label class="field_label" for="data[home_cat]">
					{$msgs.usercp_pages_settings_homecatlabel}
				</label>
				<input type="text" name="data[home_cat]" id="data[home_cat]" value="{$home_cat}" class="field" />
			</div>
			<div class="{cycle values='row_odd,row_even'}">
				{if !$no_pages}
					{* pages exist -- show dropdown to select default *}
					<label class="field_label" for="data[default_page]">
						{$msgs.usercp_pages_settings_defaultlabel}
					</label>
					
					<select name="data[default_page]" id="data[default_page]" class="field">
						<option value="">{$msgs.usercp_pages_settings_defaultpagenull}</option>
						{foreach from=$pages item=page}
							<option value="{$page.page_id}" {if $page.selected}selected="selected"{/if}>{$page.name}</option>
						{/foreach}
					</select>
				{/if}
			</div>
			
			<div class="{cycle values='row_odd,row_even'}">
				<a href="{$classifieds_file_name}?a=ap&amp;addon=storefront&amp;page=control_panel&amp;action=update&amp;action_type=pages&amp;create_pages=yes" class="button" onclick="if(!confirm('{$msgs.usercp_pages_settings_restoredefaults_confirm}')) return false;">{$msgs.usercp_pages_settings_restoredefaults}</a>
			</div>
			
			<h1 class="subtitle">{$msgs.usercp_pages_settings_addnewheader}</h1>
			<div class="{cycle values='row_odd,row_even'}">
				<label class="field_label" for="data[new_cat]">
					{$msgs.usercp_pages_settings_addnewcategory}
				</label>
				<input type="text" name="data[new_cat]" id="data[new_cat]"  class="field" />
			</div>
				
			<div class="{cycle values='row_odd,row_even'}">
				<label class="field_label" for="data[new_page]">
					{$msgs.usercp_pages_settings_addnewpage}
				</label>
				<input type="text" name="data[new_page]" id="data[new_page]" value="{$new_page}" class="field" />
			</div>
			<div class="center"><input type="submit" value="{$msgs.usercp_pages_btn_save}" class="button" /></div>
		</div>
	</form>
	<br />
	{if $category_count > 0}
		<div class="content_box">
			<h1 class="title">{$msgs.usercp_pages_cats_header}</h1>

			<div id="category_sort_result"></div>

			<ul class="sortable_list" id="category_list">
				{foreach from=$categories item=cat}
					<li id="cat_sort_{$cat.category_id}" class="storefront_sort_item">
						<div class="sortable_item_controls" id="cat_controls_{$cat.category_id}">
							<a href="javascript:void(0);" onclick="edit_cat({$cat.category_id}); return false;" class="button_green">{$msgs.usercp_pages_btn_edit}</a>
							<a href="{$classifieds_file_name}?a=ap&amp;addon=storefront&amp;page=control_panel&amp;action=update&amp;action_type=pages&amp;del_cat={$cat.category_id}" class="button_red">{$msgs.usercp_pages_btn_delete}</a>
						</div>
						
						<div id="cat_name_{$cat.category_id}" class="item_name">
							{$cat.category_name}
							<a href="javascript:void(0);" onclick="jQuery('#reveal_add_subcat_{$cat.category_id}').show();" class="button_green" />{$msgs.usercp_pages_btn_addsub}</a>
						</div>
						
						<div id="subcategories_for_{$cat.category_id}">
							{foreach $cat.subcategories as $sub_id => $sub_name}
								<hr class="subcat_divider" />
								<div id="subcategory_{$sub_id}" class="subcat_input_container">
									<span id="subcat_main_{$sub_id}">
										<span id="subcat_name_{$sub_id}">{$sub_name}</span> 
										<a href="javascript:void(0);" onclick="jQuery('#subcat_main_{$sub_id}').hide(); jQuery('#subcat_edit_{$sub_id}').show();" class="button_green" />{$msgs.usercp_pages_btn_edit}</a>
										<a href="{$classifieds_file_name}?a=ap&amp;addon=storefront&amp;page=control_panel&amp;action=update&amp;action_type=pages&amp;del_cat={$sub_id}" class="button_red">{$msgs.usercp_pages_btn_delete}</a>
									</span>
									<span id="subcat_edit_{$sub_id}" style="display: none;">
										<input type="text" name="edit_subcat_txt_{$sub_id}" id="edit_subcat_txt_{$sub_id}" value="{$sub_name}" placeholder="{$msgs.usercp_pages_plh_newsub}" class="field" />
										<a href="javascript:void(0);" onclick="editSubcategory({$sub_id});" class="button_green" />{$msgs.usercp_pages_btn_save}</a>
										<a href="javascript:void(0);" onclick="jQuery('#subcat_main_{$sub_id}').show(); jQuery('#subcat_edit_{$sub_id}').hide();" class="button_red" />{$msgs.usercp_pages_btn_cancel}</a>
									</span>
								</div>
							{/foreach}
						</div>
						
						<div id="reveal_add_subcat_{$cat.category_id}" style="display:none;">
							<hr class="subcat_divider" />
							<div class="subcat_input_container">
								<input type="text" name="new_subcat_for_{$cat.category_id}" id="new_subcat_for_{$cat.category_id}" placeholder="{$msgs.usercp_pages_plh_newsub}" class="field" />
								<a href="javascript:void(0);" onclick="addSubcategory({$cat.category_id});" class="button_green" />{$msgs.usercp_pages_btn_save}</a>
								<a href="javascript:void(0);" onclick="jQuery('#reveal_add_subcat_{$cat.category_id}').hide();" class="button_red" />{$msgs.usercp_pages_btn_cancel}</a>
							</div>
						</div>
						
						<input type="hidden" id="cat_oldname_{$cat.category_id}" value="{$cat.category_name}" /> {*hidden input field, so the ajax form can grab the name *}
						<div class="clr"></div>
					</li>
				{/foreach}
			</ul>
		</div>

		<script type="text/javascript">
		//<![CDATA[
		
			var addSubcategory = function(id) {
				//add subcat to db
				jQuery.ajax('{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel_ajax', {
					data: {
						action: 'add_subcategory',
						parent: id,
						name: jQuery('#new_subcat_for_'+id).val()
					},
					type: 'POST'
				}).done(function(msg) {
					//easy way: reload page so that new subcat is shown without having to duplicate creation code here
					window.location.href = "{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel&action=display&action_type=pages"
				});
			}
			
			var editSubcategory = function(id) {
				//update db with ajax
				jQuery.ajax('{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel_ajax', {
					data: {
						action: 'edit_subcategory',
						edit: id,
						name: jQuery('#edit_subcat_txt_'+id).val()
					},
					type: 'POST'
				}).done(function(msg) {
					//ajax should return new name (even if it hasn't changed) -- update fields with new name
					jQuery("#subcat_name_"+id).html(msg);
					jQuery("#edit_subcat_txt_"+id).val(msg);
					jQuery('#subcat_main_'+id).show();
					jQuery('#subcat_edit_'+id).hide();
				});
				
				
			}
			
		{literal}
			var edit_cat = function (id) {
				nameplate = $('cat_name_'+id);
				oldValue = $('cat_oldname_'+id).value;
				
				//hide edit/delete button while editing
				$('cat_controls_'+id).hide();
				
				//make the bar a bit bigger, so that the save button fits
				$('cat_sort_'+id).style.height = '40px';
				
				formHTML = '';
				formHTML += '<input type="text" name="update_cat_name_'+id+'" id="send_cat_'+id+'" value="'+oldValue+'" class="field" /> ';
				formHTML += '<input type="button" value="{/literal}{$msgs.usercp_pages_btn_save}{literal}" onclick="SendCatName('+id+', $(\'send_cat_'+id+'\').value);" class="button" />';
				nameplate.update(formHTML);
				
				//don't use hand cursor in expanded mode
				nameplate.style.cursor = 'auto';
				
				//so that the "form" still submits when Enter is pressed
				$('send_cat_'+id).observe('keypress', function(event) {
					if(event.keyCode == Event.KEY_RETURN) {
						SendCatName(id, $('send_cat_'+id).value);
					}
				});
			}
			
			var SendCatName = function (id, val) {
				new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel_ajax'{literal}, {
					method: 'post',
					parameters: {
						cat_id: id,
						new_name: val 
					},
					onSuccess: function(returned) {
						//returned.responseText holds the new category name
						//or the old one, if new input was invalid
						changedName = returned.responseText; 
						$('cat_oldname_'+id).value = changedName; //update hidden form field
						$('cat_name_'+id).update(changedName); //remove form and replace with new name
						$('cat_sort_'+id).style.height = '25px'; //restore original bar size
						$('cat_controls_'+id).show(); //show edit/delete controls again
						$('cat_name_'+id).style.cursor='pointer'; //back to using pointer to drag
					}
				});
			}

			//load category sorter
			Event.observe(window, 'load', function () {
				Sortable.create('category_list',
				{
					onUpdate: function() { 
						new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel_ajax'{literal}, {
							method: 'post',
							parameters: {
								category_order: Sortable.serialize('category_list')
							},
							//uncomment for debugging -- ret.responseText is whatever the ajax function echos
							//onSuccess: function(ret) { $('category_sort_success').update('ajax ok: '+ret.responseText); }
							onSuccess: function() {
								$('category_sort_result').hide();
								$('category_sort_result').update('<div class="success_box">{/literal}{$msgs.usercp_pages_cats_saved}{literal}</div>');
								$('category_sort_result').appear();
								setTimeout( function() { $('category_sort_result').fade(); } , 1500); 
							}
						});
					}
				});
			});
			
		{/literal}
		//]]>
		</script>
	{/if}
		
	{if $page_count > 0}
		<br />
		<div class="content_box">
			<h2 class="title">{$msgs.usercp_pages_page_header}</h2>

			<div id="page_sort_result"></div>

			<ul class="sortable_list" id="page_list">
				{foreach from=$pages item=page}
					<li id="page_sort_{$page.page_id}" class="storefront_sort_item">
					
						<div class="sortable_item_controls" id="page_controls_{$page.page_id}">
							<a href="javascript:void(0);" onclick="edit_page({$page.page_id}); return false;" class="button_green">{$msgs.usercp_pages_btn_edit}</a>
							<a href="{$classifieds_file_name}?a=ap&amp;addon=storefront&amp;page=control_panel&amp;action=update&amp;action_type=pages&amp;del_page={$page.page_id}" class="button_red">{$msgs.usercp_pages_btn_delete}</a>
						</div>
						
						<div class="item_name" id="page_name_{$page.page_id}">{$page.name}</div>
						
						{*hidden input fields, so the ajax form can grab the name *}
						<input type="hidden" id="page_oldname_{$page.page_id}" value="{$page.name}" />
						<input type="hidden" id="page_oldlink_{$page.page_id}" value="{$page.link_text}" />
						<input type="hidden" id="page_oldbody_{$page.page_id}" value="{$page.body|escape}" />
					
						<div class="clr"></div>
						
					</li>
				{/foreach}
			</ul>
		</div>
		
		<script type="text/javascript">
		//<![CDATA[
		{literal}
			var edit_page = function (id) {
				nameplate = $('page_name_'+id);
				oldValue = $('page_oldname_'+id).value;
				oldBody = $('page_oldbody_'+id).value;
				oldLink = $('page_oldlink_'+id).value;
				
				//hide edit/delete button while editing
				$('page_controls_'+id).hide();
				
				//make the bar bigger, to accomodate the WYSIWYG box
				$('page_sort_'+id).style.height='{/literal}{$editorHeight + 190}{literal}px';
				
				formHTML = '';
				formHTML += '<div><label class="field_label">{/literal}{$msgs.usercp_pages_page_name}{literal}</label> <input type="text" name="update_page_name_'+id+'" id="send_page_'+id+'" value="'+oldValue+'" class="field" /></div>';
				formHTML += '<div><label class="field_label">{/literal}{$msgs.usercp_pages_page_link}{literal}</label> <input type="text" name="update_page_link_'+id+'" id="page_link_'+id+'" value="'+oldLink+'" class="field" /></div>';
				formHTML += '<div><label class="field_label">{/literal}{$msgs.usercp_pages_page_body}{literal}</h1></div> <div><a href="javascript:void(0)" onclick="geoWysiwyg.toggleTinyEditors();">{/literal}{$messages.add_remove_wysiwyg|escape_js}{literal}</a><br /><textarea class="editor field" id="page_body_'+id+'" name="update_page_body_'+id+'" style="width: 98%; height: 200px;">'+oldBody+'</textarea></div>';
				formHTML += '<div class="center"><input type="button" value="{/literal}{$msgs.usercp_pages_btn_save}{literal}" class="button" onclick="SendPageData('+id+');" />';
				formHTML += ' <input type="button" value="{/literal}{$msgs.usercp_pages_btn_cancel}{literal}" onclick="PageCancel('+id+')" class="cancel" /></div><div class="clr"></div>';
				nameplate.update(formHTML);
				
				nameplate.style.cursor = 'auto';
				
				//add the wysiwyg
				geoWysiwyg.editors = $$('.editor');
				if (getCookie('tinyMCE') == 'off') {
					//wysiwyg is off, notin to do
				} else {
					//this textarea has just been dynamically created, so no need to check if it's already a wysiwyg (it is not)
					tinyMCE.execCommand('mceAddControl', false, 'page_body_'+id);
				}								
			}
			
			var PageCancel = function (id)
			{
				tinyMCE.execCommand('mceRemoveControl', false, 'page_body_'+id);
				$('page_name_'+id).update($('page_oldname_'+id).value); //replace form with title
				$('page_controls_'+id).show(); //show edit/delete controls again
				$('page_sort_'+id).style.height='25px'; //return bar to smaller size
				$('page_name_'+id).style.cursor = 'pointer';
			}
			
			var SendPageData = function (id)
			{
				var name = $('send_page_'+id).getValue();
				var link = $('page_link_'+id).getValue();

				var body = (tinyMCE.getInstanceById('page_body_'+id))? tinyMCE.get('page_body_'+id).getContent() : $('page_body_'+id).getValue();
				

				new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel_ajax'{literal}, {
					method: 'post',
					parameters: {
						page_id: id,
						new_name: name,
						new_link: link,
						new_body: body
					},
					onSuccess: function(returned) {
						//returned.responseText holds the new data (or the old one, if new input was invalid)
						//format: newName~~!~~newBody(un-escaped)
						newData = returned.responseText.split("~~!~~"); 
						
						//update hidden form fields
						$('page_oldname_'+id).value = newData[0];
						$('page_oldlink_'+id).value = newData[1];
						$('page_oldbody_'+id).value = newData[2].escapeHTML(); 
						
						//clean up after ourselves and turn off the wysiwyg before hiding it???
						tinyMCE.execCommand('mceRemoveControl', false, 'page_body_'+id);
						
						$('page_name_'+id).update(newData[0]); //remove form and replace with new name
						$('page_controls_'+id).show(); //show edit/delete controls again
						
						$('page_sort_'+id).style.height='25px'; //return bar to smaller size
						$('page_name_'+id).style.cursor = 'pointer';
						
						//show confirmation message
						$('page_sort_result').hide();
						$('page_sort_result').update('<div class="success_box">Pages Updated</div>');
						$('page_sort_result').appear();
						setTimeout( function() { $('page_sort_result').fade(); } , 1500);
					}
				});
			}

			//load page sorter
			Event.observe(window, 'load', function () {
				Sortable.create('page_list',
				{
					onUpdate: function() { 
						new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=storefront&page=control_panel_ajax'{literal}, {
							method: 'post',
							parameters: {
								page_order: Sortable.serialize('page_list')
							},
							//uncomment for debugging -- ret.responseText is whatever the ajax function echos
							//onSuccess: function(ret) { $('category_sort_success').update('ajax ok: <br />'+ret.responseText); }
							onSuccess: function() {
								$('page_sort_result').hide();
								$('page_sort_result').update('<div class="success_box">{/literal}{$msgs.usercp_pages_page_saved}{literal}</div>');
								$('page_sort_result').appear();
								setTimeout( function() { $('page_sort_result').fade(); } , 1500); 
							}
						});
					}
				});
			});
			
		{/literal}
		//]]>
		</script>
	{/if}
	<div class="center">
		<a class="button" href="{$classifieds_file_name}?a=4">{$msgs.usercp_back_to_my_account}</a>
	</div>
</div>
{* end of div started in header.tpl *}
