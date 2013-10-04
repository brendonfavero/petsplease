<?php 
//module_display_featured_text_link.php	
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


if(geoPC::is_ent() || geoPC::is_premier() || geoPC::is_basic()) {
	$tpl_vars = array (
		'href' => $page->configuration_data['classifieds_file_name']."?a=9&amp;b=".$page->site_category,
		'class' => 'featured_text_link_text',
		'label' => $page->messages[1061]
	);
	
	$view->setModuleTpl($show_module['module_replace_tag'],'index')
		->setModuleVar($show_module['module_replace_tag'],$tpl_vars);
}
