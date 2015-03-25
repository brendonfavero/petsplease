<?php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.4
Copyright (c) 2001-2014 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## 
##    7.3.2-88-g45a1e5f
## 
##################################

class meta_not_usedImportItem extends geoImportItem
{
	protected $_name = 'Field Not Used';
	protected $_description = 'Select this for fields in the source file that do not map to datapoints within GeoCore';
	protected $_fieldGroup = self::NOT_USED_FIELDGROUP;	
	
	protected function _cleanValue($value)
	{
		//do nothing!
		return '';
	}
	
	protected function _updateDB($value, $groupId)
	{
		//do nothing!
		return true;
	}
}