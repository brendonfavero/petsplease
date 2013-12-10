<?php
//app_top.main.php
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
## 
##    7.0.1-5-g113e20b
## 
##################################

require_once "app_top.common.php";

//Anything that needs to be initiallized, started, or whatever at the beginning needs to go in here.

//error_reporting  (E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);


trigger_error('DEBUG STATS: Before Product Configuration initialized.');
if (!isset($product_configuration) || !is_object($product_configuration)) {
	$product_configuration = geoPC::getInstance();
}

trigger_error('DEBUG STATS: After Product Configuration initialized.');

$session = geoSession::getInstance();

$session->cleanSessions();
$classified_session = $session->initSession();
$user_id = $session->getUserId();
$db = DataAccess::getInstance();
if ($db->get_site_setting('site_on_off')) {
	//get valid IP's
	if ($session->getUserId() != 1 && !geoUtil::isAllowedIp()) {
		header("Location: ".$db->get_site_setting("disable_site_url"));
		include GEO_BASE_DIR . 'app_bottom.php';
		exit;
	}
}


if (isset($_GET['c']) && is_numeric($_GET['c']) && $_GET['c'] > 0 && $db->get_site_setting('noindex_sorted')) {
	//add nofollow meta info if this is a page with sorted results
	geoView::getInstance()->addTop('<meta name="robots" content="noindex,nofollow" />');
}

//see if we should switch to SSL or to NON-SSL
if (!isset($_GET['no_ssl_force']) && $_SERVER['REQUEST_METHOD'] == 'GET' && $db->get_site_setting('force_ssl_url') && isset($_SERVER['REQUEST_URI'])) {
	$sslChecks = (isset($sslChecks))? $sslChecks: array();
	$useSsl = (isset($useSsl))? $useSsl: false;
	//Add SSL Checks...
	
	if (defined('IN_REGISTRATION') && $db->get_site_setting('use_ssl_in_registration')) {
		//we're in registration process, and we're supposed to use SSL for registration...
		$useSsl = true;
	}
	
	if ($db->get_site_setting('use_ssl_in_sell_process')) {
		//if in any part of the cart 
		$sslChecks [] = array ('a' => 'cart');
	}
	
	if ($db->get_site_setting('use_ssl_in_login')) {
		//ssl for user login
		$sslChecks [] = array ('a' => '10');
	}
	
	if ($db->get_site_setting('use_ssl_in_user_manage')) {
		//user management pages
		$sslChecks [] = array ('a' => '4');
	}
	
	//add future checks here...
	
	//get any checks from addons
	$sslChecks = geoAddon::triggerDisplay('filter_ssl_url_checks', $sslChecks, geoAddon::FILTER);
	
	//allow for special case, where the addon returns "true":
	if ($sslChecks === true) $useSsl = true;
	
	//clean up so it doesn't throw errors
	if (!is_array($sslChecks)) $sslChecks = array();
	
	if (count($sslChecks) || $useSsl) {
		//Only do checks if there is at least one SSL url
		
		//check for if we are in SSL mode right now
		$isSsl = geoSession::isSSL();
		
		foreach ($sslChecks as $check) {
			if ($useSsl) {
				//found one that matches all the checks, don't do more of the checks
				break;
			}
			foreach ($check as $key => $value) {
				if (isset($_GET[$key]) && $_GET[$key] == $value) {
					//this check matches, so continue on
					$useSsl = true;
				} else if (!isset($_GET[$key]) && $value === null) {
					//special case, if value is null, then the key doesn't
					//have to be set
					$useSsl = true;
				} else {
					//found a check that did not match up, go on to the next
					//url checks
					$useSsl = false;
					break;
				}
			}
		}
		if ($isSsl !== $useSsl) {
			//need to switch from ssl to non, or visa versa
			//Do NOT preserve sub-domain when going between SSL/non-SSL, as SSL cert
			//will not be valid for sub-domains.
			$setting = ($useSsl)? 'classifieds_ssl_url': 'classifieds_url';
			$url = $db->get_site_setting($setting);
			if ($url) {
				//only do it if set correctly
				$to_url = $_SERVER['REQUEST_URI'];
				$parts = explode('/',dirname($url));
				//I hope they have their url settings set correctly!
				
				//Get rid of the first three parts in a "correctly set" url setting, the "http:", "", and "example.com"
				unset ($parts[0], $parts[1], $parts[2]);
				if (count($parts)) {
					//Geo is installed in a sub-directory, remove the sub-directory from the beginning
					//since it will be added back later down
					$beginning = '/'.implode('/',$parts);
					if (strpos($to_url,$beginning) === 0) {
						$to_url = substr($to_url, strlen($beginning));
					}
				}
				//now figure out the full "before" URL as it was re-written
				$to_url = dirname($url).$to_url;
				
				if ($to_url) {
					header ("Location: $to_url");
					require GEO_BASE_DIR . 'app_bottom.php';
					exit;
				}
			}
		}
	}
}

$session->setLanguage();
$language_id = (int)$session->getLanguage();

$current_time = geoUtil::time();

/***************************************
 *    ---FILTERS---
 * Init all the different built-in filters
 * Addon Developers: Note that you can do 
 * stuff like this in an app_top.php file 
 * in your addon.
 * ************************************/

//NOTE: filters are things like the state filter, the zip filter, etc.
//where it filters what listings are displayed according to a filter...

