<?php
//addons/site_stats/tags.php
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
##
##    7.1beta1-1166-gf8ef348
##
##################################

class addon_site_stats_tags extends addon_site_stats_info
{
	private $accepted_listing_types = array(0,1,2);
	private $testing = 0;
	private function _get_listing_count($type=0,$time_frame=0)
	{
		//type = 0 get all live listing types
		//type = 1 get all live classifieds
		//type = 2 get all live auctions
		
		//time_frame = 0 get all live 
		//time_frame = 1 get all live in the last 24hrs
		//time_frame = 7 get all live 7 days
		//time_frame = 30 get all live 30 days
		
		//time_frame = -1 get all live ends in the next 24hrs
		//time_frame = -7 get all live ends in the next 7 days
		//time_frame = -30 get all live ends in the next 30 days
		
		
		$sql = "SELECT COUNT(id) FROM ".geoTables::classifieds_table." WHERE `live` = 1 ";
		
		if ($type == 1)
		{
			$type_sql .= " AND `item_type`=1 ";
		} elseif ($type == 2) {
			$type_sql .= " AND `item_type`=2 ";
			
		} else {
			//nothing to add...list all types
			$type_sql ="";
		}
		
		if ($time_frame > 0)
		{
			//add to query condition added in the past number of days
			$cutoff_time = (geoUtil::time() - ($time_frame * 86400));
			$cutoff_sql = " AND `date` > ".$cutoff_time;
		} elseif ($time_frame < 0) {
			//add to query condition expiring in next number of days
			$cutoff_time = (geoUtil::time() + (abs($time_frame) * 86400));
			$cutoff_sql = " AND `ends` < ".$cutoff_time;
		} else	{
			//get count of all irregardless of time
			$cutoff_sql = "";
		}
		
		trigger_error('DEBUG ADDON: SQL - '.$sql.$type_sql.$cutoff_sql);
		$db = DataAccess::getInstance();
		$sql_result = $db->GetOne($sql.$type_sql.$cutoff_sql);
		if (is_numeric($sql_result))
		{
			if ($this->testing == 1) {
				$return_this = $sql_result." (".$sql.$type_sql.$cutoff_sql.") time is: (".date('D, d M Y H:i:s',$cutoff_time).")";
				return $return_this;
			} else {
				return $sql_result;
			}
		} else {
			//didn't work so just return empty
			if ($this->testing == 1) { 
				return $sql.$type_sql.$cutoff_sql;
			} else {
				return '';
			}
		}		
	
	}	
	
	private function _get_listing_count_language($type=0,$language=1)
	{
		//type = 0 get all live listing types
		//type = 1 get all live classifieds
		//type = 2 get all live auctions
	
		$sql = "SELECT COUNT(id) FROM ".geoTables::classifieds_table." WHERE `live` = 1 ";
	
		if ($type == 1)
		{
			$type_sql .= " AND `item_type`=1 ";
		} elseif ($type == 2) {
			$type_sql .= " AND `item_type`=2 ";
				
		} else {
			//nothing to add...list all types
			$type_sql ="";
		}
	
		//add to query condition added in the past number of days
		$language_sql = " AND language_id = ".$language;
	
		trigger_error('DEBUG ADDON: SQL - '.$sql.$type_sql.$language_sql);
		$db = DataAccess::getInstance();
		$sql_result = $db->GetOne($sql.$type_sql.$language_sql);
		if (is_numeric($sql_result))
		{
			if ($this->testing == 1) {
				$return_this = $sql_result." (".$sql.$type_sql.$language_sql.")";
				return $return_this;
			} else {
				return $sql_result;
			}
		} else {
			//didn't work so just return empty
			if ($this->testing == 1) {
				return $sql.$type_sql.$language_sql;
			} else {
				return '';
			}
		}
	
	}	
	
	private function _get_listing_count_category($type=0,$category=0)
	{
		//type = 0 get all live listing types
		//type = 1 get all live classifieds
		//type = 2 get all live auctions
		
		if ($category == 0)
			return 0;
	
		$sql = "SELECT `auction_category_count`, `category_count` 
				FROM ".geoTables::categories_table." where `category_id` = ".$category;
	
		trigger_error('DEBUG ADDON: SQL - '.$sql.$type_sql);
		$db = DataAccess::getInstance();
		$sql_result = $db->Execute($sql.$type_sql);
		if (!$sql_result){
			trigger_error('ERROR SQL ADDON: Sql: '.$sql.$type_sql.' Err Msg: '.$db->ErrorMsg());
				
			return false;
		}		
		
		if ($sql_result->RecordCount() == 1)
		{
			$count_result = $sql_result->FetchRow();
			if ($type == 1)
				$count_to_return = $count_result['category_count'];
			elseif ($type == 2)
				$count_to_return = $count_result['auction_category_count'];
			else 
				$count_to_return = $count_result['category_count'] + $count_result['auction_category_count'];
			
			if ($this->testing == 1) {
				$return_this = $count_to_return." (".$sql.$type_sql.") and count of rows: ".count($sql_result->RecordCount());
				return $return_this;
			} else {
				return $count_to_return;
			}
		} else {
			//didn't work so just return empty
			if ($this->testing == 1) {
				return $sql.$type_sql;
			} else {
				return '';
			}
		}
	
	}	
	
