<?php
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
## ##    6.0.7-25-g658a8ef
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- PAYMENTS
if (geoPC::is_ent() || geoPC::is_premier() || geoPC::is_basic()){
	menu_category::addMenuCategory('payments',$parent_key,'Payments','admin_images/menu_payments.gif','','',$head_key);

		//moved to be a Master Switch
		//menu_page::addPage('payments_charge_for_listings','payments','Charge Site Fees?','menu_payments.gif','admin_payment_management_class.php','Payment_management');
		
		menu_page::addPage('payments_currency_designation','payments','Currency Designation','menu_payments.gif','admin_payment_management_class.php','Payment_management');
		
		menu_page::addPage('payment_gateways','payments','Payment Gateways','menu_payments.gif','payment_gateways.php','paymentGatewayManage');
		
		if (geoPC::is_ent()) {
			menu_page::addPage('seller_buyer_config', 'payments', 'Seller to Buyer Gateways', 'menu_trans.gif','seller_buyer_transactions.php', 'AdminSellerBuyerTransactions');	
		}
		
		menu_page::addPage('payments_revenue_report','payments','Revenue Reports','menu_payments.gif','admin_payment_management_class.php','Payment_management');
}