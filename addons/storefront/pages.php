<?php
//addons/storefront/pages.php
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
## ##    7.2.4-5-gb71efb1
## 
##################################

# Storefront Addon

class addon_storefront_pages extends addon_storefront_info
{
	private $_storeId,$storeID, $_storeTemplateId, $_categories, $_pages, $_pageId, $_listingId, $_homelinkTxt;
	
	public function home()
	{
		$store_id = $this->storeID = $this->init();
		$db = true;
		include GEO_BASE_DIR . 'get_common_vars.php';
		
		$util = geoAddon::getUtil('storefront');
		
		if ($util->expirationTime($store_id) < geoUtil::time()) {
			$url = geoFilter::getBaseHref() . $db->get_site_setting('classifieds_file_name');
			if ($store_id == geoSession::getInstance()->getUserId()) {
				$url .= "?a=cart&action=new&main_type=storefront_subscription&storefront_need_sub=1";
			} else {
				$url .= "?a=6&b=$store_id";
			}
			header("Location: $url");
		}
		
		$on_hold = geoUser::getData($store_id, 'storefront_on_hold');
		if($on_hold == 1 && $store_id != geoSession::getInstance()->getUserId()) {
			//user has turned store off and this is not the store owner
			//redirect to seller's other listings
			$url = geoFilter::getBaseHref() . $db->get_site_setting('classifieds_file_name').'?a=6&b='.$store_id;
			header("Location: ".$url);
		}
		
		$util->setStoreId($store_id);
		
		$this->processTraffic($store_id);
		
		$this->update();
				
		
		if (!$this->_initView($store_id)) {
			return;
		}
		
		$view = geoView::getInstance();
		
		$view->is_owner = ($store_id == geoSession::getInstance()->getUserId()) ? true : false;
		
		//figure out what to display on the main page.
		if ($this->_pageId) {
			//displaying a page
			$view->addBody(geoString::fromDB($this->_pages[$this->_pageId]['body']));
		} else if ($this->_listingId) {
			//display specific listing
			$msgs = $db->get_text(true,1);
			$tree = $msgs['2'] . $this->_homelink.' > ';
			$listing = geoListing::getListing($this->_listingId);
			
			if ($listing && $listing->storefront_category) {
				$tree .= "<a href='{$this->_categories[$listing->storefront_category]['url']}'>{$this->_categories[$listing->storefront_category]['category_name']}</a> > ";
			}
			$view->category_tree = $tree;
			
			//find other ads by this seller in this category
			//and adjust the next/previous links accordingly
			//(do it here instead of using LockSetVarNewOnly because we want the text from the main page)
			$sql = "select id from ".geoTables::classifieds_table." where storefront_category = ? and live = 1 and seller = ? order by id ASC";
			$result = $db->Execute($sql, array($listing->storefront_category, $store_id));
			$catListings = array();
			if($result && $result->RecordCount() > 0) {
				$i = 0;
				while($line = $result->FetchRow()) {
					$catListings[$i++] = $line['id'];
				}
				foreach($catListings as $key => $id) {
					if($id == $this->_listingId) {
						$previous_link = ($catListings[$key-1]) ? $db->get_site_setting('classifieds_file_name').'?a=ap&amp;addon=storefront&amp;page=home&amp;store='.$store_id.'&amp;listing='.$catListings[$key-1] : '';
						$next_link = ($catListings[$key+1]) ? $db->get_site_setting('classifieds_file_name').'?a=ap&amp;addon=storefront&amp;page=home&amp;store='.$store_id.'&amp;listing='.$catListings[$key+1] : '';
						break;
					}
				}
			}
			$view->previous_ad_link = ($previous_link) ? '<a href="'.$previous_link.'" class="mini_button">'.$msgs[787].'</a>' : '';
			$view->next_ad_link = ($next_link) ? '<a href="'.$next_link.'" class="mini_button">'.$msgs[786].'</a>' : '';
			
			require_once CLASSES_DIR . 'browse_display_ad.php';
			$browse = new Display_ad;
			$view->getListingVarsOnly = true; //tell browse_display_ad.php to skip doing template stuff, 'cause we're gonna do it separately here
			$view->lockSetVarNewOnly();//make it so that the category tree can't be over-written
			
			//Use new Smarty templates, find which template is assigned to this page
			
			$view->setLanguage($db->getLanguage());
			$view->setCategory($listing->category);
			
			//also set the old-school category variable, then force it to re-get Fields
			$browse->site_category = $listing->category;
			$browse->get_configuration_data(); //do this to make sure the Storefront listing display has the correct category Fields to Use set 
			
			$page_id = 'addons/storefront/';
			$page_id .= ($listing->item_type == 1) ? 'classifieds_details_sub_template' : 'auctions_details_sub_template';
			$tpl_file = $view->getTemplateAttachment($page_id);
			
			//get site class to use in modules
			$view->setPage($browse);
			
			$view->loadModules($page_id);
			
			$view->setBodyTpl($tpl_file);
			
			$browse->display_classified($this->_listingId,false,false,false);
			$view->unLockSetVarNewOnly(); //un-lock to prevent any lasting damage..
		} else {
			//display listing list
			$this->_displayListings();
		}
		
		//Set the category ID to be the template selected
		if ($this->_storeTemplateId) {
			$view->setCategory($this->_storeTemplateId);
			//need to set category ID in site class as well or it over-writes what we set
			$site = Singleton::getInstance('geoSite');
			$site->site_category = $this->_storeTemplateId;
		}
	}
	
	
	function processTraffic($store_id) 
	{
		if(!$store_id) {
			return false;
		}
		
		$db = DataAccess::getInstance();
		$util = geoAddon::getUtil('storefront');
		$tables = $util->tables();
		//unix time stamp of current date
		$currentDate =  $util->timeToDate(geoUtil::time());
	
		$sql = "INSERT INTO $tables->traffic_cache SET
		owner=?,
		ip=?,
		time=?";
		$r = $db->Execute($sql,array($store_id,getenv('REMOTE_ADDR'),geoUtil::time()));
		if($r===false) {
			//die($db->ErrorMsg()."<br />");
		}
		if(geoUser::getData($store_id,'storefront_traffic_processed_at')>=$currentDate) {
			return false;
		}
		
		$sql = "SELECT * FROM $tables->traffic_cache WHERE `owner`=? AND `time` < $currentDate";
		$all = $db->GetAll($sql, array($store_id));
		
		$ips = $days = array();
		
		foreach ($all as $row) {
			$day = $util->timeToDate($row['time']);
			if (!isset($days[$day])) {
				$days[$day] = 0;
			}
			$days[$day] ++;
			$ips[$day][$row['ip']] = $row['ip'];
		}
		
		foreach ($days as $day => $tvisits) {
			$uvisits = count ($ips[$day]);
			$sql = "INSERT INTO $tables->traffic SET
				owner=?, 
				time=?, 
				uvisits=?,
				tvisits=?";
			$r = $db->Execute($sql,array($store_id,$day,$uvisits,$tvisits));
		}
		
		$sql = "DELETE FROM $tables->traffic_cache
		WHERE time < $currentDate AND 
		owner = ".$store_id;
		$result = $db->Execute($sql);
		if(!$result) return false; 
		
		$user = geoUser::getUser($store_id);
		if ($user) $user->storefront_traffic_processed_at = time();
		
		return true;
	}
	
