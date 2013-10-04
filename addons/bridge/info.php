<?php
//addons/example/info.php
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
## ##    7.2.3-2-gefe5b87
## 
##################################

# Bridge

class addon_bridge_info{
	var $name = 'bridge';
	var $version = '2.3.2';
	var $core_version_minimum = '7.0.0';
	var $title = 'Bridge';
	var $author = "Geodesic Solutions LLC.";
	var $icon_image = 'menu_bridge.gif';
	var $description = 'This addon gives the ability to link the users across different software applications.';
	var $auth_tag = 'geo_addons';
	var $info_url = 'http://geodesicsolutions.com/component/content/article/51-third-party-integrations/63-geoproducts-bridge.html?directory=64';
	var $tags = array ();
	var $core_events = array (
		'session_create',
		'session_touch',
		'session_login',
		'session_logout',
		'user_edit',
		'user_register',
		'user_remove');
	var $exclusive = array ();//at this point, doesn't matter if it's exclusive or not..
}

/**
 * Bridge Changelog
 * 
 * 2.3.2 - GeoCore 7.2.4
 *  - Fixed issue with vBulletin 4.2.1 so it works better.
 *  
 * 2.3.1 - GeoCore 7.0.2
 *  - Changed so that vb bridge works with https cookies for login
 * 
 * 2.3.0 - GeoCore 7.0.0
 *  - Changes so that the bridge addon for purchase only includes the bridges
 *  
 * 2.2.3 - Geo 6.0.0 (released for 5.2.4)
 *  - Fixed a Fatal Error that could occur when syncing users to a newer vBulletin install (confirmed for vB 4.2.1)
 *  - Fixed problem with logout sync for Geo bridge to other Geo installations
 *  - Fixed problem with registering a user using a registration code in geo_all
 * 
 * 2.2.2 - Geo 5.1.5
 *  - Fixed a Fatal Error that could occur when syncing a vbulletin installation while logged into the front end
 * 
 * 2.2.1 - Geo 5.1.1
 *  - Fixed a Fatal Error that could occur when syncing users between two Geo installs
 * 
 * 2.2.0 - Geo 5.0.0
 *  - Changes to match new 5.0 admin design
 *  
 * 2.1.0 - Geo 4.1.3
 *  - Full support for bridged logins/logouts in Joomla! v1.5
 * 
 * 2.0.2 - Geo 4.1.2
 *  - Added require_once to top of all bridges to make sure bridge util file is included
 *  - mysqli_real_escape_string() isn't supported on all hosts, so replaced it with the more generic DataAccess::qstr()
 *  
 * 2.0.1 - Geo 4.0.4
 *  - First version using changelog block for Bridge addon
 *  - Changed log to send e-mail using geoEmail::sendMail() instead of old
 *    method.
 * 
 */

