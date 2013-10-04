<?php
//addons/storefront/util.php
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
## ##    7.2.4-23-ge4da757
## 
##################################

# storefront Addon

require_once ADDON_DIR . 'storefront/info.php';

class addon_storefront_util extends addon_storefront_info {
	private $tables; //will hold all the tables above and be named the same (without $table_) but what is after that
	
	private	
	$table_categories = 'geodesic_addon_storefront_categories',
	$table_display = 'geodesic_addon_storefront_display',
	$table_group_subscriptions_choices = 'geodesic_addon_storefront_group_subscriptions_choices',
	$table_newsletter = 'geodesic_addon_storefront_newsletter',
	$table_pages = 'geodesic_addon_storefront_pages',
	$table_subscriptions = 'geodesic_addon_storefront_subscriptions',
	$table_subscriptions_choices = 'geodesic_addon_storefront_subscriptions_choices',
	$table_template_modules = 'geodesic_addon_storefront_template_modules',
	$table_traffic = 'geodesic_addon_storefront_traffic',
	$table_traffic_cache = 'geodesic_addon_storefront_traffic_cache',
	$table_users = 'geodesic_addon_storefront_users',
	$table_user_settings = 'geodesic_addon_storefront_user_settings';
	
	
	var $user_id;
	var $stores = array();
	public $isOwner;
	public static $store_id;
	
	
	function __construct()
	{
		$images_dir = dirname(__file__).'/images';
		if(defined('IN_ADMIN') && !is_writable($images_dir)) {
			Notifications::addNoticeAlert("Storefront addon requires your attention",array("The following directory requires proper permissions (CHMOD 777)" => $images_dir));
		}
	}
	
	public function __set($setting,$mixed_value)
	{
		if($setting=='update_user_settings') {
			$this->updateSettings($mixed_value);
		} else {
			if(strpos($setting,'user_') === 0) {
				$setting = substr($setting,strlen('user_'));
			}
			$this->updateSettings($setting,$mixed_value);
		}
	}
	
	public function __get($setting)
	{
		if(strpos($setting,'user_')!==false) {
			$setting = substr($setting, strlen('user_'));
		}
		return $this->getUserSetting($setting);
		
	}
	
	private function updateSettings($update_array_var = array(),$mixed_value=null)
	{
		$db = DataAccess::getInstance();
		$tables = $this->tables();
		$owner = $this->getStoreId();

		if(is_array($update_array_var) && !empty($update_array_var) && $mixed_value===null) {
			foreach($update_array_var as $k => $v) {
				$v = $db->qstr($v);
				$vs[] = "`$k` = $v";
			}
			
			$sql = "SELECT owner FROM $tables->user_settings WHERE owner=?";
			$r = $db->getrow($sql,array((int) $owner));
		
			if(!array_key_exists('owner',$update_array_var)) {
				$vs[] = "`owner` = '$owner'";
			}
			
			if(empty($r)) {
				$sql = "INSERT INTO $tables->user_settings SET ";
				$sql .= implode(",",$vs);
			} else {
				$sql = "UPDATE $tables->user_settings SET ";
				$sql .= implode(",",$vs);
				$sql .= " WHERE owner='$owner'";
			}
			$r = $db->Execute($sql);
			return $r;
		} else {
			$sql = "SELECT $update_array_var setting FROM $tables->user_settings WHERE owner=?";
			$r = $db->getrow($sql,array((int) $owner));
			if(empty($r)) {
				$sql = "INSERT INTO $tables->user_settings SET ";
				$sql .= "$update_array_var=?, owner='$owner'";
			} else {
				$sql = "UPDATE $tables->user_settings SET ";
				$sql .= "$update_array_var=?";
				$sql .= " WHERE owner='$owner'";
			}
			$r = $db->Execute($sql,array($mixed_value));
			if($r===false) {
				die($db->ErrorMsg()." <br /> $sql");
			}
			return $r;
		}
	}
	
	
	private function getUserSetting($setting)
	{
		$util = geoAddon::getUtil('storefront');
		$db = DataAccess::getInstance();
		$tables = $this->tables();
		$sql = "SELECT $setting setting FROM $tables->user_settings WHERE owner='{$this->getStoreId()}' LIMIT 1";
		$r = $db->getrow($sql);
		if($r===false) {
			die("$sql<br />".$db->ErrorMsg());
		}
		return  (isset($r['setting']))?$r['setting']:'';
	}
	
	public function tables()
	{
		if(!empty($this->tables)) {
			return $this->tables;
		}
		$prefix = "table_";
		$obj = new stdClass();
		foreach($this as $setting => $value) {
			if(strpos($setting,$prefix) !==false) {
				$obj->{substr($setting,strlen($prefix))} = $value;
			}
		}
		if(!empty($obj)) {
			$this->tables = $obj;
			return $obj;
		}
	}

