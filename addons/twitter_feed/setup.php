<?php
//addons/storefront/setup.php
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

# storefront Addon

require_once ADDON_DIR . 'twitter_feed/info.php';

class addon_twitter_feed_setup extends addon_twitter_feed_info
{
	public function install () 
	{
		$db = DataAccess::getInstance();
		$sql = "CREATE TABLE IF NOT EXISTS `geodesic_addon_twitter_feed_usernames` (
			listing_id int(1) NOT NULL,
			twitter_name varchar(30) NOT NULL,
			active int(1) NOT NULL default 0,
			PRIMARY KEY(listing_id)
		)";
		$result = $db->Execute($sql);
		if(!$result) {
			return false;
		}
		
		//default settings
		$reg = geoAddon::getRegistry('twitter_feed',true);
		$config = array(
			'behavior' => 'default',
			'interval' => 4,
			'rpp' => 10,
			'defaultuser' => '',
			'hashtags' => 1,
			'timestamps' => 1,
			'avatars' => 1,
			'width' => 250,
			'autowidth' => 1,
			'height' => 300,
			'shell' => '4174A6',
			'heading' => 'FFFFFF',
			'background' => 'F7F7F7',
			'text' => '000000',
			'links' => '4AED05',
		);
		$reg->config = $config;
		$reg->save();
		return true;
	}
	
	public function uninstall ()
	{
		$db = DataAccess::getInstance();
		$sql = "DROP TABLE `geodesic_addon_twitter_feed_usernames`";
		$result = $db->Execute($sql);
		return ($result) ? true : false;
	}
	
	
}