	/**
	 * rolls back timestamps to midnight
	 *
	 * @param integer $time
	 * @return integer
	 */
	function timeToDate($time) {
		return trim(mktime(0,0,0,date("n",$time),date("j",$time),date("y",$time)));
	}
	
	/**
	 * gets storefront subscribers
	 * 
	 * @param object $db ADODB database object
	 * @return array email addresses
	 */
	function getSubscribers()
	{
		$db = DataAccess::getInstance();
		$util = geoAddon::getUtil('storefront');
		$tables = $util->tables();
		$sql = "SELECT * FROM ".$tables->users."
		WHERE store_id = ".$util->isOwner()."";
		$result = $db->Execute($sql);
		if($result===false) { 
			die('Error:'.$db->ErrorMsg());
		}
		
		$storefrontSubscribers = array();
		while($emailAddress = $result->FetchRow()) {
			array_push($storefrontSubscribers, $emailAddress["user_email"]);
		}
		
		return $storefrontSubscribers;
	}
	
	
	function init()
	{
		//Do NOT intval the store, it can be a username.
		$storeId = isset($_GET['store'])? trim($_GET['store']): 0;
		
		$util = geoAddon::getUtil('storefront');
		$storeId = $util->storeIdFromString($storeId);
		if (!$storeId) {
			//no ID number found
			$util->exitStore();
			return false;
		}
		return $storeId;
	}
	
	function logo()
	{
		$util = geoAddon::getUtil('storefront');
		$db = DataAccess::getInstance();
		$tables = $util->tables();
		
		static $logo;
		if(!is_object($logo)) {
			$logo = new stdClass();
		}
		
		$sql = "SELECT logo,logo_width width,logo_height height, logo_list_width, logo_list_height FROM $tables->user_settings WHERE owner=?";
		$r = $db->getrow($sql,array($util->getStoreId()));
		$logo->logo = $r['logo'];
		$logo->htmlSize = "style='width:{$r['width']}px; height:{$r['height']}px'";
		$logo->width = $r['width'];
		$logo->height = $r['height'];
		$logo->list_width = $r['logo_list_width'];
		$logo->list_height = $r['logo_list_height'];
		
		return $logo;
	}
	
	
	function control_panel()
	{
		$user_id = geoSession::getInstance()->getUserId();
		if(!$user_id) {
			//user not logged in
			//TODO: force auth page
			return false;
		}
		//make sure this user has a current subscription
		$util = geoAddon::getUtil('storefront');
		if(!$util->userHasCurrentSubscription($user_id)) {
			return false;
		}
		//let templates know what {$storefront_id} is in control panel
		$view = geoView::getInstance();
		$view->storefront_id = $user_id;
		
		require_once('control_panel.php');
		$cp = new geoStoreCP();
		$action = ($_GET['action'] === 'update') ? 'update' : 'display';
		
		$validActions = array('pages','customize','newsletter','main');
		$action_type = (in_array($_GET['action_type'], $validActions)) ? $_GET['action_type'] : 'main';
		
		$function = $action . '_' . $action_type;
		
		$data = ($_POST['data']) ? $_POST['data'] : null;
		
		
		
		$result = $cp->$function($data);
		
		if($function === 'update_main' && isset($data['fromPage'])) {
			//special case, just updated main on/off switch
			//return to whatever page we were on before
			$display_function = "display_".$data['fromPage'];
		} else {
			$display_function = "display_".$action_type;
		}
		if($action === 'update') {
			if($result === false) {
				//failed to update
				$cp->$display_function(false);
			} else {
				//update OK
				$cp->$display_function(true);
			}
		}
		
		return ''; 
	}
	