	function get_pagename($page_id,$user_id)
	{
		$db  = DataAccess::getInstance();
		$table = geoAddon::getUtil('storefront')->tables();
		$sql = "SELECT page_name as page FROM $table->pages  WHERE page_id=? and owner=?";
		$r = $db->GetRow($sql, array($page_id, $user_id));
		if($r)return geoString::fromDB($r['page']);
	}
	
	function get_Category_name($cat_id,$store_user_id)
	{
		$db  =  DataAccess::getInstance();
		$table = geoAddon::getUtil('storefront')->tables();
		$sql = "SELECT category_name cat FROM $table->categories WHERE category_id=? and owner=?";
		$r = $db->GetRow($sql, array($cat_id, $store_user_id));
		if($r) return geoString::fromDB($r['cat']);
	}

	/**
	 * find out whether to display the storefront link when browsing a specific category
	 *
	 * @param int $category
	 * @return bool
	 */
	private function _categoryDisplayLink($category, $fields = false)
	{
		if ($fields===false) {
			//OLD WAY: Just check if it is enabled!
			
			$userGroup = (int)geoUser::getData(geoSession::getInstance()->getUserId(), 'group_id');
			$category = (int)$category;
			
			$fields = geoFields::getInstance($userGroup,$category);
			$return = (bool)$fields->addon_storefront_display_link->is_enabled;
		} else {
			$return = (isset($fields['addon_storefront_display_link']) && $fields['addon_storefront_display_link']);
		}
		return $return;
	}
	
	public function core_Search_classifieds_BuildResults_addHeader ($vars)
	{
		//figure out which category is being searched, if any
		$category = $_REQUEST['c'] ? intval($_REQUEST['c']) : 0;
		//get the "display link" switch from that category, or the master if no category was found
		$fields = $vars['search_fields'];
		
		$display = $this->_categoryDisplayLink($category, $fields);
		
		if ($display) {
			$msgs = geoAddon::getText('geo_addons','storefront');
			$headers [] = array (
				'text' => $msgs['search_results_storefront_header']
			);
			return $headers;
		}
		return false;
	}
	
	public function core_Search_classifieds_BuildResults_addRow ($vars)
	{
		$db = DataAccess::getInstance();
		$listing_id = $vars['listing_id'];
		$html = '';
		
		//figure out which category is being searched, if any
		$category = $_REQUEST['c'] ? intval($_REQUEST['c']) : 0;
		$fields = $vars['search_fields'];
		
		//get the "display link" switch from that category, or the master if no category was found
		if (!$this->_categoryDisplayLink($category, $fields)) {
			//not showing this column at all, so return false and skip everything else
			return false; 
		}
		
		$listing = geoListing::getListing($listing_id);
		if (!is_object($listing)) {
			return array('&nbsp;');
		}
		
		//showing links for the category being searched, but might be turned off
		//for the specific child category holding this listing, so check that here
		//NOTE: not sending fields, that way it is just a check of whether the field
		//is enabled or not for that category
		if (!$this->_categoryDisplayLink($listing->category)) {
			//should not show storefront links for this listing's category
			return array('&nbsp;');
		}

		$sql = "SELECT * FROM geodesic_addon_storefront_subscriptions WHERE user_id=? LIMIT 1";
		$subscriptionInfo = $db->GetRow($sql, array($listing->seller));
		if ($subscriptionInfo===false){
			trigger_error('ERROR SQL STOREFRONT: Sql Execute Error, error details should be already output.');
			return array ("&nbsp;");
		}
		if (empty($subscriptionInfo)) {
			return array ("&nbsp;");
		}
		
		$expiresAt = $subscriptionInfo["expiration"];
		$user = geoUser::getUser($listing->seller);
		$onhold = (is_object($user))? $user->storefront_on_hold : 0;
		if (geoUtil::time() > $expiresAt || $onhold) {
			$html = "&nbsp;";
		} else {
			$msgs = geoAddon::getText('geo_addons','storefront');
			$html = "<a href=\"".$db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$listing->seller."\">".$msgs['search_results_storefront_link']."</a>";
		}
		return array($html);
	}
	
	public function core_Browse_ads_display_browse_result_addHeader ($vars)
	{
		$object = $vars['this'];
		$fields = $vars['browse_fields'];
		if ($this->_categoryDisplayLink($object->site_category, $fields)) {
			$headers [] = array ('text' => $object->messages[500003],'label' => $object->messages[500003]);
			return $headers;
		}
		return false;
	}
	
	
	function expirationTime($userid)
	{
		$db = DataAccess::getInstance();
		$table = geoAddon::getUtil('storefront')->tables();
		$sql = "SELECT `expiration` FROM $table->subscriptions WHERE `user_id`=?";
		$r = $db->getrow($sql,array($userid));
		return (isset($r['expiration'])?$r['expiration']:0);
	}
	
