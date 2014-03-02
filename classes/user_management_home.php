<?php 
//user_management_home.php
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
## ##    7.2beta1-125-g6278f20
## 
##################################

class User_management_home extends geoSite
{
	var $error_found;
	var $error;

	var $debug_home = 0;
	
	var $_boxes;
	var $tableRows;
	
	//so we don't have to get these for each individual box
	var $user_id;
	var $db;
	var $index;
	var $userData;
	
		

	
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	public function menu()
	{
		
		
		if (!intval(geoSession::getInstance()->getUserID())) {
			//no user id
			
			echo 'no user id';
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		
		if(DataAccess::getInstance()->get_site_setting('my_account_home_type') == 1) {
			return $this->myAccountHome();
		} else {
			return $this->legacy_UserAccountHomePage();
		}
	}
		
	public function myAccountHome()
	{
		$view = geoView::getInstance();
		$view->boxes = $this->GetBoxes();
		
		$view->setBodyTpl('home/my_account_home.tpl','','user_management')
		->addCssFile(geoTemplate::getUrl('css','system/user_management/my_account_home.css'));
		$this->display_page();
		return true;
	}

	/**
	 * Do computations for figuring out stats/info to show
	 * 
	 * @return array $boxes array of boxes to be sent to geoView
	 *
	 */
	public function GetBoxes()
	{	
		
		$this->user_id = geoSession::getInstance()->getUserId();
		$this->db = DataAccess::getInstance();
		$this->index = $this->db->get_site_setting('classifieds_file_name');
		$this->userData = geoUser::getData($this->user_id);
		
		$this->page_id = 10209;
		$this->get_text();
		
		$this->tableRows = $this->db->get_site_setting('my_account_table_rows') ? $this->db->get_site_setting('my_account_table_rows') : 5;

		
		//re-arrange the following lines to re-order boxes on the page
		
		//NOTE: add here all boxes that could POTENTIALLY be displayed
		//each box itself is responsible for not returning display=true if it doesn't want to be shown 
		//and for shortcircuting itself for runtime considerations if there's no chance it gets displayed (e.g. auctions box on classified-only setup)
		
		//eventually (TODO), the display switch can be admin-controlled by whatever criteria we want (Usergroup/priceplan/sitewide?)
		$this->_addBox($this->NewMessagesBox());
		$this->_addBox($this->AccountBalanceBox());
		$this->_addBox($this->AuctionsBox());
		$this->_addBox($this->ClassifiedsBox());
		$this->_addBox($this->RecentlySoldBox());
		
		$addonBoxes = geoAddon::triggerDisplay('my_account_home_add_box', null, geoAddon::ARRAY_ARRAY);
		foreach($addonBoxes as $addonBox) {
			if (isset($addonBox['display'])) {
				//this addon returned one box to add, not an array of box(es).
				$this->_addBox($addonBox);
			} else {
				//assume that it is an array of boxes
				foreach ($addonBox as $anotherAddonBox) {
					//At this level, it must be a box.
					$this->_addBox($anotherAddonBox);
				}
			}
		}
				
			
		return $this->_boxes;
	}
	
	private function NewMessagesBox()
	{
		//New Messages
		$messages = array();
		$messages['title'] = $this->messages[500543];
		
		//unread messages		
		$unreadMessages['label'] = $this->messages[500544];
		$sql = "SELECT count(message_id) FROM ".geoTables::user_communications_table." WHERE `read` <> '1' AND `message_to` = ".$this->user_id;
		$unreadCount = $this->db->GetOne($sql);
		if(!$unreadCount) {
			$unreadCount = 0;
		}
		if($unreadCount > 0) {
			$unreadMessages['link'] = $this->index . '?a=4&amp;b=8';
		}
		$unreadMessages['data'] = $unreadCount;
		
		//messages sent within last X
		
		$lastWeek['label'] = $this->messages[500545];
		$sql = "SELECT count(message_id) FROM ".geoTables::user_communications_table." WHERE `date_sent` > ".(geoUtil::time()-604800)." AND `message_to` = ".$this->user_id;
		$newMessages = $this->db->GetOne($sql);
		if(!$newMessages) {
			$newMessages = 0;
		}
		if($newMessages > 0) {
			$lastWeek['link'] = $this->index . '?a=4&amp;b=8';
		}
		$lastWeek['data'] = $newMessages;

		if($this->messages[500544]) $messages['rows'][] = $unreadMessages;
		if($this->messages[500545]) $messages['rows'][] = $lastWeek;
		$messages['display'] = ($this->db->get_site_setting('my_account_show_new_messages')) ? true : false;
		return $messages;
	}
	
	private function AccountBalanceBox()
	{
		//Account Balance

		$gateway = geoPaymentGateway::getPaymentGateway('account_balance');
		if(!$gateway) {
			//account balance gateway not installed -- nothing to do here
			return array('display' => false);
		}
		if (!$gateway->getEnabled() || (!$gateway->get('allow_positive') && !$gateway->get('allow_negative'))) {
			//account balance not enabled -- nothing to do here
			return array('display' => false);
		}
		$freeze = $this->userData['balance_freeze'];
		$user_balance = $this->userData['account_balance'];
		
		$balance = array();
		$balance['title'] = $this->messages[500546];
		
		$currBal['label'] = $this->messages[500547].geoString::displayPrice($user_balance);
		
		
		if($gateway->canAddToBalance($this->user_id)) { 
			//"add to balance" link
			//only show if user is actually able to add to his balance
			$currBal['link'] = $this->index . '?a=cart&amp;action=new&amp;main_type=account_balance';
			$currBal['data'] = $this->messages[500548];
		}
		
		if($this->messages[500547]) $balance['rows'][] = $currBal;
		
		//if balance is frozen, show why
		
		if($freeze > 0) {
			$frzBal['label'] = $this->messages[500549];
			if($freeze == 1) {
				//frozen until negative balance paid off
				$frzBal['data'] = $this->messages[500550]; 
			} elseif($freeze == 2) {
				//can only add to balance
				$frzBal['data'] = $this->messages[500551];
			} else {
				//complete freeze
				$frzBal['data'] = $this->messages[500552];
			}
			$frzBal['data'] .= $this->messages[500553];
			if($this->messages[500549]) $balance['rows'][] = $frzBal;
		}
		$balance['display'] = ($this->db->get_site_setting('my_account_show_account_balance')) ? true : false;
		return $balance;
	}
	
	private function AuctionsBox()
	{
		if(!geoMaster::is('auctions')) {
			//auctions are not turned on -- don't display the Auctions box
			return array('display' => false);
		}
		$auctionStats = array();
		$auctionStats['title'] = $this->messages[500554];
		
		//lifetime total auctions
		$sql = "select count(id) from ".geoTables::classifieds_table." where seller = ? and item_type = 2";
		$mainTotal = $this->db->GetOne($sql, array($this->user_id));
		$sql = "select count(id) from ".geoTables::classifieds_expired_table." where seller = ? and item_type = 2";
		$expiredTotal = $this->db->GetOne($sql, array($this->user_id));
		$totalAuctions = $mainTotal + $expiredTotal;
		$lifetimeTotal['label'] = $this->messages[500566].$totalAuctions;
		if($this->messages[500566]) $auctionStats['rows'][] = $lifetimeTotal;
		
		
		
		//get user's bids within last 30 days
		$sql = "select distinct(auction_id) as a_id from `geodesic_auctions_bids` where `bidder` = ".$this->user_id." AND `time_of_bid` >= ".(geoUtil::time()-60*60*24*30)." and buy_now_bid = 0 ORDER BY `time_of_bid` desc";
		$result = $this->db->Execute($sql);
		$recentCount = $result->RecordCount();
		
		if($recentCount == 0) {
			$recentBids['label'] = $this->messages[500555].'0';
		} else {
			
			while($line = $result->FetchRow()){
				$recents[] = $line['a_id'];
			}
	
			$recentBids['table'] = $this->_getMiniTable($recents);
			$recentBids['label'] = $this->messages[500555].intval(count($recentBids['table'])).$this->messages[500556].$recentCount;
		}
		if($this->messages[500555]) $auctionStats['rows'][] = $recentBids;

		
		//get all live, standard auctions ending in the next day that user has bid on,
		//that are not buy now with price_applies=item
		$sql = "SELECT distinct(class.id) as a_id FROM `geodesic_classifieds` as class, `geodesic_auctions_bids` as bids WHERE 
				class.id = bids.auction_id AND class.item_type = 2 AND bids.bidder = ".$this->user_id." AND class.live = 1 
				AND (class.auction_type = 1 OR class.auction_type = 3) AND class.price_applies='lot' AND class.ends > ".(geoUtil::time()-60*60*24);
		$result = $this->db->Execute($sql);
		
		if(!$result) {
			trigger_error('ERROR SQL: query error: sql: '.$sql.'<br />Error: '.$this->db->ErrorMsg());
		} 
		$bidded = array();
		while($auction = $result->FetchRow()) {
			 $bidded[] = $auction['a_id'];
		}
		
				
		foreach($bidded as $idToCheck) {
			//standard auctions
			$sql = "SELECT b.`auction_id`, b.`bidder` FROM ".geoTables::bid_table." as b, ".geoTables::classifieds_table." as c 
			WHERE b.auction_id = c.id AND b.`auction_id` = ".$idToCheck." AND c.auction_type = 1 ORDER BY 
					b.`bid` DESC, b.`time_of_bid` ASC LIMIT 1";
			$line = $this->db->GetRow($sql);
			if($line['bidder'] == $this->user_id) {
				$win[] = $line['auction_id'];
			} elseif($line) {
				//there is a winning bidder, but it is not this bidder
				$lose[] = $line['auction_id'];
			}
			
			//reverse auctions
			$sql = "SELECT b.`auction_id`, b.`bidder` FROM ".geoTables::bid_table." as b, ".geoTables::classifieds_table." as c
			WHERE b.auction_id = c.id AND b.`auction_id` = ".$idToCheck." AND c.auction_type = 3 ORDER BY
			b.`bid` ASC, b.`time_of_bid` ASC LIMIT 1";
			$line = $this->db->GetRow($sql);
			if($line['bidder'] == $this->user_id) {
				$win[] = $line['auction_id'];
			} elseif($line) {
				//there is a winning bidder, but it is not this bidder
				$lose[] = $line['auction_id'];
			}
		}
		
		$winCount = count($win);
		$loseCount = count($lose);
	
		$winning = $losing = array();
		//auctions ending soon that user is winning
		if($winCount == 0) {
			$winning['label'] = $this->messages[500557].'0';	
		} else {
			$winning['table'] = $this->_getMiniTable($win);
			$winning['label'] = $this->messages[500557].intval(count($winning['table'])).$this->messages[500556].$winCount;
		}
		if($this->messages[500557]) $auctionStats['rows'][] = $winning;
		
			
			
		//auctions ending soon that user has bid on but is not winning
		if($loseCount == 0) {
			$losing['label'] = $this->messages[500558].'0';
		} else {
			$losing['table'] = $this->_getMiniTable($lose);
			$losing['label'] = $this->messages[500558].intval(count($losing['table'])).$this->messages[500556].$loseCount;
		}
		if($this->messages[500558]) $auctionStats['rows'][] = $losing;
		
		
		//open feedback
		$sql = "select auction_id, rated_user_id from ".geoTables::auctions_feedbacks_table." where rater_user_id=? AND done=0 ORDER BY auction_id DESC";
		// LIMIT $this->tableRows: don't use that here since there can be orphaned feedbacks (i.e. listings never given feedback that have since been removed from the archive (most common on older sites))
		$result = $this->db->Execute($sql, array($this->user_id));
		while ($line = $result->FetchRow()) {
			$openFeedbacks[] = array('id' => $line['auction_id'], 'rated' =>$line['rated_user_id']);
		}
		
		foreach($openFeedbacks as $open) {
			$listing = geoListing::getListing($open['id'], true, true);
			
			if(!$listing) {
				//listing has probably been manually removed from the system
				//skip it and go on
				continue;
			}
			
			$title = geoString::fromDB($listing->title);
			$id = $listing->id;
			
			$seller = (($listing->seller == $this->user_id) ? 1 : 0);
			$sellText = ($seller) ? $this->messages[500560] : $this->messages[500561];
			
			$expired = (($listing->isExpired()) ? 1 : 0);
			$link = $this->index . (($expired) ? "?a=4&amp;b=2&amp;c=" : "?a=2&amp;b=") . $id;
			
			if($listing->item_type == 2 && $listing->auction_type == 2 && $seller) {
				//this is the seller of a dutch auction
				//need to specify which bidder is being rated
				$rated = '&amp;f='.$open['rated'];
			} else {
				$rated = '';
			}
			
			//don't use listing_id to key this array, because there can be multiple entries for a dutch auction
			$feedback['table'][] = array('title' => $title.' '.$sellText,
											'link' => $link,
											'link2' => $this->index . "?a=4&amp;b=22&amp;c=2&amp;d=" . $id . $rated,
											'link2text' => $this->messages[500562]);
		}
		
		if(count($feedback['table']) > $this->tableRows) {
			//remove extra listings
			array_splice($feedback['table'], $this->tableRows);
		}
		
		if(count($feedback['table']) > 0) {
			$sql = "select auction_id from ".geoTables::auctions_feedbacks_table." where rater_user_id=? AND done=0";
			$result = $this->db->Execute($sql, array($this->user_id));
			$feedbackCount = 0;
			while($line = $result->FetchRow()) {
				if(is_object(geoListing::getListing($line['auction_id'], false, true))) {
					$feedbackCount++;
				}
			}

			//$feedbackCount
			$feedback['label'] = $this->messages[500559].intval(count($feedback['table'])).$this->messages[500556].$feedbackCount;

			
		} else {
			$feedback['label'] = $this->messages[500559].'0';
		}
		if($this->messages[500559]) $auctionStats['rows'][] = $feedback;
		$auctionStats['display'] = ($this->db->get_site_setting('my_account_show_auctions')) ? true : false;
		$auctionStats['full'] = true;//full box, make it twice the width
		return $auctionStats;
		
	}
	
	private function ClassifiedsBox()
	{
		if(!geoMaster::is('classifieds')) {
			//classifieds are not turned on -- don't display the Classifieds box
			return array('display' => false);
		}
		$classStats = array();
		$classStats['title'] = $this->messages[500567];
		
		//lifetime total auctions
		$sql = "select count(id) from ".geoTables::classifieds_table." where seller = ? and item_type = 1";
		$mainTotal = $this->db->GetOne($sql, array($this->user_id));
		$sql = "select count(id) from ".geoTables::classifieds_table." where seller = ? and item_type = 1 and sold_displayed = 1";
		$mainSold = $this->db->GetOne($sql, array($this->user_id));
		$sql = "select count(id) from ".geoTables::classifieds_expired_table." where seller = ? and item_type = 1";
		$expiredTotal = $this->db->GetOne($sql, array($this->user_id));
		$sql = "select count(id) from ".geoTables::classifieds_expired_table." where seller = ? and item_type = 1 and sold_displayed = 1";
		$expiredSold = $this->db->GetOne($sql, array($this->user_id));
		$totalClass = $mainTotal + $expiredTotal;
		$totalSold = $mainSold + $expiredSold;
		
		if($this->messages[500568]) $classStats['rows'][] = array('label' => $this->messages[500568].$totalClass); 
		if($this->messages[500569] && geoPC::is_ent()) $classStats['rows'][] = array('label' => $this->messages[500569].$totalSold);
		if($this->messages[500570] && geoPC::is_ent()) $classStats['rows'][] = array('label' => $this->messages[500570].(round(($totalSold/$totalClass*100),2))."%");
		
		$classStats['display'] = ($this->db->get_site_setting('my_account_show_classifieds')) ? true : false;
		$classStats['full'] = true;//full box, make it twice the width
		return $classStats;
	}
	
	
	private function RecentlySoldBox()
	{
		$sold = array();
		$sold['title'] = $this->messages[500563];
		
		
		 //this is a number of days -- multiply by 86400 before using
		$recently_sold_time = $this->db->get_site_setting('my_account_recently_sold_time') ? $this->db->get_site_setting('my_account_recently_sold_time') : 30;
		//timestamp to determine how far back to query
		$recent = geoUtil::time() - ($recently_sold_time * 60 * 60 * 24);
		
		$usingAuctions = geoMaster::is('auctions');
		$usingClassifieds = geoMaster::is('classifieds');
		
		if($usingClassifieds) {
			//most recently closed classifieds marked as sold
			$sql = "SELECT `id`, `title`, `ends` FROM ".geoTables::classifieds_table." WHERE `seller` = ?
					 AND `sold_displayed` = 1 AND `item_type` = 1 AND `ends` > ? ORDER BY `ends` DESC LIMIT ".$this->tableRows;
			$result = $this->db->Execute($sql, array($this->user_id, $recent));
			while($line = $result->FetchRow()) {
				$class['table'][$line['id']] = array('title' => geoString::fromDB($line['title']),
											'link' => $this->index . "?a=2&amp;b=".$line['id']);
			}
			if(count($class['table'])) {
				$sql = "SELECT count(id) FROM ".geoTables::classifieds_table." WHERE `seller` = ?
					 AND `sold_displayed` = 1 AND `item_type` = 1 AND `ends` > ?";
				$classCount = $this->db->GetOne($sql, array($this->user_id, $recent));            
                
				$class['label'] = $this->messages[500564].intval(count($class['table'])).$this->messages[500556].$classCount;
			} else {
			    $sql = "SELECT count(*) FROM petsplease_merchant_order WHERE `seller` = ?
                     AND paypal_status='paid' AND `date` > ?";
                $classCount = $this->db->GetOne($sql, array($this->user_id, $recent));
                
                if ($classCount > 0) {
                    $class['label'] = $this->messages[500564].$classCount;
                }
                else {
                    $class['label'] = $this->messages[500564].'0';
                }				
			}
			if($this->messages[500564]) $sold['rows'][] = $class;
			
		}
		
		if($usingAuctions) {
			//most recently closed auctions with a bid above reserve
			$sql = "SELECT class.id, class.title, class.ends 
					 FROM ".geoTables::classifieds_table." as class, ".geoTables::bid_table." as bids 
			 		 WHERE bids.auction_id = class.id AND ((bids.bid >= class.reserve_price AND class.auction_type!=3) OR (bids.bid <= class.reserve_price AND class.auction_type=3)) 
			 		 AND class.seller = ? AND class.live = 0 AND class.item_type = 2 AND class.ends > ? 
			 		 GROUP BY class.id ORDER BY class.ends DESC LIMIT ".$this->tableRows;
			$result = $this->db->Execute($sql, array($this->user_id, $recent));
			while($line = $result->FetchRow()) {
				$auc['table'][$line['id']] = array('title' => geoString::fromDB($line['title']),
											'link' => $this->index . "?a=2&amp;b=".$line['id']);
			}
			if(count($auc['table'])) {
				$sql = "SELECT class.id 
					 FROM ".geoTables::classifieds_table." as class, ".geoTables::bid_table." as bids 
			 		 WHERE bids.auction_id = class.id AND ((bids.bid >= class.reserve_price AND class.auction_type!=3) OR (bids.bid <= class.reserve_price AND class.auction_type=3)) 
			 		 AND class.seller = ? AND class.live = 0 AND class.item_type = 2 AND class.ends > ?
			 		 GROUP BY class.id";
				$result = $this->db->Execute($sql, array($this->user_id, $recent));
				$aucCount = $result->RecordCount();
				$auc['label'] = $this->messages[500565].intval(count($auc['table'])).$this->messages[500556].$aucCount;
				
			} else {
				$auc['label'] = $this->messages[500565].'0';
			}
			if($this->messages[500565]) $sold['rows'][] = $auc;
			
		}		

		$sold['display'] = ($this->db->get_site_setting('my_account_show_recently_sold') && ($usingAuctions || $usingClassifieds)) ? true : false;
		
		return $sold;
	}
	
	/**
	 * gets the data for a preview table of the given IDs
	 *
	 * @param array $ids
	 * @param int $numRows
	 */
	private function _getMiniTable($ids)
	{
		if(count($ids) <= 0) {
			return array();
		}
		
		$numRows = ($this->tableRows) ? $this->tableRows : 5;
		$in_str = implode(', ',$ids);
		
		$sql = "SELECT `id`, `title` FROM `geodesic_classifieds` WHERE `id` IN (".$in_str.") LIMIT ".$numRows;
		$result = $this->db->Execute($sql);
		if(!$result) {
			trigger_error('ERROR SQL: query error: sql: '.$sql.'<br />Error: '.$this->db->ErrorMsg());
			return array();
		}
		
		$table = array();
		while($line = $result->FetchRow()) {
			$table[$line['id']] = array('title' => geoString::fromDB($line['title']),
										'link' => $this->index . "?a=2&amp;b=".$line['id']);
		}
		return $table;
		
	}

	protected function _addBox($box)
	{
		if($box['display'] == true) {
			$this->_boxes[] = $box;
		} else {
			//no data to add
		}
	}
	
	//==================================================================================================
	//***************************************LEGACY CODE FOLLOWS****************************************
	//==================================================================================================
	
	public function legacy_UserAccountHomePage()
	{
		$view = geoView::getInstance();
		
		$this->page_id = 43;
		$this->get_text();
		
		$section_title = $this->messages[620];
		$page_title ="<span class=\"user_management_page_title\">".$this->messages[418]."</span>";
		$description ="<span class=\"page_description\">".$this->messages[419]."</span>";
		$active_ads = $this->messages[420];
		$expired_ads = $this->messages[421];
		$current_info = $this->messages[422];
		$place_ad = $this->messages[423];
		$ad_filters = $this->messages[424];
		$favourites = $this->messages[427];
		$comm = $this->messages[425];
		$comm_config = $this->messages[426];
		$signs = geoAddon::getUtil('signs_flyers');
		if ($signs) {
			$signs_flyers = $this->messages[1182];
		} else {
			$signs_flyers = "";
		}
		if (geoPC::is_ent()){$sql = "select group_id from ".$this->db->geoTables->user_groups_price_plans_table." where id = ".geoSession::getInstance()->getUserID();
		$result = $this->db->Execute($sql);
		$groupResult = $result->FetchRow();
		if (!$result){
			//error!
			trigger_error('ERROR SQL: Error running query.  SQL: '.$sql.' -- Error Msg: '.$this->db->ErrorMsg());
			return false;
		}

		$sql = "select restrictions_bitmask from ".$this->db->geoTables->classified_groups_table." where group_id = ".$groupResult['group_id'];
		$result = $this->db->Execute($sql);
		if (!$result){
			//error!  Could not get bitmask from database!
			trigger_error('ERROR SQL: Error running query for bitmask, falling back to have all links showing.  SQL: '.$sql.' -- Error Msg: '.$this->db->ErrorMsg());
			$bitmask = 1+2+4+8+16+32; //fall back to have all links turned on, if query fails.
		} else {
			$result = $result->FetchRow();
			$bitmask = (geoPC::is_ent())? $result['restrictions_bitmask'] : 1+2+4+8+16+32;
		}		
		$place_ad = (($bitmask & 1) == 1) ? $place_ad : '';
		$active_ads = (($bitmask & 1) == 1) ? $active_ads : '';
		$expired_ads = (($bitmask & 1) == 1) ? $expired_ads : '';
		$comm = (($bitmask & 2) == 2) ? $comm : '';
		$comm_config = (($bitmask & 2) == 2) ? $comm_config : '';
		$favourites = (($bitmask & 4) == 4) ? $favourites : '';
		$ad_filters = (($bitmask & 8) == 8) ? $ad_filters : '';}
		
		$view->section_title = $section_title;
		$view->page_title = $page_title;
		$view->display_username = geoUser::userName(geoSession::getInstance()->getUserId());
		$view->description = $description;
		$view->active_ads = $active_ads;
		$view->expired_ads = $expired_ads;
		$view->current_info = $current_info;
		$view->place_ad = $place_ad;
		$view->ad_filters = $ad_filters;
		$view->favourites = $favourites;
		$view->communications = $comm;
		$view->communications_config = $comm_config;
		$view->signs_and_flyers = $signs_flyers;
		
		if(geoMaster::is('auctions'))
		{
			$feedback = $this->messages[200017];
			$current_bids = $this->messages[200018];

			if ($this->db->get_site_setting('black_list_of_buyers') && geoPC::is_ent()) {
				$blacklist_buyers =$this->messages[200019];
			} else {
				$blacklist_buyers = "";
			}
			if ($this->db->get_site_setting('invited_list_of_buyers') && geoPC::is_ent()) {
				$invited_buyers =urldecode($this->messages[200020]);
			} else {
				$invited_buyers = "";
			}

			if (geoPC::is_ent()){$blacklist_buyers = (($bitmask & 16) == 16) ? $blacklist_buyers : '';
			$invited_buyers = (($bitmask & 16) == 16) ? $invited_buyers : '';
			$feedback = (($bitmask & 32) == 32) ? $feedback : '';}
				
			// Auction specific ones
			$view->feedback = $feedback;
			$view->current_bids = $current_bids;
			$view->blacklist_buyers = $blacklist_buyers;
			$view->invited_buyers = $invited_buyers;
		} else {
			$view->feedback = '';
			$view->current_bids = '';
			$view->blacklist_buyers = '';
			$view->invited_buyers = '';
		}
		//Make sure user group is set for payment gateways before calling
		$sql = "SELECT `group_id` FROM ".geoTables::user_groups_price_plans_table." WHERE `id` = ".intval(geoSession::getInstance()->getUserID());
		$user_group_result = $this->db->GetRow($sql);
		
		geoPaymentGateway::setGroup($user_group_result['group_id']);
		
		//first, set default values so that it doesn't break on old system.
		$view->add_money_with_balance = '';
		$view->balance_transactions = '';
		$view->paid_invoices = '';
		$view->add_money = '';
		$view->unpaid_invoices = '';
		$view->renew_extend_subscription = '';
		//allow different payment gateways to display things on the user account home page
		geoPaymentGateway::callUpdate('User_management_home_body');
		//also allow items to add stuff if they need
		geoOrderItem::callUpdate('User_management_home_body');
		
		//Use new file based tempaltes
		$view->setLanguage($this->db->getLanguage());
		$page_id = '43_home';
		$tpl_file = $view->getTemplateAttachment($page_id);
		//load modules so that stuff in sub-page is parsed
		$view->setPage($this);
		$view->loadModules($page_id);
		
		$view->setBodyTpl($tpl_file);
		
		// Display the page
		$this->display_page();
		return true;
		
	} //end of function user_management_home_body

	/**
	 * NOTE: This function is part of the legacy code in the above function for
	 * old-style templates. When we don't need to support db-based tamplates anymore,
	 * this can be done away with.
	 */
	public function user_management_menu ()
	{
		if (geoSession::getInstance()->getUserID())
		{
			$this->body .="<table cellpadding=\"0\" cellspacing=\"1\" border=\"0\" align=\"center\" width=\"100%\" class=\"user_management_menu_links\">\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=1\" class=\"user_links\">".urldecode($this->messages[420])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=2\" class=\"user_links\">".urldecode($this->messages[421])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=3\" class=\"user_links\">".urldecode($this->messages[422])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"";
			if ($this->db->get_site_setting('use_ssl_in_sell_process'))
				$this->body .=trim($this->db->get_site_setting('classifieds_ssl_url'));
			else
				$this->body .= trim($this->db->get_site_setting('classifieds_file_name'));
			$this->body .= "?a=1\" class=\"user_links\">".urldecode($this->messages[423])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=9\" class=\"user_links\">".urldecode($this->messages[424])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td>\n\t\t<a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=10\" class=\"user_links\">".urldecode($this->messages[427])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=8\" class=\"user_links\">".urldecode($this->messages[425])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=7\" class=\"user_links\">".urldecode($this->messages[426])."</a>\n\t\t</td>\n\t</tr>\n\t";
			$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=12\" class=\"user_links\">".urldecode($this->messages[1182])."</a>\n\t\t</td>\n\t</tr>\n\t";

			$sql = "select * from ".$this->user_groups_price_plans_table." where id = ".geoSession::getInstance()->getUserID();
			$user_group_result = $this->db->Execute($sql);
			if ($this->debug_home) echo $sql."<br />";
			if (!$user_group_result)
			{
				if ($this->debug_home) echo $sql."<br />";
				return false;
			}
			elseif ($user_group_result->RecordCount() == 1)
			{
				$show_user_stuff = $user_group_result->FetchNextObject();
				$sql = "select * from ".$this->price_plans_table." where price_plan_id = ".$show_user_stuff->PRICE_PLAN_ID;
				$price_plan_result = $this->db->Execute($sql);
				if ($this->debug_home) echo $sql."<br />";
				if (!$price_plan_result)
				{
					if ($this->debug_home) echo $sql."<br />";
					return false;
				}
				elseif ($price_plan_result->RecordCount() == 1)
				{
					$base_price_plan = $price_plan_result->FetchNextObject();
					if ($base_price_plan->TYPE_OF_BILLING == 2)
					{
						$this->body .= "<tr>\n\t<td  class=\"user_links\">\n\t";
						$this->body = "<a href=\"";
						if ($this->db->get_site_setting('use_ssl_in_sell_process'))
							$this->body .= trim($this->db->get_site_setting('classifieds_ssl_url'));
						else
							$this->body .= trim($this->db->get_site_setting('classifieds_file_name'));
						$this->body .= "?a=24\" class=\"user_links\">".urldecode($this->messages[1695])."</a>";
						$this->body .= "</td>\n</tr>\n";
						
					}
				}
			}
			if ($this->db->get_site_setting('use_account_balance'))
			{
				$user_data = $this->get_user_data(geoSession::getInstance()->getUserID());

				// Show balances
				if ($this->db->get_site_setting('positive_balances_only'))
				{
					$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"";
					if ($this->db->get_site_setting('use_ssl_in_sell_process'))
						$this->body .= trim($this->db->get_site_setting('classifieds_ssl_url'));
					else {
					  $display_amount = $this->show_money($user_data->ACCOUNT_BALANCE,$this->db->get_site_setting('precurrency'),$this->db->get_site_setting('postcurrency'));
	          $this->body .= trim($this->db->get_site_setting('classifieds_file_name'));
  					$this->body .= "?a=29\" class=\"user_links\">".urldecode($this->messages[2548])."</a> ".
  					  urldecode($this->messages[2549])." ".$display_amount."</td>\n</tr>\n";
					}

					$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"".$this->db->get_site_setting('classifieds_file_name')."?a=4&amp;b=18\" class=\"user_links\">".urldecode($this->messages[103304])."</a></td></tr>";

					//$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"";
					//if\" ($this->db->get_site_setting('use_ssl_in_sell_process'))
					//	$this->body .= trim($this->db->get_site_setting('auctions_ssl_url'));
					//else
					//	$this->body .= trim($this->db->get_site_setting('auctions_file_name'));
					//$this->body .= "?a=1033 class=\"user_links\">".urldecode($this->messages[103122])."</a></td>\n</tr>\n";
				}
				else
				{
					/*
					 * TODO: reimplement this under new system
					 * 
					 * 
					 
					//show links to unpaid invoices and paid invoices
					$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"";
					if ($this->db->get_site_setting('use_ssl_in_sell_process'))
						$this->body .=trim($this->db->get_site_setting('classifieds_ssl_url'));
					else
						$this->body .= trim($this->db->get_site_setting('classifieds_file_name'));
					$this->body .="?a=4&amp;b=15\" class=\"user_links\">".urldecode($this->messages[3171])."</a></td>\n</tr>\n";

					$this->body .="<tr class=\"user_links\">\n\t\t<td><a href=\"";
					if ($this->db->get_site_setting('use_ssl_in_sell_process'))
						$this->body .=trim($this->db->get_site_setting('classifieds_ssl_url'));
					else
						$this->body .= trim($this->db->get_site_setting('classifieds_file_name'));
					$this->body .="?a=4&amp;b=16\" class=\"user_links\">".urldecode($this->messages[3172])."</a></td>\n</tr>\n";
					*/
				}
			}

			$this->body .="</table>\n\t";
		}
		else
		{
			//no user id
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
	} //end of function user_management_menu
	
	
}

