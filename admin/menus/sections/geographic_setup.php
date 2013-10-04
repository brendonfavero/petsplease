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
## ##    6.0.7-206-g7af9ac8
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- GEOGRAPHIC SETUP
menu_category::addMenuCategory('geographic_setup',$parent_key,'Geographic Setup','admin_images/menu_geo.gif','','',$head_key);
	
	menu_page::addPage('regions','geographic_setup','Regions','menu_geo.gif','regions.php','RegionsManagement');
		menu_page::addPage('region_create','regions','Add Region','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
		menu_page::addPage('region_create_bulk','regions','Bulk Add Region','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
		menu_page::addPage('region_edit','regions','Edit Region','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
		menu_page::addPage('region_edit_bulk','regions','Mass Edit Regions','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
		menu_page::addPage('region_move','regions','Move Regions','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
		menu_page::addPage('region_delete','regions','Delete Regions','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
		menu_page::addPage('region_enabled','regions','Enable/Disable Region','menu_geo.gif','regions.php','RegionsManagement', 'sub_page');
	
	menu_page::addPage('region_levels','geographic_setup','Levels','menu_geo.gif','regions.php','RegionsManagement');