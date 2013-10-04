<?php
//addons/core_display/util.php
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
## ##    7.1beta1-1139-g3d5df08
## 
##################################

# storefront Addon

require_once ADDON_DIR . 'core_display/info.php';

class addon_core_display_util extends addon_core_display_info {
	
	public function core_process_browsing_filters($category)
	{
		//turn on calendar inputs
		geoCalendar::init();
		
		//let filter class know if we're in a certain category
		geoBrowsingFilter::setBrowsingCategory($category);

		//grab all previously-existing filters out of the database and load them to be active
		geoBrowsingFilter::retrieveAll();
		
		if(isset($_GET['setFilter']) && isset($_GET['filterValue'])) {
			//setting a new filter
			$target = $_GET['setFilter'];
			$value = geoString::toDB($_GET['filterValue']);
			$newFilter = geoBrowsingFilter::getFilter($target);
			if($newFilter) {
				$newFilter->activate($value);
			} else {
				//not a valid filter target
			}
		}
		
		if(isset($_POST['filterRange'])) {
			foreach($_POST['filterRange'] as $target => $values) {
				$newFilter = geoBrowsingFilter::getFilter($target);
				if($newFilter && $newFilter->getType() == geoBrowsingFilter::RANGE) {
					//clean inputs
					$values['low'] = floatval($values['low']);
					$values['high'] = floatval($values['high']);
					
					$value = array();
					$value['low'] = max(0,$values['low']);
					$value['high'] = ($values['high']) ? min(100000000,$values['high']) : 100000000;
					if($value['low'] == 0 && $value['high'] == 100000000) {
						//not actually filtering by anything (both high and low left blank or invalid)
						continue;
					}
					$newFilter->activate($value);
				} else {
					//not a valid filter target
				}
			}
		}
		
		if(isset($_POST['filterDate'])) {
			foreach($_POST['filterDate'] as $target => $values) {
				$newFilter = geoBrowsingFilter::getFilter($target);
				if($newFilter && $newFilter->getType() == geoBrowsingFilter::DATE_RANGE) {
					//clean inputs
					$values['start'] = intval($values['start']);
					$values['end'] = intval($values['end']);
					
					$value = array();
					$value['low'] = $values['start'] ? $values['start'] : 0;
					$value['high'] = $values['end'] ? $values['end'] : 100000000;
					if($value['low'] == 0 && $value['high'] == 100000000) {
						//not actually filtering by anything (both high and low left blank or invalid)
						continue;
					}
					$newFilter->activate($value);
				} else {
					//not a valid filter target
				}
			}
		}
		
		if(isset($_GET['resetFilter'])) {
			//removing an existing filter
			$deactivate = $_GET['resetFilter'];
			$filter = geoBrowsingFilter::getFilter($deactivate);
			if($filter) {
				$filter->deactivate();
			} else {
				//sanity failure -- deactivating a filter that doesn't exist!
			}
		}
		
		if(isset($_GET['resetAllFilters']) && $_GET['resetAllFilters'] == 1) {
			geoBrowsingFilter::deactivateAll();
		}
	}
	
	public function core_geoFields_getDefaultLocations ($vars)
	{
		//expected to return using following format:
		return array (
			'core_featured_gallery' => 'Browsing Featured Gallery',
			//you can add as many locations as you want.
		);
	}
	
}