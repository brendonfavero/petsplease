<?php
//Category.class.php
/**
 * Holds the geoCategory class.
 * 
 * @package System
 * @since Version 4.0.0
 */
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
## ##    7.2.4-1-g68ac455
## 
##################################

/**
 * Utility class that holds various methods to do stuff with categories in the system.
 * @package System
 * @since Version 4.0.0
 * @todo Clean this class up a bunch and optimize it.
 */
class geoCategory
{
	/**
	 * Gets an instance of the geoCategory class.
	 * @return geoCategory
	 */
	public static function getInstance(){
		return Singleton::getInstance('geoCategory');
	}
	/**
	 * Internal
	 * @internal
	 */
	private static $_getInfoCache;
	/**
	 * Get basic info about given category.
	 * @param int $category_id
	 * @param int $language_id
	 * @return array
	 */
	public static function getBasicInfo($category_id=0, $language_id = 0)
	{
		$db = DataAccess::getInstance();
		if (!$category_id){
			//TODO: get this text from the DB somewhere...
			return array('category_name' => "Main");
		}
		if (!$language_id){
			$language_id = $db->getLanguage();
		}
		if (isset(self::$_getInfoCache[$category_id][$language_id])){
			return self::$_getInfoCache[$category_id][$language_id];
		}
		
		$sql = "SELECT `category_name`,`category_cache`,`cache_expire`,`description` FROM ".geoTables::categories_languages_table." WHERE `category_id` = ? and language_id = ? LIMIT 1";
		$result = $db->Execute($sql, array($category_id, $language_id));
		if (!$result || $result->RecordCount() == 0)
		{
			trigger_error('ERROR CATEGORY SQL: Cat not found for id: '.$category_id.', Sql: '.$sql.' Error Msg: '.$db->ErrorMsg());
			return false;
		}
		$show = $result->FetchRow();
		$show['category_name'] = geoString::fromDB($show['category_name']);
		$show['description'] = geoString::fromDB($show['description']);
		//save it, so we don't query the db a bunch
		self::$_getInfoCache[$category_id][$language_id] = $show;
		return $show;
	}
	
	/**
	 * Add category data to local cache for use later in same page load.  This
	 * mainly serves to greatly speed up SEO addon when there are a lot of category
	 * links on the page, so it doesn't have to re-look
	 * up data already retrieved.
	 * 
	 * @param array $data The un-filtered results from DB for category.  Must
	 *   contain category_id, language_id, category_name, and description to be
	 *   of any use.
	 * @since Version 5.1.2
	 */
	public static function addCategoryResult ($data)
	{
		$category_id = (int)$data['category_id'];
		$language_id = (int)$data['language_id'];
		
		if ($category_id && $language_id && isset($data['category_name'], $data['description'])) {
			$data['category_name'] = geoString::fromDB($data['category_name']);
			$data['description'] = geoString::fromDB($data['description']);
			self::$_getInfoCache[$category_id][$language_id] = $data;
		}
	}
	
	/**
	 * Allows adding multiple rows at once to local cache for use later.  Basically
	 * just calls self::addCategoryResult() for each item in the array.
	 * 
	 * @param array $data Array of category results
	 * @since Version 5.1.2
	 */
	public static function addCategoryResults ($data)
	{
		foreach ($data as $row) {
			self::addCategoryResult($row);
		}
	}
	
	/**
	 * Gets the name of the given category, already decoded.
	 * 
	 * @param int $category_id
	 * @param bool $justTheName if true, it acts like the method name sounds like,
	 *   returning just the name.
	 * @return string
	 */
	public static function getName($category_id, $justTheName = false)
	{
		//need to clean up a little
		$category_id = intval($category_id);
		if (!$category_id) return 'Main';
		$db = DataAccess::getInstance();
		
		$sql = "select category_name,description from ".geoTables::categories_languages_table." where category_id = ".$category_id." and language_id = ".$db->getLanguage();
		$r = $db->getrow($sql);
		if (!$r) {
			return false;
		}
		if ($justTheName) {
			return geoString::fromDB($r['category_name']);
		}
		$show = new stdClass();
		$show->CATEGORY_NAME = geoString::fromDB($r['category_name']);
		$show->DESCRIPTION = geoString::fromDB($r['description']);
		return $show;
	}
	