	/**
	 * Initializes the view and assigns all the stuff to it that is needed
	 *
	 */
	private function _initView ($store_id)
	{
		$view = geoView::getInstance();
		$db = DataAccess::getInstance();
		$util = geoAddon::getUtil('storefront');
		$tables = $util->tables();
		
		//set up settings
		$setting = geoAddon::getRegistry('storefront');
		if ($setting) {
			$view->storefront = $setting->toArray();
		}
		
		$sql = "SELECT * FROM $tables->user WHERE store_id=?";
		$r = $db->Getrow($sql,$store_id);
		
		//figure out what storefront template to use
		$user = geoUser::getUser($store_id);
		if (!is_object($user)) {
			$util->exitStore();
			return false;
		}
		//let templates know $storefront_id
		$this->_storeId = $view->storefront_id = $store_id;
		
		//get template ID to use, in case it has changed on this pageload
		$this->_storeTemplateId = $user->storefront_template_id;
		
		$tables = $util->tables();
		//get the storefront categories
		$sql = "SELECT `category_id`, `category_name` FROM ".$tables->categories." WHERE `owner` = ? AND `parent` = 0 ORDER BY `display_order`";
		$categories = $db->GetAll($sql, array($store_id));
		
		$sql = "SELECT `category_id`, `category_name` FROM ".$tables->categories." WHERE `owner` = ? AND `parent` = ? ORDER BY `display_order`";
		$getSubcategories = $db->Prepare($sql);
		
		//$allCats is a bit of a hack to make the new subcategories work with the old code. make sure category navigation still works if you change anything here
		$cats = $allCats = array();
		foreach ($categories as $cat) {
			$allCats[$cat['category_id']] = $cats[$cat['category_id']] = array(
				'url'=> $db->get_site_setting('classifieds_file_name').'?a=ap&amp;addon=storefront&amp;page=home&amp;store='.$store_id.'&amp;category='.$cat['category_id'],
				'category_name' => $cat['category_name'],
				'category_id' => $cat['category_id']
			);
			
			$subs = $db->Execute($getSubcategories, array($store_id, $cat['category_id']));
			foreach($subs as $sub) {
				$allCats[$sub['category_id']] = $cats[$cat['category_id']]['subcategories'][$sub['category_id']] = array(
					'url' => $db->get_site_setting('classifieds_file_name').'?a=ap&amp;addon=storefront&amp;page=home&amp;store='.$store_id.'&amp;category='.$sub['category_id'],
					'category_name' => $sub['category_name'],
					'category_id' => $sub['category_id']
				);
			}
		}
		if (count($cats)) {
			$view->storefront_categories = $cats;
			$this->_categories = $allCats; //$this->_categories needs everything on the same "level"
		}
		$view->storefront_category_count = count($cats);
		
		//get the storefront pages
		$sql = "SELECT `page_id`, `page_link_text`, `page_name`, `page_body` FROM ".$tables->pages." WHERE `owner` = ? ORDER BY `display_order`";
		$pages = $db->GetAll($sql, array($store_id));
		$storefront_pages = array();
		
		foreach ($pages as $page) {
			$storefront_pages[$page['page_id']] = array(
				'url'=>$db->get_site_setting('classifieds_file_name').'?a=ap&amp;addon=storefront&amp;page=home&amp;store='.$store_id.'&amp;p='.$page['page_id'],
				'link_text' => $page['page_link_text'],
				'page_id' => $page['page_id'],
				'name' => $page['page_name'],
				'body' => $page['page_body']
			);
		}
		if (count($storefront_pages)) {
			$view->storefront_pages = $this->_pages = $storefront_pages;
			if (isset($_GET['p']) && $_GET['p'] && isset($storefront_pages[$_GET['p']])) {
				$view->storefront_page = $storefront_pages[$_GET['p']];
				$this->_pageId = $storefront_pages[$_GET['p']]['page_id'];
			} elseif($_GET['p'] == 'home' || $_GET['category'] || $_GET['listing']) {
				//looking for a specific page, so don't use default page
			} elseif($util->default_page) {
				//show the owner's choice of default Page
				$this->_pageId = $util->default_page;
			}
		}
		$listingId = intval((isset($_GET['listing']) && $_GET['listing'])? $_GET['listing']: 0);
		if ($listingId) {
			//verify
			$listing = geoListing::getListing($listingId);
			if (!is_object($listing)) {
				$listingId = 0;
			}
		}
		$this->_listingId = $listingId;

		$view->storefront_messages = geoAddon::getInstance()->getText('geo_addons','storefront');
		
				
		$logo = $this->logo();
		
		//replace storefront logo
		$view->storefront_logo = '';
		if ($logo->logo) {
			$view->storefront_logo = "<img src='addons/storefront/images/{$logo->logo}' id='logo' alt='{$util->storefront_name}' {$logo->htmlSize}/>";
			
			$view->logo_width = $logo->width;
			$view->logo_height = $logo->height;
			$view->logo_list_width = $logo->list_width;
			$view->logo_list_height = $logo->list_height;
			$view->logo2 = "<img src='addons/storefront/images/{$logo->logo}' id='logo' alt='{$util->storefront_name}' style='width:50px'/>";
		} else {
			//no logo, so use default logo
			$view->storefront_logo = '<img src="addons/storefront/images/addon_storefront_logo.gif" alt="Your logo here!" />';
		}
				
		//replace storefront welcome note
		$view->storefront_welcome_note = $util->user_welcome_message;
		
		$view->home_link = ($util->user_home_link)? $util->user_home_link: $user->username;
		$view->storefront_homelink = $this->_homelink = "<a href='".$db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=$store_id&amp;p=home'>$view->home_link</a>";
		
		//storefront e-mail added
		$view->storefront_email_added = (isset($_COOKIE["emailAdded_".$store_id]) && $_COOKIE["emailAdded_".$store_id])? true: false;
		
		$view->display_newsletter = $util->display_newsletter;
		
		//{storefront_manager} is a special tag
		$tpl = $view->getTemplateObject();
		$tpl->registerPlugin('function', 'storefront_manager',array('addon_storefront_util','displayStorefrontManager'));
		
		//need to assign to storefront manager old-school slow way, since using old DB-based templates
		$tpl = new geoTemplate('addon','storefront');
		$tpl->assign($view->getAllAssignedVars());
		
		#die(print_r($manager,1));
		$view->storefront_manager = ($util->isOwner())? $tpl->fetch('manager.tpl'): '';
		
		return true;
	}
	
