<?php
//home.php
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
## ##    7.0.0-71-g719f0c5
## 
##################################

class geoAdminHome
{
	public function display_quick_find ()
	{
		//so simple, just need to set the template and that's it, nothing dynamic
		//about it
		$tpl = new geoTemplate(geoTemplate::ADMIN);
		echo $tpl->fetch('quick_find.tpl');
		geoView::getInstance()->setRendered(true);
	}
	
	
	public function display_home()
	{
		$admin = geoAdmin::getInstance();
		$db = DataAccess::getInstance();
		
		//$admin->v()->hide_side_menu = 1; //make it not load side menu
		$admin->v()->hide_title = false; //make true to not show title
		
		$admin->v()->hide_notifications = false; // make true to not show notifications
		//get notifications as HTML so we can move them around on the page
		//$admin->v()->notify = Notifications::getNotificationsAsHTML();
		
		//find software versions so we know which stats to show
		$isAuctions = geoMaster::is('auctions');
		$isClassifieds = geoMaster::is('classifieds');
		$isLeased = geoPC::is_leased();
		
		$settings = array();
		$settings['product'] = array(
									'auctions' => $isAuctions,
									'classifieds' => $isClassifieds,
									'leased' => $isLeased,
									);
		
		
		//get stats
		$stats['users'] = $this->getUsersStats();
		
		if ($isAuctions) {
			$stats['auctions'] = $this->getAuctionStats();
		}
		if ($isClassifieds) {
			$stats['classifieds'] = $this->getClassifiedStats();
		}
		$stats['groupsplans'] = $this->getGroupsPlansStats();
		$stats['orders'] = $this->getOrderStats();
		$stats['other'] = $this->getOtherStats();
		
		//To "test" having expired support/downloads, swap this out for value of either one below:
		//geoUtil::time()-1000;//
		
		$stats['supportExpire'] = geoPC::getSupportExpire();
		if (!$isLeased) {
			$stats['downloadExpire'] = geoPC::getDownloadExpire();
		}
		
		$stats['packageId'] = geoPC::getPackageId();
		
		$currentTime = geoUtil::time();
		//die ("stats:<pre>".print_r($stats,1));
		
		if ($stats['supportExpire']!=='never' && $stats['supportExpire'] > $currentTime) {
			$stats['supportLeft'] = $this->_timeLeft($stats['supportExpire']);
		}
		
		if (!$isLeased && $stats['downloadExpire']!=='never' && $stats['downloadExpire'] > $currentTime) {
			$stats['downloadLeft'] = $this->_timeLeft($stats['downloadExpire']);
		}
		
		if ($isLeased) {
			$stats['licenseExpire'] = geoPC::getLicenseExpire();
			$stats['localLicenseExpire'] = geoPC::getLocalLicenseExpire();
			if ($stats['localLicenseExpire'] > $currentTime) {
				$stats['licenseLeft'] = $this->_timeLeft($stats['localLicenseExpire']);
			}
			$stats['licenseKey'] = $db->get_site_setting('license');
		}
		
		$settings['stats'] = $stats;
		
		$db = DataAccess::getInstance();
		
		$table = new geoTable();
		
		
		//Can assign things to "debug" to make them display at the bottom of
		//admin home.
		
		/*
		$settings['debug'] = $table->fetchAll($table->select()
			->from(array ('class' => geoTables::classifieds_table),
				array ('title','description'))
			//->join(array('tag' => geoTables::tags), "tag.listing_id=class.id")
			//->columns(array('tag_name'=>'name','tag_id'=>'id'), geoTables::tags)
			->where($db->quoteInto("`live`=?",1,DataAccess::TYPE_BOOL))
			->order("`ends`")
			->limit(10))->fetchRow();
		*/
		//landing page
		$settings['landingPage'] = $db->get_site_setting('adminLandingPage');
		
		$settings['adminMsgs'] = geoAdmin::m();
		
		if(geoPC::is_trial()) {
			$settings['is_trial_demo'] = true;
			$settings['demo_deletion'] = $this->_timeLeft($stats['downloadExpire'] + 86400*14);
		} 
			
		//call template and show page
		$admin->setBodyTpl('home/index.tpl')
			->v()->setBodyVar($settings);
	}
	
	public function update_home()
	{
		//change if show last page viewed, or home page, after admin login
		$landingPage = (isset($_POST['landingPage']) && $_POST['landingPage'] == 'home')? 'home': false;
		
		$db = DataAccess::getInstance();
		$db->set_site_setting('adminLandingPage',$landingPage);
		
		return true;
	}
	