	/**
	 * return an array with a random category information
	 *
	 * @return array
	 */
	public static function getRandomBasicInfo()
	{
		$db = DataAccess::getInstance();
		
		$language_id = $db->getLanguage();
		
		$sql = "SELECT `category_id`,`category_name`,`description` FROM ".geoTables::categories_languages_table." WHERE language_id = ?  AND category_id !=? ORDER BY RAND() LIMIT 1";
		$result = $db->GetRow($sql, array($language_id,0));
		if ($result===false)
		{
			trigger_error('ERROR CATEGORY SQL: Cat not found for id: '.$category_id.', Sql: '.$sql.' Error Msg: '.$db->ErrorMsg());
			return false;
		}
		//use array_map, since all fields being returned (well except for the id) need to be geoString::fromDB
		$show = array_map(array('geoString','fromDB'),$result);
		
		//save it, so we don't query the db a bunch
		self::$_getInfoCache[$category_id][$language_id] = $show;
		return $show;	
	}
	/**
	 * Internal
	 * @internal
	 */
	private static $_getCategoryConfig_cache;
	/**
	 * Gets the categories' info for the given category.
	 * 
	 * @param int $category_id
	 * @param bool $bubbleUpFields if true, bubble up through parents' "what fields to use" settings
	 * @return array
	 */
	public static function getCategoryConfig($category_id, $bubbleUpFields=false)
	{
		$db = DataAccess::getInstance();
		$category_id = intval($category_id);
		if (!$category_id){
			return array();
		}
		
		$bubbleUpFields = $bubbleUpFields ? 1 : 0;
		
		if (isset(self::$_getCategoryConfig_cache[$category_id][$bubbleUpFields])){
			return self::$_getCategoryConfig_cache[$category_id][$bubbleUpFields];
		}
		
		$sql = "SELECT * FROM ".geoTables::categories_table." WHERE `category_id` = {$category_id} LIMIT 1";
		$result = $db->Execute($sql);
		if (!$result){
			trigger_error('ERROR SQL CATEGORY: Sql: '.$sql.' Error Msg: '.$db->ErrorMsg());
		}
		$cfg = $result->FetchRow();
		
		if($cfg['what_fields_to_use'] == 'parent' && $bubbleUpFields) {
			if(!geoPC::is_ent() || $cfg['parent_id'] == 0) {
				//not enterprise -- must use site-wide settings
				// -- OR --
				//parent is 0 -- no category-specific settings in use
				$cfg = array('what_fields_to_use' => 'site');
			} else {
				//recurse up to parent
				$cfg = self::getCategoryConfig($cfg['parent_id'], 1);
			}
		}
		
		self::$_getCategoryConfig_cache[$category_id][$bubbleUpFields] = $cfg;
		return $cfg;
	}
	
	/**
	 * Get the listing counts for the category requested.
	 * 
	 * @param int $category_id
	 * @param bool $force_on_fly If true, will calculate count on the fly instead
	 *   of retrieving count stored in DB
	 * @param bool $ignore_filters If true, query to "count" the listings will
	 *   not start from the one with any browsing filters applied.
	 * @return bool|array Boolean false if problem, or an associative array containing
	 *   listing counts for requested category.
	 * @since Version 6.0.4
	 */
	public static function getListingCount ($category_id, $force_on_fly = false, $ignore_filters = false)
	{
		$category_id = (int)$category_id;
		if (!$category_id) {
			return false;
		}
		$db = DataAccess::getInstance();
		
		if (!$force_on_fly && !$db->getTableSelect(DataAccess::SELECT_BROWSE)->hasWhere()) {
			//we can do things the easy way, get the pre-counted count
			$sql = "SELECT `category_count` as ad_count, `auction_category_count` as auction_count, (category_count+auction_category_count) as listing_count FROM ".geoTables::categories_table."
				WHERE `category_id`=?";
				
			return $db->GetRow($sql,array($category_id));
		}
		//Manually count the listings in the requested category.
		
		$counts = array ('listing_count' => 0);
		
		//Get the in statement for the category
		
		$in_stmt = $db->GetOne("SELECT `in_statement` FROM ".geoTables::categories_table." WHERE `category_id`=?", array($category_id));
		
		if (!$in_stmt) {
			//sanity check
			return false;
		}
		
		$cTable = geoTables::classifieds_table;
		
		if ($ignore_filters) {
			$query = new geoTableSelect($cTable);
		} else {
			$query = $db->getTableSelect(DataAccess::SELECT_BROWSE,true);
		}
		
		$query->where($cTable.".`live`=1",'live')
			->where($cTable.".`category` $in_stmt", 'category');
		
		//Allow addons to alter query for counting listings
		$addon_vars = array (
			'category_id' => $category_id,
			'force_on_fly' => $force_on_fly,
			'ignore_filters' => $ignore_filters,
			'query' => $query,
		);
		geoAddon::triggerUpdate('geoCategory_getListingCount', $addon_vars);
		unset($addon_vars);
		
		if (geoMaster::is('classifieds')) {
			//get classifieds count
			$query->where($cTable.".`item_type`=1", 'item_type');
			
			$counts['ad_count'] = (int)$db->GetOne( ''.$query->getCountQuery());
			$counts['listing_count'] += $counts['ad_count'];
		}
		if (geoMaster::is('auctions')) {
			//get count for auctions
			//switch item_type check to work for auctions instead
			$query->where($cTable.".`item_type` = 2", 'item_type');
				
			$counts['auction_count'] = (int)$db->GetOne(''.$query->getCountQuery());
			$counts['listing_count'] += $counts['auction_count'];
		}
		unset($query);//we are done, make sure mem is freed up
		return $counts;
	}
	