	private function _get_views($type=0)
	{
		//type = 0 get all live listing types
		//type = 1 get all live classifieds
		//type = 2 get all live auctions
	
		$sql = "SELECT COALESCE(SUM(viewed),0) FROM ".geoTables::classifieds_table." WHERE `live` = 1 ";
	
		if ($type == 1)
		{
			$type_sql .= " AND `item_type`=1 ";
		} elseif ($type == 2)
		{
			$type_sql .= " AND `item_type`=2 ";
				
		} else {
			//nothing to add...list all types
			$type_sql ="";
		}
	
		trigger_error('DEBUG ADDON: SQL - '.$sql.$type_sql.$cutoff_sql);
		$db = DataAccess::getInstance();
		$sql_result = $db->GetOne($sql.$type_sql.$cutoff_sql);
		if (is_numeric($sql_result))
		{
			if ($this->testing == 1) {
				$return_this = $sql_result." (".$sql.$type_sql.")";
				return $return_this;
			} else {
				return $sql_result;
			}
		} else {
			//didn't work so just return empty
			if ($this->testing == 1) { 
				return "error (".$sql.$type_sql.$cutoff_sql.")";
			} else {
				return '';
			}
		}
	
	}
	
	private function _get_user_count($time_frame=0,$logged_in_last=0)
	{

		//time_frame is the number of days
		//time_frame is 0 get all users
		//logged_in_last is 1 to check the number of unique registrants logged in the last number of days
		//date_joined is unixtimestamp
		//check date_joined is not 0 to eliminate anonymous and admin user
		//last_login_time is MySQL time stamp
		$sql = "SELECT COUNT(id) FROM ".geoTables::userdata_table." WHERE `date_joined` != 0 ";
	
		if (($time_frame > 0) && ($logged_in_last == 0)) {
			//add to query to get the last 24hrs
			$cutoff_time = (geoUtil::time() - ($time_frame * 86400));
			$cutoff_sql = " AND `date_joined` > ".$cutoff_time;
		} elseif (($logged_in_last == 1) && ($time_frame > 0)) {
			//add to query to get the last 24hrs
			$cutoff_sql = " AND `last_login_time` >= Date_Add(now(), INTERVAL -".$time_frame." DAY)";
		} else	{
			//get count of all irregardless of time
			$cutoff_sql = "";
		}		
		$db = DataAccess::getInstance();
		$sql_result = $db->GetOne($sql.$cutoff_sql);
		if (is_numeric($sql_result))
		{
			if ($this->testing == 1) {
				$return_this = $sql_result." (".$sql.$type_sql.$cutoff_sql.") time is: (".date('D, d M Y H:i:s',$cutoff_time).")";
				return $return_this;
			} else {
				return $sql_result;
			}
		} else {
			//didn't work so just return empty
			if ($this->testing == 1) { 
				return $sql.$type_sql.$cutoff_sql;
			} else {
				return '';
			}
		}
	
	}
	
	private function _get_user_count_with_placement($time_frame=0,$type=0)
	{
	
		//time_frame is the number of days
		//time_frame is 0 get all users
		//get the current timestamp
		//subtract the time frame from it
		
		
		//date is unixtimestamp
		$sql = "SELECT COUNT(DISTINCT seller) FROM ".geoTables::classifieds_table." WHERE `date` != 0 ";
	
		
		
		if ($type == 1)
		{
			$type_sql .= " AND `item_type`=1 ";
		} elseif ($type == 2)
		{
			$type_sql .= " AND `item_type`=2 ";
	
		} else {
			//nothing to add...list all types
			$type_sql ="";
		}
		
		if ($time_frame > 0)
		{
			//add to query condition added in the past number of days
			$cutoff_time = (geoUtil::time() - ($time_frame * 86400));
			$cutoff_sql = " AND `date` > ".$cutoff_time;
		} elseif ($time_frame < 0) {
			//add to query condition expiring in next number of days
			$cutoff_time = (geoUtil::time() + (abs($time_frame) * 86400));
			$cutoff_sql = " AND `ends` < ".$cutoff_time;
		} else	{
			//get count of all irregardless of time
			$cutoff_sql = "";
		}		
		$db = DataAccess::getInstance();
		$sql_result = $db->GetOne($sql.$type_sql.$cutoff_sql);
		if (is_numeric($sql_result))
		{
			if ($this->testing == 1) {
				$return_this = $sql_result." (".$sql.$type_sql.$cutoff_sql.") time is: (".date('D, d M Y H:i:s',$cutoff_time).")";
				return $return_this;
			} else {
				return $sql_result;
			}
		} else {
			//didn't work so just return empty
			if ($this->testing == 1) { 
				return $sql.$type_sql.$cutoff_sql;
			} else {
				return '';
			}
		}
	
	}	
	
