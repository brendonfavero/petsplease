<?php
//addons/sharing/util.php
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
## ##    7.1.2-9-gd6ef2f7
## 
##################################

# storefront Addon

require_once ADDON_DIR . 'sharing/info.php';

class addon_sharing_util extends addon_sharing_info {

	private $_methods;
	private $_methodsAreLoaded = false;
	
	private function _loadMethods()
	{
		if($this->_methodsAreLoaded) {
			return;
		}
		$method_dir = GEO_BASE_DIR . 'addons/sharing/methods';
		$dir = dir($method_dir);
		
		//make these always appear first
		$priority = array('craigslist','facebook','twitter');
		
		//exclude these methods permanently (their networks have gone away)
		$exclusions = array('buzz','digg');
		
		$this->_methods = array();
		$otherMethods = array();
		
		while(false !== ($filename = $dir->read())) {
			if($filename == '.' || $filename == '..' || (substr($filename, 0, 1) == '_')) {
				continue;
			}
			include_once($dir->path.DIRECTORY_SEPARATOR.$filename);
			$className = substr($filename,0, -4);
			
			if(in_array($className,$exclusions)) {
				//this network is dead. don't load its method
				continue;
			}
			
			$name = 'addon_sharing_method_'.$className;
			$method = new $name;
			if(is_object($method)) {
				if(in_array($className,$priority)) {
					$this->_methods[$className] = $method;
				} else {
					//not a priority method. delay adding this until later
					$otherMethods[$className] = $method;
				}
			}
		}
		//now add in all the non-priority methods
		foreach($otherMethods as $name => $m) {
			$this->_methods[$name] = $m;
		}

		$this->_methodsAreLoaded = true;
	}
	
	/**
	 * Accessor to get all possible methods, enabled or not (used by admin settings page)
	 * @return array of method objects
	 */
	public function getAllMethods()
	{
		if(!$this->_methodsAreLoaded) {
			$this->_loadMethods();
		}
		return $this->_methods;
	}
	
	/**
	 * Get methods that are active according to admin settings
	 * @param bool $force force override of method cache
	 * @param bool $statusOnly if true, return only the status of the methods instead of their full objects
	 * @return mixed
	 */
	public function getActiveMethods($force=false,$statusOnly=false)
	{
		if(!$this->_methodsAreLoaded || $force) {
			$this->_loadMethods();
		}
		$reg = geoAddon::getRegistry($this->name);
		foreach($this->_methods as $name => $methodObj) {
			if(!$reg->get("method_{$name}_is_enabled")) {
				//this method is not enabled! remove it from the list of active methods!
				unset($this->_methods[$name]);
			} elseif($statusOnly) {
				$return[$name] = 1;
			}
		}
		if($statusOnly) {
			//return only names of methods, instead of the full objects
			return $return;
		}		
		return $this->_methods;
	}
		
	public function getMethodsForListing($listingId)
	{
		if(!$listingId) {
			return '';
		}
		$this->getActiveMethods();
		
		$methodsToShow = array();
		foreach($this->_methods as $method) {
			if(is_callable(array($method,'getMethodsForListing'))) {
				$methodName = $method->getMethodsForListing($listingId);
				if($methodName) {
					$methodsToShow[$method->name] = $methodName;
				}
			}
		}
		
		if(count($methodsToShow) < 1) {
			//no methods to show!
			return '';
		}
		
		$tpl = new geoTemplate('addon', 'sharing');
		$tpl->assign('methods', $methodsToShow);
		$html = $tpl->fetch('method_types.tpl');
		return $html;
		
	}
	
	public function getOptionsForMethod($methodName)
	{
		$method = $this->getMethodByName($methodName);
		if(!$method) {
			return '';
		}
		
		$html = '';
		if(is_callable(array($method,'displayOptions'))) {
			$html = $method->displayOptions();
		}			
		
		//NOTE: displayOptions() should return complete HTML to show.
		//the method's class is responsible for any needed templatization
		return $html;
	}
	
	
	public function getShortLinks($listingId)
	{
		$this->getActiveMethods();
		$links = array();
		foreach($this->_methods as $method) {
			if(is_callable(array($method,'getShortLink'))) {
				$link = $method->getShortLink($listingId);
				if($link) {
					$links[] = $link;
				}
			}
		}
		return $links;
	}
	
