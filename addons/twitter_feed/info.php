<?php 
//addons/twitter_feed/info.php
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
## ##    7.2beta3-72-g9718307
## 
##################################

# Twitter Feed
class addon_twitter_feed_info
{
	public $name = 'twitter_feed';
	public $title = 'Twitter Feed';
	public $version = '1.0.8';
	public $core_version_minimum = '7.1.0';
	public $description = 'Allows for the insertion of a widget showing a user\'s latest Tweets in his listings.';
	public $author = 'Geodesic Solutions LLC.';
	public $auth_tag = 'geo_addons';
	public $author_url = 'http://geodesicsolutions.com';
	
	public $tags = array('show_feed');
	public $listing_tags = array ('show_feed');
}

/**
 * Twitter Feed Changelog
 * 
 * 1.0.8 - Geo 7.1.3
 *  - Change to make sure stuff is done correctly when copying a listing
 *  
 * 1.0.7 - Geo 7.1.0
 *  - Updated to work with {listing} tags, and load template internally
 * 
 * 1.0.6 - Geo 7.0.1
 * - Added new parameter to getDisplayDetails() in order items
 * 
 * 1.0.5 - Geo 7.0.0
 *  - Compatibility changes for 7.0 licensing
 *  
 * 1.0.4 - Geo 6.0.2
 *  - Update to reflect changes made by Twitter
 *  - Allow twitter feed to appear in the Storefront version of listing display pages, as well
 * 
 * 1.0.3 - Geo 6.0.0
 *  - Changes for Smarty 3.0
 *  - Changes for leased license
 *  - Order item changes for 6.0
 *  - Added color swatches to Theme section of admin settings page
 *  
 * 1.0.2 - Geo 5.1.5
 *  - Fixed a bug that prevented the twitter feed from appearing in IE7
 * 
 * 1.0.1 - Geo 5.1.3
 *  - Fixed a bug that caused the Alternate display method to not display
 * 
 * 1.0.0 - Geo 5.1.3 
 *  - Addon Created
 * 
 */