	/**
	 * Sees if the given category has any kids.
	 * @param int $category_id
	 * @return bool
	 */
	public static function hasChildren($category_id)
	{
		//TODO: cache stuff if possible
		//check input
		$category_id = intval($category_id);
		if ($category_id==0){
			return false;
		}
		$db = DataAccess::getInstance();
		$sql = "SELECT COUNT(*) FROM ".geoTables::categories_table." WHERE `parent_id` = ?";
		$count = (int)$db->GetOne($sql, array($category_id));
		return ($count > 0);
	}
	
	
	/**
	 * Not currently used, we plan to move the same named method in site class here,
	 * but it's not done all the way yet.
	 * 
	 * @param $name
	 * @param $category_id
	 * @param $no_main
	 * @param $css_control
	 * @param $all_cat_text
	 * @param $return_type
	 * @param $max_depth
	 * @return unknown_type
	 */
	public static function get_category_dropdown($name,$category_id=0,$no_main=0,$css_control=0,$all_cat_text='',$return_type=1, $max_depth=-1)
	{
		$all_cat_text = (strlen($all_cat_text)>0) ? $all_cat_text : "All Categories";
		$content = "";

		if (!in_array( $name, $this->category_dropdown_settings_array) ||
			!in_array( $max_depth, $this->category_dropdown_settings_array) ||
			!in_array( $no_main, $this->category_dropdown_settings_array) )
		{
			// Empty the arrays if it is new values
			$this->category_dropdown_settings_array = array_slice($this->category_dropdown_settings_array,0,0);
			$this->category_dropdown_name_array = array_slice($this->category_dropdown_name_array,0,0);
			$this->category_dropdown_id_array = array_slice($this->category_dropdown_name_array,0,0);
		}

		if (empty($this->category_dropdown_settings_array))
		{
			// Add settings if array is empty
			array_push($this->category_dropdown_settings_array, $name);
			array_push($this->category_dropdown_settings_array, $no_main);
			array_push($this->category_dropdown_settings_array, $max_depth);
		}


		//echo count($this->category_dropdown_id_array)." is the count of category_dropdown_id_array<br />\n";
		if (!$no_main)
		{
			if (!in_array(0,$this->category_dropdown_id_array) )
			{
				array_push($this->category_dropdown_name_array, $all_cat_text);
				array_push($this->category_dropdown_id_array,0);
			}
		}

		//echo count($this->category_dropdown_id_array)." is the count of category_dropdown_id_array<br />\n";

		if ((count($this->category_dropdown_id_array) == 0) || (count($this->category_dropdown_id_array) == 1))
		{
			//echo "building categories array<br />\n";
			$this->get_all_subcategories_for_dropdown(0,0,$max_depth);
		}
		else
		{
			//echo "resetting categories array<br />\n";
			reset($this->category_dropdown_name_array);
			reset($this->category_dropdown_id_array);
		}

		$tpl = new geoTemplate('system', 'classes');
		$tpl->assign('name', $name);
		$tpl->assign('css', $css_control);
		$options = array();
		foreach($this->category_dropdown_name_array as $key => $value)
		{
			$options[$key]['value'] = $this->category_dropdown_id_array[$key];
			$options[$key]['label'] = geoString::fromDB($value);
			if ($this->category_dropdown_id_array[$key] == $category_id) {
				$options[$key]['selected'] = true;
			}
		}
		$tpl->assign('options', $options);
		$content = $tpl->fetch('Category/category_dropdown.tpl');
		if ($return_type == 2) {
			return $content;
		} else {
			$this->body .= $content;
			return true;
		}


	} //end of function get_category_dropdown
	
