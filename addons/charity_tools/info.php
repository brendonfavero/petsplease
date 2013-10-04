<?php
//addons/charity_tools/info.php
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
## ##    7.2.4-13-gbf3c85e
## 
##################################

# Charity Tools
class addon_charity_tools_info //disabled, is not ready to be released yet.
{
	public $name = 'charity_tools';
	public $title = 'Charity Tools';
	public $version = '1.0.2';
	public $core_version_minimum = '7.2.0';
	public $description = 'Tools to assist in running a charity-based site';
	public $author = 'Geodesic Solutions LLC.';
	public $icon_image = '';
	public $auth_tag = 'geo_addons';
	public $author_url = 'http://geodesicsolutions.com';
	
	public $core_events = array(
		'Admin_site_display_user_data',
		'Admin_user_management_update_users_view',
		'add_listing_icons',
		'use_listing_icons'
	);
	
}

/**
 * Charity Tools Changelog
 *  
 * 1.0.2 - Geo 7.2.5
 *  - Corrected some conflicts with the Attention Getters addon
 *  
 * 1.0.1 - Geo 7.2.2
 *  - Corrected an issue that could make Charitable Badge selections not appear
 *  
 * 1.0.0 - Geo 7.2.0 
 *  - Addon Created
 * 
 */

