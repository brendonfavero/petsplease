<?php
// addons/enterprise_pricing/admin.php
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
##    06473f3
## 
##################################

# Example Addon

class addon_enterprise_pricing_admin extends addon_enterprise_pricing_info {
	
	//open up access to admin pages unlocked by this addon
	function init_pages () {
		menu_page::addPage('pricing_new_price_plan','pricing','Add New Price Plan','menu_pricing.gif','admin_price_plan_management_class.php','Price_plan_management'); //create new price plan
		menu_page::addPage('pricing_category_costs','pricing_price_plans','Category Specific Costs','menu_pricing.gif','admin_price_plan_management_class.php','Price_plan_management','sub_page'); //category-specific pricing
		
		menu_page::addPage('users_group_add_plan','users_groups','Add Price Plan','menu_users.gif','admin_group_management_class.php','Group_management','sub_page'); //add secondary price plan to group
		menu_page::addPage('users_new_group','users','Add New User Group','menu_users.gif','admin_group_management_class.php','Group_management'); //add new user group
	}
	
	
}