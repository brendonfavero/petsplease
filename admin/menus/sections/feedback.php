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
## ##    6.0.7-33-g97c7eec
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- FEEDBACK
if (geoMaster::is('auctions')) {
	menu_category::addMenuCategory('feedback',$parent_key,'Feedback','admin_images/menu_feedback.gif','','',$head_key);
		
		menu_page::addPage('GlobalSettings','feedback','Feedback Management','menu_feedback.gif','Admin_Feedback.class.php','Admin_Feedback');
		
		menu_page::addPage('IncrementSettings','feedback','Edit Feedback Increments','menu_feedback.gif','Admin_Feedback.class.php','Admin_Feedback');
		
		menu_page::addPage('feedback_show','GlobalSettings','Feedback','menu_feedback.gif','Admin_Feedback.class.php','Admin_Feedback','sub_page');
}