<?php
//addons/mobile_api/util.php
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
//declared as final to prevent subclassing exploits
final class addon_mobile_api_util extends addon_mobile_api_info
{
	private $license;

	public function __construct ()
	{
		
	}
	
	public function getDevices ()
	{
		$files = array_diff(scandir(ADDON_DIR.'mobile_api/api/_transports/'), array('.','..'));
		$devices = array();
		foreach ($files as $file) {
			if (strpos($file,'.php')!==false) {
				$devices[] = str_replace('.php','',$file);
			}
		}
		return $devices;
	}
	
	public function validateDevice ($device, $license_key = '')
	{
		//hard-code secret for any devices in this method, if new device is released
		//we'll just need to release updated copy of this file.
		$secrets = array (
			'iphone' => 'f52b593e5cdd35feca73f34570f3e661',
		);
		$validDevices = $this->getDevices();
		if (in_array($device, $validDevices) && isset($secrets[$device])) {
			$pc = geoPC::getInstance();
			//Note that the validation built in checks to make sure it has field addon_...
			$result = $pc->validateAddon($this->name, $secrets[$device], $device, $license_key);
			if (!$result) {
				trigger_error('DEBUG LICENSE: LOCAL: License key did not validate.');
				return false;
			}
			//make sure valid for device
			$custom_fields = $pc->getAddonFields($this->name, $secrets[$device]);
			if (!isset($custom_fields['device_'.$device])||!$custom_fields['device_'.$device]) {
				//device_... not set in license, this license is for another device...
				trigger_error('DEBUG LICENSE: LOCAL: License not valid for given device.');
				return false;
			}
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * Gets the API key specifically for use with specified device
	 * 
	 * @param string $device
	 * @return string
	 */
	public function getDeviceApiKey ($device)
	{
		return Singleton::getInstance('geoAPI')->getKeyFor('transport:'.$device);
	}
	
	/**
	 * validates that the device specific api key is set correctly.  If it is,
	 * it sets the api_key in the params to the global valid api key, so that it
	 * passes validation done by base API class.  If it fails, it returns
	 * the params with no changes (which will make buit-in checks fail).
	 * 
	 * @param string $device The device, must match transport name
	 * @param array $params the array of params
	 * @return array The params with the global api key set correctly if the device
	 *   API key is set correctly in the params array.
	 */
	public function validateDeviceApiKey ($device, $params)
	{
		$api = Singleton::getInstance('geoAPI');
		$api_key = $params[$device.'_api_key'];
		$correct_key = $this->getDeviceApiKey($device);
		
		if ($api_key === $correct_key) {
			unset ($params[$device.'_api_key']);
			$params['api_key'] = $api->getKeyFor();
		} else {
			unset($params['api_key']);
		}
		return $params;
	}
}
