<?php
//addons/sharing/methods/myspace.php
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
## ##    c7c54e8
## 
##################################

class addon_sharing_method_myspace {
	
	public $name = 'myspace';
	
	/**
	 * Gets the name of any methods that want to be used for this listing id.
	 * Note that this function being called in the first place implies that the listing in question is live and belongs to the current user
	 * @param int $listingId
	 * @return String the name of any available method, sans any formatting
	 */
	public function getMethodsForListing($listingId) {
		
		//we want this to be available for all listings, so simply return the name to show
		$msgs = geoAddon::getText('geo_addons','sharing');
		return $msgs['method_btn_myspace'];
			
	}
	
	/**
	 * Gets the full HTML to show in the "options" block of the main addon page.
	 * This function is responsible for any needed templatization to generate that HTML.
	 * @return String HTML
	 */
	public function displayOptions()
	{
		$data = $_POST;
		$urlToListing = geoFilter::getBaseHref() . DataAccess::getInstance()->get_site_setting('classifieds_file_name').'?a=2&b='.$data['listing'];
				
		$tpl = new geoTemplate('addon','sharing');
		$tpl->assign('listing_url', urlencode($urlToListing));
		$html = $tpl->fetch('methods/myspace_options.tpl');
		return $html;
	}

	public function getShortLink($listingId)
	{
		$tpl = new geoTemplate('addon','sharing');
		$tpl->assign('iconUrl', geoTemplate::getUrl('images','addon/sharing/icon_myspace.png'));
		$msgs = geoAddon::getText('geo_addons','sharing'); 
		$tpl->assign('text', $msgs['shortlink_myspace']);
		
		$listing = geoListing::getListing($listingId);
		$urlToListing = geoFilter::getBaseHref() . DataAccess::getInstance()->get_site_setting('classifieds_file_name').'?a=2&b='.$listing->id;
		$listing_url = urlencode($urlToListing);
		$tpl->assign('link', 'http://www.myspace.com/Modules/PostTo/Pages/?u='.$listing_url);
		
		return $tpl->fetch('shortLink.tpl');
	}
}