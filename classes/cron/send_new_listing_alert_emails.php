<?php
//send_new_listing_alert_emails.php
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
## ##    7.1.2-35-gcf98ad3
## 
##################################

if (!defined('GEO_CRON_RUN')){
	die('NO ACCESS');
}

require_once CLASSES_DIR.'site_class.php';
require_once CLASSES_DIR.'user_management_ad_filters.php';
$filters = new User_management_ad_filters();

//get a list of users to check filters for (users who have at least one filter and haven't been checked recently)
$sql = "SELECT `id` FROM ".geoTables::userdata_table." 
		WHERE (`new_listing_alert_last_sent` + `new_listing_alert_gap`) <= ?
		AND `id` IN (
			SELECT DISTINCT `user_id` FROM ".geoTables::ad_filter_table."
		)";
$time = geoUtil::time(); // let's save time by only getting the time one time
$this->log('master query is: '.$sql, __LINE__);
$this->log('using time: '.$time, __LINE__);
$result = $this->db->Execute($sql, array($time));
foreach($result as $u) {
	$this->log('queried user: '.$u['id'], __LINE__);
	$user = geoUser::getUser($u['id']);
	$filters->checkUserFilters($user->id);
	$user->new_listing_alert_last_sent = $time;
}

$this->log('task complete', __LINE__);
return true;