//first figure out if we are to use any built-in filters, and set any cookies
//for any freshly set filters.
$set_filter_id = (isset($_REQUEST['set_filter_id']))? $_REQUEST['set_filter_id']: false;

if (is_array($set_filter_id)) {
	//reset($set_filter_id);
	foreach ($set_filter_id as $value) {
		if ($value == "clear") {
			$filter_id = "";
			setcookie('filter_id','',time()-86400,'/');
			unset($_COOKIE['filter_id']);
			break;
		} else if (strlen(trim($value)) > 0) {
			$filter_id = $value;
			//break;
		}
	}
	if($filter_id && is_numeric($filter_id)) {

		$expires = time() + 86400;
		setcookie("filter_id",$filter_id,$expires,'/');
		$_COOKIE['filter_id'] = $filter_id; //so we can read it on this pageload
	}
} else if (isset($_COOKIE["filter_id"]) && $_COOKIE['filter_id']) {
	$filter_id = $_COOKIE["filter_id"];
} else {
	$filter_id = 0;
}

if ((isset($_POST['set_state_filter']) && $_POST["set_state_filter"]) || (isset($_GET['set_state_filter']) && $_GET['set_state_filter'])) {
	$set_state_filter = (isset($_POST['set_state_filter']))? $_POST['set_state_filter'] : $_GET['set_state_filter'];
	
	if ($set_state_filter != "clear state" && $_POST["clear_zip_filter"] != "clear localizer") {
		//set state filter
		$expires = time() + 31536000;
		setcookie("state_filter",$set_state_filter,$expires,'/');
		$state_filter = $set_state_filter;
	} else {
		//clear state filter
		setcookie("state_filter","",0,'/');
		$state_filter = "";
	}
} else if (isset($_COOKIE["state_filter"]) && $_COOKIE["state_filter"]) {
	$state_filter = $_COOKIE["state_filter"];
} else {
	$state_filter = 0;
}



//Now we know what filters we are going to be using, so set the filters
if ($filter_id) {
	//set region filter or whatever it's called these days
	$filter_id = intval($filter_id);
	$sql = "SELECT `in_statement` FROM ".geoTables::filters_table." WHERE `filter_id` = ?";
	$row = $db->GetRow($sql, array($filter_id));
	
	if (isset($row['in_statement']) && strlen(trim($row['in_statement'])) > 0) {
		$in_stmt = trim($row['in_statement']);
		$db->getTableSelect(DataAccess::SELECT_BROWSE)->where(geoTables::classifieds_table.".`filter_id` $in_stmt", 'field_filter');
	}
}

if ($state_filter) {
	//set state filter (different than the region and sub region addon)
	//add state to end of sql_query
	$state_filter = intval($state_filter); //this is a numerical region ID
	$overrides = geoRegion::getLevelsForOverrides();
	$stateLevel = $overrides['state'];
	$tbl = geoTables::listing_regions;
	$db->getTableSelect(DataAccess::SELECT_BROWSE)->join($tbl,"$tbl.`listing` = ".geoTables::classifieds_table.".`id`")->where("$tbl.`region` = '$state_filter'", 'state');
}

//language filter
if ($db->get_site_setting('filter_by_language')) {
	//filter: only show listings in user's currently selected language OR listings with no language set
	//(meaning listings that pre-date when language_id is set for listing)
	$part = geoTables::classifieds_table.".`language_id`='{$language_id}' OR ".geoTables::classifieds_table.".`language_id`='0'";
	$db->getTableSelect(DataAccess::SELECT_BROWSE)
		->where($part, 'language');
	
	//Also do it for search results...
	$db->getTableSelect(DataAccess::SELECT_SEARCH)
		->where($part, 'language');
	
	//Not for the feed though..
	
	unset($part);
}



//Make sure "common" text is available to all pages: (for instance, for the "reserve met" image location,
//which is referenced from tons of different places)
$db->get_text(false, 59); 

//Since most of the front side still uses site class, include it
include_once (CLASSES_DIR . 'site_class.php');





// ARDEX CUSTOM STUFF
// Here we are setting the cookie to allow us to navigate back to a page from the merchant cart's "Continue Shopping" button
// If user navigates to a store, or a listing that belongs to a store, we set that store as the last shop visited
// If user navigates away from either the shop, any of its listings or the cart (or cart related areas)
$a = $_REQUEST['a'];
if ($a == 2) { // Listing page
	$listing = geoListing::getListing($_REQUEST['b']);

	$ppListingUtil = geoAddon::getUtil('ppListingDisplay');
	$ppStoreUtil = geoAddon::getUtil('ppStoreSeller');

	$storeid = null;

	if ($listing->category != 412 && $ppStoreUtil->listingIsValidStoreProduct($listing->id, true)) { // is a product
		$storedata = $ppListingUtil->getUsersSpecialListings($listing->seller, 412, true);
		$storeid = (string)$storedata['id'];
	}
	else if ($listing->category == 412) {
		$store = $listing;
		$storeid = (string)$store->id;
	}

	if ($storeid) {
		setcookie("laststorevisited", $storeid, time() + 3600, '/');
	}
	else {
		if ($_COOKIE['laststorevisited'])
			setcookie("laststorevisited"); // remove cookie
	}
}
else if ($a == 19 || !$a) { 
	if ($_COOKIE['laststorevisited'])
		setcookie("laststorevisited"); // remove cookie
}