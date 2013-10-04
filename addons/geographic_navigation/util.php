<?php
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
## ##    7.2.2-14-g3f4496e
## 
##################################

require_once ADDON_DIR . 'geographic_navigation/info.php';

class addon_geographic_navigation_util extends addon_geographic_navigation_info
{
	private $_regError;
	
	const SEARCH_LOCATION_PLACEHOLDER = '##SEARCH_LOCATION##';

	
	public function core_notify_Display_ad_display_classified_after_vars_set($vars)
	{
		$listingId = $vars['id'];
		//make sure we're in the right GeoNav region for this listing
		//(prevent search engines from indexing a listing under the wrong region/subdomain)
		
		//but first make sure this isn't just a preview...
		if (geoView::getInstance()->preview_listing) {
			//this is a preview!  Don't try to correct user region.
			return;
		}
		
		//get the regions for this listing
		$regionsForListing = $this->getRegionsForDisplay($listingId);
		$userRegion = $this->getLabelFor($this->getCurrentRegion());

		if($userRegion && $regionsForListing && !in_array($userRegion, $regionsForListing, true)) {
			//not in the correct region for this listing
			//try to recover by redirecting to the base, regionless version
			//first, kill the region cookie
			setcookie('region',0,1,'/');
			
			$reg = geoAddon::getRegistry($this->name);
			if($reg->geo_ip) {
				//override the geo-ip region finder.
				//otherwise, it can get into an infinite loop if accessing a listing from no subdomain
				setcookie('region_skip_autoassign',1,0,'/');
				if(geoSession::getInstance()->is_robot()) {
					//for bots, settting that cookie won't do much good. just go ahead and show them this page.
					return;
				}
			}
			
			//then redirect to the base URL for this page
			$target = geoListing::getListing($listingId)->getFullUrl();
			header('Location: '.$target);
		}
	}
	
	private function _addHeader ($fields)
	{
		if (!$fields['addon_geographic_navigation_location']) {
			//not supposed to show in here
			return;
		}
		
		$msgs = geoAddon::getText($this->auth_tag, $this->name);
		
		$headings = array();
		//first heading to add
		$headings [] = array (
			'text' => $msgs['browsingHeader'],
		);
		return $headings;
	}
	
	private function _addRow ($fields, $listing_id, $tabular=true)
	{
		if (!$fields['addon_geographic_navigation_location']) {
			//not supposed to show in here
			return;
		}
		
		$rows = array();
		$parts = $this->getRegionsForDisplay($listing_id,0);
		
		$rows[] = (count($parts)>0)? implode (' &gt; ', $parts) : '-';
				
		return $rows;
	}
	
	
	
	public function core_module_title_prepend_text ()
	{
		//NOTE:  at this time, categories only display end category in title module,
		//so we do same for regions for consistency.  If/when main software is 
		//changed to show entire category breadcrumb in title module, this can be
		//updated to also show full breadcrumb for region in title.
		$view = geoView::getInstance();
		$reg = geoAddon::getRegistry($this->name);
		if ($view->classified_id) {
			//we are displaying a listing
			if ($reg->showInTitleListing) {
				$regions = $this->getRegionsForDisplay($view->classified_id);
				
				return geoString::specialChars(array_pop($regions)).' : ';
			}
		}
		
		if (!$reg->showInTitle) {
			return '';
		}
		$region = $this->getCurrentRegion();
		if (!$region) {
			//nothing selected currently
			return '';
		}
		$breadcrumb = $this->getBreadcrumbFor($region);
		$entry = array_pop($breadcrumb);
		return geoString::specialChars($entry['label']).' : ';
	}
	
	public function core_notify_ListingFeed_generateSql ($feed)
	{
		$query = $feed->getTableSelect();
		
		if ($feed->geoNavRegion == geoListingFeed::COOKIE_SET) {
			$feed->geoNavRegion = $_COOKIE['region'] ? $_COOKIE['region'] : 0;
		} else if($feed->geoNavRegion == geoListingFeed::URL_SET) {
			$feed->geoNavRegion = isset($_GET['geoNavRegion']) ? $_GET['geoNavRegion'] : 0;
		}
		
		if ($feed->geoNavRegion) {
			$this->applyFilter($feed->getTableSelect(), $feed->geoNavRegion);
		}
	}
	
