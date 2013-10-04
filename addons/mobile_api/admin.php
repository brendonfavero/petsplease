<?php 
//addons/mobile_api/admin.php
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
## ##    7.0.3-961-g298dc72
## 
##################################

# Mobile API
//declared as final to prevent subclassing exploits
final class addon_mobile_api_admin extends addon_mobile_api_info
{
	public function init_text($language_id)
	{
		$return_var['sitekey_header'] = array (
			'name' => 'Show Site Key - Header',
			'desc' => '',
			'type' => 'input',
			'default' => 'GeoMobile Community Network'
		);
		$return_var['sitekey_prelink'] = array (
			'name' => 'Show Site Key - Pre-Link Text',
			'desc' => '',
			'type' => 'input',
			'default' => 'Browse our listings on your iPhone with the GeoMobile app!'
		);
		$return_var['sitekey_link'] = array (
			'name' => 'Show Site Key - Linked Text',
			'desc' => '',
			'type' => 'input',
			'default' => '<strong>Download GeoMobile from the App Store</strong>'
		);
		$return_var['sitekey_postlink'] = array (
			'name' => 'Show Site Key - Post-Link Text',
			'desc' => '',
			'type' => 'input',
			'default' => 'and enter Site Key'
		);
		return $return_var;
	}
	
	public function init_pages ()
	{
		menu_page::addonAddPage('addon_mobile_api_manage','','Manage Devices',$this->name);
		
		menu_page::addonAddPage('addon_mobile_api_clear_key','addon_mobile_api_manage','Clear Device Key',$this->name,'','sub_page');
		menu_page::addonAddPage('addon_mobile_api_get_site_key','addon_mobile_api_manage','Register Site Key',$this->name,'','sub_page');
		
		$reg = geoAddon::getRegistry($this->name);
		if($reg->iphoneis_personal == 1) {
			menu_page::addonAddPage('addon_mobile_api_iphone_field_overrides','','iPhone Field Overrides',$this->name);
		}
	}
	
	public function display_addon_mobile_api_manage ()
	{
		//KNOWN ISSUE:  Once we add more devices, the "errors" are going to be set
		//if any of the devices has errors, making all devices look like they have problems.
		//Will need to address this when come to it.
		$util = geoAddon::getUtil($this->name);
		$reg = geoAddon::getRegistry($this->name);
		$pc = geoPC::getInstance();
		$db = DataAccess::getInstance();
		
		$tpl_vars = array();
		
		$tpl_vars['admin_msgs'] = geoAdmin::m();
		
		$base = 'classifieds_url';
		if (strlen($db->get_site_setting('classifieds_ssl_url')) > 0 && $db->get_site_setting('use_ssl_in_sell_process')){
			$base = 'classifieds_ssl_url';
		}
		
		$tpl_vars['base_api_url'] = dirname($db->get_site_setting($base)).'/geo_api.php';
		
		$devices = $util->getDevices();
		foreach ($devices as $device) {
			$info = array();
			$info['license_key'] = $reg->get($device.'license_key');
			if ($info['license_key']) {
				$info['valid'] = $util->validateDevice($device,'');
				if (!$info['valid']) {
					$tpl_vars['keysToSave']=true;
					$info['errors'] = $pc->errors($this->name);
				} else {
					//valid, show extra stuff
					$info['leased'] = geoPC::is_leased($this->name);
					
					$exp = geoPC::getLocalLicenseExpire($this->name);
					if ($exp != 'never' && $exp != 'pending...' && $exp > 0) {
						$exp = date ('F j, Y', $exp);
					}
					
					$info['localExpire'] = $exp;
					
					$exp = geoPC::getLicenseExpire($this->name);
					if ($exp != 'never' && $exp != 'pending...' && $exp > 0) {
						$exp = date ('F j, Y', $exp);
					}
					$info['licenseExp'] = $exp;
					$info['api_key'] = $util->getDeviceApiKey($device);
					$info['site_key'] = $reg->get('site_key');
					$info['site_name'] = $reg->get($device.'_site_name');
				}
			} else if (isset($_POST['license_keys'][$device]) && $_POST['license_keys'][$device]) {
				$tpl_vars['keysToSave']=true;
				$info['errors'] = $pc->errors($this->name);
				$info['mustAgree'] = $pc->mustAgree();
				if ($info['mustAgree']) {
					//user must agree to thingy...  keep the license key what they entered
					$info['license_key'] = $_POST['license_keys'][$device];
					//load up CSS to make it look perdy
					geoView::getInstance()->addCssFile('css/login.css');
				}
			} else {
				$tpl_vars['keysToSave']=true;
			}
			$tpl_vars['devices'][$device] = $info;
		}
		
		geoView::getInstance()->setBodyTpl('admin/manage.tpl', $this->name)
			->setBodyVar($tpl_vars);
	}
	
