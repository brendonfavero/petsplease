<?php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.4
Copyright (c) 2001-2014 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.4.4-148-g872703d
## 
##################################

//Loads a simplified version of the admin menu for the AdPlotter Edition 

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');


//top level pages
menu_category::addMenuCategory('top_level',0,'','','','');
menu_page::addPage('home','top_level','Admin Home','menu_home.gif','home.php','geoAdminHome','sub_page');
menu_page::addPage('quick_find','top_level','Quick Find','menu_home.gif','home.php','geoAdminHome','sub_page');

$parent_key = $head_key = 0;

//Site Setup: Badword list and IP Bans
menu_category::addMenuCategory('site_setup',$parent_key,'Site Setup','admin_images/menu_siteconfig.gif','','',$head_key);
menu_page::addPage('main_badwords','site_setup','Badwords','menu_siteconfig.gif','admin_text_badwords_class.php','Text_badwords_management');
menu_page::addPage('main_ip_banning','site_setup','IP Banning','menu_siteconfig.gif','admin_site_configuration_class.php','Site_configuration');

//Orders: just enough to allow administrating listings
menu_category::addMenuCategory('orders',$parent_key,'Orders','admin_images/menu_trans.gif','','',$head_key);
menu_page::addPage('orders_list_items','orders','Manage Items','menu_trans.gif','items.php','OrderItemManagement');
menu_page::addPage('orders_list_items_item_details','orders_list_items','View Order Item','menu_trans.gif','items.php','OrderItemManagement','sub_page');
menu_page::addPage('orders_list_items_item_unlock','orders_list_items','View Order Item','menu_trans.gif','items.php','OrderItemManagement','sub_page');

//Pages Management, for text editing
include 'sections/pages_management.php';
//do we need Addons? Maybe just for text management?
include 'sections/addons.php';

//full Design section (for now?)
include 'sections/design.php';

//allow multiple languages?
//include 'sections/languages.php';

//Admin Tools: View Expired Ads, Change Password, License Info
menu_category::addMenuCategory('admin_tools_settings',$parent_key,'Admin Tools &amp; Settings','admin_images/menu_admintools.gif','','',$head_key);
menu_page::addPage('admin_tools_view_ads','admin_tools_settings','View Expired Ads','menu_admintools.gif','admin_classauction_tools.php','Admin_classauction_tools');
menu_page::addPage('admin_tools_password','admin_tools_settings','Change Password','menu_admintools.gif','admin_authentication_class.php','Admin_auth');
menu_page::addPage('admin_tools_license','admin_tools_settings','License Info','menu_admintools.gif','admin_classauction_tools.php','Admin_classauction_tools');

//let addons know we're using this menu
$addon = geoAddon::getInstance();
$addon->initAdmin('adplotter_admin');