	public function list_stores()
	{
		$reg = geoAddon::getRegistry('storefront');
		$stores_per_page = $reg->get('list_max_stores',25);
		
		$page_num =  ( isset( $_GET['p'] )? intval($_GET['p']) : 1);	
		$db = DataAccess::getInstance();
		
		$tpl_vars = array();
		$tpl_vars['text'] = $text = geoAddon::getText('geo_addons','storefront');
		$util = geoAddon::getUtil('storefront');
		
		$table = $util->tables();
		
		$query = new geoTableSelect();
		
		$subTable = $table->subscriptions;
		$userTable = geoTables::userdata_table;
		$userRegionsTable = geoTables::user_regions;
		
		$query->from($subTable, array('`user_id`'))
			->join($userTable,"$subTable.`user_id` = $userTable.`id`",array('`username`'))
			->where("$subTable.`expiration` > ".geoUtil::time())
			->where("$userTable.`storefront_on_hold`=0")
			->order("$userTable.username")
			->limit(($page_num-1)*$stores_per_page, $stores_per_page);
		
		//figure out what level 'state' is on
		$regionOverrides = geoRegion::getLevelsForOverrides();
		$stateLevel = $regionOverrides['state'] ? $regionOverrides['state'] : false;
		
		if($_POST['storefront_state_filter']) {
			$filter_state = $_POST['storefront_state_filter'];
			if($filter_state == -1) {
				//unset cookie
				setcookie('storefront_state_filter', '-', 1, '/'); //1 to expire cookie now
				$filter_state = false;
			} else {
				setcookie('storefront_state_filter', $filter_state, 0, '/'); //0 to expire at end of browser session
			}
		} elseif($_COOKIE['storefront_state_filter']) {
			//filter set in cookie from a previous pageload
			$filter_state = $_COOKIE['filter_state'];
		}
		if ($filter_state && $stateLevel) {
			$query->join($userRegionsTable, "$subTable.`user_id` = $userRegionsTable.`user`")
				->where("$userRegionsTable.`level` = ".$stateLevel)
				->where($db->quoteInto("$userRegionsTable.`region` = ?", $filter_state));
		}
		$tpl_vars['filter_state'] = $filter_state;
		
		if ($reg->geonav_filter_storefronts && geoView::getInstance()->geographic_navigation_region) {
			//figure out how to filter...
			$geo = geoAddon::getUtil('geographic_navigation');
			$geo->applyFilterUser($query, geoView::getInstance()->geographic_navigation_region);
		}
		
		$rs = $db->Execute(''.$query);
		
		$total_row = (int)$db->GetOne(''.$query->getCountQuery());
		
		//pull a list of all the "states" of active stores for the "filter" dropdown
		$sql = "SELECT DISTINCT r.region
				FROM ".$table->subscriptions." as sub, ".geoTables::user_regions." as r, ".geoTables::userdata_table." as user  
				WHERE sub.expiration > ".geoUtil::time()." AND user.storefront_on_hold = 0 AND user.id = sub.user_id AND sub.user_id = r.user AND r.level = ".$stateLevel;
		$state_result = $db->Execute($sql);
		$states = array();
		while($state_result && $line = $state_result->FetchRow()) {
			$states[$line['region']] = geoRegion::getNameForRegion($line['region']);
		}
		$tpl_vars['states'] = $states;
		
		//normal assign type thingies
		
		if ( !$rs ) {
			trigger_error('ERROR STATS SQL: Sql: '.$sql.' Error Msg: '.$db->ErrorMsg().' - list stores failed.');
			return 'Storefront List Error.';
		}
		$stores = array();
		while ( $user = $rs->FetchRow() ) {
			$user_name = $user["username"];
			$user_id = intval($user["user_id"]);
			$count_sql = "SELECT count(*) as count FROM ".geoTables::classifieds_table." WHERE seller = ".$user_id." AND `live`=1";
			$count_row = $db->GetRow( $count_sql );
			$count = $count_row['count'];
			
			$user_sql = "SELECT username, city, state, zip FROM ".geoTables::userdata_table." WHERE id = ".$user_id;
			$user_row = $db->GetRow( $user_sql );

			$store_sql = "SELECT logo, logo_width, logo_list_width, logo_height, logo_list_height, welcome_message from geodesic_addon_storefront_user_settings where owner = ".$user_id;
			$store_row = $db->GetRow($store_sql);
			
			//if "list" width/height values aren't set yet for logo, fallback is "normal" values
			$width = ($store_row['logo_list_width']) ? $store_row['logo_list_width'] : $store_row['logo_width'];
			$height = ($store_row['logo_list_height']) ? $store_row['logo_list_height'] : $store_row['logo_height'];
			//if, for some reason, those values exceed admin-configured max, for the admin setting
			if($reg) {
				$max_width = $reg->max_logo_width;
				$max_height = $reg->max_logo_height;
				$width = ($max_width && ($width > $max_width || !$width)) ? $max_width : $width;
				$height = ($max_height && ($height > $max_height || !$height)) ? $max_height : $height;
			}
			$description_length = $db->get_site_setting('length_of_description') ? $db->get_site_setting('length_of_description') : 20;
			
			$sql = "SELECT `storefront_name` FROM `geodesic_addon_storefront_user_settings` WHERE `owner` = ?";
			$store_name = $db->GetOne($sql, array($user_id));
			
			if(!$store_name) {
				//storefront name not set -- default to username
				$store_name = $user_name;
			}
			
			//get state (and optionally city) from region info
			$state = geoRegion::getStateNameForUser($user_id);
			if($regionOverrides['city']) {
				$userRegions = geoRegion::getRegionsForUser($user_id);
				$city = geoRegion::getNameForRegion($userRegions[$regionOverrides['city']]);
			} else {
				$city = $user_row['city'];
			}
			
			$stores[] = array ( 
				"image" => "<img src='addons/storefront/images/".(($store_row['logo'])?$store_row["logo"]:'addon_storefront_logo.gif')."' alt='' style=\"width: ".$width."px; height: ".$height."px;\" />",
				"title" => $user_row['username'],
				"userid" => $user_id,
				"name" => $store_name,
				"items" => $count,
				"desc" => geoFilter::listingShortenDescription(geoFilter::replaceDisallowedHtml($store_row['welcome_message'],1),$reg->get('list_description_length',30)),
				"city" => $city,
				"state" => $state,
				"zip" => $user_row['zip']
			);
			
		}
		$tpl_vars['stores'] = $stores;
		
		$switches = array (
			'logo' => $reg->list_show_logo,
			'title' => $reg->list_show_title,
			'num_items' => $reg->list_show_num_items,
			'description' => $reg->list_show_description,
			'city' => $reg->list_show_city,
			'state' => $reg->list_show_state,
			'zip' => $reg->list_show_zip
		);
		$tpl_vars['switches'] = $switches;
		
		// pagination
		if ($stores_per_page < $total_row) {
			$tpl_vars['show_pagination'] = true;
			
			$tpl_vars['totalPages'] = $totalPages = ceil($total_row / $stores_per_page);
			$tpl_vars['currentPage'] = $page_num;
			$link = $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=list_stores&amp;p=";
			
			$css = "";
			$tpl_vars['pagination'] = geoPagination::getHTML($totalPages, $page_num, $link, $css);
			$body .= $tpl_vars['pagination'];
		}
		geoView::getInstance()->setBodyTpl('list_all_stores.tpl','storefront')
			->setBodyVar($tpl_vars);
		return '';
	}
	
	
	public function generic ()
	{	
		$this->home();
	}
	
	/**
	 * receives AJAX request from control panel "pages" settings
	 * and passes it on to process function
	 *
	 */
	public function control_panel_ajax()
	{
		$user_id = geoSession::getInstance()->getUserId();
		if(!$user_id) {
			//user not logged in
			return false;
		}
		//make sure this user has a current subscription
		$util = geoAddon::getUtil('storefront');
		if(!$util->userHasCurrentSubscription($user_id)) {
			return false;
		}
		
		require_once('control_panel.php');
		$cp = new geoStoreCP();
		
		$cp->doAjax($_POST);
		geoView::getInstance()->setRendered(true);
	}
	
