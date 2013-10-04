<?php 
//user_management_list_bids.php
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
## ##    6.0.7-2-gc953682
## 
##################################

class Auction_list_bids extends geoSite {

	var $auction_id;
	var $auction_user_id;
	var $feedback_messages;
	var $user_data;

	// Debug variables
	var $filename = "user_management_list_bids_auctions.php";
	var $function_name;
	
	var $debug_bids = 0;
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function Auction_list_bids($db,$language_id,$auction_user_id,$production_configuration=0)
	{
		parent::__construct();
		$this->auction_user_id = $auction_user_id;
		$this->user_data = $this->get_user_data($this->auction_user_id);
	} //end of function Auction_feedback

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function list_auctions_with_your_bid()
	{
		if (!$this->auction_user_id) {
			return false;
		}
		$db = DataAccess::getInstance();
		$this->page_id = 10175;
		$msgs = $db->get_text(true, $this->page_id);
		$tpl = new geoTemplate('system', 'user_management');
		
		$this->sql_query = "select distinct(auction_id) from ".$this->bid_table." where bidder = ".$this->auction_user_id." order by time_of_bid desc";
		$bid_result = $db->Execute($this->sql_query);
		if (!$bid_result) {
			$this->site_error($db->ErrorMsg());
			return false;
		} elseif ($bid_result->RecordCount() > 0) {
			$tpl->assign('showAuctions', true);
			
			$auctions = array();
			for ($i = 0; $show_list = $bid_result->FetchNextObject(); $i++)
			{
				$auction_data = $this->get_classified_data($show_list->AUCTION_ID);
				if ($auction_data)
				{
					$auctions[$i]['type'] = $auction_data->AUCTION_TYPE;
					$auctions[$i]['title'] = $auction_data->TITLE;
					$auctions[$i]['link'] = $this->configuration_data['classifieds_file_name']."?a=2&amp;b=".$show_list->AUCTION_ID;
					if ($auction_data->LIVE == 0) {
						$auctions[$i]['expired'] = true;
					}
					$auctions[$i]['ends'] = date(trim($this->configuration_data['entry_date_configuration']),$auction_data->ENDS);
					if ($auction_data->AUCTION_TYPE == 1 || $auction_data->AUCTION_TYPE == 3) {
						//standard/reverse auction specifics
						
						$this->sql_query = "select bid,time_of_bid from ".$this->bid_table." where bidder = ".$this->auction_user_id." and auction_id = ".$show_list->AUCTION_ID." order by time_of_bid desc limit 1";
						$user_bid_result = $db->Execute($this->sql_query);
						
						if (!$user_bid_result) {
							$this->site_error($db->ErrorMsg());
							return false;
						} elseif ($user_bid_result->RecordCount() == 1) {
							$show_last_bid = $user_bid_result->FetchNextObject();
						}
							
						$display_amount = geoString::displayPrice($show_last_bid->BID,$auction_data->PRECURRENCY,$auction_data->POSTCURRENCY);
						$auctions[$i]['display_amount'] = $display_amount;
						$auctions[$i]['quantity'] = $auction_data->QUANTITY;
												
						$this->sql_query = "select maxbid,time_of_bid from ".$this->autobid_table." where bidder = ".$this->auction_user_id." and auction_id = ".$show_list->AUCTION_ID;
						$user_maxbid_result = $db->Execute($this->sql_query);
						
						if (!$user_maxbid_result) {
							$this->site_error($db->ErrorMsg());
							return false;
						} elseif ($user_maxbid_result->RecordCount() == 1) {
							$show_maxbid = $user_maxbid_result->FetchNextObject();
							$maxbid = $show_maxbid->MAXBID;
							$maxbid = geoString::displayPrice($maxbid,$auction_data->PRECURRENCY,$auction_data->POSTCURRENCY);
						} else {
							$maxbid = false;
						}
						
						
						$auctions[$i]['maxbid'] = $maxbid;
							
						$current_high_bidder = $this->get_high_bidder($db,$show_list->AUCTION_ID);
							
						if ($current_high_bidder["bidder"] == $this->userid){
							$payment_link = '';
							if (geoPC::is_ent()){
								//get any possible purchase buttons
								if ($auction_data->LIVE == 0 && ($auction_data->RESERVE_PRICE <= $auction_data->FINAL_PRICE)){
									$sb = geoSellerBuyer::getInstance();
									$vars = array (
											'listing_id' => $show_list->AUCTION_ID,
											'winning_bidder_id' => $this->auction_user_id,
											'listing_details' => $auction_data,
											'final_price' => $show_last_bid->BID
									);
									$payment_link = geoSellerBuyer::callDisplay('displayPaymentLinkCurrentBids', $vars,'<br />');
									if (strlen($payment_link) > 0){
										$payment_link = '<br />'.$payment_link;
									}
								}
							}

							$auctions[$i]['payment_link'] = (($auctions[$i]['type']==3)? $msgs[501014] : $msgs[102796]).$payment_link;
						} else {
							$auctions[$i]['payment_link'] = $msgs[102797];
						}
					} else {
						//dutch auction specifics

						$this->sql_query = "select bid,time_of_bid,quantity from ".$this->bid_table." where bidder = ".$this->auction_user_id." and auction_id = ".$show_list->AUCTION_ID;
						$user_bid_result = $db->Execute($this->sql_query);
						
						if (!$user_bid_result) {
							if ($this->debug_bids) echo $this->sql_query."<br />\n";
							$this->site_error($db->ErrorMsg());
							return false;
						} elseif ($user_bid_result->RecordCount() == 1) {
							$show_last_bid = $user_bid_result->FetchNextObject();
						}
						
						$display_amount = $this->show_money($show_last_bid->BID,$auction_data->PRECURRENCY,$auction_data->POSTCURRENCY);
						$auctions[$i]['display_amount'] = $display_amount;
						$auctions[$i]['quantity'] = $show_last_bid->QUANTITY;
						
						//check to see if winning anything
						$this->sql_query = "select * from ".$this->bid_table." where auction_id=".$show_list->AUCTION_ID." order by bid desc,time_of_bid asc";
						$dutch_bid_result = $db->Execute($this->sql_query);
						
						if (!$dutch_bid_result) {
							return false;
						} elseif ($dutch_bid_result->RecordCount() > 0) {
							$total_quantity = $auction_data->QUANTITY;
							
							$final_dutch_bid = 0;
							$quantity_winning = 0;
							$seller_report = "";
							$show_bidder = $dutch_bid_result->FetchNextObject();
							do
							{
								$quantity_bidder_receiving = 0;
								if ( $show_bidder->QUANTITY <= $total_quantity ) {
									$quantity_bidder_receiving = $show_bidder->QUANTITY;
									$total_quantity = $total_quantity - $quantity_bidder_receiving;
								} else {
									$quantity_bidder_receiving = $total_quantity;
									$total_quantity = 0;
								}
								if ($quantity_bidder_receiving) {
									if ($this->auction_user_id == $show_bidder->BIDDER) {
										$quantity_winning = $quantity_bidder_receiving;
										$bid_made = $show_bidder->BID;
										$final_dutch_bid = $show_bidder->BID;
										break;
									}
								}
								
							} while (($show_bidder = $dutch_bid_result->FetchNextObject()) && ($total_quantity != 0));


							if ($quantity_winning) {
								$auctions[$i]['quantity_winning'] = $quantity_winning." ".$msgs[102798];
							} else {
								$auctions[$i]['quantity_winning'] = $msgs[102799];
							}
							
						}
					}
				}
			}
			$tpl->assign('auctions', $auctions);
		} else {
			//there are no auction filters for this user
			$tpl->assign('showAuctions', false);
		}
		$tpl->assign('userManagementHomeLink', $this->configuration_data['classifieds_file_name']."?a=4");
		$this->body = $tpl->fetch('list_bids/auctions_with_users_bid.tpl');
		$this->display_page();
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

} // end of Auction_list_bids
