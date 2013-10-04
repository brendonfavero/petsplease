<?php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.1.2-38-gb5497b1
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- LISTING SETUP
menu_category::addMenuCategory('listing_setup',$parent_key,'Listing Setup','admin_images/menu_config.gif','','',$head_key);

	menu_page::addPage('listing_general_settings','listing_setup','General Settings','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
	
	menu_page::addPage('fields_to_use','listing_setup','Fields to Use','menu_config.gif','fields_to_use.php','FieldsManage');
	
	menu_page::addPage('listing_hide_fields','listing_setup','Hide Fields','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
	
	menu_page::addPage('leveled_fields','listing_setup','Multi-Level Fields','menu_config.gif','leveled_fields.php','LeveledFieldsManage');
		menu_page::addPage('leveled_fields_add','leveled_fields','Add','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_edit','leveled_fields','Edit','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_fields_delete','leveled_fields','Delete','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_levels','leveled_fields','Levels','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_values','leveled_fields','Values','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_create','leveled_fields','Add Value','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_edit','leveled_fields','Edit Value','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_create_bulk','leveled_fields','Bulk Add Values','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_delete','leveled_fields','Delete Value','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_edit_bulk','leveled_fields','Mass Edit Value','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_enabled','leveled_fields','Enable/Disable Value','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_move','leveled_fields','Move Values','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
		menu_page::addPage('leveled_field_value_copy','leveled_fields','Copy Values','menu_config.gif','leveled_fields.php','LeveledFieldsManage', 'sub_page');
	
	menu_page::addPage('listing_placement_steps','listing_setup','Listing Placement Steps','menu_config.gif','listing_steps.php','listingStepsManage');
	
	menu_page::addPage('listing_extras','listing_setup','Listing Extras','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
			
	if (geoMaster::is('auctions')){
		menu_page::addPage('listing_bid_increments','listing_setup','Bid Increments','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
	}
	
	menu_page::addPage('listing_payment_types','listing_setup','Payment Types','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
	
	menu_page::addPage('listing_listing_durations','listing_setup','Listing Durations','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
	
	menu_page::addPage('listing_allowed_uploads','listing_setup','Allowed Uploads','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
		menu_page::addPage('uploads_new_type','listing_allowed_uploads','New File Type','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration','sub_page');
	
	menu_page::addPage('listing_photo_upload_settings','listing_setup','File Upload &amp; Display Settings','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
	
	menu_page::addPage('listing_currency_types','listing_setup','Currency Types','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration');
		menu_page::addPage('listing_currency_types_delete','listing_currency_types','Delete','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration', 'sub_page');
		menu_page::addPage('listing_currency_types_add','listing_currency_types','Add','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration', 'sub_page');
		menu_page::addPage('listing_currency_types_edit','listing_currency_types','Edit','menu_config.gif','admin_ad_configuration_class.php','Ad_configuration', 'sub_page');
	
	menu_page::addPage('dropdowns','listing_setup','Pre-Valued Dropdowns','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions');
		menu_page::addPage('edit_dropdown','dropdowns','Edit','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions','sub_page');
		menu_page::addPage('delete_dropdown','dropdowns','View Dropdowns','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions','sub_page');
		menu_page::addPage('delete_dropdown_value','dropdowns','Edit','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions','sub_page');
		menu_page::addPage('delete_dropdown_int','dropdowns','Confirm Deletion','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions','sub_page');
		menu_page::addPage('new_dropdown','dropdowns','New Dropdown','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions','sub_page');
