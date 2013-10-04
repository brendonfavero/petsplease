<?php
//addons/core_display/setup.php
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
## ##    7.1beta1-1161-g170d676
## 
##################################

# Core Display

require_once ADDON_DIR . 'core_display/info.php';

class addon_core_display_setup extends addon_core_display_info
{
	public function install ()
	{
		$reg = geoAddon::getRegistry($this->name,true);
		
		//browsing filter defaults
		$reg->browsing_filters_enabled = 1;
		$reg->expandable_threshold = 5;
		
		//browsing featured gallery defaults
		$reg->featured_show_automatically = 1;
		$reg->featured_2nd_page=false;
		$reg->featured_carousel=1;
		$reg->featured_show_listing_type = 1;
		$reg->featured_max_count = 20;
		$reg->featured_column_count = 4;
		$reg->featured_levels = array(1=>1);
		$reg->featured_thumb_width =  150;
		$reg->featured_thumb_height =  150;
		$reg->featured_desc_length = 20;
		
		$reg->save();
		
		return true;
	}
	
}