	public function update_addon_mobile_api_manage ()
	{
		$util = geoAddon::getUtil($this->name);
		$reg = geoAddon::getRegistry($this->name);
		
		$devices = $util->getDevices();
		$keys = $_POST['license_keys'];
		
		foreach ($devices as $device) {
			$existing_key = $reg->get($device.'license_key');
			if (!strlen($keys[$device])) {
				//they made it blank, clear it
				$reg->set($device.'license_key',false);
				$reg->set($device.'license_data',false);
				$reg->set($device.'is_personal', false);
				continue;
			}
			if ($keys[$device]==$existing_key) {
				//no change, nothing to do
				continue;
			}
			//it's different, validate it...
			//note that validate addon saves the key for us if it is valid
			
			$valid = $util->validateDevice($device, trim($keys[$device]));
			if (!$valid) {
				geoAdmin::m("License key for $device not valid.", geoAdmin::ERROR);
			} elseif(stripos('Personal',$keys[$device]) !== false) {
				//this is a valid, personalized license. may want to do something with this later, so make a note of it
				$reg->set($device.'is_personal', 1);
			}
		}
		$reg->save();
		return true;
	}
	
	public function display_addon_mobile_api_get_site_key ()
	{
		if (!geoAjax::isAjax()) {
			return $this->display_addon_mobile_api_manage();
		}
		
		$util = geoAddon::getUtil($this->name);
		$reg = geoAddon::getRegistry($this->name);
		
		$tpl_vars = array();
		$tpl_vars['device'] = $device = $_GET['device'];
		
		if (!in_array($tpl_vars['device'], $util->getDevices())) {
			//invalid device!  just failsafe check
			die ('Invalid device!');
		}
		$tpl_vars['site_name'] = $reg->get($device.'_site_name');
		$tpl_vars['site_key'] = $reg->get('site_key');
		
		$tpl = new geoTemplate(geoTemplate::ADDON, $this->name);
		$tpl->assign($tpl_vars);
		echo $tpl->fetch('admin/get_site_key.tpl');
		geoView::getInstance()->setRendered(true);
	}
	