	public function number_listings_all_types ()
	{
		trigger_error('DEBUG ADDON: top of number_listings_all_types');
		return $this->_get_listing_count();
	}
	
	public function number_classifieds ()
	{
		return $this->_get_listing_count(1);
	}
	
	public function number_auctions ()
	{
		return $this->_get_listing_count(2);
	}
	
	public function number_listings_all_types_last_24hrs ()
	{
		return $this->_get_listing_count(0,1);
	}	
	
	public function number_classifieds_last_24hrs ()
	{
		return $this->_get_listing_count(1,1);
	}
	
	public function number_auctions_last_24hrs ()
	{
		return $this->_get_listing_count(2,1);
	}
	
	public function number_listings_all_types_last_7days ()
	{
		return $this->_get_listing_count(0,1);
	}	
	
	public function number_classifieds_last_7days ()
	{
		return $this->_get_listing_count(1,7);
	}
	
	public function number_auctions_last_7days ()
	{
		return $this->_get_listing_count(2,7);
	}	
	
	public function number_listings_all_types_last_30days ()
	{
		return $this->_get_listing_count(0,30);
	}
	
	public function number_classifieds_last_30days ()
	{
		return $this->_get_listing_count(1,30);
	}
	
	public function number_auctions_ending_next_24hrs ()
	{
		return $this->_get_listing_count(2,-1);
	}

	public function number_auctions_ending_next_7days ()
	{
		return $this->_get_listing_count(2,-7);
	}	
	
	public function number_auctions_last_30days ()
	{
		return $this->_get_listing_count(2,-30);
	}	
	
	public function total_views ($params)
	{
		if ((!$params['listing_type']) || (!in_array($params['listing_type'],$this->accepted_listing_types)))
			$listing_type = 0;
		else
			$listing_type = $params['listing_type'];
		return $this->_get_views($listing_type,$days);
	}

	public function number_of_registrants_24hrs ()
	{
		return $this->_get_user_count(1);
	}
	
	public function number_of_registrants_7days ()
	{
		return $this->_get_user_count(7);
	}
	
	public function number_of_registrants_30days ()
	{
		return $this->_get_user_count(30);
	}
	
	public function number_of_registrants_last ($params)
	{
		// default to one day
		if ((!$params['days']) || (!is_numeric($params['days'])))
			$days = 1;
		else
			$days = $params['days'];		
		return $this->_get_user_count($days);
	}	
	
	public function number_of_logins_last ($params)
	{
		// default to one day
		if ((!$params['days']) || (!is_numeric($params['days'])))
			$days = 1;
		else
			$days = $params['days'];
		return $this->_get_user_count($days,1);
	}
	
	public function number_of_users_place_listing_in_last ($params)
	{
		// default to one day
		if ((!$params['days']) || (!is_numeric($params['days'])))
				$days = 1;
		else 
				$days = $params['days'];
		
		// default to all types
		if ((!$params['listing_type']) || (!is_numeric($params['listing_type'])))
				$type = 0;
		else 
				$type = $params['listing_type'];
		return $this->_get_user_count_with_placement($days,$type);
	}	
	
	public function number_listings_language ($params)
	{
		// default to language 1
		if ((!$params['language']) || (!is_numeric($params['language'])))
			$language = 1;
		else
			$language = $params['language'];
		
		// default to all types
		if ((!$params['listing_type']) || (!is_numeric($params['listing_type'])))
			$type = 0;
		else
			$type = $params['listing_type'];		
	
		return $this->_get_listing_count_language($type,$language);
	}	
	
	public function number_listings_category ($params)
	{
		// default to language 1
		if ((!$params['category']) || (!is_numeric($params['category'])))
			return 0;
		else
			$category = $params['category'];
	
		// default to all types
		if ((!$params['listing_type']) || (!is_numeric($params['listing_type'])))
			$type = 0;
		else
			$type = $params['listing_type'];
	
		return $this->_get_listing_count_category($type,$category);
	}

}