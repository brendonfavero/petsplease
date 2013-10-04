<?php
//addons/google_maps/info.php
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
## ##    7.2beta3-83-g806659c
## 
##################################

# Google maps Addon

class addon_google_maps_info
{
	public $name = 'google_maps';
	public $version = '2.0.1';
	public $core_version_minimum = '7.2.0';
	public $title = 'Google Maps';
	public $author = "Geodesic Solutions LLC.";
	public $description = 'The Google maps addons allows you the ability to use maps on your pages powered by maps.google.com.';
	public $auth_tag = 'geo_addons';
	public $icon_image = 'menu_google.gif';
	public $upgrade_url = 'http://geodesicsolutions.com/component/content/article/50-browsing-enhancements/78-google-maps.html?directory=64';
	public $author_url = 'http://geodesicsolutions.com';
	public $info_url = 'http://geodesicsolutions.com/component/content/article/50-browsing-enhancements/78-google-maps.html?directory=64';
	public $tags = array (
		'listing_map'
	);
	public $listing_tags = array (
		//tag is smart!
		'listing_map',
		);
	
	public $core_events = array (
		'notify_display_page'
	);
}
/**
 * Changelog for Google Maps
 * 
 * v2.0.1 - Geo 7.2.0
 *  - Add ability to specify % for width / height of map (Bug 833)
 *  
 * v2.0.0 - Geo 7.2.0
 *  - Updated to use Google Maps API V3
 *  - Removed the old tag for user_map, it's been deprecated long enough
 * 
 * v1.1.3 - REQUIRES Geo 7.1.3
 *  - Improvements for {listing} tags so that it doesn't screw up SSL
 * 
 * v1.1.2 - Geo 7.1.0
 *  - Changes to make the google maps work with the new {listing} tags
 *  
 * v1.1.1 - Geo 7.0.2
 *  - Point API link to updated instructions
 * 
 * v1.1.0 - Geo 7.0.0
 *  - Change to use new mapping_location field
 * 
 * v1.0.8 - Geo 6.0.0
 *  - Changes for Smarty 3.0
 *  - Use common setting for google API setting, to share with site wide setting
 *  
 * v1.0.7 - Geo 5.1.2
 *  - Fixed a bug that could cause the pointer to appear in the wrong location if an address contained an apostrophe
 *  
 * v1.0.6 - Geo 5.1.0
 *  - Changed template to use escape_js which should be safe to use in trial demos
 *  - Upped the min version to 5.0 since that is version escape_js was added.
 *  
 * v1.0.5 - Geo 5.0.0
 *  - Made it attempt to utf8-encode location to get the coords for it.
 * 
 * v1.0.4 - Geo 4.1.3
 *  - Changes to JS used "inline" to use Event.observe() instead of window.onload
 *    to make it more compatible with other onload events.
 *    
 * v1.0.3 - Geo 4.1.2
 *  - Made change to get proper encoding from google maps (utf-8), so it works
 *    better with international addresses.
 *  
 * v1.0.2 - Geo 4.1.1
 *  - Fixed location generation to omit country and/or state if either is set
 *    to "none"
 *  
 * v1.0.1 - Geo 4.0.9
 *  - Addon code cleaned up, removed stuff not used.
 *  - Changed to use addon registry instead of it's own table to save settings.
 *  
 * v 1.0.0 - Geo 4.0.0
 *  - Addon created.
 * 
 */




