<?php
//geographic_navigation/CLASSES.ajax.php
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
## ##    6.0.7-2-gc953682
## 
##################################


// DON'T FORGET THIS
if( class_exists( 'classes_AJAX' ) or die());

class addon_geographic_navigation_CLASSES_ajax extends classes_AJAX {	
	public function selectRegion ()
	{
		if (!$this->_checkSession()) {
			return;
		}
		$util = geoAddon::getUtil('geographic_navigation');
		$util->ajaxSelectRegion();
	}
	
	public function chooseRegionBox ()
	{
		if (!$this->_checkSession()) {
			return;
		}
		
		$util = geoAddon::getUtil('geographic_navigation');
		$util->usePostAsGet();
		$util->ajaxChooseRegionBox();
	}
	
	private function _checkSession ()
	{
		//If any security checks were needed, do them here.
		
		//At this time, returning a list of regions to display does not pose a
		//security risk, so no session checks needed.  That may change in the future.
		
		return true;
	}
}