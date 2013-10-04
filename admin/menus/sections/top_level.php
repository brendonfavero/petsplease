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

// ----------------- Top-Level Pages
//Put stuff like home page and all in this "hidden" category, basically
//anything that is linked to directly, and you want the breadcrumb to be
//such that that page is the only thing there, with no parent categories displayed.


menu_category::addMenuCategory('top_level',0,'','','','');

	menu_page::addPage('site_map','top_level','Admin Map','','map.php','geoAdminMap','sub_page');
	
	menu_page::addPage('home','top_level','Admin Home','menu_home.gif','home.php','geoAdminHome','sub_page');

	menu_page::addPage('quick_find','top_level','Quick Find','menu_home.gif','home.php','geoAdminHome','sub_page');