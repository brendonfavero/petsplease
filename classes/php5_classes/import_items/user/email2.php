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
##    7.3.2-118-g4b2181d
##
##################################

class email2ImportItem extends geoImportItem
{
	protected $_name = "Email Address 2";
	protected $_description = "The user's secondary contact email address";
	protected $_fieldGroup = self::USER_GENERAL_FIELDGROUP;
	
	public $displayOrder = 5;
	
	private $prep_emailExists;
	protected final function _cleanValue($value)
	{
		$value = trim($value);
		
		if(!geoString::isEmail($value)) {
			trigger_error('ERROR IMPORT: invalid email address');
			return false;			
		}
		/* don't care if email2 already exists...do we?
		$db = DataAccess::getInstance();
		if(!$this->prep_emailExists) {
			$this->prep_emailExists = $db->Prepare("SELECT `id` FROM ".geoTables::userdata_table." WHERE `email` = ?");
		}
		if($db->GetOne($prep_emailExists, array($value))) {
			trigger_error('ERROR IMPORT: email address already exists!');
			return false;
		}
		*/
		return $value;
	}
	
	protected final function _updateDB($value, $groupId)
	{
		geoImport::$tableChanges['userdata']['email2'] = " `email2` = '{$value}' ";
		return true;
	}
	 
}