	public function getUsersStats()
	{
		$db = DataAccess::getInstance();
		
		$sql = "select count(geodesic_logins.id) from geodesic_logins,geodesic_userdata where geodesic_logins.id > 1 and geodesic_logins.status = 1 and geodesic_logins.id = geodesic_userdata.id";
		$user_stats['total'] = $db->GetOne($sql);
		
		$sql = "select count(`id`) from geodesic_confirm";
		$user_stats['registrations'] = $db->GetOne($sql);
		
		$sql = "select count(geodesic_logins.id) from geodesic_logins,geodesic_userdata where geodesic_logins.id > 1 and geodesic_logins.status = 1 and geodesic_logins.id = geodesic_userdata.id and geodesic_userdata.date_joined > ".(geoUtil::time() - 86400);
		$user_stats['last1'] = $db->GetOne($sql);
		
		$sql = "select count(geodesic_logins.id) from geodesic_logins,geodesic_userdata where geodesic_logins.id > 1 and geodesic_logins.status = 1 and geodesic_logins.id = geodesic_userdata.id and geodesic_userdata.date_joined > ".(geoUtil::time() - 604800);
		$user_stats['last7'] = $db->GetOne($sql);

		$sql = "select count(geodesic_logins.id) from geodesic_logins,geodesic_userdata where geodesic_logins.id > 1 and geodesic_logins.status = 1 and geodesic_logins.id = geodesic_userdata.id and geodesic_userdata.date_joined > ".(geoUtil::time() - 2592000);
		$user_stats['last30'] = $db->GetOne($sql);

		return $user_stats;
	}
		
	public function getAuctionStats()
	{
		$db = DataAccess::getInstance();
		
		$sql_query = "select count(*) as total_ads from ".$db->geoTables->auctions_table." where live=1 and item_type=2";
		$result = $db->Execute($sql_query);
		if (!$result) {
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		}
		$show_stats = $result->FetchRow();
		$auction_stats['count'] = $show_stats['total_ads'];


		$query = "select count(distinct(geodesic_logins.id)) as total_users_with_ads from geodesic_logins,geodesic_classifieds where geodesic_logins.id = geodesic_classifieds.seller and geodesic_classifieds.live = 1 and item_type=2";
		$result = $db->Execute($query);
		if (!$result) {
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		}
		$show_stats = $result->FetchRow();
		$auction_stats['users'] = $show_stats['total_users_with_ads'];


		$query = "select sum(viewed) as total_viewed from ".$db->geoTables->auctions_table." where live=1 and item_type=2";
		$result = $db->Execute($query);
		if (!$result) {
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		}
		$show_stats = $result->FetchRow();
		$auction_stats['viewed'] = (!$show_stats['total_viewed']) ? 0 : $show_stats['total_viewed'];
		if ($db->tableColumnExists(geoTables::classifieds_table, 'customer_approved')) {
			$sql = "SELECT count(*) as count FROM ".geoTables::classifieds_table." WHERE ((live = 0 and ends > ".geoUtil::time()." and customer_approved = 1) or renewal_payment_expected != 0 or live = 2) AND (`order_item_id` = 0 OR `order_item_id` = '') AND `item_type` = 2";
			$row = $db->GetRow($sql);
			if ($row === false) {
				trigger_error("ERROR DB: ".$db->ErrorMsg());
				$row = array('count'=>'0');
			}
			$count = intval($row['count']);
		} else {
			$count = 0;
		}
		//figure out count from new system
		
		$sql = "SELECT count(oi.id) as count
		FROM ".geoTables::order_item." as oi, ".geoTables::logins_table." as u, ".geoTables::order." as o
		WHERE oi.status IN ('pending', 'pending_edit') AND oi.type='auction'
		AND oi.`parent` = 0 AND o.id = oi.`order` AND o.status = 'active' AND u.id = o.buyer";
		$row = $db->GetRow($sql);
		if ($row === false) {
			trigger_error("ERROR DB: ".$db->ErrorMsg());
			$row = array('count'=>'0');
		}
		$count += intval($row['count']);
		
		$auction_stats['unapproved'] = $count;
		
		return $auction_stats;
	}
	
