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

class pricing_configurePaymentGateways extends geoGettingStartedCheck
{
	/**
	 * User-readable name/title for this check
	 * @var String
	 */
	public $name = 'Configure Payment Gateways';
	/**
	 * Name of the section this check belongs in
	 * @var String
	 */
	public $section = 'Pricing';
	/**
	 * Descriptive text that explains the check and how to resolve it
	 * @var String
	 */
	public $description = 'Enable and configure at least one <a href="index.php?page=payment_gateways&mc=payments">Payment Gateway</a>
	 <strong>OR</strong> turn off the <a href="index.php?page=master_switches&mc=site_setup">Site Fees Master Switch</a> if not charging for anything';
	
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
		$enabledGateways = geoPaymentGateway::getPaymentGatewayOfType('all');
		$siteFees = geoMaster::is('site_fees');
		return (!$siteFees || count($enabledGateways) > 0);
	}
	
}