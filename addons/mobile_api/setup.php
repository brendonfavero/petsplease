<?php
//addons/mobile_api/setup.php
/**************************************************************************
Addon Created by Geodesic Solutions, LLC
Copyright (c) 2001-2014 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## 
##    7.3rc1-38-g306cce4
## 
##################################

# Mobile API

require_once ADDON_DIR . 'mobile_api/info.php';

class addon_mobile_api_setup extends addon_mobile_api_info
{
	public function install()
	{
		$sqls[] = "CREATE TABLE IF NOT EXISTS `geodesic_addon_mobile_api_flags` (
				  `listing_id` int(11) NOT NULL,
				  `time` int(11) NOT NULL)";
		
		$db = DataAccess::getInstance();
		foreach($sqls as $sql) {
			$db->Execute($sql);
		}
		
		return true;
	}
	
	public function upgrade($old_version)
	{
		$db = DataAccess::getInstance();
		if(version_compare($old_version, '1.0.2', '<=')) {
			if(!$db->Execute("CREATE TABLE IF NOT EXISTS `geodesic_addon_mobile_api_flags` (
				  `listing_id` int(11) NOT NULL,
				  `time` int(11) NOT NULL)")) {
				return false;
			}
		}
		return true;
	}
	
	public function uninstall()
	{
		$sqls[] = "DROP TABLE IF EXISTS `geodesic_addon_mobile_api_flags`";
		
		$db = DataAccess::getInstance();
		foreach($sqls as $sql) {
			$db->Execute($sql);
		}
		
		return true;
	}
}