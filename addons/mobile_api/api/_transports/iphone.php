<?php 
//addons/mobile_api/_transports/iphone.php
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
## ##    7.1.2-25-gca5e2f2
## 
##################################

//this is the iphone transport

class iphoneTransport implements iApiTransport
{
	public $name = 'iphone';
	public $version = '1.0.0';
	
	
	public $allowedCalls = array (
		'core.category.list','core.listing.search','core.listing.get','core.listing.get_custom'
	);
	
	/**
	 * Get array of parameters based on whatever the transport uses for passing
	 * parameters in.
	 *
	 * @return array
	 */
	public function getParams ()
	{
		return geoAddon::getUtil('mobile_api')->validateDeviceApiKey($this->name, $_POST['params']);
	}
	
	/**
	 * Get the requested API call name, somthing like core.misc.echo
	 *
	 * @return string
	 */
	public function getCall ()
	{
		if (!$this->_licenseCheck()) {
			$this->outputError(245, 'Server error, please try again later.');
			require GEO_BASE_DIR.'app_bottom.php';
			exit;
		}
		if (!isset($_POST['function']) || !in_array($_POST['function'], $this->allowedCalls)) {
			//this one is not allowed!
			$this->outputError(246, 'Not authorized, or out-dated transport.');
			require GEO_BASE_DIR.'app_bottom.php';
			exit;
		}
		
		return $_POST['function'];
	}
	
	/**
	 * Allow overriding the content of certain fields for personalized licenses.
	 * For instance, if the site owner wants to display expiration time instead of start time, this would be the place to do it
	 * 
	 */
	public function checkPersonalOverrides($data){
		
		$reg = geoAddon::getRegistry('mobile_api');
		if(!$reg->iphoneis_personal) {
			//this is not a personal license. can't do anything here.
			return $data;
		}
		
		$overrides = $reg->iphonefield_overrides;
		if(!$overrides) {
			//nothing to do, so don't waste time doing it
			return $data;
		}

		foreach($data as $key => $value) {
			
			if(is_array($value)) {
				//nested array -- loop this one, too
				foreach($value as $nKey => $nValue) {
					if($nKey === 'listingId') {
						//this always comes first in the array, so we can reliably just grab the listing object now
						//this is a little bit of a dirty hack, but it should work
						$listing = geoListing::getListing($nValue);
					}
					if(isset($overrides[$nKey])) {
						//an override is set for this field
						$fieldToOverride = $nKey;
						$fieldToInject = $overrides[$nKey];
						$data[$key][$nKey] = ($fieldName === 'ends') ? date('M j, Y', $listing->$fieldToInject) : geoString::fromDB($listing->$fieldToInject);
					}
				}
			} 
			
			if(!$listing) {
				//listingId is always the first field checked.
				//Therefore, if we get to this point without a $listing, this is not an overridable api call
				//Break out of the loop to save time
				break;
			}
		}
		return $data;		
	}
	
	/**
	 * Output given data to the client.  Should be able to handle arrays, string,
	 * numbers, etc.
	 *
	 * @param mixed $data
	 */
	public function outputSuccess ($data)
	{
		$data = $this->checkPersonalOverrides($data);
		echo $this->_toXML('root', $data);
		return true;
	}
	
	/**
	 * Output an error to the client.  Responsible for adding a delay if $addDelay
	 * is not 0, and applicable for transport.
	 *
	 * @param int $errno Just a number used to help quickly identify an error
	 * @param string $errmsg Error message to return back
	 * @param int $addDelay Delay time in seconds, used to slow down brute force attempts
	 */
	public function outputError ($errno, $errmsg, $addDelay)
	{
		if ($addDelay) {
			sleep($delay_time);
		}
		$msg = "$errmsg (err $errno)";
		
		echo $this->_toXML('error',$msg);
		return false;
	}
	
	/**
	 * The type of transport, should be the filename minus the .php and in all
	 * lowercase.  Note that the filename shoudl be all lowercase anyways.
	 *
	 * @return string;
	 */
	public function getType ()
	{
		return 'iphone';
	}
	
	/**
	 * Whether or not script should exit after output success/failure is called.
	 * This is here to allow "local" transports to prevent the rest of the script
	 * from stopping.
	 *
	 * Any "remote" transport should return true.
	 *
	 * @return bool
	 */
	public function exitAfterOutput ()
	{
		return true;
	}
	
	/**
	 * Convert an associative array of infinite depth into XML (recursively).
	 * @param $name String name for the base level of the array
	 * @param $value Array the array to convert
	 */
	private function _toXML ($name, $value=null, $depth=0)
	{
		$xml = '';		
		$tabs = '';
		//idiot-proof things (also make it harder to break the app's parser)
		$name = $this->_encode($name);
		
		for ($i=0; $i < $depth; $i++) {
			//make the XML look pretty...
			$tabs .= "  ";
		}
		
		if ($depth == 0) {
			$xml .= '<?xml version="1.0" ?>'."\n";
		}
		
		if (is_array($value)) {
			$xml .= "$tabs<array name=\"$name\">\n";
			foreach ($value as $k => $v) {
				$xml .= $this->_toXML($k, $v, $depth+1);
			}
			$xml .= "$tabs</array>\n";
		} else {
			if (is_bool($value)) {
				//convert to 1/0 if it is bool
				$value = (int)$value;
			} else {
				//encode value for safe transfer over net
				$value = $this->_encode($value);
			}
			$xml .= "$tabs<item name=\"$name\">$value</item>\n";
		}
		
		return $xml;
	}
	
	/**
	 * Converts a string to a format suitable for transfer over the web, and
	 * display within an iOS app
	 * 
	 * @param string $str string to convert
	 * @return string encoded string
	 */
	private function _encode($str)
	{
		// undo HTML filtering here, because iPhone can't
		$str = geoString::specialCharsDecode($str);
		//now pull out all html formatting altogether, since iPhone won't parse it anyway
		$str = geoFilter::replaceDisallowedHtml($str, true);
		//remove newlines and tab characters.
		$str = str_replace(array("\n","\r","\t"), '', $str);
		//just to be safe
		$str = trim($str);
		//use db encoding for transmit across the net
		$str = geoString::toDB($str);
		//iPhone can't decode '+', so use '%20' instead
		$str = str_replace('+','%20', $str);
		return $str;
	}
	
	/**
	 * Checks the license, if any problems, returns false.
	 * @return boolean
	 */
	private function _licenseCheck ()
	{
		$util = geoAddon::getUtil('mobile_api');
		
		return $util->validateDevice($this->getType());
	}
}