	public function core_notify_modules_preload ($modules)
	{
		//load the needed js and such
		$reg = geoAddon::getRegistry($this->name);
		if (!$reg->showInSearchBox) {
			return;
		}
		$this->addMainTop();
	}
	
	private $_headInserted = false;
	
	
	/**
	 * Add stuff to the top of the page (through {header_html}) needed for most
	 * Geo Nave things to work.
	 */
	public function addMainTop ()
	{
		if ($this->_headInserted || !$this->checkBrowsingEnabled()) {
			//already done once, or browsing disabled
			return;
		}
		$this->_headInserted = true;
	
		$tpl = new geoTemplate(geoTemplate::ADDON, $this->name);
	
		geoView::getInstance()->addCssFile(geoTemplate::getURL('css','addon/geographic_navigation/navigation.css'))
			->addJScript('addons/geographic_navigation/regions.js')
			->addTop($tpl->fetch('get_params.tpl'));
	}
	
	/**
	 * Whether or not browsing is disabled (print feature)
	 * @return boolean true if browsing enabled, false if browsing disabled
	 */
	public function checkBrowsingEnabled ()
	{
		return !(geoPC::is_print() && DataAccess::getInstance()->get_site_setting('disableAllBrowsing'));
	}
	
	/**
	 * checks that a region exists and is enabled
	 * @param int $region_id
	 * @return bool
	 */
	public function checkRegionId($region_id)
	{
		$db = DataAccess::getInstance();
		$enabled = $db->GetOne("SELECT `enabled` FROM ".geoTables::region." WHERE `id` = ?", array($region_id));
		return ($enabled === 'yes');
	}
	
	/**
	 * Used internally throughout the Geographic Navigation addon, mostly in legacy display functions, to get the regions to display in a feature
	 * @param int $region_id ID of parent region to get children of
	 * @param bool $orSiblings if true and selected region has no children, function will return selected region's siblings instead
	 * @return Array
	 */
	public function getChildrenFor($region_id, $orSiblings=false)
	{
		$data = geoRegion::getRegionsFromParent('', $region_id);
		$subdomains = (geoAddon::getRegistry($this->name)->subdomains == 'on');
		$children = array();
		
		if(!$data && $orSiblings) {
			//no children to show...get siblings instead
			$parent = (int)DataAccess::getInstance()->GetOne("SELECT `parent` FROM ".geoTables::region." WHERE `id` = ?", array($region_id));
			$data = geoRegion::getRegionsFromParent('', $parent);
		}
		
		foreach($data['regions'] as $region) {
			$subdomain = ($subdomains) ? $region['unique_name'] : '';
			$children[] = array(
				'id' => $region['id'],
				'label' => $region['name'],
				'subdomain' => $subdomain,
				'link' => $this->getLinkForRegion($region['id'], $subdomain),
			);
		}
		return $children;
	}
	
	public function getChildrenCountFor($region_id)
	{
		$region_id = intval($region_id);
		$db = DataAccess::getInstance();
		return (int)$db->GetOne("SELECT COUNT(*) FROM ".geoTables::region." WHERE `parent` = ?", array($region_id));
	}
	

	public function getLevelNumber ($region)
	{
		$db = DataAccess::getInstance();
		return (int)$db->GetOne("SELECT `level` FROM ".geoTables::region." WHERE `id` = ?", array($region));		
	}
	
	/**
	 * Gets the friendly name for a region, in the current language
	 * @param int $region_id
	 * @return String
	 */
	public function getLabelFor ($region_id)
	{
		return geoRegion::getNameForRegion($region_id);
	}
	
