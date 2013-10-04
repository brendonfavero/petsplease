<?php 
//addons/sharing/info.php
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
## ##    7.2.2-7-g5d9fb51
## 
##################################

# Sharing
class addon_sharing_info
{
	public $name = 'sharing';
	public $title = 'Sharing';
	public $version = '1.2.3';
	public $core_version_minimum = '7.2.0';
	public $description = 'Provides a simple interface for users to share their listings across various websites.';
	public $author = 'Geodesic Solutions LLC.';
	public $auth_tag = 'geo_addons';
	public $author_url = 'http://geodesicsolutions.com';
	
	public $core_events = array(
		'my_account_links_add_link',
		'admin_display_page_attachments_edit_end',
		//'listing_display_add_action_button',
		'current_listings_add_action_button',
		'notify_display_page',
	);
	
	public $core_tags = array (
		'listing_social_buttons',
		);
	
	public $pages = array (
		'main',
		'craigslist_output',
	);
	
	public $pages_info = array (
		'main' => array ('main_page' => 'basic_page.tpl', 'title' => 'Sharing Main Page'),
		'craigslist_output' => array ('main_page' => 'craigslist_default.tpl', 'title' => 'Craigslist Output Templates'),
	);
}

/**
 * Sharing Changelog
 * 
 * 1.2.3 - Geo 7.2.3
 *  - Corrected the "No Listings" page not appearing when it should
 * 
 * 1.2.2 - Geo 7.2.0
 * - Use rewritten URLs in Sharing tools, where appropriate/available
 * 
 * 1.2.1 - Geo 7.1.3
 * - strip HTML from descriptions sent to facebook
 * - fix admin method unloader for Sharing page
 * 
 * 1.2.0 - Geo 7.1.0
 *  - No more listing_display_add_action_button, using tag of listing_social_button instead
 *  - improved w3c compliance
 *  - Added admin switches to disable individual networks
 * 
 * 1.1.8 - Geo 7.1beta
 *  - Added og:description tag to help Facebook find the right listing description
 *  - Neutered the Digg files, since digg.com is now defunct as a social network
 * 
 * 1.1.7 - Geo 7.0.3
 *  - Send LinkedIn the un-encoded URL they're expecting
 * 
 * 1.1.6 - Geo 7.0.2
 *  - Filter description sent to Pinterest remove encoding and html tags
 * 
 * 1.1.5 - Geo 7.0.1
 *  - Added LinkedIn
 *  - Fixed Reddit shortlinks not having listing URL
 * 
 * 1.1.4 - Geo 7.0.0
 *  - Compatibility changes for 7.0 licensing
 *  - Added Pinterest "Pin It" button to listing display
 *  
 * 1.1.3 - Geo 6.0.4
 * - Fixed a bug that caused Craigslist sharing to display the incorrect currency type
 * - Removed Google Buzz share method, due to Google dropping the service
 * 
 * 1.1.2 - Geo 6.0.0
 *  - Resolve a template issue that could cause a false-posistive result in Avira antivirus 
 *  - Changes for leased license
 *  - Add Google +1 button to listings
 *  - Add a "close" button to the listing details popup
 *  - Specify which image to use for share methods that include a thumbnail
 *  - Corrected the submission method for Reddit.
 * 
 * 1.1.1 - Geo 5.2.2
 *  - Changes needed for Smarty 3.0
 *  - Ensure Scriptaculous is always loaded where needed
 *  - Update for IE9
 *  - Made to work on smarty 2.* as well, so we can release IE9 changes sooner.
 *  
 * 1.1.0 - Geo 5.2.0
 *  - First public release; added some new features over prerelease build
 * 
 * 1.0.0 - Geo 5.1.3 
 *  - Addon Created
 * 
 */

