<?php
//module_display_category_quick_navigation.php	
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

if (geoPC::is_print() && $this->get_site_setting('disableAllBrowsing')) {
	//browsing disabled, do not show module contents
	return;
}

if (!isset($page->quick_nav_id)) {
	$page->quick_nav_id = 0;
} else {
	$page->quick_nav_id++;
}

$cat = ($show_module['module_display_sub_category_nav_links'])? $show_module['number_of_browsing_columns']: 0;
$tpl_vars = array();

$tpl_vars['options'] = $page->get_category_dropdown('category_quick_nav',0,0,0,$page->messages[500819],3,$cat);
$tpl_vars['nav_id'] = $page->quick_nav_id;

$view->setModuleTpl($show_module['module_replace_tag'],'index')
	->setModuleVar($show_module['module_replace_tag'],$tpl_vars);

