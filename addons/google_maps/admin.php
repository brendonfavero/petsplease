<?php
//addons/google_maps/admin.php
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
## ##    7.2beta3-83-g806659c
## 
##################################

# google_maps Addon
require_once ADDON_DIR . 'google_maps/info.php';
class addon_google_maps_admin extends addon_google_maps_info
{
	public function init_pages ()
	{
		menu_page::addonAddPage('addon_google_maps','','Settings','google_maps', $this->icon_image);
		
		if (!function_exists('curl_init')) {
			Notifications::addNoticeAlert("<a href='?page=addon_google_maps&mc=addon_cat_google_m'>Google Maps</a> Addon requires your attention",array("cURL is required to use Google Maps." =>"Consult your hosting provider regarding how to enable use of cURL."));
			return;
		}
		$db = DataAccess::getInstance();
		if (!$db->get_site_setting('googleApiKey')) {
			Notifications::addNoticeAlert("<a href='?page=addon_google_maps&mc=addon_cat_google_m'>Google Maps</a> Addon requires your attention",array("An API key is required in order to use Google Maps." =>"Sign up for a free google API key for you to use"));	
		}
		
	}
	
	
	public function display_addon_google_maps ()
	{
		$view = geoView::getInstance();
		$map = geoAddon::getUtil('google_maps');
		$reg = geoAddon::getRegistry($this->name);
		$db = DataAccess::getInstance();
		
		$vars = array();
		$vars['googleApiKey'] = $googleApiKey = $db->get_site_setting('googleApiKey');
		
		if (!function_exists('curl_init')) {
			geoAdmin::m('Google Maps requires cURL, contact your host to activate cURL.', geoAdmin::ERROR);
		}
		
		$vars['errors'] = geoAdmin::m();
		
		$vars['width'] = $reg->get('width',600);
		$vars['height'] = $reg->get('height',400);
		$vars['width_type'] = $reg->get('width_type','px');
		$vars['height_type'] = $reg->get('height_type','px');
		$vars['off'] = $reg->off;
		$vars['listing_id'] = '0';
		
		if ($googleApiKey) {
			$util = geoAddon::getUtil($this->name);
			$util->initHead(true);
			$vars['preview'] = $util->getMap();
		}
		
		$view->setBodyTpl('admin/config.tpl','google_maps')
			->setBodyVar($vars);
	}
	
	public function update_addon_google_maps ()
	{
		$admin = geoAdmin::getInstance();
		
		$reg = geoAddon::getRegistry($this->name);
		$db = DataAccess::getInstance();
		
		$googleApiKey = (isset($_POST['googleApiKey']))? $_POST['googleApiKey'] : '';
		if (strlen($googleApiKey) < 30) {
			geoAdmin::m("Invalid API Key",geoAdmin::ERROR,true);
			return false;
		}
		$db->set_site_setting('googleApiKey', $googleApiKey);
		$reg->width = (isset($_POST['width']))? (int)$_POST['width'] : 600;
		$reg->height = (isset($_POST['height']))? (int)$_POST['height'] : 400;
		
		$types = array ('px', '%');
		$reg->width_type = (in_array($_POST['width_type'], $types))? $_POST['width_type'] : 'px';
		$reg->height_type = (in_array($_POST['height_type'], $types))? $_POST['height_type'] : 'px';
		
		$reg->off = (isset($_POST['noApiKey']) || (isset($_POST['on']) && $_POST['on']))? false : 1;
		
		$reg->save();
		return true;
	}
	public function init_text ($language_id)
	{
		return array (
			'map_label' => array ( //text_index1 is the text_id
				'name' => 'Google Maps Label', //name is used in the admin section for editing text messages
				'desc' => 'This is displayed above the Google Map.', //desc is used in the admin section for editing text messages
				'type' => 'textarea', //type is either textarea, or input, and designates what form will be used to edit the text in the admin.
				'default' => 'Map Location' //default is used when installing the addon, to set the default value for the text.
			),
		);
	}
}