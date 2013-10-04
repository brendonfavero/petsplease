<?php
//addons/exporter/info.php
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
## ##    7.2.2-6-g4661b5a
## 
##################################

/**
 * Exporter Addon
 * 
 * Allows admins to export listings, and possibly (at a later time) users, and 
 * other data.
 */

class addon_exporter_info{
	public $name = 'exporter';
	public $version = '3.1.5';
	public $core_version_minimum = '7.0.0';
	public $title = 'Listing Exporter';
	public $author = "Geodesic Solutions LLC.";
	public $icon_image = 'menu_listing_export.gif';
	public $description = "Allows admins to export listings, and possibly (at a later time) users, and other data.";
	public $auth_tag = 'geo_addons';
	public $upgrade_url = 'http://geodesicsolutions.com/component/content/article/52-importing-exporting/75-listing-export.html?directory=64'; //[ Check For Upgrades ] link
	public $author_url = 'http://geodesicsolutions.com'; //[ Author's Site ] link
	public $info_url = 'http://geodesicsolutions.com/component/content/article/52-importing-exporting/75-listing-export.html?directory=64'; //[ More Info ] link
	
	const SETTINGS_TABLE = '`geodesic_addon_exporter_settings`';
}


/**
 * Listing Exporter Changelog
 * 
 * v3.1.5 - Geo 7.2.3
 *  - Fixed broken buttons on save/load settings form
 *  - Added Duration as an exportable field
 * 
 * v3.1.4 - Geo 7.0.4
 *  - Added High Bidder ID to list of exportable fields
 *  - Fixed generating the feed, was overly complex and was not working
 * 
 * v3.1.3 - Geo 7.0.0
 *  - Changes for 7.0 license compatibility
 *  - Changes to recognize new mapping_location field 
 *  
 * v3.1.2 - Geo 6.0.2
 *  - Fixed a bug that caused no results to return if no date ranges were entered
 * 
 * v3.1.1 - Geo 6.0.0
 *  - Changes for Smarty 3.0
 *  - Changes for how listing feed class works now
 *  - Use new geoTabs JS to let it do work of changing tabs
 *  - Make it not show data not actually requested (id, title, description, etc.)
 *  - Changes for leased license
 *  - Fix to use ob_end_clean() to prevent headers from being sent early when exporting
 *  
 * v3.1.0 - Geo 5.2.0
 *  - Cleaned up settings HTML some
 *  - Added load/save ability for export settings
 *  - Added ability to save exports to the server
 *  
 * v3.0.0 - Geo 5.1.2
 *  - First version using changelog block
 *  - Re-wrote interface to remove dependence on YUI.
 *  - Re-wrote entire back-end to use geoListingFeed class and Smarty template files
 *  - Requires at least 5.1.2 since it uses stuff only available in that version
 *  - Removed (or rather, didn't re-implement) Save/Restore functionality, plan
 *    to re-implement much more rhobust system in future release.
 */