	/**
	 * Update the listing count on the given category ID.
	 * @param int $category_id
	 * @param bool $count_parents If false, will not count parent categories - param
	 *   added in version 6.0.5
	 */
	public static function updateListingCount ($category_id, $count_parents = true)
	{
		$category_id = (int)$category_id;
		if (!$category_id){
			return false;
		}
		$db = DataAccess::getInstance();
		
		$counts = self::getListingCount($category_id, true, true);
		
		if ($counts === false) {
			//oops!  can't do anything without counts
			return false;
		}
		
		$parts = array();
		$query_parts = array();
		
		if (isset($counts['ad_count'])) {
			$parts[] = "`category_count`=?";
			$query_parts[] = $counts['ad_count'];
		}
		
		if (isset($counts['auction_count'])) {
			$parts[] = "`auction_category_count`=?";
			$query_parts[] = $counts['auction_count'];
		}
		$query_parts[] = $category_id;
		$sql = "UPDATE ".geoTables::categories_table." SET ".implode(', ', $parts)." WHERE `category_id` = ? LIMIT 1";
		$db->Execute($sql, $query_parts);
		
		if ($count_parents) {
			//go through parents and update the parent categories as well
			$parent = self::getParent($category_id);
			if ($parent>0) {
				self::updateListingCount($parent);
			}
		}
	}
	/**
	 * Internal
	 * @internal
	 */
	private static $_category_tree_array = array();
	
	
	/**
	 * Gets a tree for the given category.
	 * @param int $category
	 * @return array
	 */
	public static function getTree ($category)
	{
		$db = DataAccess::getInstance();
		$category = (int)$category;
		if (!$category) {
			return;
		}
		
		$category_next = $category;
		
		$get_parent_stmt = $db->Prepare("SELECT c.`parent_id`, c.`in_statement`, l.`category_name` FROM ".geoTables::categories_table." as c, ".geoTables::categories_languages_table." as l WHERE c.`category_id` = l.`category_id` AND c.`category_id` = ? AND l.`language_id` = ".$db->getLanguage()." LIMIT 1");
		
		if (!$get_parent_stmt) {
			trigger_error('ERROR SQL: message: '.$db->ErrorMsg());
			return false;
		}
		$tree = array();
		while ($category_next > 0) {
			/*
			 * Store the category info in static array, one entry for each category
			 * so we only get info for each category once for times that we need
			 * to get category tree a bunch of times in same page load.  Doing it
			 * this way should minimize extra memory usage
			 */
			if (!isset(self::$_category_tree_array[$category_next])) {
				$category_result =  $db->Execute($get_parent_stmt, array($category_next));
				
				if (!$category_result) {
					trigger_error('ERROR SQL: message: '.$db->ErrorMsg());
					return false;
				}
				if ($category_result->RecordCount() == 1) {
					$show = $category_result->FetchRow();
					self::$_category_tree_array[$category_next] = array(
						'parent_id' => $show['parent_id'],
						'in_statement' => $show['in_statement'],
						'category_name' => geoString::fromDB($show['category_name']),
						'category_id' => $category_next,
					);
					
				} else {
					return false;
				}
			}
			$tree[] = self::$_category_tree_array[$category_next];
			$category_next = self::$_category_tree_array[$category_next]['parent_id'];
	 	}
		
		//reverse order of tree, or it will be backwards since we started from
 		//the outermost cat and worked our way up.
 		return array_reverse($tree, true);
	}
	
	/**
	 * Gets the HTML for new icon if there are new listings and setting is turned on,
	 * otherwise returns empty string.
	 * 
	 * @param int $category_id
	 * @return string
	 */
	public static function new_ad_icon_use($category_id=0)
	{
		$db = DataAccess::getInstance();
		
		if ($db->get_site_setting('category_new_ad_limit') > 0 && $category_id) {
			$messages = $db->get_text(true);
			if (strlen($messages[500794])) {
				$date_limit = (geoUtil::time() - ($db->get_site_setting('category_new_ad_limit') * 3600));
				$db->preload_num_new_ads(geoUtil::time(),$date_limit);
	
				$count = $db->num_new_ads_in_category($category_id,geoUtil::time(),$date_limit);
				if ($count > 0) {
					$tpl = new geoTemplate('system', 'classes');
					return $tpl->fetch('Category/new_ad_image.tpl');
				}
			}
		}
		return '';
	}
	
	/**
	 * Gets the parent category ID for the given category.  If it can't find the
	 * parent, or 0 is specified, will return 0.  This is NOT efficient for running
	 * multiple times for same category, so use once and save info.
	 * 
	 * @param int $categoryId
	 * @return int The parent category ID.
	 * @since Version 5.0.0
	 */
	public static function getParent ($categoryId)
	{
		$categoryId = (int)$categoryId;
		if (!$categoryId) {
			return 0;
		}
		$db = DataAccess::getInstance();
		
		$sql = "SELECT `parent_id` FROM ".geoTables::categories_table." WHERE `category_id`=$categoryId";
		$row = $db->GetRow($sql);
		if ($row && isset($row['parent_id'])) {
			return (int)$row['parent_id'];
		}
		return 0;
	}
	
