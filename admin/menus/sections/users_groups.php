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
## ##    6.0.7-17-g938673d
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- USERS / USER GROUPS
menu_category::addMenuCategory('users',$parent_key,'Users / User Groups','admin_images/menu_users.gif','','',$head_key);

	if (geoPC::is_ent() || geoPC::is_premier() || geoPC::is_basic()){
		menu_page::addPage('users_groups','users','User Groups Home','menu_users.gif','admin_group_management_class.php','Group_management');
			menu_page::addPage('users_group_move','users_groups','Move Users','menu_users.gif','admin_group_management_class.php','Group_management','sub_page');
			menu_page::addPage('users_group_edit','users_groups','Edit Group','menu_users.gif','admin_group_management_class.php','Group_management','sub_page');
			menu_page::addPage('users_group_delete','users_groups','Delete Group','menu_users.gif','admin_group_management_class.php','Group_management','sub_page');
			menu_page::addPage('users_group_price_edit','users_groups','Edit Price Plan','menu_users.gif','admin_group_management_class.php','Group_management','sub_page');
			
			menu_page::addPage('users_group_registration','users_groups','Edit Registration Specifics','menu_users.gif','admin_group_management_class.php','Group_management','sub_page');
			menu_page::addPage('users_group_questions','users_groups','Edit Group Questions','menu_users.gif','admin_group_questions_class.php','Admin_category_questions','sub_page');	
			menu_page::addPage('users_group_questions_new','users_groups','New Group Question','menu_users.gif','admin_group_questions_class.php','Admin_category_questions','sub_page');
			menu_page::addPage('users_group_questions_edit','users_groups','Edit Group Question','menu_users.gif','admin_group_questions_class.php','Admin_category_questions','sub_page');
			menu_page::addPage('users_group_questions_delete','users_groups','Delete Group Question','menu_users.gif','admin_group_questions_class.php','Admin_category_questions','sub_page');
		
		
	}

	menu_page::addPage('users_list','users','List Users','menu_users.gif','admin_user_management_class.php','Admin_user_management');
		menu_page::addPage('users_view','users_list','User Data Display','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_remove','users_list','Remove User','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_edit','users_list','Edit User Data','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
			menu_page::addPage('users_edit_filters','users_edit','Edit User Filter','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_add','users_list','Add User','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_subs_change','users_list','Edit User Subscriptions','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_subs_delete','users_list','User Subscriptions Delete','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_restart_ad','users_list','Restart Ad','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_view_ad','users_list','View Ad','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
		menu_page::addPage('users_max_photos','users_list','Max Photos','menu_users.gif','admin_user_management_class.php','Admin_user_management','sub_page');
	
	menu_page::addPage('users_search','users','Search Users','menu_users.gif','admin_user_management_class.php','Admin_user_management');
	
	if (geoPC::is_ent()) {
		menu_page::addPage('user_export','users','Export Users','menu_users.gif','admin_user_export.php','user_export');
	}