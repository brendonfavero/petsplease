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
##    7.3beta4-32-ge27807e
##
##################################

require_once ADMIN_DIR . 'getting_started.php';

class text_registrationAgreement extends geoGettingStartedCheck
{
	/**
	 * User-readable name/title for this check
	 * @var String
	 */
	public $name = 'Customize registration agreement';
	/**
	 * Name of the section this check belongs in
	 * @var String
	 */
	public $section = 'Text';
	/**
	 * Descriptive text that explains the check and how to resolve it
	 * @var String
	 */
	public $description = 'Edit the <a href="index.php?page=sections_edit_text&b=15&c=768&l=1">Registration Agreement / Terms of Use text</a> and customize it for your site.';
	
	/**
	 * Value that represents how important this check is towards final completion.
	 * Most will use a value of 1. A check with a weight of 2 should be roughly twice as important as normal.
	 * @var float
	 */
	public $weight = 1;
	
	/**
	 * Accessor for user-selected state of checkbox for this item
	 * @var bool
	 */
	public $isChecked;
	
	/**
	 * Just a constructor.
	 */
	public function __construct()
	{
		$this->isChecked = (bool)DataAccess::getInstance()->get_site_setting('gettingstarted_'.$this->name.'_isChecked');
	}
	
	/**
	 * This function should return a bool based on whether the checked item "appears" to be complete. 
	 * @return bool
	 */
	public function isComplete()
	{
		$text = DataAccess::getInstance()->get_text(true,15);
		return (stripos($text[768], "Update your Terms of Use by logging in to the Admin Panel") === false);
	}
	
}