	/**
	 * Addon core_ event:
	 * name: core_User_management_information_display_user_data_plan_information
	 * vars: array (this => Object) (this is the instance of class that called.
	 */
	public function core_User_management_information_display_user_data_plan_information($vars)
	{
		$object = $vars['this'];
		$user_data = $vars['user_data'];
		
		if(!$this->exposeStore($object->userid)) {
			//user doesn't have and can't buy storefront, so don't show it
			return false;
		}
		
		$db = DataAccess::getInstance();
		
		$sql = "SELECT storefront from ".$object->groups_table." WHERE group_id = ".$user_data->GROUP_ID;
		$r =  $db->getrow($sql);
		if($r===false) {
			die($db->ErrorMsg());
		}
		if(!isset($r['storefront'])) return false;
		
		$tpl = new geoTemplate('addon','storefront');
		
		//figure out if there is recurring item for this user
		$subscription = $db->GetRow("SELECT * FROM ".self::SUBSCRIPTIONS_TABLE." WHERE `user_id`=? LIMIT 1", (int)$object->userid);
		$recurring = false;
		if ($subscription && geoPC::is_ent()) {
			$recurring = ($subscription['recurring_billing'])? geoRecurringBilling::getRecurringBilling((int)$subscription['recurring_billing']) : false;
			if ($recurring && (!$recurring->getId() || $recurring->getItemType() != 'storefront_subscription' || $recurring->getUserId() != $object->userid)) {
				//recurring not for this item, or not valid
				$recurring = false;
				//unset the recurring billing column if it is not valid
				$sql = "UPDATE ".self::SUBSCRIPTIONS_TABLE." SET `recurring_billing`=0 WHERE `user_id` = ".(int)$object->userid;
				$db->Execute($sql);
			}
			if ($recurring && $recurring->getStatus() != geoRecurringBilling::STATUS_CANCELED) {
				$tpl->assign('recurringId', $recurring->getId());
				$tpl->assign('cycleDuration', floor($recurring->getCycleDuration()/(60*60*24)));
				$tpl->assign('cyclePrice', geoString::displayPrice($recurring->getPricePerCycle()));
				$tpl->assign('nextCycleDate', date($db->get_site_setting('member_since_date_configuration'), $subscription['expiration']));
				
				$tpl->assign('cancelRecurringLink', $db->get_site_setting('classifieds_file_name').'?a=4&amp;b=24&amp;recurring_id='.$recurring->getId());
			}
		}
		if (!$recurring) {
			if($this->canBuyMoreStore($object->userid)) {
				$tpl->assign('showRenewLink', true);
			}
		}
		
		$tpl->assign('msgs', geoAddon::getText($this->auth_tag, $this->name));
		$tpl->assign('expiration_date', geoDate::toString(self::expirationTime($object->userid), $db->get_site_setting('member_since_date_configuration')));
		$html = $tpl->fetch("client/display_subscription_info.tpl");
		
		return $html;
	}
	
	/**
	 * Addon core_ event:
	 * name: User_management_current_ads_classified_ad_detail_form
	 * vars: array (this => Object) (this is the instance of $this.
	 */
	function core_User_management_current_ads_classified_ad_detail_form($vars)
	{
		$object = $vars['this'];
		$show = $vars['show'];
		$table = geoAddon::getUtil('storefront')->tables();
		$sql = "SELECT * FROM $table->subscriptions WHERE user_id=?";
		$subscriptionResult = $object->db->Execute($sql,array($object->classified_user_id));

		$sql = "SELECT * FROM $table->categories WHERE user_id=? order by display_order asc";
		$categoryResults = $object->db->Execute($sql,array($object->classified_user_id));

		if($subscriptionResult->RecordCount()==1&&$categoryResults->RecordCount()) {
			$subscriptionInfo = $subscriptionResult->FetchRow();
			$expiresAt = $subscriptionInfo["expiration"];
			if(time()<=$expiresAt) {
				if($object->page_id == 32);
					$object->messages[500002] = $object->messages[500007];
				$html .="<tr>\n\t<td>&nbsp;</td>\n\t<td class=\"field_labels\">".urldecode($object->messages[500002])."</td>\n\t";
				$html .="<td class=\"place_an_ad_details_data\">\n\t";
				$html .="<select name=\"d[storefront_category]\" class=\"place_an_ad_details_data\">\n\t\t";
				while ($showCats = $categoryResults->FetchRow()) {
					$selected = ($show->STOREFRONT_CATEGORY==$showCats["category_id"]) ? "selected=\"selected\"" : "";
					$html .="<option value=\"".$showCats["category_id"]."\"";
					$html .=" $selected />".stripslashes($showCats["category_name"])."\n\t\t";
				}

				$html .="</select>\n\t";
				$html .="</td>\n</tr>\n";
			}
		}
		return $html;
		//die('html:'.$html);
	}