	/**
	 * Removes a specified category and all sub-categories, and anything "attached".
	 * But it can have the option to "move" listings to the parent instead of
	 * deleted.
	 * 
	 * this does NOT re-count the parent category counts, it is up to calling
	 * caller to do that this is designed to be called multiple times.
	 * 
	 * As of version 6.0.6, will automatically update listing counts and the in_statement
	 * for any categories where those might be affected by category removal
	 * 
	 * @param int $categoryId
	 * @param bool|int $moveTo If true, will move all listings in category being
	 *   removed to the parent category.  If an int, will move listings to specified
	 *   value used as category id to move to.
	 * @param bool $recurse Used internally for whether this is recursive call or not,
	 *   param added inversion 6.0.6
	 * @return bool
	 */
	public static function remove ($categoryId, $moveTo = null, $recurse = false)
	{
		$categoryId = (int)$categoryId;
		if (!$categoryId) {
			//can't remove a non-existent category...
			return false;
		}
		$db = DataAccess::getInstance();
		
		if (!$recurse || $moveTo===true) {
			//we will need to figure out the parent ID if this is the initial call,
			//or if we don't know where we are moving the listings to...
			$sql = "SELECT `parent_id` FROM ".geoTables::categories_table." WHERE `category_id`={$categoryId}";
			$parentId = $db->GetOne($sql);
			if ($parentId === false) {
				//error
				trigger_error('ERROR SQL: Error finding parent cat using sql: '.$sql.', Error: '.$db->ErrorMsg());
				return false;
			}
			//just to be sure...
			$parentId = (int)$parentId;
		}
		
		if ($moveTo === true) {
			//specified that it should be moved, but not sure where to...
			
			$moveTo = (int)$parentId;
			if (!$moveTo || $moveTo == $categoryId) {
				//moving not possible, block removing this category when move
				//is specified but not able to determine where to move to
				trigger_error('ERROR STATS: Problem finding category to move to, can not proceed with category removal.');
				return false;
			}
		}
		//turn moveTo into int after this, we know if it's 0 we're deleting all
		//attached listings, otherwise we're moving attached listings to specified
		//category.
		$moveTo = (int)$moveTo;
		if ($moveTo == $categoryId) {
			//can't move to the same place!
			trigger_error('ERROR STATS: Problem moving listings to category, category from and to cannot be the same!');
			return false;
		}
		//get all sub-categories of this one and remove them
		//This needs to be super efficient, clean up vars after using them, etc.
		
		$sql = "SELECT `category_id` FROM ".geoTables::categories_table." WHERE `parent_id`={$categoryId}";
		$result = $db->Execute($sql);
		if (!$result) {
			//error
			trigger_error('ERROR SQL: Error finding sub-cates using sql: '.$sql.', Error: '.$db->ErrorMsg());
			return false;
		}
		while ($row = $result->FetchRow()) {
			//don't use fancy $db->GetAll() as that loads them all into array at once, which
			//can take a ton more memory for sites with tons of cats.
			$subCat = (int)$row['category_id'];
			if ($subCat && $subCat != $categoryId) {
				$deleteSub = self::remove($subCat, $moveTo, true);
				if (!$deleteSub) {
					//problem with deleting sub-category, do not proceed
					return false;
				}
			}
		}
		
		//next, remove (or move) all listings in this category.
		if ($moveTo) {
			//"move" all listings to the moveTo location.
			$sql = "UPDATE ".geoTables::classifieds_table." SET `category`={$moveTo} WHERE `category`={$categoryId}";
			$result = $db->Execute($sql);
			if (!$result) {
				//error
				trigger_error('ERROR SQL: Error moving listings to new category using sql: '.$sql.', Error: '.$db->ErrorMsg());
				return false;
			}
		} else {
			//remove all listings in this category
			$sql = "SELECT `id` FROM ".geoTables::classifieds_table." WHERE `category`={$categoryId}";
			$result = $db->Execute($sql);
			if (!$result) {
				//error
				trigger_error('ERROR SQL: Error finding listings in category to remove using sql: '.$sql.', Error: '.$db->ErrorMsg());
				return false;
			}
			while ($row = $result->FetchRow()) {
				$listingId = (int)$row['id'];
				if ($listingId) {
					$deleteListing = geoListing::remove($listingId);
					if (!$deleteListing) {
						//problem removing one of the listings...
						trigger_error('ERROR STATS: Problem removing a listing in a category, stopping removal of the category.');
						return false;
					}
				}
			}
		}
		
		//Need to do something special to find and remove all category filters
		$sql = "SELECT `filter_id` FROM ".geoTables::ad_filter_table."
			WHERE `category_id`={$categoryId}";
		$select_filter_result = $db->Execute($sql);
		if (!$select_filter_result) {
			trigger_error('ERROR SQL: Error during cat removal using sql: '.$sql.', Error: '.$db->ErrorMsg());
			return false;
		}
		while ($row = $select_filter_result->FetchRow()) {
			$sql = "DELETE FROM ".geoTables::ad_filter_categories_table."
				WHERE `filter_id` = ".(int)$row["filter_id"];
			$delete_filter_result = $db->Execute($sql);
			if (!$delete_filter_result) {
				trigger_error('ERROR SQL: Error during cat removal using sql: '.$sql.', Error: '.$db->ErrorMsg());
				return false;
			}
		}
		
		//special to remove languages for category questions
		$sql = "SELECT `question_id` FROM ".geoTables::questions_table."
			WHERE `category_id`={$categoryId}";
		$select_question_result = $db->Execute($sql);
		if (!$select_question_result) {
			trigger_error('ERROR SQL: Error during cat removal using sql: '.$sql.', Error: '.$db->ErrorMsg());
			return false;
		}
		while ($row = $select_question_result->FetchRow()) {
			$sql = "DELETE FROM ".geoTables::questions_languages."
				WHERE `question_id` = ".(int)$row["question_id"];
			$delete_filter_result = $db->Execute($sql);
			if (!$delete_filter_result) {
				trigger_error('ERROR SQL: Error during cat removal using sql: '.$sql.', Error: '.$db->ErrorMsg());
				return false;
			}
		}
		
		//remove "simple" things from categories that don't need anything more
		//than removing entries from the DB based on cat id
		$simpleRemoves = array (
			geoTables::ad_filter_table,//main ad filters page
			geoTables::ad_filter_categories_table,//alt category filters table
			geoTables::price_plans_categories_table,//category price plans
			geoTables::questions_table,//category questions
			geoTables::categories_languages_table,//category languages table
		);
		foreach ($simpleRemoves as $tableName) {
			//delete all entries that match this listing
			$sql = "DELETE FROM {$tableName} WHERE `category_id`={$categoryId}";
			$result = $db->Execute($sql);
			if (!$result) {
				trigger_error('ERROR SQL: Error removing stuff from category, using sql: '.$sql.', Error: '.$db->ErrorMsg());
				return false;
			}
		}
		
		//move order items attached to this category to use cat 0
		$sql = "UPDATE ".geoTables::order_item." SET `category`=0 WHERE `category`={$categoryId}";
		$result = $db->Execute($sql);
		if (!$result) {
			trigger_error('ERROR SQL: Error moving order items to 0 category, using sql: '.$sql.', Error: '.$db->ErrorMsg());
			return false;
		}
		//remove any price plan items
		geoPlanItem::remove(null, $categoryId);
		
		//remove from category-specific fields to use
		geoFields::remove(null, $categoryId);
		
		//delete the actual category
		$sql = "DELETE FROM ".geoTables::categories_table." WHERE `category_id`={$categoryId}";
		$result = $db->Execute($sql);
		if (!$result) {
			trigger_error('ERROR SQL: Error removing category, using sql: '.$sql.', Error: '.$db->ErrorMsg());
			return false;
		}
		
		if (!$recurse && $parentId) {
			//now that category is deleted, and this is not recursively called,
			//and there is a parent, need to update the parent
			
			//now update the parent in statement...  this will automatically update child
			//and parent in statements if they are different than what is generated.
			geoCategory::updateInStatement($parentId);
			
			//now update the parent's listing count, this will automatically update
			//parents as well.
			geoCategory::updateListingCount($parentId);
		}
		return true;
	}
	