	public function processOptionsForMethod($methodName)
	{
		$method = $this->getMethodByName($methodName);
		if(!$method) {
			return '';
		}
		$html = '';
		
		if(is_callable(array($method,'updateOptions'))) {
			$html = $method->updateOptions();
		}			
		return $html;
	}
	
	public function getMethodByName($methodName)
	{
		if(!$methodName) {
			return false;
		}
		$this->getActiveMethods();
		
		foreach($this->_methods as $method) {
			if($method->name == $methodName) {
				return $method;
			}
		}
		return false; //didn't find method by that name
	}
	
	public function core_my_account_links_add_link($vars)
	{		
		//TODO: any processing needed to determine whether to show the link in My Account Links. possibly: hide if user has no active listings?
		$msgs = geoAddon::getText('geo_addons','sharing');
		$return['sharing'] = array(
			'link' => $vars['url_base'] . "?a=ap&amp;addon=sharing&amp;page=main", 
			'label' => $msgs['my_account_links_label'],
			'icon' => $msgs['my_account_links_icon'],
			'active' => (($_REQUEST['addon'] == 'sharing') ? true : false)
		);
			
		return $return;
	}
	

	public function core_admin_display_page_attachments_edit_end ($tpl_vars)
	{
		//list of all the methods that need/can support multiple template attachements
		$multiTemplatePages = array('craigslist_output', 'printable_sign', 'printable_flyer');
		
		if (!isset($tpl_vars['addon']) || $tpl_vars['addon'] != 'sharing' || !in_array($tpl_vars['addonPage'],$multiTemplatePages) ) {
			//nothing to do
			return;
		}
		//use a different template to show the page
		
		//figure out "new cat ID"
		$newCatId = 1;
		
		foreach ($tpl_vars['attachments'] as $langId => $cats) {
			foreach ($cats as $catId => $attachment) {
				if ($catId >= $newCatId) {
					$newCatId = $catId + 1;
				}
			}
		}
		
		$view = geoView::getInstance();
		
		$view->setBodyVar('newCatId',$newCatId);
		
		$view->setBodyTpl('admin/sharingTemplateEdit.tpl','sharing');
		
	}
	
	
	public function core_listing_display_add_action_button($vars)
	{
		//NOT USED!!!!!!!!!!!
		//See the tags.php for where this happens now.
		//TODO: Remove this once we no longer need it for reference.
		
		//add CSS to page
		$view = geoView::getInstance();
		$view->addCssFile(geoTemplate::getUrl('css', 'addon/sharing/sharing.css'));
		
		$tpl_vars = array ();
		
		//specify which image should be used for methods that pick thumbnails (like Facebook and Digg)
		$imgUrl = DataAccess::getInstance()->GetOne("SELECT `image_url` FROM `geodesic_classifieds_images_urls` WHERE `classified_id` = ? ORDER BY `display_order`", array($vars['listingId']));
		if($imgUrl) {
			$img = (stripos($imgUrl, '://') === false) ? geoFilter::getBaseHref() : '';
			$img .= $imgUrl;
			//image_url for digg/reddit, og:image for facebook
			$view->addTop("<link rel='image_url' href='".$img."' />")->addTop('<meta property="og:image" content="'.$img.'"/>');
			$tpl_vars['lead_image'] = $img;
		}
		
		$tpl = new geoTemplate('addon','sharing');
		
		$tpl_vars['text'] = geoAddon::getText('geo_addons','sharing');
		$tpl_vars['shortLinks'] = $this->getShortLinks($vars['listingId']);

		$tpl_vars['showMoreLink'] = false; //set this later when number of total methods exceeds what we want to show in the popup
		$tpl_vars['numMethods'] = count($this->_methods);
		
		$tpl_vars['shareButtonImage'] = geoTemplate::getUrl('images','addon/sharing/icon_share.png');
		$tpl_vars['forListing'] = $vars['listingId'];
		$tpl_vars['file_name'] = $fileName = DataAccess::getInstance()->get_site_setting('classifieds_file_name');
		$tpl_vars['listing_url_unencoded'] = geoFilter::getBaseHref() . $fileName . '?a=2&amp;b=' . $vars['listingId'];
		$tpl_vars['listing_url'] = urlencode($tpl_vars['listing_url_unencoded']);
		
		$listing = geoListing::getListing($vars['listingId']);
		$tpl_vars['listing_data'] = $listing->toArray();
		$tpl_vars['listing_data']['description'] = geoString::specialChars(geoFilter::listingDescription($listing->description, true));
		
		//add OG description tag so that facebook (and other Open Graph sites) pulls the listing description instead of the meta description
		$view->addTop("<meta property='og:description' content='".$tpl_vars['listing_data']['description']."'>");
		
		//set up formatted price as a separate var
		if($listing->item_type == 1) {
			$tpl_vars['price'] = geoString::displayPrice($listing->price, $listing->precurrency, $listing->postcurrency);
		} elseif($listing->item_type == 2) {
			if($listing->buy_now_only == 1) {
				$auction_price = $listing->buy_now;
			} elseif($listing->current_bid < $listing->starting_bid) {
				$auction_price = $listing->starting_bid;
			} else {
				$auction_price = $listing->current_bid;
			}
			$tpl_vars['price'] = geoString::displayPrice($auction_price, $listing->precurrency, $listing->postcurrency);
		}
		
		
		$tpl->assign($tpl_vars);
		return (!$vars['isPreview']) ? $tpl->fetch('button_listing_display.tpl') : false;
	}
	
