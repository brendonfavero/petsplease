<?php 
//module_title_auctions.php
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
## ##    7.1beta1-1096-g8d9093a
## 
##################################

$display = "";

$debug_title = 0;

trigger_error("DEBUG MODULES: top of title module");

$tpl_vars = array();
$tpl_vars['page_id'] = $page->page_id;

$tpl_vars['addonText'] = geoAddon::triggerDisplay('module_title_add_text');

$tpl_vars['addonTextPre'] = geoAddon::triggerDisplay('module_title_prepend_text');

//it can be customized, like changing order of info and stuff.
switch ($page->page_id) {
	case 1:
		//listing details
		
	
		//find category name
		$tpl_vars['category_name'] = geoCategory::getName($page->site_category, true);
				
		//if this is a classified marked as sold, the "title" view var has the HTML
		//for the sold graphic prepended. Grab title fresh from the DB here to make sure
		//that doesn't happen
		$tpl_vars['titleOnly'] = geoListing::getTitle($view->classified_id);
		break;
		
	case 2:
		//front page of site : use text in the template
		//nothing specific to do in PHP portion...
		break;
		
	case 3:
		//category browsing
		$name = geoCategory::getName($page->site_category);
		$name = is_object($name) ? $name->CATEGORY_NAME : $name;
		$tpl_vars['category_title'] = geoString::fromDB($name);
		break;
		
	case 84:
		//full size image display
		$name = geoCategory::getName($page->site_category);
		$name = is_object($name) ? $name->CATEGORY_NAME : $name;
		$tpl_vars['category_name'] = geoString::fromDB($name);
		
		break;
		
	case 10210:
		//listing tags browsing
		$tpl_vars['listing_tag'] = (isset($_GET['tag']))? geoFilter::cleanListingTag($_GET['tag']) : '';
		
		break;
		
	default:
		//check with addons to see if they have a title for this page
		if (!strlen(trim($tpl_vars['addonText']))) {
			//default behavior, get title text to use
			$sql = "SELECT `title_module_text` FROM geodesic_classifieds_ad_configuration";
			$title_result = $this->Execute($sql);
			if ($title_result) {
				$page_result = $title_result->FetchRow();
				$tpl_vars['text'] = geoString::fromDB($page_result['title_module_text']);
			}
		}
		break;
}

//if something has specified a page number, then use it.  Primarily for category results.
$tpl_vars['page_number'] = ($page->page_result)? $page->page_result: 1;

$view->setModuleTpl($show_module['module_replace_tag'],'index')
	->setModuleVar($show_module['module_replace_tag'], $tpl_vars);

