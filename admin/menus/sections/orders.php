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
## ##    6.0.7-2-gc953682
## 
##################################

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- ORDERS
menu_category::addMenuCategory('orders',$parent_key,'Orders','admin_images/menu_trans.gif','','',$head_key);
	
	menu_page::addPage('orders_list','orders','Manage Orders','menu_trans.gif','orders.php','OrdersManagement');
		menu_page::addPage('orders_list_order_details','orders_list','View Order','menu_trans.gif','orders.php','OrdersManagement','sub_page');
	
	if (geoPC::is_ent()) {
		menu_page::addPage('recurring_billing_list','orders','Manage Recurring Billing','menu_trans.gif','recurring_billing.php','RecurringBillingManagement');
			menu_page::addPage('recurring_billing_details','recurring_billing_list','View Recurring Billing','menu_trans.gif','recurring_billing.php','RecurringBillingManagement','sub_page');
	}
	
	menu_page::addPage('orders_list_items','orders','Manage Items','menu_trans.gif','items.php','OrderItemManagement');
		menu_page::addPage('orders_list_items_item_details','orders_list_items','View Order Item','menu_trans.gif','items.php','OrderItemManagement','sub_page');
		menu_page::addPage('orders_list_items_item_unlock','orders_list_items','View Order Item','menu_trans.gif','items.php','OrderItemManagement','sub_page');
	
	menu_page::addPage('admin_cart','orders','Create Order','menu_trans.gif','cart.php','AdminCart');
		menu_page::addPage('admin_cart_select_user', 'admin_cart', 'Select User', 'menu_trans.gif', 'cart.php', 'AdminCart');
		menu_page::addPage('admin_cart_delete', 'admin_cart', 'Delete Order', 'menu_trans.gif', 'cart.php', 'AdminCart');
		menu_page::addPage('admin_cart_swap', 'admin_cart', 'Swap Cart Contents', 'menu_trans.gif', 'cart.php', 'AdminCart');
		menu_page::addPage('admin_cart_edit_price', 'admin_cart', 'Edit Item Price', 'menu_trans.gif', 'cart.php', 'AdminCart');