	public function update_addon_mobile_api_get_site_key ()
	{
		$util = geoAddon::getUtil($this->name);
		$db = DataAccess::getInstance();
		$reg = geoAddon::getRegistry($this->name);
		
		$info = array();
		
		$info['device'] = $device = $_GET['device'];
		if (!in_array($info['device'], $util->getDevices())) {
			//invalid..
			return false;
		}
		$info['siteName'] = trim($_POST['site_name']);
		if (preg_match('/[^-_a-zA-Z0-9 ]+/', $info['siteName'])) {
			geoAdmin::m('Invalid site name!  Can only use A-Z, 0-9, spaces, underscores, and dashes.  Site Key NOT registered/refreshed.', geoAdmin::ERROR);
			return false;
		}
		if (strlen($info['siteName'])>13) {
			geoAdmin::m('Invalid site name!  Cannot be longer than 13 characters (it will not fit on the iphone screen).',geoAdmin::ERROR);
			return false;
		}
		if (!strlen($info['siteName'])) {
			geoAdmin::m("Site name required!", geoAdmin::ERROR);
			return false;
		}
		$info['siteUrl'] = $db->get_site_setting('classifieds_url');
		
		$base = 'classifieds_url';
		if (strlen($db->get_site_setting('classifieds_ssl_url')) > 0 && $db->get_site_setting('use_ssl_in_sell_process')){
			$base = 'classifieds_ssl_url';
		}
		$info['apiUrl'] = dirname($db->get_site_setting($base)).'/geo_api.php?transport='.$device;
		$info['apiKey'] = $util->getDeviceApiKey($device);
		$info['validate'] = sha1('tlqtXjbPUmndXZ5zrwuwhlfVd4MaJ7xCxF1d8VBQcM'.$info['apiKey']);
		$info[$device.'_license_key'] = $reg->get($device.'license_key');
		if (!$info[$device.'_license_key'] || !$util->validateDevice($device)) {
			geoAdmin::m("Invalid device license key!",geoAdmin::ERROR);
			return false;
		}
		
		$info['site_license_key'] = $db->get_site_setting('license');
		
		$data = geoPC::urlPostContents('http://geodesicsolutions.com/iphone_util/register.php', $info);
		$data = json_decode($data, true);
		if (!$data) {
			//not able to do nothin?
			geoAdmin::m("Not able to contact the GeoMobile server to register/refresh GeoMobile site key!",geoAdmin::ERROR);
			return false;
		}
		if (isset($data['error'])) {
			geoAdmin::m("Error when getting site key: ".$data['error'], geoAdmin::ERROR);
			return false;
		}
		if ($data['status']!='active') {
			geoAdmin::m("Site or License Key Not Active!",geoAdmin::ERROR);
			return false;
		}
		$reg->set('site_key', $data['code']);
		$reg->set($device.'_site_name', $info['siteName']);
		$reg->save();
		geoAdmin::m("Successfully registered/refreshed GeoMobile Site Key!  Use GeoMobile Site Key below to connect the GeoMobile app to your site.");
		return true;
	}
	
	public function display_addon_mobile_api_clear_key ()
	{
		return $this->display_addon_mobile_api_manage();
	}
	
	public function update_addon_mobile_api_clear_key ()
	{
		$device = $_POST['device'];
		$util = geoAddon::getUtil($this->name);
		if (!in_array($device, $util->getDevices())) {
			geoAdmin::m('Invalid device!',geoAdmin::ERROR);
			return false;
		}
		$reg=geoAddon::getRegistry($this->name);
		$reg->set($device.'license_key',false);
		$reg->set($device.'license_data',false);
		$reg->save();
		geoAdmin::m('License cleared.');
		return true;
	}
	
	public function display_addon_mobile_api_iphone_field_overrides()
	{
		
		$reg = geoAddon::getRegistry($this->name);
		$existing = $reg->iphonefield_overrides;
		$tpl_vars['adminMessages'] = geoAdmin::m();
		
		//note: "id" is the *SELLER'S USERNAME*
		$tpl_vars['overridables'] = array('price' => $existing['price'], 'date' => $existing['date'], 'id' => $existing['id']);
		
		//note: "id" is the *LISTING ID#*
		$tpl_vars['injectables'] = array('id','seller','ends','title');
		if(geoPC::is_ent()) {
			for($i=1;$i<=20;$i++) {
				$tpl_vars['injectables'][] = "optional_field_$i";
			}
		}
		$tpl_vars['hiddenCategories'] = DataAccess::getInstance()->get_site_setting('api_hidden_categories');
		geoView::getInstance()->setBodyTpl('admin/iphone_field_overrides.tpl', $this->name)
			->setBodyVar($tpl_vars);
			
	}
	
	public function update_addon_mobile_api_iphone_field_overrides()
	{
		$overrides = $_POST['override'];
		foreach($overrides as $field => $replace) {
			if(!$replace) {
				//not overriding this field
				unset($overrides[$field]);
			}
		}
		$reg = geoAddon::getRegistry($this->name);
		$reg->iphonefield_overrides = $overrides;
		$reg->save();
		
		DataAccess::getInstance()->set_site_setting('api_hidden_categories', $_POST['hiddenCategories']);
		
		return true;
	}
}