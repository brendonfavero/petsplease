<?php
//seller_buyer_transactions.php
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

class AdminSellerBuyerTransactions {
	function display_seller_buyer_config(){
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		
		$sb_html = geoSellerBuyer::callDisplay('adminDisplaySettings');
		
		$html = $menu_loader->getUserMessages()."
<fieldset>
	<legend>Seller Buyer Transactions</legend>
	<form method=\"post\" action=\"\">
	$sb_html
	
	<div style=\"text-align: center;\"><input type=\"submit\" name=\"auto_save\" value=\"Save\" /></div>
	</form>
</fieldset>";
		geoAdmin::display_page($html);
	}
	
	
	
	function update_seller_buyer_config(){
		geoSellerBuyer::callUpdate('adminUpdateSettings');
		
		return true;
	}
}