	/**
	 * Gets the "top" parent ID for the given category ID by traveling up the
	 * category tree.
	 * 
	 * @param int $categoryId
	 * @return boolean|number The top category ID or false if there was a problem.
	 * @since Version 6.0.6
	 */
	public static function getTopParent ($categoryId)
	{
		$categoryId = (int)$categoryId;
		
		while (($parent = geoCategory::getParent($categoryId)) > 0) {
			$categoryId = $parent;
		}
		return $categoryId;
	}
	/**
	 * Used internally
	 * @var array
	 * @internal
	 */
	private static $_in_statements = array();
	
	/**
	 * Gets the in statement for the category
	 * @param int $categoryId
	 * @return boolean|string The in string, or false on error
	 */
	public static function getInStatement ($categoryId)
	{
		$db = DataAccess::getInstance();
		$categoryId = (int)$categoryId;
		if (!$categoryId) {
			//not valid category!
			return false;
		}
		
		if (!isset(self::$_in_statements[$categoryId])) {
			$result = $db->Execute("SELECT `in_statement` FROM ".geoTables::categories_table." WHERE `category_id`=$categoryId");
			if (!$result) {
				//actual DB error
				trigger_error("ERROR SQL: Error getting in statement for category $categoryId");
				return false;
			}
			if ($result->RecordCount() == 0) {
				trigger_error("ERROR CATEGORY: Not a valid category!");
				return false;
			}
			$row = $result->FetchRow();
			if (!strlen(trim($row['in_statement']))) {
				//in statement not known?  go ahead and update on fly, probably
				//silly site owner manually entered categories or it timed out or something
				$in_array = geoCategory::updateInStatement($categoryId);
				
				self::$_in_statements[$categoryId] = 'in ('.implode(',',$in_array).')';
				unset($in_array);
			} else {
				self::$_in_statements[$categoryId] = trim($row['in_statement']);
			}
		}
		return self::$_in_statements[$categoryId];
	}
	
