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
## ##    7.3beta3-38-g74b3325
## 
##################################

//Loads the menu for site setup

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- SITE SETUP

menu_category::addMenuCategory('getting_started',$parent_key,'Getting Started','admin_images/menu_siteconfig.gif','','',$head_key);

	menu_page::addPage('checklist','getting_started','Checklist','menu_siteconfig.gif','getting_started.php','adminGettingStarted');
	
	
	