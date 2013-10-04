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

// ----------------- CATEGORIES
menu_category::addMenuCategory('categories',$parent_key,'Categories','admin_images/menu_cats.gif','','',$head_key);
	
	menu_page::addPage('categories_setup','categories','Category Setup','menu_cats.gif','admin_categories_class.php','Admin_categories');
		menu_page::addPage('categories_delete','categories_setup','Delete a Main Category','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_edit','categories_setup','Edit Category','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_add','categories_setup','Add New Category','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_durations','categories_setup','Category Specific Durations','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
			menu_page::addPage('categories_durations_delete','categories_durations','Delete Duration','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_questions','categories_setup','Category Questions','menu_cats.gif','admin_category_questions_class.php','Admin_category_questions','sub_page');
			menu_page::addPage('categories_questions_add','categories_questions','New Category Question','menu_cats.gif','admin_category_questions_class.php','Admin_category_questions','sub_page');
			menu_page::addPage('categories_questions_edit','categories_questions','Edit Category Question','menu_cats.gif','admin_category_questions_class.php','Admin_category_questions','sub_page');
			menu_page::addPage('categories_questions_delete','categories_questions','Delete Category Question','menu_cats.gif','admin_category_questions_class.php','Admin_category_questions','sub_page');
		menu_page::addPage('categories_reset_count','categories_setup','Reset Category Count','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_copy_subcats','categories_setup','Copy Category Subcategories','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_copy_questions','categories_setup','Copy Category Specific Questions','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
		menu_page::addPage('categories_templates','categories_setup','Category Specific Templates','menu_cats.gif','admin_categories_class.php','Admin_categories','sub_page');
	
	menu_page::addPage('dropdowns','categories','Pre-Valued Dropdowns','menu_siteconfig.gif','admin_extra_questions.php','admin_extra_questions');