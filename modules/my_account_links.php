<?php 
//my_account_links.php	
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
## ##    6.0.7-35-g29ebda5
## 
##################################

$user_id = intval(geoSession::getInstance()->getUserID());

//TODO: Make this a setting set in admin...
//for now, change this to 1 to make the total displayed for the cart include taxes and discounts.
$fullCartTotal = false;

if (!$user_id) {
	return false;
}

$tpl_vars = array();

$cart = geoCart::getInstance();
//Init the cart, but only the cart items, don't need all the overhead of
//initializing everying in the cart.  If we are on a "cart" page, the cart
//will already be initialized, in which case the init() will know to not
//re-initilize itself.
$cart->init(true);

$bitmask = 1+2+4+8+16+32;

if (geoPC::is_ent() && isset($cart->user_data['restrictions_bitmask'])){
	$bitmask = $cart->user_data['restrictions_bitmask'];	
}
$bitmask = (int)$bitmask;

$links = array();
$url_base = $this->get_site_setting('classifieds_file_name');

$inCart = $tpl_vars['inCart'] = (isset($_GET['a']) && $_GET['a'] == 'cart')? true: false;
$tpl_vars['allFree'] = !geoMaster::is('site_fees');

if($bitmask & 1) {
	//"place a listing" turned on
	
	/*
	 * CART Setup
	 * 
	 */
	$cartNumItems = 0;
	if ($cart->order) {
		foreach($cart->order->getItem('parent') as $item) {
			//get the number of "main" order items (no parent, processOrder < 1000)
			$processOrder = $item->getProcessOrder();
			if($processOrder < 1000) {
				//anything with process order less than 1000 is considered "normal"
				$cartNumItems++;
			}
		}
	}
	//cart data/link display
	$tpl_vars['cartItemCount'] = $cartNumItems;
	$tpl_vars['cartTotal'] = ($cart->order) ? $cart->getCartTotal() : 0;
	
	//cart "action"
	
	$cartLinks = array();
	
	$cartActionIndex = $tpl_vars['cartActionIndex'] = $cart->getAction();
	$tpl_vars['cartStepIndex'] = $cart->current_step;
	
	if ($cart->isInMiddleOfSomething()) {
		//In middle of something
		//get the text that will have actions
		$vars = array('action' => '', 'step' => 'my_account_links');
		//use getType as that will work even if in "stand alone" cart.
		$itemType = $cart->item->getType();
		$currentAction = $tpl_vars['cartAction'] = geoOrderItem::callDisplay('getActionName',$vars,'',$itemType);
		//let the template know whether it is a stand-alone cart or not.
		$tpl_vars['isStandalone'] = $cart->isStandaloneCart();
	} else {
		//not adding normal item to cart, so must be on main cart page (or checking out)
		//so show all buttons
		$cartLinks = geoOrderItem::callDisplay('my_account_links_newButton',null,'array');
		foreach ($cartLinks as $a_name => $ldata) {
			if (!isset($ldata['link'])) {
				//automatically set all the links so order items don't have to bother with
				//that part, but if they do, don't set it here.
				$cartLinks[$a_name]['link'] = $url_base."?a=cart&amp;action=new&amp;main_type=$a_name";
			}
		}
	}
	
	$tpl_vars['cartLinks'] = $cartLinks;
	
	//so we don't show the cart-specific template stuff if this section turned off by bitmask
	$tpl_vars['show_cart'] = true;
	
	//active/expired listings
		
	$links['active_ads'] = array('link' => $url_base . "?a=4&amp;b=1", 'label' => $page->messages[500458], 'icon' => $page->messages[500459]);
	$links['expired_ads'] = array('link' => $url_base . "?a=4&amp;b=2", 'label' => $page->messages[500460], 'icon' => $page->messages[500461]);
}

if($bitmask & 2) {
	
	//check for unread messages
	$sql = "SELECT count(message_id) FROM ".geoTables::user_communications_table." WHERE `read` <> '1' AND `message_to` = ".$user_id;
	$tpl_vars['num_unread_messages'] = $unreadCount = $this->GetOne($sql);
	$msg_needsAttention = ($unreadCount > 0) ? true : false;
	
	$links['my_messages'] = array('link' => $url_base . "?a=4&amp;b=8", 'label' => $page->messages[500472], 'icon' => $page->messages[500473], 'needs_attention' => $msg_needsAttention);
	$links['message_settings'] = array('link' => $url_base . "?a=4&amp;b=7", 'label' => $page->messages[500474], 'icon' => $page->messages[500475]);
}

if($bitmask & 4) {
	$links['favourites'] = array('link' => $url_base . "?a=4&amp;b=10", 'label' => $page->messages[500462], 'icon' => $page->messages[500463]);	
}

if($bitmask & 8) {
	$links['ad_filters'] = array('link' => $url_base . "?a=4&amp;b=9", 'label' => $page->messages[500464], 'icon' => $page->messages[500465]);	
}