	/**
	 * Addon core_ event:
	 * name: core_notify_Display_ad_display_classified_after_vars_set
	 * vars: array (this => Object) (this is the instance of $this.
	 */
	public function core_notify_Display_ad_display_classified_after_vars_set ($vars)
	{
		//NOTE: This done for backwards compatibility only, the link is now
		//displayed by listing tag
		$listing = geoListing::getListing($vars['id']);
		
		if ($listing && $this->userHasCurrentSubscription($listing->seller)) { 
			$db = DataAccess::getInstance();
		
			$view = geoView::getInstance();
			$msgs = $db->get_text(true);
			$view->storefront_link = "<a href=\"".$db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$listing->seller."\" class=\"notify_seller_link\">".$msgs[500005]."</a>";
		}
	}
	
	public function core_Browse_ads_display_browse_result_addRow ($vars)
	{
		$object = $vars['this'];
		$show_classifieds = $vars['show_classifieds'];
		$fields = $vars['browse_fields'];
		$html = '';
		if (!$this->_categoryDisplayLink($object->site_category, $fields)) {
			return ;
		}
		
		$user = geoUser::getUser($show_classifieds['seller']);
		$onhold = (is_object($user))? $user->storefront_on_hold : 0;

		$sql = "SELECT * FROM geodesic_addon_storefront_subscriptions WHERE user_id=? LIMIT 1";
		$subscriptionResult = $object->db->GetRow($sql, array($show_classifieds['seller']));
		if ($subscriptionResult===false){
			trigger_error('ERROR SQL STOREFRONT: Sql Execute Error, error details should be already output.');
			return array("&nbsp;");
		}
		if(empty($subscriptionResult)) {
			return array("&nbsp;");
		}
		
		$subscriptionInfo = $subscriptionResult;
		$expiresAt = $subscriptionInfo["expiration"];
		//TODO: make it use logo if logo for user is set...
		if (geoUtil::time() > $expiresAt || $onhold) {
			$html = "&nbsp;";
		} else {
			$html = "<div class='center'><a href=\"".$object->db->get_site_setting('classifieds_file_name')."?a=ap&amp;addon=storefront&amp;page=home&amp;store=".$show_classifieds['seller']."\">".$object->messages[500004]."</a></div>";
		}
		return array($html);
	}
	