	public function getDataFor($region_id)
	{
		$reg = geoAddon::getRegistry($this->name);
		$db = DataAccess::getInstance();

		$subdomain = $db->GetOne("SELECT `unique_name` FROM ".geoTables::region." WHERE `id` = ?", array($region_id));
		$return = array(
				'id' => $region_id,
				'label' => geoRegion::getNameForRegion($region_id),
				'link' => $this->getLinkForRegion($region_id, $subdomain)
		);
		
		//find out if this is the only region on this level
		if(count(geoRegion::getDirectSiblingsOfRegion($region_id)) == 1) {
			$return['onlyRegionOnLevel'] = true;
		}
		
		
		return $return;
	}
	
	public function getBreadcrumbFor ($regionName, $endLabelOnly = false)
	{
		$reg = geoAddon::getRegistry($this->name);
		
		$regions = geoRegion::getRegionWithParents($regionName);
		$return = array();
		foreach($regions as $region) {
			if($data = $this->getDataFor($region)) {
				$return[] = $data;
			}
		}
		
		return $return;
	}
	
	private $_linkVars;
	
	public function getLinkForRegion ($regionId, $subdomain)
	{
		if (!isset($this->_linkVars)) {
			$this->_linkVars['subdomains'] = (geoAddon::getRegistry($this->name)->subdomains == 'on');
			$this->_linkVars['domain'] = $this->getDomain(true).'/'.DataAccess::getInstance()->get_site_setting('classifieds_file_name').rtrim($this->getBaseUrl(true),'?&');
			$this->_linkVars['base_url'] = $this->getBaseUrl();
		}
		if(!isset($this->_linkVars['language_cookie'])) {
			//this gets its own "if" since the other three can be pre-set by usePostAsGet()
			$this->_linkVars['language_cookie'] = $this->_langCookieString($subdomain);	
		}
		$domain = $this->_linkVars['domain'];
		$base_url = $this->_linkVars['base_url'];
		//adjust both, replacing the part with this region ID
		$domain = str_replace(self::SEARCH_LOCATION_PLACEHOLDER, $regionId, $domain);
		$base_url = str_replace(self::SEARCH_LOCATION_PLACEHOLDER, $regionId, $base_url);
		return ($this->_linkVars['subdomains'] && $subdomain)? "http://{$subdomain}.{$domain}".$this->_linkVars['language_cookie'] : "{$base_url}region={$regionId}";
	}
	
	private function _langCookieString($subdomain)
	{
		//if this link will change the subdomain and we're using a non-default language,
		//we need to preserve the language cookie to the new subdomain
		if($subdomain && geoSession::getInstance()->getLanguage() != 1) {
			if(strpos($this->_linkVars['domain'], '?') !== false) {
				//there is at least one other URL parameter already set
				if(stripos($this->_linkVars['domain'], 'set_language_cookie') !== false) {
					//the language setting code is already present in the link
					//typically, this means the user just changed languages
					return '';
				} else {
					//set the cookie as coming after whatever other GET params are already in place
					return '&amp;set_language_cookie='.geoSession::getInstance()->getLanguage();
				}
			} else {
				//no other GET params -- make this the first
				return '?set_language_cookie='.geoSession::getInstance()->getLanguage();
			}
		} else {
			//not a subdomain link, or not using a non-default language
			return '';
		}
	}
	
	
	public function getRegionsForDisplay ($listingId, $userId)
	{
		$db = DataAccess::getInstance();
		
		//sanity checks
		$listingId = (int)$listingId;
		$userId = (int)$userId;
		
		if (!$listingId && !$userId) {
			//id NOT set
			return '';
		}
		
		$names = array();
		$regions = ($listingId) ? geoRegion::getRegionsForListing($listingId) : geoRegion::getRegionsForUser($userId);
		foreach($regions as $level => $region) {
			$name = geoRegion::getNameForRegion($region);
			if(!$name) {
				//region must be deleted -- try using the default name saved specifically for this
				$name = ($listingId) ? geoRegion::getDefaultNameForRegion($region, $listingId, 0) : geoRegion::getDefaultNameForRegion($region, 0, $userId);
			}
			$names[$level] = $name;
		}
		return $names;
	}
	
	
	public function usePostAsGet ()
	{
		$this->_linkVars['subdomains'] = (geoAddon::getRegistry($this->name)->subdomains == 'on');
		$this->_linkVars['domain'] = $this->getDomain(true).'/'.DataAccess::getInstance()->get_site_setting('classifieds_file_name').rtrim($this->getBaseUrl(true, true, true),'?&');
		$this->_linkVars['base_url'] = $this->getBaseUrl(false, true, true);
	}
	
	
	
