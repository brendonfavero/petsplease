<?php 
//main_classified_level_navigation_1.php
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


if (isset($page->category_configuration->CATEGORY_NAME)){
	$current_category_name = $page->category_configuration->CATEGORY_NAME;
}

$tpl_vars = array();

//get the categories inside of this category

$tpl_vars['parent_category'] = 0;

$tpl_vars['no_subcategory_text'] = $page->messages[1516];

$tpl_vars['column_css'] = 'main_classified_level_navigation';

require MODULES_DIR . 'shared/category_navigation.php';