	public function core_addon_SEO_rewriteUrl ($vars)
	{
		$get = $vars['get'];
		$url = $vars['url'];
		$anchor = $vars['anchor'];
		//new URL for encoding titles used in RSS feed
		$url_encode_titles = (isset($vars['url_encode_titles']) && $vars['url_encode_titles']);
		
		if (!isset($get['a']) || $get['a'] != 'ap' || !isset($get['addon']) || $get['addon'] != 'storefront') {
			//not an addon page
			return null;
		}
		$seoUtil = geoAddon::getUtil('SEO');
		
		if (count($get) == 3 && isset($get['page']) && $get['page'] == 'list_stores') {
			//list all stores page
			$search = $replace = array();
			
			//no text replacing to be done since there are no URL parameters
			
			$rewriteUrl = true;
			//figure out if on nth page or not
			$page = intval((isset($get['p']) && $get['p'] > 1)? $get['p']: 0);
			$p_reg = ($page)? ' pages': '';
			//replace page ID
			$search[] = '(!PAGE_ID!)';
			$replace[] = $page;
			
			$urlName = 'list all stores'.$p_reg;
		} else if (isset($get['page']) && $get['page'] == 'home' && isset($get['store'])) {
			//one of the home pages
			$search = $replace = array ();
			
			$store_id = $this->storeIdFromString($get['store']);
			if(!$store_id) {
				//don't know which store this is -- can't proceed
				return null;
			}

			$sql = "SELECT seo_name FROM geodesic_addon_storefront_user_settings WHERE owner = ?";
			$seo_name = DataAccess::getInstance()->GetOne($sql, array($store_id));
			if($seo_name) {
				$username = $seo_name;
			} else {
				//nothing is set, so use the username, but be sure to clean invalid characters first
				$username = geoUser::userName($store_id);
				$username = preg_replace("/[^a-zA-Z0-9_]+/", ' ', $username); //replace any invalid characters with whitespace
				$username = preg_replace("/\s+/", '-', $username); //replace any whitespace with hyphens
			}
			
			if ($url_encode_titles) {
				$username = urlencode($username);
			}
			
			if (!$username) {
				//didn't find an acceptable name for the rewrite -- can't proceed
				return null;
			}
			
			//one thing in common, replace the store name in all these URL's
			$search[] = '(!STORE_NAME!)';
			$replace[] = $username;
			
			$urlName = "storefront store";
			
			$rewriteUrl = false;
			if (isset($get['p']) && count($get) == 5) {
				//page
				$pId = (int)$get['p'];
				if (!$pId) {
					//invalid page id
					return null;
				}
				$pName = $this->get_pagename($pId, $store_id);
				$search[] = '(!PAGE_TITLE!)';
				$search[] = '(!PAGE_ID!)';
				$replace[] = $seoUtil->revise($pName, array(), false, $url_encode_titles);
				$replace[] = $pId;
				
				$urlName .= " page";
				$rewriteUrl = true;
			} else if (count($get)==5 && isset($get['category']) && $get['category']) {
				//category
				$catId = (int)$get['category'];
				$catName = $this->get_Category_name($catId, $store_id);
				
				if (!$catId) {
					return null;
				}
				
				//figure out if on nth page or not
				$page = intval((isset($get['page_result']) && $get['page_result'] > 1)? $get['page_result']: 0);
				$p_reg = ($page)? ' pages': '';
				//replace page ID
				$search[] = '(!PAGE_ID!)';
				$replace[] = $page;
							
				$search[] = '(!CATEGORY_ID!)';
				$replace[] = $catId;
				
				$search[] = '(!CATEGORY_TITLE!)';
				$replace[] = $seoUtil->revise($catName, array(), false, $url_encode_titles);
				$urlName .= " category".$p_reg;
				$rewriteUrl = true;
				
				if(is_numeric($get['c']) && $get['c'] > 0) {
					//this is a "sort" link. don't rewrite it.
					$rewriteUrl = false;
				}
				
			} else if (count($get) == 5 && isset($get['listing'])) {
				//listing
				$listingId = (int)$get['listing'];
				$listing = geoListing::getListing($listingId);
				if (!$listing) {
					return null;
				}
				
				$listingTitle = geoString::fromDB($listing->title);
				
				$catId = $listing->storefront_category;
				$catName = $this->get_Category_name($catId, $store_id);
				
				$search[] = '(!LISTING_ID!)';
				$replace[] = $listingId;
				
				$search[] = '(!LISTING_TITLE!)';
				$replace[] = $seoUtil->revise($listingTitle, array(), false, $url_encode_titles);
				
				$search[] = '(!CATEGORY_ID!)';
				$replace[] = $catId;
				
				$search[] = '(!CATEGORY_TITLE!)';
				$replace[] = $seoUtil->revise($catName, array(), false, $url_encode_titles);
				
				$urlName .= " listing";
				$rewriteUrl = true;
			} else if (count($get) == 4) {
				$rewriteUrl = true;
			}
		}
			
		if ($rewriteUrl) {
			$seoUtil->registry_id = $urlName;
			
			$tpl = $seoUtil->getUrlTemplate('url_template');
			
			if ($tpl) {
				//get the user name
				$tpl = str_replace($search, $replace, $tpl);
				return $tpl.$anchor;
			}
		}
		
		
		//got this far, we don't re-write this URL
		
		return null;
	}
	
	//store urls can contain one of: storefront name, username, user id
	//this is a handy util function to down-cast all possibilities to user id
	public function storeIdFromString($store_id)
	{
		if (!is_numeric($store_id)) {
			//might be an SEO / user-selected store name
			$sql = "select owner from geodesic_addon_storefront_user_settings where seo_name = ?";
			$owner = DataAccess::getInstance()->GetOne($sql, array($store_id));
			if($owner) {
				//found the owner ID for this store name!
				$store_id = $owner;
			} else {
				//this is a username, if indeed it is anything at all
				$store_id = geoUser::getUserId($store_id);
				if(!$store_id) {
					//still don't have the store ID...one last thing to check!
					//this might be a username that contains a character that has been transformed into a hyphen for SEO
					
					//it's a many-to-one conversion, so can't accurately undo it, but if anything gets here, it's trying to access a storefront that hasn't been bought yet
					//so it ought to be safe to redirect everything here to the "purchase storefront subscription" page
					header("Location: ".geoFilter::getBaseHref().'?a=cart&action=new&main_type=storefront_subscription');
				}				
			}
		} else {
			//already a number -- nothing to do here
		}
		
		return intval($store_id);
	}
	
	function setStoreId($store_id)
	{
		$store_id = intval($store_id);
		if(!$store_id) {
			return false;
		}
		$store_id=(trim($store_id));
		self::$store_id = $store_id;
		return $store_id;
	}
	
