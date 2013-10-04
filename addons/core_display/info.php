<?php
//addons/core_display/info.php
/**************************************************************************
Addon Created by Geodesic Solutions, LLC
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/

##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.1.2-43-g32ae2d3
## 
##################################

# Storefront Addon
class addon_core_display_info {
	
	public $name = 'core_display';
	public $version = '1.0.1';
	public $core_version_minimum = '7.1.0';
	public $title = '<strong style="font-style: italic;">Core</strong> Display';
	public $author = "Geodesic Solutions LLC.";
	public $description = 'This addon is used internally to easily display content on multiple pages';
	public $auth_tag = 'geo_addons';
	
	//public $icon_image = 'images/menu_storefront.gif';
	public $author_url = 'http://geodesicsolutions.com';
	
	public $tags = array (
		'display_browsing_filters',
		'browsing_featured_gallery',
	);
	public $core_tags = array (
		'browsing_before_listings_column',
		'browsing_before_listings',
		);
	
	public $core_events = array(
		'process_browsing_filters',
		'geoFields_getDefaultLocations'
	);

}

/**
 * Core Display Changelog
 * 
 * 1.0.1 - Geo 7.1.3
 *  - Fixed problem where counts reflected category that the browsing filters were
 *    being used from instead of the actual browsing category.  See bug 766
 * 
 * 1.0.0 - Geo 7.1.0
 *  - Addon Created
 *  - Used to allow display of Browsing Filters from multiple core pages
 * 
 */

