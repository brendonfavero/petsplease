<?php
//addons/mobile_api/tags.php
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
## ##    f99c9e7
## 
##################################

# Mobile API
 class addon_mobile_api_tags extends addon_mobile_api_info
{
	public function show_site_key()
	{
		$reg = geoAddon::getRegistry('mobile_api');
		$site_key = $reg->site_key;
		
		$tpl = new geoTemplate('addon','mobile_api');
		$tpl->assign('site_key', $site_key);
		$tpl->assign('itunes_link', 'http://itunes.apple.com/us/app/geomobile/id473774461');
		$tpl->assign('text', geoAddon::getText('geo_addons', 'mobile_api'));
		$html = $tpl->fetch('tags/show_site_key.tpl');
		return $html;
	}
}