	public function getStoreId()
	{
		$return = (self::$store_id) ? self::$store_id : false;
		if(!$return) {
			//store id not set yet -- probably updating this user's store
			$return = geoSession::getInstance()->getUserId();
			self::$store_id = $return;
		}
		return $return;
	}
	
	function displayStore($store_id=null)
	{
		if(!self::$store_id) {
			die('invalid store id');
		}
		require_once('display_storefront.php');
		$storefrontDisplay = new Display_Storefront();
		
		return $storefrontDisplay;
	}
	
	function exitStore()
	{
		exit("You have entered an invalid store id.");
	}
	
	public function isOwner($owner=null)
	{
		$session = geoSession::getInstance();
		$id=(trim($session->getUserId()));
		if($owner!==null) {
			$store_id = $owner;
		} else {
			$store_id = self::$store_id;
		}
		$owner=(($id && $store_id && $id === $store_id)? $id:false);
		return $owner;
	}
	
	public static function displayStorefrontManager ($params, $smarty)
    {
        $util = geoAddon::getUtil('storefront');
        if (!$util->isOwner()) {
            //do not show storefront manager
            return '';
        }
        
        $tpl_file = (isset($params['template']))? $params['template']: 'manager.tpl';
        
        $params['smarty_include_tpl_file'] = $tpl_file;
        $vars = array();
        $vars['g_type'] = 'addon';
        $vars['g_resource'] = 'storefront';
        $params['smarty_include_vars'] = $vars;
        
        $smarty_template_vars = $smarty->_tpl_vars;
        $smarty->_smarty_include($params);
        $smarty->_tpl_vars = $smarty_template_vars;
        return '';
    }
	
	public function core_my_account_links_add_link($vars)
	{
		$user_id = geoSession::getInstance()->getUserId();
		
		if (!$this->exposeStore($user_id)) {
			//should not display
			return false;
		}
		$return = array();
		
		$msgs = geoAddon::getText($this->auth_tag, $this->name);
		
		$image = false;
		if(DataAccess::getInstance()->get_site_setting('show_addon_icons')) {
			$image = $msgs['my_account_links_icon'];
		}
		
		$return['storefront'] = array(
			'link' => $vars['url_base'] . "?a=ap&amp;addon=storefront&amp;page=home&amp;store=$user_id", 
			'label' => $msgs['my_storefront_label'],
			'icon' => $image,
			'active' => (($_REQUEST['addon'] == 'storefront' && $_REQUEST['page'] == 'home') ? true : false)
		);
		
		if($this->userHasCurrentSubscription($user_id)) {
			$return['storefront_cp'] = array(
				'link' => $vars['url_base'] . "?a=ap&amp;addon=storefront&amp;page=control_panel",
				'label' => $msgs['cp_link_text'],
				'icon' => $image,
				'active' => (($_REQUEST['addon'] == 'storefront' && $_REQUEST['page'] == 'control_panel') ? true : false)
			);
		}
			
		return $return;
	}
	
	public function core_my_account_home_add_box($vars)
	{
		$db = DataAccess::getInstance();
		$user_id = geoSession::getInstance()->getUserId();
		$index = $db->get_site_setting('classifieds_file_name');
		$text = geoAddon::getText('geo_addons', 'storefront');
		
		$storefrontBox = array();
		$storefrontBox['title'] = $text['mal_section_title'];
		
		$buyMoreStoreLink = $index . "?a=cart&amp;action=new&amp;main_type=storefront_subscription";
		
		if($this->userHasCurrentSubscription($user_id)) {
			//your subscription expires on (date) -- renew link
			
			$renewLink = ($this->canBuyMoreStore($user_id)) ? $text['mal_renew_link'] : '' ;
	
			
			$expDate = date($db->get_site_setting('entry_date_configuration'),$this->getExpirationTime($user_id, true));
			$storefrontBox['rows'][] = array('label' => $text['mal_expdate_label'].$expDate,
											'link' => $buyMoreStoreLink,
											'data' => $renewLink);
			
			//link to storefront
			$myStoreLink = $index . "?a=ap&amp;addon=storefront&amp;page=home&amp;store=" . $user_id;
			$storefrontBox['rows'][] = array('link' => $myStoreLink, 'data' => $text['mal_storefront_link']);
			
			//link to store cp
			$cpLink = $index . "?a=ap&amp;addon=storefront&amp;page=control_panel";
			$storefrontBox['rows'][] = array('link' => $cpLink, 'data' => $text['my_account_cp_link_text']);
		} else {
			//purchase a subscription
			$storefrontBox['rows'][] = array('label' => $text['mal_no_sub'],
											'link' => $buyMoreStoreLink,
											'data' => $text['mal_new_sub_link']);
		}
				
		
		//don't show box if user isn't supposed to see it
		$reg = geoAddon::getRegistry('storefront');
		$admin_switch = $reg->get('my_account_show_storefront',1);
		$storefrontBox['display'] = ($this->exposeStore($user_id) && $admin_switch) ? true : false;
	
		return $storefrontBox;
	}
	