	/**
	 * Update the in statement for the given category.
	 * 
	 * @param int $categoryId
	 * @param string $current_in_statement If supplied, will compare the generated
	 *   in statement to this, and if the same, will not bother updating the in statement
	 * @param bool $start_at_top if false, will not go to the "top parent" and process
	 *   starting at that level.  Used internally mostly.
	 * @return array|bool Returns the array of categories that are part of the
	 *   in statement (including category being requested), or false if there is
	 *   a problem.
	 * @since Version 6.0.6
	 */
	public static function updateInStatement ($categoryId, $current_in_statement='', $start_at_top = true)
	{
		$categoryId = (int)$categoryId;
		if ($start_at_top) {
			//always "start" at top parent and work our way down the category tree
			return self::updateInStatement(geoCategory::getTopParent($categoryId),'',false);
		}
		if (!$categoryId) {
			return false;
		}
		
		$cats = array ($categoryId);
		
		$db = DataAccess::getInstance();
		
		$result = $db->Execute("SELECT `category_id`, `in_statement` FROM ".geoTables::categories_table." WHERE `parent_id`=$categoryId");
		if (!$result) {
			trigger_error("ERROR SQL: db error getting children cats");
			return false;
		}
		foreach ($result as $row) {
			$in_stmt = self::updateInStatement($row['category_id'], $row['in_statement'], false);
			if (is_array($in_stmt)) {
				$cats = array_merge($cats, $in_stmt);
			}
		}
		//ensure that the order of the categories are always the same, so that
		//it doesn't re-save in statement just because the order comes out different...
		sort($cats, SORT_NUMERIC);
		$in_statement = 'in ('.implode(',',$cats).')';
		
		if (trim($current_in_statement) !== $in_statement) {
			//update in statement
			$db->Execute("UPDATE ".geoTables::categories_table." SET `in_statement`=? WHERE `category_id`=$categoryId", array($in_statement));
		}
		return $cats;
	}
	/**
	 * Gets the HTML to add to the header for the specific category
	 * @param int $catId
	 * @return string
	 */
	public static function getHeaderHtml ($catId)
	{
		$db = DataAccess::getInstance();
		
		$start = "\n<!-- Category Specific Start -->\n";
		$end = "\n<!-- Category Specific End -->\n";
		
		$catId = (int)$catId;
		
		if (!$catId) {
			//get defautl text
			$text = $db->get_text(true, 3);
			$header = self::_parseTpl($text[500961]);
			return ($header ? $start.$header.$end : ''); 
		}
		
		//see "which header html" to use
		$cat = $db->GetRow("SELECT * FROM ".geoTables::categories_table." WHERE `category_id`=?", array($catId));
		
		if (!$cat) {
			//could not get info for category use default
			return self::getHeaderHtml(0);
		}
		
		$which = $cat['which_header_html'];
		$parent = $cat['parent_id'];
		
		$return = array();
		
		if ($which=='parent') {
			return self::getHeaderHtml($parent);
		}
		if ($which=='cat'||$which=='cat+default') {
			//append category specific!
			$langRow = $db->GetRow("SELECT `header_html` FROM ".geoTables::categories_languages_table." WHERE `category_id`=? AND `language_id`=?", array($catId, $db->getLanguage()));
			if ($langRow && $langRow['header_html']) {
				$return[] = self::_parseTpl(geoTemplate::parseExternalTags(geoString::fromDB($langRow['header_html'])));
			}
		}
		if ($which=='default'||$which=='cat+default') {
			$text = $db->get_text(true, 3);
			if ($text[500961]) {
				$return[] = self::_parseTpl($text[500961]);
			}
		}
		
		$header = implode("\n\n",$return);
		return ($header ? $start.$header.$end : ''); 
	}
	/**
	 * Get catgory values, in an array format that is expected by the multi-level
	 * selection stuff.  This allows the multi-level field selection stuff able
	 * to be used to select category as well.
	 * 
	 * @param int $parent
	 * @param int $listing_types_allowed
	 * @param int $selected
	 * @param int $page
	 * @param int $language_id
	 * @param int $level
	 * @return boolean|array The array of values as needed to show in multi-level
	 *   selection format, or false on error
	 */
	public static function getCategoryLeveledValues ($parent, $listing_types_allowed, $selected = 0, $page='all', $language_id=null, $level=null)
	{
		$db = DataAccess::getInstance();
		$parent = (int)$parent;
		$selected = (int)$selected;
		$page = ($page=='all')? 'all' : (int)max(1,$page);
		
		if ($parent<0) {
			//invalid input
			return false;
		}
		$return = array('values'=>array(), 'maxValues'=>0, 'page'=>1, 'maxPages'=>1,
			'level' => 1);
		
		$language_id = (int)$language_id;
		if (!$language_id) {
			//get the current language id
			$language_id = (int)geoSession::getInstance()->getLanguage();
		}
		
		//figure out the level
		if (!$parent) {
			$return['level'] = $level = 1;
		} else {
			if ($level!==null) {
				$return['level'] = (int)$level;
			} else {
				//calculate the level
				$prev_parent = $parent;
				while ($prev_parent>0) {
					$prev_parent = (int)$db->GetOne("SELECT `parent_id` FROM ".geoTables::categories_table." WHERE `category_id`=?", array($prev_parent));
					$return['level']++;
				}
				$level = $return['level'];
			}
		}
		
		$catTbl = geoTables::categories_table;
		$langTbl = geoTables::categories_languages_table;
		$query = new geoTableSelect($catTbl);
		$query->from($catTbl, array("$catTbl.category_id", "$catTbl.display_order"));
		
		$orderByAlpha = (bool)$db->get_site_setting('order_choose_category_by_alpha');
		
		$cat_img = ($orderByAlpha)? ", $catTbl.category_image": '';
		$order_by = ($db->get_site_setting('order_choose_category_by_alpha'))? "$langTbl.category_name" : "$catTbl.display_order, $langTbl.category_name";
		
		$types_allowed = "$catTbl.`listing_types_allowed` = 0";
		if ($listing_types_allowed) {
			$types_allowed .= " OR $catTbl.`listing_types_allowed` = '$listing_types_allowed'";
		}
		
		$query->join($langTbl, "$catTbl.category_id = $langTbl.category_id", "$langTbl.category_name{$cat_img}, $langTbl.description")
			->where("$catTbl.`parent_id` = '$parent'", 'parent_id')
			->where("$langTbl.language_id = {$language_id}", 'language_id')
			->where($types_allowed, 'listing_types_allowed')
			->order($order_by);
		
		//kick the query over to any addons that care to modify which categories are shown
		geoAddon::triggerDisplay('filter_listing_placement_category_query', $query, geoAddon::FILTER);
		
		$return['maxValues'] = (int)$db->GetOne($query->getCountQuery());
		if (!$return['maxValues']) {
			//no use in running the normal query, we already know count is 0
			return $return;
		}
		
		$values_per_page = (int)$db->get_site_setting('leveled_max_vals_per_page');
		
		if ($return['maxValues'] > $values_per_page) {
			//calculate number of pages
			$return['maxPages'] = ceil($return['maxValues']/$values_per_page);
		
			if ($page!=='all' && $page <= $return['maxPages']) {
				//add limit
				$start = ($page-1) * $values_per_page;
		
				$query->limit($start, $values_per_page);
				//this is the "actual" page we are on
				$return['page'] = $page;
			} else if ($page === 'all' && $return['maxPages']>1) {
				//set the returned page to 'all'
				$return['page'] = 'all';
			}
		}
		$result = $db->Execute($query);
		if (!$result) {
			//error?
			trigger_error('ERROR SQL: Error getting leveled field values!');
			return false;
		}
		$foundSelected = false;
		$lastRow = null;
		$rows = array();
		foreach ($result as $row) {
			//unescape name
			$row['name'] = geoString::fromDB($row['category_name']);
			$row['id'] = $row['category_id'];
			if ($selected) {
				$row['selected'] = ($selected==$row['id']);
				if ($row['selected']) {
					$foundSelected = true;
				}
			}
			$row['level'] = $level;
			$rows[$row['id']] = $lastRow = $row;
		}
		$return['values'] = $rows;
		if (!$foundSelected && $selected > 0) {
			//add selected to front / end!
			$query->where("$catTbl.`category_id`=$selected")->limit();
			$row = $db->GetRow($query);
			if ($row) {
				$row['name'] = geoString::fromDB($row['category_name']);
				$row['id'] = $row['category_id'];
				$row['level'] = $level;
				$row['selected'] = true;
				$row['is_off_page'] = true;
				
				//figure out if "before" or "after"
				$addBefore = ($page > 1);
				if ($addBefore) {
					//it is "possible" that it should be before, so verify it
					if ($orderByAlpha || $lastRow['display_order'] == $row['display_order']) {
						//only order by alpha...
						$check = array(
							$lastRow['id'] => $lastRow['name'],
							$row['id'] => $row['name'],
							);
						asort($check);
						//now figure out which one is first in the array, that is the one
						//that is first alphabetically.
						$check = array_keys($check);
						$addBefore = ($check[0] == $row['id']);
					} else {
						$addBefore = ($row['display_order'] < $lastRow['display_order']);
					}
				}
				//now either add it "before" or "after" the return array...
				if ($addBefore) {
					//add it before!
					$return['values'] = array();
					$return['values'][$row['id']] = $row;
					foreach ($rows as $key => $val) {
						$return['values'][$key] = $val;
					}
				} else {
					//add it after!
					$rows[$row['id']] = $row;
					$return['values'] = $rows;
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Used internally
	 * @param string $content
	 * @return string
	 * @internal
	 */
	private static function _parseTpl ($content)
	{
		if (substr(trim($content),0,9)=='template:') {
			$tpl = new geoTemplate(geoTemplate::MAIN_PAGE);
			return $tpl->fetch(str_replace('template:','',trim($content)));
		}
		return $content;
	}
}