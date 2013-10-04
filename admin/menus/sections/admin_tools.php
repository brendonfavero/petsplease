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
## ##    7.0.3-292-gda9654f
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- ADMIN TOOLS & SETTINGS
menu_category::addMenuCategory('admin_tools_settings',$parent_key,'Admin Tools &amp; Settings','admin_images/menu_admintools.gif','','',$head_key);
	
	menu_category::addMenuCategory('admin_messaging','admin_tools_settings','Messaging','','','');
		menu_page::addPage('admin_messaging_send','admin_messaging','Send Message','menu_admintools.gif','admin_messaging_class.php','Admin_messaging');
		menu_page::addPage('admin_messaging_form','admin_messaging','Form Messages','menu_admintools.gif','admin_messaging_class.php','Admin_messaging');
			menu_page::addPage('admin_messaging_form_new','admin_messaging_form','New Form Message','menu_admintools.gif','admin_messaging_class.php','Admin_messaging');
			menu_page::addPage('admin_messaging_form_delete','admin_messaging_form','Delete Form Message','menu_admintools.gif','admin_messaging_class.php','Admin_messaging');
			menu_page::addPage('admin_messaging_form_edit','admin_messaging_form','Edit Form Message','menu_admintools.gif','admin_messaging_class.php','Admin_messaging');
		menu_page::addPage('admin_messaging_history','admin_messaging','Message History','menu_admintools.gif','admin_messaging_class.php','Admin_messaging');
	
	menu_page::addPage('admin_tools_view_ads','admin_tools_settings','View Expired Ads','menu_admintools.gif','admin_classauction_tools.php','Admin_classauction_tools');
	
	menu_page::addPage('admin_tools_password','admin_tools_settings','Change Password','menu_admintools.gif','admin_authentication_class.php','Admin_auth');
	
	menu_page::addPage('admin_tools_license','admin_tools_settings','License Info','menu_admintools.gif','admin_classauction_tools.php','Admin_classauction_tools');
	
	menu_page::addPage('admin_tools_clean_images','admin_tools_settings','Remove Orphaned Images','menu_admintools.gif','admin_classauction_tools.php','Admin_classauction_tools');

	menu_category::addMenuCategory('beta_tools','admin_tools_settings','BETA Tools','','','');
		menu_page::addPage('beta_general_settings','beta_tools','BETA Settings','menu_admintools.gif','admin_beta_settings.php','Beta_configuration');

	menu_category::addMenuCategory('security_center','admin_tools_settings','Security Settings','','','');
		menu_page::addPage('general_settings','security_center', 'General Security Settings','menu_admintools.gif','security_settings.php','securitySettings');
	
	menu_page::addPage('wysiwyg_general_config','admin_tools_settings','Editor Settings','menu_admintools.gif','admin_wysiwyg_config.php', 'wysiwyg_configuration');
	