	public function check_name_ajax()
	{
		//so the view class doesn't try and print anything
		geoView::getInstance()->setRendered(true);
		
		$user_id = geoSession::getInstance()->getUserId();
		if(!$user_id) {
			//user not logged in
			return false;
		}
		//make sure this user has a current subscription
		$util = geoAddon::getUtil('storefront');
		if(!$util->userHasCurrentSubscription($user_id)) {
			return false;
		}
		
		$userid = geoSession::getInstance()->getUserId();
		
		$name = trim($_POST['name_to_check']);
		//remove any HTML
		$name = strip_tags(geoString::specialCharsDecode($name));
		
		if(!$name) {
			return false;
		}
		
		$db = DataAccess::getInstance();
		//preliminary username check (before cleaning badwords)
		if(is_numeric($name)) {
			//pure-numeric store names won't fly
			exit('INVALID');
		}
		
		$sql = "select username from geodesic_userdata where username = ? and id <> ?";
		$result = $db->Execute($sql, array($name, $userid));
		if($result->RecordCount() > 0) {
			//this is someone else's username
			exit('INVALID');
		}
		
		$site = Singleton::getInstance('geoSite');
		$original = $name;
		$name = $site->check_for_badwords($name);		

		//check username again now that badwords are gone, but only if name has changed
		if($original !== $name) {
			$sql = "select username from geodesic_userdata where username = ? and id <> ?";
			$result = $db->Execute($sql, array($name, $userid));
			if($result->RecordCount() > 0) {
				//this is someone else's username
				exit('INVALID');
			}
		}
		
		//now clean for URLs
		$name = preg_replace("/[^a-zA-Z0-9_]+/", ' ', $name); //replace any invalid characters with whitespace
		$name = preg_replace("/\s+/", '-', $name); //replace any whitespace with hyphens
		
		//check cleaned name against other names already stored in the DB.
		$sql = "select seo_name from geodesic_addon_storefront_user_settings where seo_name = ? AND owner <> ?";
		$result = $db->Execute($sql, array($name, $userid));
		if($result->RecordCount() > 0) {
			//name already in use
			exit('IN_USE');
		}
		
		//so far, so good -- allow submission of this name
		exit('OK');
		
	}
	
	
	function getSubscription()
	{
		$db = DataAccess::getInstance();
		$table = geoAddon::getutil("storefront")->tables();
		$sql = "SELECT * FROM $table->subscriptions WHERE user_id ='$this->store_id'";
		$r = $db->getrow($sql);
		if(!$r) {
			return false;			
		}
		$expiresAt = $r['expiration'];
		if(geoUtil::time()>=$expiresAt) {
			return false;
		}
		return true;
	}
	
