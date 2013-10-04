<?php
//addons/bulk_uploader/cron/renew_revolving_inventory.php


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
## ##    3659f23
## 
##################################

if (!defined('GEO_CRON_RUN')){
	die('NO ACCESS');
}

$reg = geoAddon::getRegistry('bulk_uploader');
if(!$reg) {
	$this->log('Could not get the bulk uploader registry. Either a critical error occured, or the Bulk Uploader addon is not installed/enabled. Quitting cron execution.', __line__);
	//return true so this doesn't keep repeating on every heartbeat
	return true;
}

//find a list of revolving uploads to renew
$sql = "SELECT * FROM `geodesic_addon_bulk_uploader_revolving` WHERE `next_run` <= ?";
$result = $this->db->Execute($sql, array(geoUtil::time()));


if($result->RecordCount() == 0) {
	$this->log('found no revolving inventory to do right now. exiting.', __line__);
	return true;
}

$toDo = array();
while($row = $result->FetchRow()) {
	$toDo[$row['id']] = $row['label'];
}

$this->log('array of uploads to update: '.print_r($toDo,1), __line__);

require_once(ADDONS_DIR . 'bulk_uploader/admin.php');
$bulkAdmin = new addon_bulk_uploader_admin();
if(!is_object($bulkAdmin)) {
	$this->log('failed to get the bulk uploader admin object. cannot continue', __line__);
	return true;
}

//loop through, check cache, inject session data, call upload function
foreach($toDo as $id => $label) {
	$this->log('starting update on: ['.$id.' => '.$label.']', __line__);
	
	$settings = $reg->get($label);
	
	
	//****check for cached/unchanged listings in this upload****
	
	//get the column used as the unique id for this upload
	$cacheKey = $settings['unique_id'];
	
	//figure out which column is the cache key in the upload data
	$cols = $settings['columns'];
	$cacheCol = array_search($cacheKey,$cols);
	$this->log("cacheKey is: $cacheKey cacheCol is $cacheCol", __line__);
	
	if ($cacheCol === false) {
		//0 is a valid value here, so be sure to check against ===false
		$this->log("didn't find the unique ID / cache column in the upload data. cannot proceed with this upload.", __line__);
		continue;
	}
	$cacheCol = (int)$cacheCol;
	//now, $cacheCol is the csv column number of the field used for the unique id
	
	$sql = "SELECT * FROM `geodesic_addon_bulk_uploader_revolving_map` WHERE `revolving_id` = ?";
	$result = $this->db->Execute($sql, array($label));
	$old_uids_mapped = array();
	while($line = $result->FetchRow()) {
		$old_uids_mapped[$line['listing_id']] = $line['uid'];
	}
	//now that we have the old uids saved to a local var, cleanse them all out of the db
	//because we want to be sure to only have new ones in the table when we're done uploading
	$this->db->Execute("DELETE FROM geodesic_addon_bulk_uploader_revolving_map WHERE `revolving_id` = ?", array($label));
	
	$settings['config']['revolving_label'] = $label;	
		
	/**
	 * $old_uids_mapped and $new_uids_mapped are arrays of listingID => uniqueVal
	 * old_uids_mapped is saved settings from the last time this was run
	 * new_uids_mapped is created from the actual listings used during this run
	 * 
	 * they're used to determine what to do with a given listing, as such:
	 * 
	 * 1) listing's unique value in old_uids_mapped? UPDATE IT (done in admin.php)
	 * 2) listing's unique value NOT in old_uids_mapped? INSERT IT (done in admin.php)
	 * 3) listing's unique value in old_uids_mapped but NOT in new_uids_mapped? DELETE IT (done below)
	 * 
	 */
	
	
	//***call the main bulk upload function and make it do all the heavy lifting***
	
	$this->log('about to open file for read: '.$settings['config']['updatefile'], __line__);
	$handle = fopen($settings['config']['updatefile'], 'r');
	
	$this->log('about to call main uploader', __line__);
	$bulkAdmin->insertCSV($settings, $handle, $old_uids_mapped);
	$this->log('main upload done', __line__);
	fclose($handle);
	
	$newSettings = $reg->$label;
	
	//now get the new mapping
	$sql = "SELECT * FROM `geodesic_addon_bulk_uploader_revolving_map` WHERE `revolving_id` = ?";
	$result = $this->db->Execute($sql, array($label));
	$new_uids_mapped = array();
	while($line = $result->FetchRow()) {
		$new_uids_mapped[$line['listing_id']] = $line['uid'];
	}
	
	//if there are any unused uids, delete those listings
	
	$deleteListings = array_keys(array_diff($old_uids_mapped, $new_uids_mapped));
	if ($deleteListings) {
		$sql = "DELETE FROM ".geoTables::classifieds_table." WHERE `id` IN (".implode(', ',$deleteListings).")";
		$deleteResult = $this->db->Execute($sql);
		$this->log("Deleted listings no longer found in CSV file (Listing IDs ".implode(', ',$deleteListings).")", __LINE__);
		
		//also remove images attached to those listings
		$sql = "SELECT image_id FROM ".geoTables::images_urls_table." WHERE classified_id IN (".implode(', ',$deleteListings).")";
		$imgs = $this->db->GetAll($sql);
		foreach($imgs as $img) {
			$this->log('trying to remove image from disk with image id: '.$img['image_id'], __LINE__);
			geoImage::remove($img['image_id']);
		}		
	}
	
	if($reg->_failedUserCheck == 1 && count($deleteListings) > 0) {
		//we prevented the a user from uploading due to a listing limit, then deleted some listings.
		//run through the upload file one more time, in case there are listings to add in place of the deleted ones
		
		//TODO: is there a better way to do this than running insertCSV a second time? maybe...
		$this->log('2nd pass on file: '.$settings['config']['updatefile'], __line__);
		$handle = fopen($settings['config']['updatefile'], 'r');
		$this->log('about to call main uploader for **2nd PASS**', __line__);
		$bulkAdmin->limitFails = array(); //clear checkUserLimits cache var
		$bulkAdmin->insertCSV($settings, $handle, $new_uids_mapped);
		$this->log('main upload (2nd PASS) done', __line__);
		fclose($handle);
		//insertCSV() takes care of fixing the uids, and there's no reason to look for deletes this time, so we're done.
	}
	$reg->_failedUserCheck = false; //reset failure flag so it doesn't bleed into the next upload	
	
	//update db, set next_run to a week from now
	$next_run = geoUtil::time() + ($this->db->get_site_setting('bulk_revolve_period') * 86400);
	$sql = "UPDATE `geodesic_addon_bulk_uploader_revolving` SET `next_run` = ? WHERE `id` = ?";
	$result = $this->db->Execute($sql, array($next_run, $id));
	$this->log('updated next_run in db: '.$next_run, __line__);
	
}
$reg->save(); //probably saved some stuff to the registry -- save it outside the loop, so we only have to save once. 
$this->log('done. exiting.', __line__);

return true;