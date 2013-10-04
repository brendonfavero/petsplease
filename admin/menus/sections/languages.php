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
## ##    6.0.7-54-g747ba87
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- LANGUAGES

menu_category::addMenuCategory('languages',$parent_key,'Languages','admin_images/menu_lang.gif','','',$head_key);
	
	menu_page::addPage('languages_home','languages','Languages Home','menu_lang.gif','admin_text_management_class.php','Text_management');		
		menu_page::addPage('languages_edit','languages_home','Edit Language','menu_lang.gif','admin_text_management_class.php','Text_management','sub_page');
		menu_page::addPage('languages_delete','languages_home','Delete Language','menu_lang.gif','admin_text_management_class.php','Text_management','sub_page');
		menu_page::addPage('languages_import','languages_home','Import Language','menu_lang.gif','admin_text_management_class.php','Text_management','sub_page');
		menu_page::addPage('languages_export','languages_home','Export Language','menu_lang.gif','admin_text_management_class.php','Text_management','sub_page');
	
	menu_page::addPage('languages_new','languages','Add New Language','menu_lang.gif','admin_text_management_class.php','Text_management');