	private function _displayListings ()
	{
		$db = DataAccess::getInstance();
		$site = Singleton::getInstance('geoSite');
		$tables = geoAddon::getUtil('storefront')->tables();
		$setting = geoAddon::getRegistry('storefront');
		$body = '';
		$category_id = intval((isset($_GET['category']))? $_GET['category']: 0);
		if ($category_id && !isset($this->_categories[$category_id])) {
			//invalid category id
			$category_id = 0;
		}
		$listing_id = intval((isset($_GET['listing']))? $_GET['listing']: 0);
		$listing = 0;
		if ($listing_id) {
			$listing = geoListing::getListing($listing_id);
			if (!is_object($listing)) {
				$listing_id = $listing = 0;
			}
		}
		$page = intval((isset($_GET['page_result']))? $_GET['page_result']: 1);
		if ($page <= 0) $page = 1;
		$store_id = intval($this->_storeId);
		if (!$store_id) {
			//can't do anything without a store id.
			return false;
		}
		$tpl_vars = array();
		//TODO: Move these to use storefront text eventually, when done be sure
		//to remove the old page 10003 from system as it isn't used elsewhere
		$db->get_text(false,3);
		$msgs = $db->get_text(true,10003);
		
		if(isset($_GET['c'])) {
			$sort_type = intval($_GET['c']);
			$use_default_orders = false;
		} else {
			$auctions_default_order = ($db->get_site_setting('default_auction_order_while_browsing')) ? $db->get_site_setting('default_auction_order_while_browsing') : 0;
			$class_default_order = ($db->get_site_setting('default_classifed_order_while_browsing')) ? $db->get_site_setting('default_classifed_order_while_browsing') : 0;
			$use_default_orders = true;
			$sort_type = 0;
		}
		if ($sort_type < 0) $sort_type = 0;
		
		$tpl_vars['sort_type'] = $sort_type;
		
		$query = $db->getTableSelect(DataAccess::SELECT_BROWSE, true);
		$classTable = geoTables::classifieds_table;
		
		$query->where("$classTable.`live`=1",'live')
			->where("$classTable.`seller`=$store_id")
			->where("$classTable.`ends` > ".geoUtil::time());
		
		if ($category_id) {
			//category_id is a cleaned int
			//$query->where("$classTable.`storefront_category` = $category_id");
			$query->where("$classTable.`storefront_category` IN (SELECT `category_id` FROM {$tables->categories} WHERE `category_id` = $category_id OR `parent` = $category_id)");
		}
		
		$results_per_page = $db->get_site_setting('number_of_ads_to_display');
		$limit = ($page - 1) * $results_per_page;
		$query->limit($limit, $results_per_page);
		$listingsA = array();
		$listing_type_allowed = $db->get_site_setting('listing_type_allowed');
		//get results for classifieds
		if (geoMaster::is('classifieds') && $listing_type_allowed != 2) {
			$classQuery = clone $query;
			
			$classQuery->where("$classTable.`item_type`=1");
			if ($use_default_orders) {
				$this->_getOrderByClause($class_default_order, $classQuery);
			} else {
				//default ordering
				$this->_getOrderByClause($sort_type, $classQuery);
			}
			$listingsA[1] = $db->GetAll(''.$classQuery);
			
			$tpl_vars['display_classifieds'] = 1;
		} else {
			$tpl_vars['display_classifieds'] = 0;
		}
		if (geoMaster::is('auctions') && $listing_type_allowed != 1) {
			$auctionQuery = clone $query;
			
			$auctionQuery->where("$classTable.`item_type`=2");
			if ($use_default_orders) {
				$this->_getOrderByClause($auctions_default_order, $auctionQuery);
			} else {
				//default ordering
				$this->_getOrderByClause($sort_type, $auctionQuery);
			}
			
			$listingsA[2] = $db->GetAll(''.$auctionQuery);
			
			$tpl_vars['display_auctions'] = 1;
		} else {
			$tpl_vars['display_auctions'] = 0;
		}
		//we're done with the query now
		unset($query);
		
		$messages = $db->get_text(true);
		$no_image_url = ($messages[500795])? geoTemplate::getUrl('',$messages[500795]) : '';
		$photo_icon_url = ($messages[500796])? geoTemplate::getUrl('',$messages[500796]) : '';
		foreach ($listingsA as $browse_type => $listings) {
			foreach ($listings as $id => $listing) {
				if ($setting->display_photo_icon) {
					$html = '';
					
					if ($db->get_site_setting('photo_or_icon') == 1) {
						if ($listing['image'] > 0) {
							$html = geoImage::display_thumbnail($listing['id'],0,0,0,$store_id,'store');
						} else if ($no_image_url && (!$listing['image'])) {
							if (($db->get_site_setting('popup_while_browsing'))
								&& ($db->get_site_setting('popup_while_browsing_width'))
								&& ($db->get_site_setting('popup_while_browsing_height')))
							{
								$html .= "<td class='listing_column'><a href=\"";
								$html .= $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$store_id."&amp;";
								$html .= "listing=".$listing['id']."\" ";
								$html .= "onclick=\"window.open(this.href,'_blank','width=".$db->get_site_setting('popup_while_browsing_width').",height=".$db->get_site_setting('popup_while_browsing_height').",scrollbars=1,location=0,menubar=0,resizable=1,status=0'); return false;\" class=".$css_class_tag.">";
								$html .="<img src='$no_image_url' alt='' /></a></td>";
							} else {
								$html .="<td class='listing_column'><a href=\"";
								$html .= $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$store_id."&amp;";
								$html .="listing=".$listing['id']."\">";
								$html .="<img src='$no_image_url' alt='' /></a></td>";
							}
						} else {
							$html .="<td id='listing_column'>&nbsp;</td>\n\t";
						}
					} else {
						if ($listing['image'] > 0) {
							if (($db->get_site_setting('popup_while_browsing'))
								&& ($db->get_site_setting('popup_while_browsing_width'))
								&& ($db->get_site_setting('popup_while_browsing_height')))
							{
								$html .= "<td class='listing_column'><a href=\"";
								$html .= $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$store_id."&";
								$html .= "l=".$listing['id']."\" ";
								$html .= "onclick=\"window.open(this.href,'_blank','width=".$db->get_site_setting('popup_while_browsing_width').",height=".$db->get_site_setting('popup_while_browsing_height').",scrollbars=1,location=0,menubar=0,resizable=1,status=0'); return false;\" class=".$css_class_tag.">";
								$html .="<img src='$photo_icon_url' alt='' /></a></td>";
							} else {
								$html .= "<td class='listing_column'><a href=\"";
								$html .= $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$store_id."&amp;";
								$html .= "l=".$listing['id']."\">";
								$html .= "<img src='$photo_icon_url' alt='' /></td>";
							}
						} else if ($no_image_url && (!$listing['image'])) {
							if (($db->get_site_setting('popup_while_browsing'))
								&& ($db->get_site_setting('popup_while_browsing_width'))
								&& ($db->get_site_setting('popup_while_browsing_height')))
							{
								$html .= "<td class='listing_column'><a href=\"";
								$html .= $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$store_id."&amp;";
								$html .= "l=".$listing['id']."\" ";
								$html .= "onclick=\"window.open(this.href,'_blank','width=".$db->get_site_setting('popup_while_browsing_width').",height=".$db->get_site_setting('popup_while_browsing_height').",scrollbars=1,location=0,menubar=0,resizable=1,status=0'); return false;\" class=".$css_class_tag.">";
								$html .="<img src='$no_image_url' alt='' /></td>";
							} else {
								$html .="<td class='listing_column'><a href=".$db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$store_id."&amp;";
								$html .="listing=".$listing['id'].">";
								$html .="<img src='$no_image_url' alt='' /></td>";
							}
						} else {
							$html .="<td class='listing_column'>&nbsp;</td>\n\t";
						}
					}
					$listingsA[$browse_type][$id]['image_icon'] = $html;
				}
				$version = geoPC::getVersion();
				if($listing['precurrency']) {
					$listingsA[$browse_type][$id]['precurrency'] = (version_compare($version, '4.2.0','<')) ? geoString::fromDB($listing['precurrency']) : $listing['precurrency'];
				}
				if($listing['postcurrency']) {
					$listingsA[$browse_type][$id]['postcurrency'] = (version_compare($version, '4.2.0','<')) ? geoString::fromDB($listing['postcurrency']) : $listing['postcurrency'];
				}
				if($listing['item_type'] == 2) {
					if ((strlen($messages[500799]) >0)) {
						$number_of_bids = geoListing::bidCount($listing['id']);
						if (($listing['buy_now']!= 0) && (($listing['current_bid'] == 0) || ($db->get_site_setting('buy_now_reserve') && $listing['current_bid'] < $listing['reserve_price']))) {
							$listingsA[$browse_type][$id]['title_extra_txt'] .= "<img src='".geoTemplate::getUrl('',$messages[500799])."' alt='' />";
						}
					}								
					if (strlen($messages[500800]) >0) {
						if ( $listing['reserve_price'] != 0) {
							if ($listing['current_bid'] >= $listing['reserve_price']) {
								$listingsA[$browse_type][$id]['title_extra_txt'] .= "<img src='".geoTemplate::getUrl('',$messages[500800])."' alt='' />";
							}
						}
					}				
					if (strlen($messages[500802]) >0) {
						if ($listing['reserve_price'] == 0.00) {
							$listingsA[$browse_type][$id]['title_extra_txt'] .= "<img src='".geoTemplate::getUrl('',$messages[500802])."' alt='' />";
						}
					}
					if (strlen($messages[501665]) && $listing['reserve_price']>$listing['current_bid']) {
						$listingsA[$browse_type][$id]['title_extra_txt'] .= "<img src='".geoTemplate::getUrl('',$messages[501665])."' alt='' />";
					}
				}
				
				//clean up description in php
				$description = $listing['description'];
				$description = geoFilter::listingDescription($description); //does fromDB, remove bad tags/words
				if (!$db->get_site_setting('display_all_of_description')) {
					$description = geoFilter::listingShortenDescription($description, $db->get_site_setting('length_of_description')); //shorten
				}
				$listingsA[$browse_type][$id]['clean_desc'] = $description;
				
				if ($setting->display_number_bids) {
					//number of bids
					$listingsA[$browse_type][$id]['number_bids'] = geoListing::bidCount($listing['id']);
				}
				if ($browse_type == 2 && $setting->display_price) {
					//for auctions, put price as the right value for the bid
					if ($listing['buy_now_only']) {
						$listingsA[$browse_type][$id]['price'] = $listing['buy_now'];
					} elseif ($listing['minimum_bid'] != 0) {
						if ($listing['minimum_bid'] < $listing['starting_bid']) {
							$listing['minimum_bid'] = $listingsA[$browse_type][$id]['minimum_bid'] = $listing['starting_bid'];
						}
						$listingsA[$browse_type][$id]['price'] = $listing['minimum_bid'];
					} elseif ($listing['starting_bid'] != 0) {
						$listingsA[$browse_type][$id]['price'] = $listing['starting_bid'];
					}
				}
				
				if($setting->display_price) {
					$unformatted = $listingsA[$browse_type][$id]['price'];
					$version = geoPC::getVersion();
					if (version_compare($version, '4.2.0','<')) {
						$pre = geoString::fromDB($listing['precurrency']);
						$post = geoString::fromDB($listing['postcurrency']);
					} else {
						$pre = $listing['precurrency'];
						$post = $listing['postcurrency'];
					}
					$listingsA[$browse_type][$id]['price'] = geoString::displayPrice($unformatted,$pre,$post);
				}
				
				if(($listing['item_type'] == 1 && $setting->display_entry_date) || ($listing['item_type'] == 2  && $setting->auction_entry_date)) {
					$listingsA[$browse_type][$id]['entry_date'] = date($db->get_site_setting('entry_date_configuration'), $listing['date']);
				}
				
				if($setting->display_time_left
					&& (($listing['item_type'] == 2) 
						|| ($setting->classified_time_left && ($listing['item_type'] == 1))))
				{
					$weeks = $site->DateDifference('w',geoUtil::time(),$listing["ends"]);
					$remaining_weeks = ($weeks * 604800);
	
					// Find days left
					$days = $site->DateDifference('d',(geoUtil::time()+$remaining_weeks),$listing["ends"]);
					$remaining_days = ($days * 86400);
	
					// Find hours left
					$hours = $site->DateDifference('h',(geoUtil::time()+$remaining_days),$listing["ends"]);
					$remaining_hours = ($hours * 3600);
	
					// Find minutes left
					$minutes = $site->DateDifference('m',(geoUtil::time()+$remaining_hours),$listing["ends"]);
					$remaining_minutes = ($minutes * 60);
	
					// Find seconds left
					$seconds = $site->DateDifference('s',(geoUtil::time()+$remaining_minutes),$listing["ends"]);
					if ($weeks > 0) {
						$listingsA[$browse_type][$id]['time_left'] = "$weeks $msgs[103003], $days $msgs[103004]";
					} else if ($days > 0) {
						$listingsA[$browse_type][$id]['time_left'] = $days." ".$msgs[103004].", ".$hours." ".$msgs[103005];
					} else if ($hours > 0) {
						$listingsA[$browse_type][$id]['time_left'] = $hours." ".$msgs[103005].", ".$minutes." ".$msgs[103006];
					} else if ($minutes > 0) {
						$listingsA[$browse_type][$id]['time_left'] = $minutes." ".$msgs[103006].", ".$seconds." ".$msgs[103007];
					} else if ($seconds > 0) {
						$listingsA[$browse_type][$id]['time_left'] = $seconds." ".$msgs[103007];
					} else {
						// If closed we want to display closed text
						$listingsA[$browse_type][$id]['time_left'] = "<div class='auction_closed'>$msgs[100051]</div>";
					}
				}
				
				//optional field formatting
				if(geoPC::is_ent()) {
					$fields = geoFields::getInstance(geoUser::getData($listing['seller'],'group_id'),0);
					for($i=1; $i<=20; $i++) {
						$fieldName = 'optional_field_'.$i;
						$fieldType = $fields->$fieldName->field_type;
						if($fieldType == 'cost') {
							$listingsA[$browse_type][$id][$fieldName] = geoString::displayPrice($listing[$fieldName],$listing['precurrency'], $listing['postcurrency'], 'listing');
						} elseif($fieldType == 'date') {
							$listingsA[$browse_type][$id][$fieldName] = geoCalendar::display($listing[$fieldName], true);
						}
					}
				}
				
				//use new regions stuff to show country and state
				$listingsA[$browse_type][$id]['location_state'] = geoRegion::getStateNameForListing($listing['id']);
				$listingsA[$browse_type][$id]['location_country'] = geoRegion::getCountryNameForListing($listing['id']);
			}
		}
		$tpl_vars['all_listings'] = $listingsA;
		$countClass = $tpl_vars['total_classifieds'] = $classQuery ? $db->GetOne(''.$classQuery->getCountQuery()) : 0;
		$countAuc = $tpl_vars['total_auctions'] = $auctionQuery ? $db->GetOne(''.$auctionQuery->getCountQuery()) : 0;
		$count = $countClass + $countAuc;
		
		//cleanup query objects, now that the counting is done.
		if(isset($classQuery)) unset($classQuery);
		if(isset($auctionQuery)) unset($auctionQuery);		
		
		$countMax = $tpl_vars['total_all'] = (($countClass > $countAuc)? $countClass: $countAuc);
		$tpl_vars['page_num'] = $page;
		
		if ($countMax > $db->get_site_setting('number_of_ads_to_display')) {
			//set up pagination if all the ads don't fit on one page.
			$totalPages = $tpl_vars['total_pages'] = ceil($countMax / $db->get_site_setting('number_of_ads_to_display'));
			$link = $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store={$store_id}&amp;category={$category_id}&amp;c={$sort_type}".(($_GET['p'])?'&amp;p='.$_GET['p']:'')."&amp;page_result=";
			$pagination = geoPagination::getHTML($totalPages, $page, $link);
			$tpl_vars['pagination'] = $pagination;
		}
		
		
		//generate pagination
		//get the url to use
		if (($db->get_site_setting('popup_while_browsing'))
			&& ($db->get_site_setting('popup_while_browsing_width'))
			&& ($db->get_site_setting('popup_while_browsing_height'))) {
			$tpl_vars['listing_link'] = "javascript:winimage('".$db->get_site_setting('classifieds_file_name')."?a=2&b=(!ID!)','".$db->get_site_setting('popup_while_browsing_width')."','".$db->get_site_setting('popup_while_browsing_height')."')";
		} else {
			$tpl_vars['listing_link'] = $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=$store_id&amp;listing=(!ID!)";
		}
		
		$user = geoUser::getUser($store_id);
		
		if ($category_id) {
			$tpl_vars['category_name'] = $this->_categories[$category_id]['category_name'];
		}
		$util = geoAddon::getUtil('storefront');
		$tpl_vars['storefront_name'] = ($util->storefront_name)? $util->storefront_name : $user->username;
			
		$tpl_vars['sort_url'] = $db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=$store_id&amp;category=$category_id&amp;p=home&amp;c=";
		$tpl_vars['storefront'] = $setting->toArray();
		
		//set "sold" image from text, if applicable
		$sold_image = (geoPC::is_ent() && $messages[500798])? geoTemplate::getUrl('',$messages[500798]): '';
		$tpl_vars['storefront']['sold_image'] = $sold_image;
		
		$tpl_vars['number_of_ads_to_display'] = $db->get_site_setting('number_of_ads_to_display');
		//$tpl_vars['site_config'] = $db->get_site_settings(1);
		$tpl_vars['browse_type'] = $browse_type;
		//for stupid redundant optional fields
		$optional_fields = array();
		if (geoPC::is_ent()) {
			$text = geoAddon::getText('geo_addons','storefront');
			for ($i = 1; $i <= 20; $i++) {
				$sName = 'display_optional_field_'.$i;
				if ($setting->$sName) {
					$a = (($i <= 10)? $i + 14: $i+44);
					$txt = 'listings_opt_'.$i.'_column';
					$optional_fields[$i] = array (
						'i' => 'optional_field_'.$i,
						'sortA' => $a,
						'sortB' => $a + 1,
						'header_text' => $text[$txt]
					);
				}
			}
		}
		$tpl_vars['optional_vars'] = $optional_fields;
		
		//display edit/delete buttons
		$session = geoSession::getInstance();
		$tpl_vars['auth_edit'] = $tpl_vars['auth_delete'] = false;
		if ($session->getUserId() == 1 || geoAddon::triggerDisplay('auth_listing_edit',null,geoAddon::NOT_NULL) === true) {
			$tpl_vars['auth_edit'] = 1;
		}
		if ($session->getUserId() == 1 || geoAddon::triggerDisplay('auth_listing_delete',null,geoAddon::NOT_NULL) === true) {
			$tpl_vars['auth_delete'] = 1;
		}
		geoView::getInstance()->setBodyTpl('listings.tpl','storefront')
			->setBodyVar($tpl_vars);
			
		return;
	}
	