	public function core_my_account_admin_options_display($vars)
	{
		$reg = geoAddon::getRegistry('storefront');
		$setting = $reg->get('my_account_show_storefront',1);
		$return = geoHTML::addOption('Storefront', '<input type="checkbox" name="b[my_account_show_storefront]" value="1" '.(($setting)?'checked="checked"':'').' />');
		return $return;
	}
	
	public function core_my_account_admin_options_update($vars)
	{
		$setting = $vars['my_account_show_storefront'] ? 1 : 0;
		$reg = geoAddon::getRegistry('storefront');
		$reg->set('my_account_show_storefront',$setting);
		$reg->save();
	}
	
	public function core_admin_display_page_attachments_edit_end ($tpl_vars)
	{
		if (!isset($tpl_vars['addon']) || $tpl_vars['addon'] != 'storefront' || $tpl_vars['addonPage'] != 'home') {
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
		
		$view->setBodyTpl('admin/templateToPageEdit.tpl','storefront');
		
	}
	
	/**
	 * Use to find out if we should show things relating to the storefront to a user
	 * yes if user has current subscription or is able to purchase one
	 * no otherwise
	 *
	 * @param int $user_id id of user to check
	 * @return bool whether or not to show store stuff
	 */
	public function exposeStore($user_id)
	{
		if($this->userHasCurrentSubscription($user_id)) {
			//user has a current subscription -- need to provide access to it
			return true;
		}
		
		if($this->canBuyMoreStore($user_id)) {
			return true;
		}

		//got this far and nothing to show yet, so return false
		return false;
	}
	
	/**
	 * Finds out if a user is able to buy more storefront (renew subscription)
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function canBuyMoreStore($user_id)
	{
		$classPP = (geoMaster::is('classifieds')) ? geoUser::getData($user_id, 'price_plan_id') : 0;
		if($classPP) {
			$planItem = geoPlanItem::getPlanItem('storefront_subscription',$classPP);
			if($planItem->isEnabled() && $planItem->get('periods',false)) {
				//found some attached periods, so ought to be able to buy them
				return true;
			}
		}
		$aucPP = (geoMaster::is('auctions')) ? geoUser::getData($user_id, 'auction_price_plan_id') : 0;
		if($aucPP) {
			$planItem = geoPlanItem::getPlanItem('storefront_subscription',$aucPP);
			if($planItem->isEnabled() && $planItem->get('periods',false)) {
				//found some attached periods, so ought to be able to buy them
				return true;
			}
		}
		return false;
	}
	
	/**
	 * easy way to find out if a given user has a current subscription
	 *
	 * @param int $user_id UID to check
	 */
	public function userHasCurrentSubscription($user_id)
	{
		if(!intval($user_id)) {
			//invalid user id
			return false;
		}
		$expiresAt = $this->getExpirationTime($user_id, true);
		if (geoUtil::time() > $expiresAt) {
			return false;
		} else {
			return true;
		}
	}
	
	public function getExpirationTime($user_id, $ignore_hold=false)
	{		
		if(!intval($user_id)) {
			//invalid user id
			return 0;
		}
		$db = DataAccess::getInstance();
		
		if(!$ignore_hold) {
			$user = geoUser::getUser($user_id);
			$onhold = (is_object($user))? $user->storefront_on_hold : 0;
			if($onhold) {
				return 0;
			}
		}
		$sql = "SELECT * FROM geodesic_addon_storefront_subscriptions WHERE user_id=? LIMIT 1";
		$result = $db->Execute($sql, array($user_id));
		if (!$result || $result->RecordCount() != 1){
			return 0;
		}
		$line = $result->FetchRow();
		return $line["expiration"];
		
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
	
	public function configureDefaultUserPages($owner)
	{
		$db = DataAccess::getInstance();
		
		//find out if user already has pages in place -- if yes, do nothing here
		$sql = "SELECT * FROM `geodesic_addon_storefront_pages` WHERE `owner` = ?";
		$result = $db->Execute($sql, array($owner));
		if($result && $result->RecordCount() > 0) {
			//nothing needs doing
			return false;
		}
		
		$user = geoUser::getUser($owner);
		
		$pages = array();
		
		$tpl = new geoTemplate('addon','storefront');
		$tpl->assign('user', $user->toArray()); //make all userdata available to templates via, e.g., {$user.email}
		
		$potentialMapPieces = array($user->address, $user->city, $user->state, $user->zip, $user->country);
		$map = array();
		foreach($potentialMapPieces as $pmp) {
			//make sure field exists before adding it to the mapping string
			if($pmp) {
				$map[] = $pmp;
			}
		}
		//using urlencode here instead of geoString::toDB in case the latter ever changes from being the same as urlencode
		$tpl->assign('locationForMap', urlencode(implode(', ',$map)));
		
		$pages['home'] = $tpl->fetch('default_pages/home.tpl');
		$pages['about'] = $tpl->fetch('default_pages/about.tpl');
		$pages['contact_us'] = $tpl->fetch('default_pages/contact_us.tpl');
		
		$txt = geoAddon::getText('geo_addons','storefront');
		
		$sql = "INSERT INTO `geodesic_addon_storefront_pages` (`owner`, `page_link_text`, `page_name`, `page_body`, `display_order`) VALUES (?,?,?,?,?)";
		if($pages['home']) {
			if(!$db->Execute($sql, array($owner, $txt['default_page_name_home'], $txt['default_page_name_home'], $pages['home'], 1))) {
				return false;
			}
			$insert_id = $db->Insert_Id();
			$this->default_page = $insert_id;
		}
		if($pages['about']) {
			if(!$db->Execute($sql, array($owner, $txt['default_page_name_about'], $txt['default_page_name_about'], $pages['about'], 2))) {
				return false;
			}
		}
		if($pages['contact_us']) {
			if(!$db->Execute($sql, array($owner, $txt['default_page_name_contact'], $txt['default_page_name_contact'], $pages['contact_us'], 3))) {
				return false;
			}
		}
		
		
		//while we're at it, let's see if there are any categories
		$sql = "SELECT * FROM `geodesic_addon_storefront_categories` WHERE owner = ?";
		$result = $db->Execute($sql, array($owner));
		if(!$result || $result->RecordCount() == 0) {
			//no categories -- let's make one, since not having categories is another potential newbie mistake
			$sql = "INSERT INTO `geodesic_addon_storefront_categories` (`owner`, `category_name`, `display_order`) VALUES (?,?,?)";
			if(!$db->Execute($sql, array($owner, $txt['default_category_name'], '0'))) {
				return false;
			}
		}
		
		//and go ahead and set the home category name, too, if it hasn't already been done
		if(!$this->home_link) {
			$this->home_link = $txt['default_home_category_name'];
		}
		
		$reg = geoAddon::getRegistry('storefront');
		
		if(!$this->storefront_name) {
			//populate initial storefront name (company name if the admin option to use it is set and it's present; username otherwise)
			$newName = ($reg->default_storename_to_company && $user->company_name) ? $user->company_name : $user->username;
			$this->storefront_name = $newName;
			
			//clean the name to make it suitable for use in URLs
			$newName = preg_replace("/[^a-zA-Z0-9_]+/", ' ', $newName); //replace any invalid characters with whitespace
			$newName = preg_replace("/\s+/", '-', $newName); //replace any whitespace with hyphens
			
			//make sure the name isn't in use by another user
			//if it is, try adding a hyphen to the end until it is not
			$checkName = $db->Prepare("select seo_name from geodesic_addon_storefront_user_settings where seo_name = ? AND owner <> ?");
			do {
				$nameExists = $db->Execute($checkName, array($newName, $userid))->RecordCount() > 0;
				$newName = ($nameExists) ? $newName.'-' : $newName;  
			} while($nameExists);
			$this->seo_name = $newName;
		}
				
		return true;
		
	}
	
	/**
	 * set the title module's text for storefront pages
	 * @return String
	 */
	public function core_module_title_add_text()
	{
		if(!($_REQUEST['a'] == 'ap' && $_REQUEST['addon'] == 'storefront')) {
			//not a storefront page. move along.
			return '';
		}
		//begin with the name of this storefront
		$title = ($this->storefront_name) ? $this->storefront_name : '';
		
		//add listing title, if applicable
		if($_REQUEST['listing'] && is_numeric($_REQUEST['listing'])) {
			$listing = geoListing::getListing($_REQUEST['listing']);
			$title .= ' ' . geoString::fromDB($listing->title);
		}
		return $title;
	}
	
	public function core_geoFields_getDefaultFields ($vars)
	{
		$categoryId = $vars['categoryId'];
		$groupId = $vars['groupId'];
		
		//expected to return using following format:
		$return = array (
				'addon_storefront_display_link' => array (
						/**
						 * NOTE: We HIGHLY recommend prepending the "field index" (example_widget)
		* with your addon name to avoid field name collisions with other addons
		* or possible future added core fields
		*/
					'label' => 'Storefront Display Link',
					'type' => 'other',
					'type' => 'other',
					'type_label' => 'Display Only',
					'skipData' => array('is_required', 'is_editable'),
					//'skipLocations' => true
				),
		);
		return $return;
	}
}