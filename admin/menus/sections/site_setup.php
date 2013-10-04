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
## ##    7.1beta1-1160-g90ff371
## 
##################################

//Loads the menu for site setup

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- SITE SETUP

menu_category::addMenuCategory('site_setup',$parent_key,'Site Setup','admin_images/menu_siteconfig.gif','','',$head_key);

	menu_page::addPage('master_switches','site_setup','Master Switches','menu_siteconfig.gif','master.php','manageMaster');
	
	menu_page::addPage('main_general_settings','site_setup','General Settings','menu_siteconfig.gif','admin_site_configuration_class.php','Site_configuration');
	
	menu_page::addPage('main_browsing_settings','site_setup','Browsing Settings','menu_siteconfig.gif','admin_site_configuration_class.php','Site_configuration');
	
	menu_page::addPage('user_account_settings','site_setup','User Account Settings','menu_siteconfig.gif','admin_user_account_settings.php','admin_user_account_settings');
	
	//Detect if the old API is being used
	require(GEO_BASE_DIR.'config.default.php');
	if (isset($api_db_host) && strlen($api_db_host) > 0){
		//old API settings are set in the config.php, so show the page
		//that displays the old API installations so that it is easy
		//to migrate settings to the new Bridge Addon.
		menu_page::addPage('main_api_integration','site_setup','API Integration','menu_siteconfig.gif','admin_module_loader_class.php','module_loader');
		//2 birds with one stone: Also show an alert in the admin, to make sure
		//they realize that the API has been replaced by the Bridge Addon:
		Notifications::addCheck( create_function( "", "
			return '<strong>Compatibility Alert:</strong> You still have the API database settings configured in your config.php, however the Geo API has been replaced by
				the new Bridge Addon.  After you have migrated the API installations over to use the new Bridge Addon, you can turn this notice off by removing the API settings
				from your config.php file.  Consult the user manual for more information about the Bridge Addon and how to migrate your current API installations.';
			"));
	}
	
	menu_page::addPage('main_html_allowed','site_setup','Allowed HTML','menu_siteconfig.gif','admin_html_allowed_class.php','HTML_allowed');
	
	menu_page::addPage('main_badwords','site_setup','Badwords','menu_siteconfig.gif','admin_text_badwords_class.php','Text_badwords_management');
	
	menu_page::addPage('main_ip_banning','site_setup','IP Banning','menu_siteconfig.gif','admin_site_configuration_class.php','Site_configuration');
	
	if (DataAccess::getInstance()->get_site_setting('use_filters')) {
		//Only show if turned on.. eventually we will remove this
		menu_page::addPage('main_filters','site_setup','Field Filter','menu_siteconfig.gif','admin_filter_class.php','Admin_filter');
	}
	
	
	menu_page::addPage('cache_config','site_setup','Cache','menu_siteconfig.gif','cache_manage.php','AdminCacheManage');
		menu_page::addPage('clear_cache','cache_config','Clear Cache','menu_siteconfig.gif','cache_manage.php','AdminCacheManage');
	
	
	menu_page::addPage('cron_config','site_setup','Cron Jobs','menu_siteconfig.gif','cron_manage.php','AdminCronManage');
	
	menu_page::addPage('api_keys','site_setup','Remote API Security Keys','menu_siteconfig.gif','api.php','AdminAPIManage');
	