	public function core_current_listings_add_action_button($vars)
	{
		$msgs = geoAddon::getText('geo_addons','sharing');
		$tpl = new geoTemplate('addon','sharing');
		$tpl->assign(array('image' => geoTemplate::getUrl('images','addon/sharing/listing_share.gif'),
		'title' => $msgs['sharing_link_text'],
		'forListing' => $vars['listingId'],
		'file_name' => DataAccess::getInstance()->get_site_setting('classifieds_file_name')));
		
		return $tpl->fetch('button_current_listings.tpl');
	}

	public function core_notify_display_page ($vars)
	{
		$site = $vars['this'];
		if ($site->page_id!==1) {
			//NOT the listing display page, we don't care about this page
			return;
		}
		$view = geoView::getInstance();
		
		if ($view->preview_listing) {
			//do not show on preview listing page, it can mess things up
			return;
		}
		//add main sharing addon CSS
		$view->addCssFile(geoTemplate::getUrl('css', 'addon/sharing/sharing.css'));
		
		$listingId = (int)$view->listing_id;
		if (!$listingId) {
			//something wrong
			return;
		}
		
		//specify which image should be used for methods that pick thumbnails (like Facebook and Digg)
		$imgUrl = DataAccess::getInstance()->GetOne("SELECT `image_url` FROM `geodesic_classifieds_images_urls` WHERE `classified_id` = ? ORDER BY `display_order`", array($listingId));
		if ($imgUrl) {
			$img = (stripos($imgUrl, '://') === false) ? geoFilter::getBaseHref() : '';
			$img .= $imgUrl;
			
			$head_tpls = array();
			$head_tpls['meta_types'] = array ('facebook'); // old option: image_url
			$head_tpls['image_url'] = $img;
			
			//get all listing data so can populate things like fb title and description
			$listing = geoListing::getListing($listingId);
			if ($listing) {
				$head_tpls['listing'] = $listing->toArray();
				$head_tpls['listing_url'] = $listing->getFullUrl();
				$head_tpls['description_clean'] = geoFilter::listingDescription($listing->description, true);
			}
			
			$tpl = new geoTemplate(geoTemplate::ADDON, $this->name);
			$tpl->assign($head_tpls);
			
			$view->addTop($tpl->fetch('head_meta.tpl'));
		}
	}
}