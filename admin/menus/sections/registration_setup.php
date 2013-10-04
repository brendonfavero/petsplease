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
## ##    6.0.7-2-gc953682
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- REGISTRATION SETUP
menu_category::addMenuCategory('registration_setup',$parent_key,'Registration Setup','admin_images/menu_regconfig.gif','','',$head_key);

	menu_page::addPage('register_general_settings','registration_setup','General Settings','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration');
	
	if ( geoPC::is_ent()) {
		menu_page::addPage('register_block_email_domains','registration_setup','Allow/Block Email Domains','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration');
			menu_page::addPage('block_email_add','register_block_email_domains','Add Domain','menu_siteconfig.gif','admin_registration_configuration_class.php','Registration_configuration','sub_page');
	}
	
	menu_page::addPage('register_unapproved','registration_setup','Unapproved Registrations','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration');
		menu_page::addPage('register_confirm_user','register_unapproved','Confirm User','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration','sub_page');
		menu_page::addPage('register_delete_user','register_unapproved','Delete User','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration','sub_page');
	
	if ( geoPC::is_ent() ) {
		menu_page::addPage('register_pre_valued','registration_setup','Pre-Valued Dropdowns','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration');		
			menu_page::addPage('register_pre_valued_add','register_pre_valued','Add New Dropdown','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration','sub_page');
			menu_page::addPage('register_pre_valued_edit','register_pre_valued','Edit','menu_regconfig.gif','admin_registration_configuration_class.php','Registration_configuration','sub_page');	
	}
	
	

	
