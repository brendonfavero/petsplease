<?php

// ampse

/**
 * @ package ppNews 
 */
 
class addon_ppNews_tags 
{
	
	public function latestNews() {
		$tpl = new geoTemplate('addon','ppNews');
		$db = DataAccess::getInstance();
		// get  tabs
		$tabs = array();
		$tabsResult = $db->Execute('SELECT `label`, `data` FROM `ampse_addon_custom_tabs` WHERE `status` > 0 ORDER BY `order`');
		if( $tabsResult ) {
			$tabs = $tabsResult->GetArray();
		}		
		$tpl->tabs = $tabs;
		
				
		return $tpl->fetch('tabs.tpl');
	}
	
	public function newListingCrumb() {
		$db = DataAccess::getInstance();
			
	
	}

}
