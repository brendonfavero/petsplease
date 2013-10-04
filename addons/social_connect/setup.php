<?php 
//addons/social_connect/admin.php
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

# Facebook Connect

require_once ADDON_DIR . 'social_connect/info.php';

class addon_social_connect_setup extends addon_social_connect_info
{
	public function install ()
	{
		$db = DataAccess::getInstance();
		
		$db->Execute("ALTER TABLE `geodesic_logins` ADD `facebook_id` VARCHAR( 16 ) NOT NULL ");
		$db->Execute("ALTER TABLE `geodesic_logins` ADD INDEX `facebook_id` ( `facebook_id` ) ");
		
		//facebook reveal to userdata
		$db->Execute("ALTER TABLE `geodesic_userdata` ADD `facebook_reveal` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'Yes'");
		
		return true;
	}
	
	public function uninstall ()
	{
		$db = DataAccess::getInstance();
		
		$db->Execute("ALTER TABLE `geodesic_logins` DROP `facebook_id` ");
		
		return true;
	}
}