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

// ----------------- ADDON MANAGEMENT
menu_category::addMenuCategory('addon_management',$parent_key,'Addons','admin_images/menu_addons.gif','','',$head_key);
	
	menu_page::addPage('addon_tools','addon_management','Manage Addons','menu_addons.gif','addon_manage.php','Addon_Manage');
		menu_page::addPage('edit_addon_text','addon_tools','Edit Text','menu_addons.gif','addon_manage.php','Addon_Manage', 'sub-page');