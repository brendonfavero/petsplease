<?php
//addons/pedigree_tree/info.php
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

# Pedigree Tree
class addon_pedigree_tree_info
{
	public $name = 'pedigree_tree';
	public $title = 'Pedigree Tree';
	public $version = '2.0.6';
	public $core_version_minimum = '7.1.0';
	public $description = 'This addon collects pedigree tree information during listing placement, and adds a new tag used on listing details, that displays pedigree tree information collected.';
	public $author = 'Geodesic Solutions LLC.';
	public $icon_image = 'menu_pedigree.gif';
	public $auth_tag = 'geo_addons';
	public $author_url = 'http://geodesicsolutions.com';
	public $info_url = 'http://geodesicsolutions.com/component/content/article/50-browsing-enhancements/79-pedigrees.html?directory=64';
	public $tags = array ('listing_tree');
	public $listing_tags = array ('listing_tree');
	public $core_events = array (
		'Search_classifieds_generate_query',
		'Search_classifieds_search_form',
		'geoFields_getDefaultFields'
	);
	//used in other parts of addon
	const LISTING_TREE = "`geodesic_addon_pedigree_tree_listings`";
}

/**
 * Pedigree Tree Changelog
 * 
 * 2.0.6 - Geo 7.1.3
 *  - Change to make sure stuff is done correctly when copying a listing
 *  
 * 2.0.5 - Geo 7.1.0
 *  - Updated the tag to use listing tags for 7.1, and load internal template
 *  
 * 2.0.4 - Geo 7.0.1
 * - Fixed search to use proper hooks.
 * - Added new parameter to getDisplayDetails() in order item
 * 
 * 2.0.3 - Geo 7.0.0
 *  - Changes for 7.0 license compatibility
 *  
 * 2.0.2 - Geo 6.0.0
 *  - Changes for Smarty 3.0
 *  - Changes to work with leased license
 *  - Don't use ucwords modifier in smarty templates, use capitalize instead.
 *  - Changes to order item for 6.0
 *  
 * 2.0.1 - Geo 5.2.0
 *  - Changes for using cart in admin panel
 *  
 * 2.0.0 - Geo 5.0.1
 *  - Changed the style for when # generations is 4 or less
 *  - Added "icon sets" for icon to show before sire/dam
 *  - Fixed problem where preview listing didn't show pedigree tree properly
 *  - Made it copy pedigree tree data when renewing/copying listing.
 *  - Made it use fields system from 5.0
 *  - Added ability to "preserve uppercase"
 *  
 * 1.1.0 - Geo 5.0.0
 *  - Changes to design to match with Geo 4.2 design
 * 
 * 1.0.0 - Geo 4.1.2 
 *  - Addon Created
 * 
 */