	private function _getOrderByClause($sort_type, $query)
	{
		$classTable = geoTables::classifieds_table;
		if (!$sort_type) {
			$query->order("$classTable.`better_placement` DESC")
				->order("$classTable.`date` DESC");
			return;
		}
		
		$sort_types = array (
			1 => array('price','minimum_bid', 'buy_now'),
			3 => 'date',
			5 => 'title',
			7 => 'location_city',
			9 => 'location_state',
			11 => 'location_country',
			13 => 'location_zip',
			15 => 'optional_field_1',
			17 => 'optional_field_2',
			19 => 'optional_field_3',
			21 => 'optional_field_4',
			23 => 'optional_field_5',
			25 => 'optional_field_6',
			27 => 'optional_field_7',
			29 => 'optional_field_8',
			31 => 'optional_field_9',
			33 => 'optional_field_10',
			35 => 'location_city',
			37 => 'location_state',
			39 => 'location_country',
			41 => 'location_zip',
			43 => 'business_type',
			45 => 'optional_field_11',
			47 => 'optional_field_12',
			49 => 'optional_field_13',
			51 => 'optional_field_14',
			53 => 'optional_field_15',
			55 => 'optional_field_16',
			57 => 'optional_field_17',
			59 => 'optional_field_18',
			61 => 'optional_field_19',
			63 => 'optional_field_20',
			//65 => '',  ////***65/66 - reserved cases, default for some SEO pages***
			69 => 'ends',
			//copy of #3, for "legacy", this should probably be
			//removed at some point once the use of it has been confirmed to
			//have been removed..
			67 => 'date',
			71 => 'image > 0', //this is valid mysql: "ORDER BY image > 0 DESC" means "show listings with at least one image first"
		);
		//fix ones where odd version is desc, and even version is asc (backwards of normal) 
		$asc_backwards = array (
			1, 2
		);
		if (in_array($sort_type,$asc_backwards)) {
			$asc_desc = ($sort_type %2)? 'DESC':'ASC';
		} else {
			$asc_desc = ($sort_type % 2)? 'ASC': 'DESC';
		}
		//Goal: if it's an even number, get it to be 1 less.
		$sort_type = (($sort_type %2)? $sort_type: $sort_type - 1);
		$sort_fields = (is_array($sort_types[$sort_type]))? $sort_types[$sort_type]: array($sort_types[$sort_type]);
		$sort = array();
		foreach ($sort_fields as $field){
			$query->order("$classTable.`$field` $asc_desc");
		}
		$query->order("$classTable.`better_placement` DESC");
	}
	
