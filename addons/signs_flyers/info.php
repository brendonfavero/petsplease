<?php
//addons/signs_flyers/info.php
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
## ##    7.1.2-10-gbfc2422
## 
##################################

# Signs & Flyers Addon

class addon_signs_flyers_info{
	//The following are required variables
	var $name = 'signs_flyers';
	var $version = '2.5.1';
	var $core_version_minimum = '7.1.0';
	var $title = 'Signs &amp; Flyers';
	var $author = "Geodesic Solutions LLC.";
	var $icon_image = 'menu_signs_flyers.gif';
	var $description = 'Adds the ability to create signs and flyers for a listed product.';
	//used in referencing tags, and maybe other uses in the future.
	var $auth_tag = 'geo_addons';

	
	var $upgrade_url = 'http://geodesicsolutions.com/component/content/article/53-added-value/68-signs-flyers.html?directory=64'; //[ Check For Upgrades ] link
	var $author_url = 'http://geodesicsolutions.com'; //[ Author's Site ] link
	var $info_url = 'http://geodesicsolutions.com/component/content/article/53-added-value/68-signs-flyers.html?directory=64'; //[ More Info ] link
	var $tag_info_url = 'index.php?mc=addon_example_admin&page=addon_signs_flyers_tag_help';
	
	var $core_events = array('my_account_links_add_link');

}


/*
 * CHANGELOG
 * 
 * v2.5.1 - Geo 7.1.3
 *  - fix missing pre/postcurrency values on forms
 * 
 * v2.5.0 - Geo 6.0.0
 *  - Changes so that it doesn't hard-code geo_templates folder.
 *  - fixed fatal error in form templates
 *  - add QR codes
 *  
 * v2.4.3 - Geo 5.0.3
 *  - Changes for updated license system
 *  
 * v2.4.2 - Geo 5.0
 *  - Update setup script to add/remove default images for signs and flyers
 * 
 * v2.4.1 - Geo 5.0.0
 *  - requires 5.0 or higher, ONLY works with file-based now.
 *  - Updated templates for new design
 *  
 * v2.4.0 - Geo 5.0.0
 *  - Build Sign/Flyer pages now use smarty templates
 *  
 * v2.3.2 - Geo 4.1.3
 *  - Display of optional fields takes into account fields that "add cost".
 *  
 * v2.3.1 - Geo 4.0.7
 *  - Fix applied for addon license checks
 *  - Fixed thing where it would get geoSite when not needed in util.
 *  
 * v2.3.0 - Geo 4.0.6
 *  - fixed some malformed HTML on the signs/flyers list page
 *  - signs and flyers will now display when using File-based templates
 *  - Added license checks
 * 
 * v2.2.2 - Geo 4.0.4
 *  - changed structure of My Account Links hook
 *  - split list of possible signs/flyers into multiple pages, to prevent OOM issues for users with lots of listings
 * 
 * v2.2.1 - Geo 4.0.0
 *  - Fixed a bug where images ignored the Admin size settings
 *  - The full-size lead image, instead of its thumbnail, is now used when creating a sign/flyer
 * 
 * v2.2.0 - Geo 4.0.0RC11
 * - Added core event to add link to my_account_links module (which is also new as of RC11)
 * - Fixed a fatal error in signs/flyers list
 * - Changelog creation
 */
	