	public function getDomain ($includeFolder = false)
	{
		//gets the domain
		$db = 1;
		include GEO_BASE_DIR . 'get_common_vars.php';
		
		$site = $db->get_site_setting('classifieds_url');
		$site = preg_replace("|^https?://(www\.)?|",'',$site);
		//clear off the end part
		if (!$includeFolder) {
			$site = preg_replace("/\/.*$/",'',$site);
		} else {
			$site = dirname($site);
		}
		
		return $site;
	}
	
	public function subdomainClean ($subdomain)
	{
		//make it lowercase and trim it
		$subdomain = trim(strtolower($subdomain));
			
		//replace spaces with hyphen
		$subdomain = preg_replace("/[\s]+/",'-',$subdomain);
		
		/**
		 * Changes applied, as per official specification of valid hostnames
		 * in RFC1123:
		 * - Only a-z, 0-9, and hyphens allows (along with . for part seperation)
		 * - each part cannot start or end with a -
		 * - each part cannot be more than 63 chars
		 */
		
		//can only contain a-z, 0-9, dots, and hyphens -
		$subdomain = preg_replace("/[^-a-z0-9.]+/",'',$subdomain);
		//cannot start or end with - or .
		$subdomain = trim($subdomain, '-.');
		
		//clean up each part of the subdomain
		$parts_raw = explode('.',$subdomain);
		$parts = array();
		foreach ($parts_raw as $part) {
			//make sure part is not more than 63 chars
			$part = substr($part, 0, 63);
			//cannot start or end in -
			$part = trim($part, '-');
			
			if (strlen($part)) {
				//there is something left after cleaning it, so add part to array of subdomain parts
				$parts[] = $part;
			}
		}
		//re-put-together parts
		$subdomain = implode('.',$parts);
		return $subdomain;
	}
	
	/**
	 * Determines whether a given subdomain is in use for a different region
	 * @param String $subdomain
	 * @param DEPRECATED $childClass -- this parameter is un-used.
	 * @param int $region_id
	 */
	public function subdomainUsed ($subdomain, $childClass='', $region_id=0)
	{
		$db = DataAccess::getInstance();
		//make sure it is string
		$subdomain = $subdomain.'';
		$count = (int)$db->GetOne("SELECT COUNT(*) FROM ".geoTables::region." WHERE `unique_name` = ? AND `id` <> ?", array($subdomain, $region_id));
				
		return ($count > 0);
	}
	
	public function getListingCounts ($regionId, $cat = 0)
	{
		$reg = geoAddon::getRegistry($this->name);
		$db = DataAccess::getInstance();
		
		$counts = array();
		if (!$regionId) {
			return $counts;
		}
		$classTable = geoTables::classifieds_table;
		$query = $db->getTableSelect(DataAccess::SELECT_BROWSE, true);
		//remove parts added by this own addon
		$query->where('', 'geographic_navigation_addon')
			->where("$classTable.`live`=1", 'live');
		
		$this->applyFilter($query, $regionId);
		
		if ($cat) {
			$query->where("$classTable.`category` ".geoCategory::getInStatement($cat), 'category');
		}
		
		//Allow other addons to manipulate the count query...
		geoAddon::triggerUpdate('geographic_navigation_region_listing_count', array('regionId' => $regionId, 'query' => $query));
		
		//ok we got the basic SQL, now just get counts...
		if (geoMaster::is('classifieds') && in_array($reg->countFormat, array('ca','ac','c'))) {
			//get classified count
			$counts['classifieds'] = $db->GetOne($query->where("$classTable.item_type=1",'item_type')->getCountQuery());
		}
		if (geoMaster::is('auctions') && in_array($reg->countFormat, array('ca','ac','a'))) {
			//auction count
			$counts['auctions'] = $db->GetOne($query->where("$classTable.item_type=2",'item_type')->getCountQuery());
		}
		if ($reg->countFormat=='all') {
			//note: don't check is_class_auctions here so that it works for all types on the demo
			//combined count
			$counts['all'] = $db->GetOne($query->where('','item_type')->getCountQuery());
		}
		
		return $counts;
	}
	
