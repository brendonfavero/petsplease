<?php 
//extra_pages.php
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

class extra_page extends geoSite 
{
	
	function extra_page($db, $page_id, $language_id, $user_id, $product_configuration)
	{
		if(ereg("^[0-9]{3}$", $page_id)) {
			$this->page_id = $page_id;
		}

		parent::__construct();
	}

	function setup_filters($filter_id, $state_filter, $zip_filter, $zip_distance_filter)
	{
		$this->state_filter = $state_filter;
		$this->zip_filter = $zip_filter;
		$this->zip_filter_distance = $zip_distance_filter;
		$this->filter_id = $filter_id;
	}

	function build_extra_page()
	{
		//let display page know to treat this as an extra page
		$this->using_extra = true;
		//set the view's language id
		geoView::getInstance()->setLanguage($this->language_id);
		
		return true;
	}

	function display_extra_page()
	{
		// Build the body of the extra page
		if(!$this->build_extra_page()) {
			return false;
		}

		// Do anything thats needed before here

		return $this->display_page();
	}
}
