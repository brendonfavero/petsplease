<?php 
//user_management_ad_filters.php
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
## ##    7.2.1-2-gac84fac
## 
##################################

class User_management_ad_filters extends geoSite
{
	var $debug_filters = 0;

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function display_all_ad_filters()
	{
		if (!geoSession::getInstance()->getUserID()) {
			return false;
		}
		
		$user = geoUser::getUser(geoSession::getInstance()->getUserID());
		
		
		$this->page_id = 27;
		$msgs = $this->db->get_text(true, $this->page_id);
		$tpl = new geoTemplate('system', 'user_management');
		$tpl->assign('helpLink', $this->display_help_link(376));
		$tpl->assign('frequencySetting', intval($user->new_listing_alert_gap / 86400));
		
		if(isset($_POST['alert_frequency'])) {
			//save alert frequency
			$frequency = $_POST['alert_frequency'];
			$days = (is_numeric($frequency) && $frequency > 0) ? $frequency : 0;
			if($days) {
				$user->new_listing_alert_gap = $days * 86400;
				$tpl->assign('frequencySaved',true);
			}
		}
				
		$sql = "select * from ".geoTables::ad_filter_table." where user_id = ".geoSession::getInstance()->getUserID()." order by date_started desc";
		$result = $this->db->Execute($sql);
		if (!$result) {
			$this->site_error($this->db->ErrorMsg());
			return false;
		} elseif ($result->RecordCount() > 0) {
			
			$tpl->assign('table_description', $msgs[375]);
			$tpl->assign('showFilters', true);

			$filters = array();
			for ($i = 0; $show = $result->FetchNextObject(); $i++)
			{
				$filters[$i]['css'] = $css_tag;
				if (!$show->CATEGORY_ID) {
					$category_name = $msgs[2313];
				} else {
					$name = geoCategory::getName($show->CATEGORY_ID);
					$category_name = $name->CATEGORY_NAME;
				}
				$filters[$i]['category_name'] = $category_name;
				if ($show->SUB_CATEGORY_CHECK) {
					$filters[$i]['sub_cat_check'] = true;
				}
				$filters[$i]['search_terms'] = $show->SEARCH_TERMS;
				$filters[$i]['date'] = date($this->db->get_site_setting('entry_date_configuration'),$show->DATE_STARTED);
				$filters[$i]['link'] = $this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=9&amp;c=2&amp;d=".$show->FILTER_ID;
			}
			$tpl->assign('filters', $filters);
			$tpl->assign('addRemoveFilterLink2', $this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=9&amp;c=3");
		} else {
			//there are no ad filters for this user
			$tpl->assign('table_description', $msgs[377]);
			$tpl->assign('showFilters', false);
		}

		$tpl->assign('addRemoveFilterLink', $this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=9&amp;c=1");
		$tpl->assign('userManagementHomeLink', $this->db->get_site_setting('classifieds_file_name')."?a=4");
		
		$this->body = $tpl->fetch('ad_filters/display_all_filters.tpl');
		$this->display_page();
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_ad_filter($db,$filter_id=0)
	{
		if (geoSession::getInstance()->getUserID())
		{
			if ($filter_id)
			{
				$sql = "delete from ".geoTables::ad_filter_table." where filter_id = ".$filter_id;
				$result = $this->db->Execute($sql);
				if ($this->debug_filters) echo $sql."<br />\n";
				if (!$result)
				{
					if ($this->debug_filters) echo $sql."<br />\n";
					$this->site_error($this->db->ErrorMsg());
					return false;
				}

				$sql = "delete from ".geoTables::ad_filter_categories_table." where filter_id = ".$filter_id;
				$result = $this->db->Execute($sql);
				if ($this->debug_filters) echo $sql."<br />\n";
				if (!$result)
				{
					if ($this->debug_filters) echo $sql."<br />\n";
					$this->site_error($this->db->ErrorMsg());
					return false;
				}
				return true;
			}
			else
			{
				//no filter id
				$this->error_message = $this->data_missing_error_message;
				return false;
			}
		}
		else
			return false;
	} //end of function delete_ad_filter

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function add_new_filter_form()
	{
		$this->page_id = 28;
		$this->get_text();
		if(!geoSession::getInstance()->getUserID()) {
			return false;
		}
		$tpl_vars = array();
		$tpl_vars['formTarget'] = $this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=9&amp;c=4";
		$tpl_vars['categoryDDL'] = $this->get_category_dropdown("d[category_id]",0,0,0,$this->messages[500244],2);
		$tpl_vars['userManagementHomeLink'] = $this->db->get_site_setting('classifieds_file_name')."?a=4";
		
		$view = geoView::getInstance();
		$view->setBodyTpl('ad_filters/add_filter_form.tpl','','user_management')
			->setBodyVar($tpl_vars);
		$this->display_page();
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function insert_new_filter($db,$filter_info=0)
	{
		$userId = (int)$this->userid;
		if (!$userId){
			return false;
		}
		
		if (!$filter_info) {
			//no filter_info
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		
		$current_time = geoUtil::time();
		$search_terms_array = explode(",",$filter_info["search_terms"]);

		foreach ($search_terms_array as $value) {
			$sql = "insert into ".geoTables::ad_filter_table."
				(user_id,search_terms,date_started,category_id,sub_category_check)
				values
				(".geoSession::getInstance()->getUserID().",\"".$value."\",".$current_time.",".intval($filter_info["category_id"]).",".intval($filter_info["subcategories_also"]).")";
			$insert_filter_result = $this->db->Execute($sql);
			if (!$insert_filter_result) {
				$this->error_message = $this->internal_error_message;
				return false;
			}

			$filter_id = $this->db->Insert_ID();

			$sql = "insert into ".geoTables::ad_filter_categories_table."
				(filter_id, category_id)
				values
				(".$filter_id.",".intval($filter_info["category_id"]).")";
			$insert_category_result = $this->db->Execute($sql);
			if (!$insert_category_result) {
				$this->error_message = $this->internal_error_message;
				return false;
			}

			if ($filter_info["subcategories_also"] == 1 && $filter_info["category_id"] > 0) {
				//get subcategories and insert them into the db
				$this->get_all_subcategories_for_dropdown($filter_info["category_id"],1);

				//use class variable to insert category_ids
				reset($this->category_dropdown_id_array);
				//make sure the same subcategory is not added multiple times.
				$already_added_cat = array();
				foreach ($this->category_dropdown_id_array as $cat_value) {
					if (in_array($cat_value, $already_added_cat)) {
						//already added for this category.
						continue;
					}
					$already_added_cat [] = $cat_value;
					$sql = "insert into ".geoTables::ad_filter_categories_table."
						(filter_id, category_id)
						values
						(".$filter_id.",".$cat_value.")";
					$result = $this->db->Execute($sql);
					if (!$result) {
						$this->error_message = $this->internal_error_message;
						return false;
					}
				}
			}
		}
		return true;
	} //end of function add_new_filter

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function clear_ad_filters()
	{
		if (geoSession::getInstance()->getUserID())
		{
			$sql = "select * from ".geoTables::ad_filter_table." where user_id = ".geoSession::getInstance()->getUserID();
			$filter_result = $this->db->Execute($sql);
			if ($this->debug_filters) echo $sql."<br />\n";
			if (!$filter_result)
			{
				if ($this->debug_filters) echo $sql."<br />\n";
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($filter_result->RecordCount() > 0)
			{
				while ($show = $filter_result->FetchNextObject())
				{
					$sql = "delete from ".geoTables::ad_filter_categories_table." where filter_id = ".$show->FILTER_ID;
					$result = $this->db->Execute($sql);
					if ($this->debug_filters) echo $sql."<br />\n";
					if (!$result)
					{
						if ($this->debug_filters) echo $sql."<br />\n";
						$this->error_message = $this->internal_error_message;
						return false;
					}
				}
			}

			$sql = "delete from ".geoTables::ad_filter_table." where user_id = ".geoSession::getInstance()->getUserID();
			$result = $this->db->Execute($sql);
			if ($this->debug_filters) echo $sql."<br />\n";
			if (!$result)
			{
				if ($this->debug_filters) echo $sql."<br />\n";
				$this->error_message = $this->internal_error_message;
				return false;
			}
			return true;
		}
		else
			return false;

	} //end of function clear_ad_filters

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	
	/**
	 * checks a given user's filters and returns data on matched listings timestamped since last sent.
	 * @param unknown_type $user_id
	 * @return array of stuff
	 */
	public function checkUserFilters($user_id)
	{
		$user = geoUser::getUser($user_id);
		if(!$user) {
			//no user? can't continue
		}
		$cron = geoCron::getInstance();
		$db = DataAccess::getInstance();
		
		if($cron) {
			$cron->log('checking filters for user '.$user_id,__LINE__);
		}
		
		//get all listings since the last time this user was checked (only live listings not belonging to this seller)
		$sql = "SELECT `id` FROM ".geoTables::classifieds_table." WHERE `date` >= ? AND `live` = '1' AND `seller` <> ?";
		$result = $db->Execute($sql, array($user->new_listing_alert_last_sent, $user->id));
		
		$filtersMatched = array();
		foreach($result as $l) {
			$listing = geoListing::getListing($l['id']);
			
			if($cron) {
				$cron->log('checking listing '.$listing->id,__LINE__);
			}
			
			//see if user has any filters that match this listing
			$searchString = $this->getSearchString($listing->id);
			
			if($cron) {
				$cron->log('search string is: '.$searchString,__LINE__);
			}
			
			$sql = "SELECT * FROM ".geoTables::ad_filter_categories_table." c, ".geoTables::ad_filter_table." f
				WHERE f.filter_id = c.filter_id 
				AND (c.`category_id` = {$listing->category} OR c.`category_id` = 0) 
				AND `user_id` = ? 
				ORDER BY `search_terms` DESC";
			$filter_category_result = $db->Execute($sql, array($user->id));
			foreach($filter_category_result as $show_filter_term) {
				//find out if this filter matches
				$filterString = $show_filter_term['search_terms'];
				if(strlen($filterString) > 0) {
					//filter string exists for this filter -- see if it exists in listing text
					if($this->checkFilterString($filterString, $searchString)) {
						$filtersMatched[$listing->id] = array(
								'type' => 'string',
								'value' => $filterString,
								'title' => geoString::fromDB($listing->title),
								'url' => $listing->getFullUrl()
						);
						
						if($cron) {
							$cron->log('matched string: '.$filterString,__LINE__);
						}
						
						break; //matched for this listing; no need to check other filters here
					}
				} else {
					//there is no string for this filter, which means it should match any listings in its category
					//that's already accounted for in the SQL query, so we can just mark this as matched and move on
					$filtersMatched[$listing->id] = array(
							'type' => 'category',
							'value' => geoCategory::getName($listing->category, true),
							'title' => geoString::fromDB($listing->title),
							'url' => $listing->getFullUrl()
					);
					
					if($cron) {
						$cron->log('matched category: '.$listing->category,__LINE__);
					}
					
					break; //this should be the last loop iteration thanks to the query's ORDER BY, but just in case...
				}
			}
		}
		if(count($filtersMatched) > 0) {
			//matched at least one filter -- send this person an email
			$this->sendAlertEmail($user, $filtersMatched);
		}
	}
	
	/**
	 * Takes the raw data and creates/sends an email from it
	 * @param geoUser $to
	 * @param Array $data
	 */
	private function sendAlertEmail($to, $data)
	{
		$this->page_id = 29;
		$this->get_text();
		
		$tpl = new geoTemplate('system','emails');
		
		//per client request, add in the URL of the lead image for each listing to template vars
		$db = DataAccess::getInstance();
		foreach($data as $listingId => $devNull) {
			$data[$listingId]['lead_image_url'] = $db->GetOne("SELECT `image_url` FROM ".geoTables::images_urls_table." WHERE `classified_id` = ? ORDER BY `display_order` ASC", array($listingId));
		}
		
		$tpl->assign('data', $data);
		
		
		$tpl->assign('messageBody',$this->messages[502067]);
		$tpl->assign('filterLabel',$this->messages[502068]);
		$tpl->assign('categoryLabel',$this->messages[502069]);
		$tpl->assign('titleLabel',$this->messages[502070]);
		$tpl->assign('linkLabel',$this->messages[502071]);
		$message = $tpl->fetch('filter_matched.tpl');
		$subject = $this->messages[1318];
		geoEmail::sendMail($to->email, $subject, $message, 0, 0, 0, 'text/html');
		
		$cron = geoCron::getInstance();
		if($cron) {
			$cron->log('sent this email: '.$message,__LINE__);
		}
		
		if ($user->communication_type != 1) {
			//DEPRECATED? (2/27/13)
			/* This used to also save the message to the user's local inbox.
			 * I'm not really sure how that would be useful in the first place,
			 * but it especially doesn't apply as much now that each email doesn't correspond 1-1 with a specific listing.
			 * If anyone happens to notice this is missing, it could be worked back in, but I don't suspect it will be missed.
			 */  
			
			//$sql = "INSERT INTO ".geoTables::user_communications_table."
			//(message_to,message_from_non_user,regarding_ad,date_sent,message)
			//values
			//(".$show_filter_term['user_id'].",'',".$listing_id.",".geoUtil::time().",?)";
			//$save_communication_result = $this->db->Execute($sql, array(geoString::toDB($message['message'])));
		}
		
	}
	
	/**
	 * Gets the filter search string for a listing
	 * @param int $listing_id
	 * @return string
	 */
	private function getSearchString($listing_id)
	{
		//Include the listing ID as first part, for addon filters to see what
		//listing is being filtered.
		$listing = geoListing::getListing($listing_id);
		$searchIn = "$listing_id:: $listing->search_text $listing->title $listing->description $listing->location_city";
		
		for ($i = 1; $i <= 20; $i++) {
			$field = "optional_field_$i";
			$searchIn .= " ".$listing->$field;
		}
		
		
		//let addons alter text to search through when checking filters
		$searchIn = geoAddon::triggerDisplay('filter_check_ad_filter_listing_text', $searchIn, geoAddon::FILTER);
		
		//remove the listing ID from what is being searched; that was just used for addon benefit
		$searchIn = str_replace("$listing_id:: ",'',$searchIn);
		
		//decode everything
		$searchIn = geoString::fromDB($searchIn);
		return $searchIn;
	}
	
	private function checkFilterString($searchFor, $searchIn)
	{
		$searchFor = trim(geoString::fromDB($searchFor));
		$decode = geoString::specialCharsDecode($searchFor);
		if ($decode && $decode!=$searchFor) {
			//also look for the "decoded" version for utf-8 characters to match
			$searchFor .= ','.$decode;
		}
			
		if($searchFor) {
			if (stripos($searchFor,",") !== false) {
				//break into multiple searches on a comma
				$termList = explode(",",$searchFor);
			} else {
				//no commas in search_terms -- only one search to do
				$termList = array($searchFor);
			}
		
			$foundSearchTerm = false;
			foreach ($termList as $term) {
		
				if(stripos($searchIn, $term) !== false) {
					return true;
				}
			}
		}
		//didn't find the string
		return false;
	}
}