	/**
	 * updater for forms in the actual storefront
	 * (right now, that's just the subscribe-to-newsletter form)
	 *
	 * @return unknown
	 */
	function update()
	{	
		$util = geoAddon::getUtil('storefront');
		$db = DataAccess::getInstance();
		$tables = $util->tables();
		$store_id = $util->getStoreId();
		$text = geoAddon::getText('geo_addons','storefront');
		
		if(isset($_POST['email']) && geoString::isEmail($_POST['email'])) {
			$sql = "SELECT user_email FROM $tables->users WHERE user_email=? and store_id=?";
			$r = $db->GetOne($sql,array($_POST['email'], $store_id));

			if($r) {
				//user already subscribed...
				$newsletter_result = $text['newsletter_subscribe_bad'];
			} else {
				$sql = "INSERT INTO $tables->users SET
				store_id=?,
				user_email=?
				";

				
				$r = $db->Execute($sql,array($store_id,$_POST['email']));
				if($r===false) {
					die($db->ErrorMsg());
				}
				setcookie('emailAdded_'.$store_id,'1');
				$_COOKIE['emailAdded_'.$store_id] = 'true';
				$newsletter_result = $text['newsletter_subscribe_good'];
			}
			geoView::getInstance()->updateResult = $newsletter_result;
			return true;
		}
		
		if(isset($_POST['contact']) && $_POST['contact']) {
			$user = geoUser::getUser($util->owner);
			if($user) {
				$data = $_POST['contact'];
				foreach($data as $key => $datum) {
					//undo clean_inputs (and trim while we're at it)
					if($key == 'extra') {
						//user-defined extra fields
						foreach($datum as $dKey => $extra) {
							$data[$key][$dKey] = geoString::specialCharsDecode(trim($datum));
						}
						continue;
					}
					$data[$key] = geoString::specialCharsDecode(trim($datum));
				}
				if(!$data['email'] || !$data['subject'] || !$data['name'] || !$data['message']) {
					$result = 'All fields are required. Message not sent.';
				} else {
					$from = $data['email'];
					$subject = $text['contact_email_subject'].$data['subject'];
					$tpl = new geoTemplate('addon','storefront');
					$tpl->assign('introduction', $text['contact_email_greeting']);
					$tpl->assign('salutation', $user->getSalutation());
					$tpl->assign('senderName', $data['name']);
					$tpl->assign('text1', $text['contact_email_text1']);
					$tpl->assign('message', nl2br($data['message']));
					
					//info from user-defined extra fields
					if(count($data['extra']) > 0) {
						foreach($data['extra'] as $key => $extra) {
							$extra[] = ucwords(str_replace('_',' ',$extra)).': '.$extra;
						}
					}
					$tpl->assign('extra', $extra);
					$message = $tpl->fetch('email_contact_store.tpl');
					
					geoEmail::sendMail($user->email, $subject, $message, $from, 0, 0, 'text/html');
					$result = $text['contact_email_result_good'];
				}
			} else {
				$result = $text['contact_email_result_bad'];
			}
			
			geoView::getInstance()->updateResult = $result;
			return true;
		}
	}
	
	public function classifieds_details_sub_template ()
	{
		$this->_pageNotUsed();
	}
	public function auctions_details_sub_template ()
	{
		$this->_pageNotUsed();
	}
	
	private function _pageNotUsed ()
	{
		echo '<h1 style="color: red;">Internal Use Only</h1>';
		include GEO_BASE_DIR . 'app_bottom.php';
		exit;
	}
}