	private function _getText ()
	{
		return geoAddon::getText($this->auth_tag, $this->name);
	}
	

	public function getBaseUrl ($queryOnly = false, $usePost = false, $adjustSearch = false)
	{
		$db = DataAccess::getInstance();
		$skip_list = array('region','subregion');
		
		$postMap = array (
			'_action' => 'action',
			'_controller' => 'controller',
		);
		
		$parts = array();
		
		$get = ($usePost)? $_POST : $_GET;
		
		if ($adjustSearch && isset($get['a']) && $get['a']==19 && isset($get['b'])) {
			//adjust the base...  Will then replace the placeholder elsewhere...
			$get['b']['search_location'] = array (self::SEARCH_LOCATION_PLACEHOLDER);
		}
		
		foreach ($get as $key => $value) {
			if (!in_array($key,$skip_list)) {
				if ($usePost && isset($postMap[$key])) {
					//change "special" vars back to what they shoudl be
					$key = $postMap[$key];
				}
				if (is_array($value)) {
					foreach ($value as $sub_i => $sub_v) {
						if (is_array($sub_v)) {
							//need to go this far in.. don't bother recursive we don't need
							//infinit number of levels...  just this many
							foreach ($sub_v as $sub_sub_i => $sub_sub_v) {
								$parts[] = "{$key}[{$sub_i}][{$sub_sub_i}]={$sub_sub_v}";
							}
						} else {
							$parts[] = "{$key}[{$sub_i}]={$sub_v}";
						}
					}
				} else {
					$parts[] = "$key=$value";
				}
			}
		}
		$base = $db->get_site_setting('classifieds_url');
		if ($queryOnly) {
			$base = '';
		}
		if (count($parts)) {
			return $base.'?'.implode("&",$parts)."&";
		}
		return $base.'?';
	}
	
	public function getCurrentRegion ()
	{
		return geoView::getInstance()->geographic_navigation_region;
	}
	
	public function applyFilter ($query, $region_id)
	{
		$db = DataAccess::getInstance();
		
		$region_id = intval($region_id);
		if ($region_id) {
			$rtable = geoTables::listing_regions;
			$query->join($rtable, "$rtable.`listing`=".geoTables::classifieds_table.".`id`")
				->where("$rtable.`region`=$region_id", 'addon_geographic_navigation');
		}		 
	}
	