	public function getClassifiedStats()
	{
		$db = DataAccess::getInstance();
		
		$sql_query = "select count(*) as total_ads from ".$db->geoTables->classifieds_table." where live=1 and item_type=1";
		$result = $db->Execute($sql_query);
		if (!$result) {
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		}
		$show_stats = $result->FetchRow();
		$ad_stats['count'] = $show_stats['total_ads'];


		$query = "select count(distinct(geodesic_logins.id)) as total_users_with_ads from geodesic_logins,geodesic_classifieds where geodesic_logins.id = geodesic_classifieds.seller and geodesic_classifieds.live = 1 and item_type=1";
		$result = $db->Execute($query);
		if (!$result) {
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		}
		$show_stats = $result->FetchRow();
		$ad_stats['users'] = $show_stats['total_users_with_ads'];


		$query = "select sum(viewed) as total_viewed from ".$db->geoTables->classifieds_table." where live=1 and item_type=1";
		$result = $db->Execute($query);
		if (!$result) {
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		}
		$show_stats = $result->FetchRow();
		$ad_stats['viewed'] = (!$show_stats['total_viewed']) ? 0 : $show_stats['total_viewed'];
		
		if ($db->tableColumnExists(geoTables::classifieds_table, 'customer_approved')) {
			$sql = "SELECT count(*) as count FROM ".geoTables::classifieds_table." WHERE ((live = 0 and ends > ".geoUtil::time()." and customer_approved = 1) or renewal_payment_expected != 0 or live = 2) AND (`order_item_id` = 0 OR `order_item_id` = '') AND `item_type` = 1";
			$row = $db->GetRow($sql);
			if ($row === false) {
				//normal for this to fail on newer installations
				trigger_error("DEBUG DB: ".$db->ErrorMsg());
				$row = array('count'=>'100');
			}
			$count = intval($row['count']);
		} else {
			$count = 0;
		}
		//figure out count from new system
		
		$sql = "SELECT count(oi.id) as count
		FROM ".geoTables::order_item." as oi, ".geoTables::logins_table." as u, ".geoTables::order." as o
		WHERE oi.status IN ('pending', 'pending_edit') AND oi.type='classified'
		AND oi.`parent` = 0 AND o.id = oi.`order` AND o.status = 'active' AND u.id = o.buyer";
		$row = $db->GetRow($sql);
		if ($row === false) {
			trigger_error("ERROR DB: ".$db->ErrorMsg());
			$row = array('count'=>'100');
		}
		$count += intval($row['count']);
		$ad_stats['unapproved'] = $count;
		
		return $ad_stats;
	}

	public function getGroupsPlansStats()
	{
		$db = DataAccess::getInstance();

		$query = "SELECT * FROM geodesic_groups";
		$group_result = $db->Execute($query);
		if (!$group_result)	{
			trigger_error("ERROR: ".$db->ErrorMsg());
			return false;
		} elseif ($group_result->RecordCount() > 0)	{
			while ($show_group = $group_result->FetchRow())	{
				$query = "select count(*) as group_total from ".$db->geoTables->user_groups_price_plans_table." where group_id = ".$show_group['group_id']." and id!=1";
				$group_count_result = $db->Execute($query);
				if (!$group_count_result) {
					trigger_error("ERROR: ".$db->ErrorMsg());
					return false;
				} elseif ($group_count_result->RecordCount() == 1) {
					$show_group_count = $group_count_result->FetchRow();
				} else {
					return false;
				}
					
				$user_groups_stats[$show_group['group_id']]['name'] = $show_group['name'];
				$user_groups_stats[$show_group['group_id']]['count'] = $show_group_count['group_total'];

			}
		}


		// Auction Price Plans
		if(geoMaster::is('auctions')) {
			$query = "SELECT * FROM ".$db->geoTables->price_plans_table." where applies_to = 2";
			$price_plan_result = $db->Execute($query);
			if (!$price_plan_result) {
				trigger_error("ERROR: ".$db->ErrorMsg());
				return false;
			} elseif ($price_plan_result->RecordCount() > 0) {
				while ($show_price_plan = $price_plan_result->FetchRow()) {
					$query = "select count(*) as price_plan_total from ".$db->geoTables->user_groups_price_plans_table." where auction_price_plan_id = ".$show_price_plan['price_plan_id']." and id != 1";
					$plan_count_result = $db->Execute($query);
					if (!$plan_count_result) {
						trigger_error("ERROR: ".$db->ErrorMsg());
						return false;
					} elseif ($plan_count_result->RecordCount() == 1) {
						$show_plan_count = $plan_count_result->FetchRow();
					}
					$price_plans_stats[$show_price_plan['price_plan_id']]['name'] = $show_price_plan['name'];
					$price_plans_stats[$show_price_plan['price_plan_id']]['count'] = $show_plan_count['price_plan_total'];	
				}
			}
		}

		// Classified Price Plans
		if(geoMaster::is('classifieds')) {
			$query = "SELECT * FROM ".$db->geoTables->price_plans_table." where applies_to = 1";
			$price_plan_result = $db->Execute($query);
			if (!$price_plan_result) {
				trigger_error("ERROR: ".$db->ErrorMsg());
				return false;
			} elseif ($price_plan_result->RecordCount() > 0) {
				while ($show_price_plan = $price_plan_result->FetchRow()) {
					$query = "select count(*) as price_plan_total from ".$db->geoTables->user_groups_price_plans_table." where price_plan_id = ".$show_price_plan['price_plan_id']." and id != 1";
					//echo $this->sql_query." is the query <bR>";
					$plan_count_result = $db->Execute($query);
					if (!$plan_count_result) {
						trigger_error("ERROR: ".$db->ErrorMsg());
						return false;
					} elseif ($plan_count_result->RecordCount() == 1) {
						$show_plan_count = $plan_count_result->FetchRow();
					}

					$price_plans_stats[$show_price_plan['price_plan_id']]['name'] = $show_price_plan['name'];
					$price_plans_stats[$show_price_plan['price_plan_id']]['count'] = $show_plan_count['price_plan_total'];

				}
			}
		}
		
		$groups_plans_stats = array('groups' => $user_groups_stats, 'plans' => $price_plans_stats);
		return $groups_plans_stats;
	}
	