if(geoMaster::is('auctions')) {
	if($bitmask & 16) {
		if($this->get_site_setting('invited_list_of_buyers')) {
			$links['whitelist'] = array('link' => $url_base . "?a=4&amp;b=20", 'label' => $page->messages[500478], 'icon' => $page->messages[500479]);
		}
		if($this->get_site_setting('black_list_of_buyers')) {
			$links['blacklist'] = array('link' => $url_base . "?a=4&amp;b=19", 'label' => $page->messages[500480], 'icon' => $page->messages[500481]);
		}
	}

	if($bitmask & 32) {
		
		//get the number of open feedbacks
		$sql = "select auction_id from ".geoTables::auctions_feedbacks_table." where rater_user_id=? AND done=0";
		$result = $this->Execute($sql, array($user_id));
		$tpl_vars['num_open_feedbacks'] = 0;
		while($auction = $result->FetchRow()) {
			//make sure auctions still exist in the DB before counting
			if(is_object(geoListing::getListing($auction['auction_id'],false,true))) {
				$tpl_vars['num_open_feedbacks']++;
			}
		}
		
		$links['feedback'] = array('link' => $url_base . "?a=4&amp;b=22", 'label' => $page->messages[500468], 'icon' => $page->messages[500469]);	
	}

	$links['current_bids'] = array('link' => $url_base . "?a=4&amp;b=21", 'label' => $page->messages[500466], 'icon' => $page->messages[500467]);	
	
}

$links['user_info'] = array('link' => $url_base . "?a=4&amp;b=3", 'label' => $page->messages[500470], 'icon' => $page->messages[500471]);	



//ask addons if they'd like to add any links
$extraVars = array('url_base' => $url_base);
$addons = geoAddon::triggerDisplay('my_account_links_add_link', $extraVars, geoAddon::ARRAY_ARRAY);
foreach($addons as $addon_name => $addon_links) {
	if (!isset($addon_links['label'])) {
		foreach($addon_links as $name => $link) {
			$links[$name] = $link;
		}
	} else {
		$links [$addon_name] = $addon_links;
	}
}

//Make sure user group is set for payment gateways before calling
$sql = "SELECT `group_id` FROM ".geoTables::user_groups_price_plans_table." WHERE `id` = ".intval(geoSession::getInstance()->getUserID());
$groupId = $this->GetOne($sql);
geoPaymentGateway::setGroup($groupId);

//allow different payment gateways to display things on the user account home page
geoPaymentGateway::callUpdate('User_management_home_body', $extraVars);
//from account balance gateway as result of above call:


//also allow items to add stuff if they need
geoOrderItem::callUpdate('User_management_home_body', $extraVars);

//since those work by assigning to view class, and modules are now loaded on-the-fly, need
//to grab view vars to use for tpl_vars
$tpl_vars['paymentGatewayLinks'] = $view->paymentGatewayLinks;
$tpl_vars['orderItemLinks'] = $view->orderItemLinks;

//set active page, so we can stylize it differently
if($_REQUEST['b'] && is_numeric($_REQUEST['b'])) {
	switch($_REQUEST['b']) {
		case 1:
			$links['active_ads']['active'] = true;
			break;
		case 2:
			$links['expired_ads']['active'] = true;
			break;
		case 3: //break intentionally omitted
		case 4:
			$links['user_info']['active'] = true;
			break;
		case 7:
			$links['message_settings']['active'] = true;
			break;
		case 8:
			$links['my_messages']['active'] = true;
			break;
		case 9: //break intentionally omitted
		case 14:
			$links['ad_filters']['active'] = true;
			break;
		case 10:
			$links['favourites']['active'] = true;
			break;
		case 12: //break intentionally omitted
		case 13:
			//$links['signs_flyers']['active'] = true;
			//addons are now responsible for setting this for themselves
			break;
		case 19:
			$links['blacklist']['active'] = true;
			break;
		case 20:
			$links['whitelist']['active'] = true;
			break;
		case 21:
			$links['current_bids']['active'] = true;
			break;
		case 22:
			$links['feedback']['active'] = true;
			break;
		default:
			//do nothing
			
	}
}
//show my account?  Hint: Can over-ride this in template using {module show_my_account_section=0 tag='my_account_links'}
$tpl_vars['show_my_account_section'] = $tpl_vars['show_account_finance_section'] = 1;


$tpl_vars['links'] = $links;

//let the template know things about this user, to assist in deciding what links to show
//NOTE: not used by our code at present, but added at client request
$tpl_vars['userData'] = geoUser::getData(geoSession::getInstance()->getUserId());

$view->setModuleTpl($show_module['module_replace_tag'],'index')
		->setModuleVar($show_module['module_replace_tag'],$tpl_vars)
		->addCssFile(geoTemplate::getUrl('css','module/my_account_links.css'));