	public function applyFilterUser ($query, $region_id)
	{
		$db=DataAccess::getInstance();
		
		$userTable = geoTables::userdata_table;
		$rtable = geoTables::user_regions;
		
		$region_id = intval($region_id);
		if ($region_id) {
			$query->join($rtable, "$rtable.`user`=$userTable.`id`")
				->where("$rtable.`region`=$region_id", 'addon_geographic_navigation');
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	/*
	 * This next bit is used to show the regions in the search module (that is, the page header thingy)
	 * This could probably all stand to be rewritten to be more in line with the new way of doing regions
	 * but it works for now! 
	 */
	
	public function core_module_search_box_add_search_fields ($vars)
	{
		$page = $vars['page'];
		$show_module = $vars['show_module'];
	
		$reg = geoAddon::getRegistry($this->name);
		if (!$reg->showInSearchBox) {
			return '';
		}
	
		$view = geoView::getInstance();
		$view->addJScript('addons/geographic_navigation/regions.js');
	
		$tpl = new geoTemplate (geoTemplate::ADDON, 'geographic_navigation');
	
		$regionId = 0;
		$region = $this->getCurrentRegion();
		$tpl->assign('levels', $this->getLevelsFor($region));
		$tpl->assign('breakBetweenLevels', true);
	
		$msgs = $this->_getText();
		//cheat a little, set select to be different
		$msgs['selectRegions'] = $msgs['allRegionsSelect'];
		$tpl->assign('msgs', $msgs);
		$tpl->assign('showInSearchBox', geoAddon::getRegistry($this->name)->showInSearchBox);
	
		return $tpl->fetch('listing_region_select/levels.tpl');
	}
	
	
	/**
	 * Used by core_module_search_box_add_search_fields to get ajaxy levels
	 */
	public function getLevelsFor ($region_id = 0)
	{
		$db = DataAccess::getInstance();
		$reg = geoAddon::getRegistry($this->name);
	
		$region_id = (int)$region_id;
		
		$levels = $finalLevels = array();

		if($region_id) {
			$tree = geoRegion::getRegionWithParents($region_id);
			foreach($tree as $keyRegion) {
				//get each entire level of things that share a parent
				$level = geoRegion::getDirectSiblingsOfRegion($keyRegion);
				
				$data = array();
				foreach($level as $region) {
					$data[] = $this->getDataFor($region);
				}
				$levels[] = array(
						'regions' => $data,
						'selected' => $keyRegion,
						'count' => count($data)
				);
			}
		}
	
		//now add in the next level down from the starting region
		//still do this part even if !$region_id, to get the top-level regions
		$data = $this->getChildrenFor($region_id);	
		if(count($data)) {
			$levels[] = array(
					'regions' => $data,
					'count' => count($data)
			);
		}
		
		while(count($data) == 1) {
			//only one region here, so advance to next level automatically
			$data = $this->getChildrenFor($data[0]['id']);
			if(count($data)) {
				$levels[] = array(
						'regions' => $data,
						'count' => count($data)
				);
			}
		}
		

		return $levels;
	}
	
	public function ajaxSelectRegion ()
	{
		$reg = geoAddon::getRegistry($this->name);
	
		$fieldName = trim($_POST['fieldName']);
		$regionId = trim($_POST['region_id']);
		//figure out if it's a country, state, or region
	
	
		//Figure out the "pre" from the field name.
		$pre = substr($fieldName,0,strpos($fieldName,'['));
	
		//make sure it's allowed, to clean user input.
		$allowedPre = array ('b','c');
		$pre = (in_array($pre,$allowedPre))? $pre : 'c';
	
		$levels = $this->getLevelsFor($regionId);
	
		$tpl = new geoTemplate (geoTemplate::ADDON, 'geographic_navigation');
		$tpl->assign('levels', $levels);
		$tpl->assign('ajax', true);
		$tpl->assign('pre', $pre);
		$tpl->assign('msgs', $this->_getText());
		$tpl->assign('showInSearchBox', geoAddon::getRegistry($this->name)->showInSearchBox);
	
		echo $tpl->fetch('listing_region_select/levels.tpl');
	}
	
	/////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	
	public function ajaxChooseRegionBox ()
	{
		$reg = geoAddon::getRegistry($this->name);
		if (isset($_POST['region'])) {
			$regionId = $_POST['region'];
		} else {
			$regionId = (isset($_COOKIE['region']))? $_COOKIE['region'] : 0;
		}
	
		$regionId = intval($regionId);
		
		$levels = $this->getLevelsFor($regionId);
	
		$tpl = new geoTemplate (geoTemplate::ADDON, 'geographic_navigation');
		$tpl->assign('levels', $levels);
		$tpl->assign('currentRegionId', $regionId);
		$tpl->assign('msgs', $this->_getText());
		$tpl->assign('resetLink', $this->getLinkForRegion(0,''));
	
		$reg = geoAddon::getRegistry($this->name);
	
		$tpl->assign('dropdownThreshold', $reg->get('dropdownThreshold', 20));
	
		echo $tpl->fetch('choose_region_box/index.tpl');
	}
	
}
