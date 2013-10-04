<?php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.1.2-38-gb5497b1
## 
##################################
trigger_error('DEBUG STATS: app_bottom.php');

//See of the geoCart has been loaded
if (defined('geoCart_LOADED') && !defined('geoCart_skipSave')){
	//cart has been loaded, make sure the settings are saved in it.
	$cart = geoCart::getInstance();
	$cart->save();
}

//serialize the session
$session = geoSession::getInstance();
$session->serialize();

//Un-comment following line to force output of messages.
//trigger_error('FLUSH MESSAGES');

//Let any addons interested to know, that it is app_bottom
geoAddon::triggerUpdate('app_bottom',0);

//write the cache files
geoCache::writeCache();

//close the db connection.
$db = DataAccess::getInstance();
$db->Close();

//display peak memory usage in human readable format
$peak = geoNumber::filesizeFormat(memory_get_peak_usage());
trigger_error('DEBUG STATS: End of app, peak memory usage: '.$peak);