	public function getOrderStats()
	{
		$db = DataAccess::getInstance();
		
		//Need this complex query to weed out orders without things in them,
		//and orders that have not yet gotten to the stage of having an invoice.
		$sql_base = "SELECT count(o.id) as count FROM `geodesic_order` AS o,`geodesic_invoice` AS i, `geodesic_order_registry` as o_r, `geodesic_logins` as u WHERE i.order = o.id AND o_r.order = o.id AND o_r.`index_key` = 'payment_type' AND u.id = o.buyer AND o.seller = 0";
		$order_stats['total'] = $db->GetOne($sql_base);
		
		//Prepare the statement, to be better optimized since the only thing changing is status.
		$stmt = $db->Prepare($sql_base . " AND o.`status` = ?");
		
		$order_stats['pending'] = $db->GetOne($stmt, array('pending'));
		$order_stats['pending_admin'] = $db->GetOne($stmt, array('pending_admin'));
		$order_stats['active'] = $db->GetOne($stmt, array('active'));
		$order_stats['suspended'] = $db->GetOne($stmt, array('suspended'));
		$order_stats['canceled'] = $db->GetOne($stmt, array('canceled'));
		$order_stats['fraud'] = $db->GetOne($stmt, array('fraud'));
		$order_stats['incomplete'] = $db->GetOne($stmt, array('incomplete'));
		
		
		
		//order items
		
		//find the list of item types typically shown in the admin
		$types = geoOrderItem::getOrderItemTypes();
		$typesUse = array ();
		foreach ($types as $type => $typeInfo) {
			if (method_exists($typeInfo['class_name'],'adminDetails')) {
				$typesUse[] = $type;
			}
		}
		
		$sql = "SELECT count(oi.id) FROM ".geoTables::order_item." as oi, ".geoTables::logins_table." as u, ".geoTables::order." as o
		WHERE oi.`type` IN ('".implode("', '",$typesUse)."') AND oi.`parent` = 0 AND o.id = oi.`order` AND o.status = 'active' AND u.id = o.buyer";
		$order_stats['total_items'] = $db->GetOne($sql); 
		$sql .= " AND oi.`status` = 'pending'";
		$order_stats['waiting_items'] = $db->GetOne($sql);
		return $order_stats;
	}
	
	public function getOtherStats()
	{
		$db = DataAccess::getInstance();
		
		$sql = "select count(name) from `geodesic_addons`";
		$other_stats['addonsInstalled'] = $db->GetOne($sql);
		
		$sql = "select count(name) from `geodesic_addons` where `enabled` = 1";
		$other_stats['addonsEnabled'] = $db->GetOne($sql);
		
		$sql = "select count(language_id) from `geodesic_pages_languages`";
		$other_stats['languagesInstalled'] = $db->GetOne($sql);
		
		$sql = "select count(language_id) from `geodesic_pages_languages` where `active` = 1";
		$other_stats['languagesEnabled'] = $db->GetOne($sql);
		
		return $other_stats;
		
	}
	
	private function _timeLeft ($exp)
	{
		$exp = (int)$exp;
		$left = $exp - geoUtil::time();
		$left = floor($left / (60*60*24));
		
		//convert to rough month/year
		if ($left > 365) {
			return round($left/365, 1).' Years';
		} else if ($left > 30) {
			return round($left/30,1).' Months';
		}
		
		//down to number of days
		return $left.' Days';
	}
}
