<?php
//addons/twitter_feed/tags.php
/**
 * Optional file.  Used for addon tags on the client side.
 * 
 * Remember to rename the class name, replacing "storefront" with
 * the folder name for your addon.
 * 
 * Also see the file php5_files/tags.php (in the package storefront_addon_php5)
 * 
 * @author Geodesic Solutions, LLC
 * @package storefront_addon
 */

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
## ##    9e8cd59
## 
##################################

# storefront Addon

//Tag replacement file, for storefront module. 
//This file needs to contain class: addon_ADDON_NAME_tags
//ADDON_NAME is the same as the folder name for the addon.

/**
 * Expects one function for each tag.  Function name should be the same as 
 * the tag name.  Can also have a constructor if anything needs to be constructed.
 * 
 * @author Geodesic Solutions, LLC
 * @version 9e8cd59
 * @copyright Copyright (c) 2001-2009 Geodesic Solutions, LLC
 * @package storefront_addon
 */
class addon_twitter_feed_tags extends addon_twitter_feed_info
{
	
	public function show_feed ($params, Smarty_Internal_Template $smarty)
	{
		$listingId = (isset($params['listing_id']))? (int)$params['listing_id'] : 0;
		
		if (!$listingId) {
			//allow working as a normal {addon} tag
			$view = geoView::getInstance();
			
			$listingId = (int)$view->classified_id;
		}
		if (!$listingId) {
			//something wrong
			return '';
		}
		
		$reg = geoAddon::getRegistry('twitter_feed');
		$config = $reg->config;
		
		$db = DataAccess::getInstance();
		$sql = "SELECT `twitter_name` FROM `geodesic_addon_twitter_feed_usernames` WHERE active = 1 AND `listing_id` = ?";
		$username = $db->GetOne($sql, array($listingId));
		if (!$username) {
			//this seller did not enter a twitter name. see if there's a site default
			if ($config['defaultuser']) {
				$username = $config['defaultuser'];
			} else {
				//no default -- don't show the widget
				return '';
			}
		}
		
		//check to make sure this user's timeline is public
		//NOTE: this is subject to change on Twitter's whim...might be a better way to do it...
		//NOTE2: there is most certainly a better way to do this using OAuth, but don't want to make site log in all the way to use this
		$check = 'http://twitter.com/'.$username;
		$response = geoPC::urlGetContents($check);
		if ($response && stripos($response, '<link rel="canonical" href="http://twitter.com/">') !== false) {
			//if this user's timeline were public, the canonical address would have the username: http://twitter.com/usernameHere
			//since it does not, this profile is either set to private or doesn't exist
			//in either case, there's nothing to show here.
			return '';
		}
		$tpl_vars = array ();
		
		$tpl_vars['twitterName'] = $username;
		$tpl_vars['config'] = $config;
		
		return geoTemplate::loadInternalTemplate($params, $smarty, 'twitter_feed.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}
	
}