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
##    7.4beta1-382-ga703b4a
##
##################################

class addressImportItem extends geoImportItem
{
	protected $_name = "Address";
	protected $_description = "The user's street address";
	protected $_fieldGroup = self::USER_LOCATION_FIELDGROUP;
	
	public $displayOrder = 0;
	
	protected final function _cleanValue($value)
	{
		$value = addslashes(trim($value));
		return $value;
	}
	
	protected final function _updateDB($value, $groupId)
	{
		geoImport::$tableChanges['userdata']['address'] = " `address` = '{$value}' ";
		return true;
	}
	 
}