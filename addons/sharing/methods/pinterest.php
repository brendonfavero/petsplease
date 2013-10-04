<?php
//addons/sharing/methods/pinterest.php
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
## ##    7.1beta4-99-g84389d8
## 
##################################




class addon_sharing_method_pinterest {
	
	public $name = 'pinterest';
	
	//note: G+ only uses the "social button"
	//the rest of this file is a "dummy" object so that it shows up in the admin
	
	/**
	 * Gets the name of any methods that want to be used for this listing id.
	 * Note that this function being called in the first place implies that the listing in question is live and belongs to the current user
	 * @param int $listingId
	 * @return String the name of any available method, sans any formatting
	 */
	public function getMethodsForListing($listingId)
	{
		return '';
	}
	
	/**
	 * Gets the full HTML to show in the "options" block of the main addon page.
	 * This function is responsible for any needed templatization to generate that HTML.
	 * @return String HTML
	 */
	public function displayOptions()
	{
		return '';
	}

	public function getShortLink($listingId)
	{
		return '';
	}
}