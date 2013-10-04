<?php
//function.listing.php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## 
##    7.0.3-288-gb9f7a9d
## 
##################################

//This fella takes care of {module ...}

function smarty_function_listing ($params, Smarty_Internal_Template $smarty)
{
	//check to make sure all the parts are there
	if (!isset($params['tag']) && !isset($params['field'])) {
		//One of the required parts is not specified
		return '{listing tag syntax error}';
	}
	$view = geoView::getInstance();
	
	//figure out the listing ID
	$listing_id = 0;
	
	//first see if it was passed in through params
	if (isset($params['listing_id']) && (int)$params['listing_id']>0) {
		$listing_id = (int)$params['listing_id'];
	}
	
	//second see if this is listing details page, if so use the listing ID from that
	if (!$listing_id && $view->listing_id) {
		$listing_id = (int)$view->listing_id;
	}
	
	if (!$listing_id) {
		//last try to figure it out based on current template vars
		$raw = $smarty->getTemplateVars('listing');
		if ($raw && isset($raw['id'])) {
			$listing_id = (int)$raw['id'];
		}
		unset($raw);
	}
	if (!$listing_id) {
		//finally, try to see if it is in template var as $listing_id
		$listing_id = (int)$smarty->getTemplateVars('listing_id');
	}
	
	if (!$listing_id) {
		//could not figure out listing ID!  Perhaps used on a generic location, don't
		//throw any errors or something, just be blank.
		return '';
	}
	
	$listing = geoListing::getListing($listing_id);
	if (!$listing) {
		//invalid listing or something?
		return '';
	}
	
	if (isset($params['tag'])) {
		//this is most common...  show contents of one of the built-in tags
		
		$tag = $params['tag'];
		
		unset($params['tag']);
		
		if (isset($params['addon'])) {
			//Let addon take care of it!
			$addonName = $params['addon'];
			unset($params['addon']);
			
			//set the listing_id in the params, this is the "special" thing listing
			//addon tags do, so the addon tag doesn't have to do work to figure out
			//which one to use
			$params['listing_id'] = $listing_id;
			return geoAddon::getInstance()->smartyDisplayTag($params, $smarty, $addonName, $tag, 'listing');
		}
		
		//OK we have the tag, the listing_id and the listing object...
		return $listing->smartyDisplayTag($tag, $params, $smarty);
	} else if (isset($params['field'])) {
		//Show pre-formatted field...  or possibly something else, but let
		//listing class take care of things from here.
		
		$field = $params['field'];
		
		unset($params['field']);
		//note:  smartyDisplayField accounts for assign=... in params.
		return $listing->smartyDisplayField($field, $params, $smarty);
	}
}
