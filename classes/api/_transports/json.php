<?php
//json.php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.4
Copyright (c) 2001-2014 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.4.3-108-g3b5c327
## 
##################################

//JSON transport for API. Currently experimental.


class jsonTransport implements iApiTransport
{
	public $name = 'json';
	
	public function __construct ()
	{
		//defined so parent's constructor doesn't get called...
	}
	
	public function getType ()
	{
		return $this->name;
	}
	
	public function getParams ()
	{
		return $_POST;
	}
	
	public function getCall ()
	{
		return $_POST['call'];
	}
	
	public function exitAfterOutput ()
	{
		return true;
	}
	
	public function outputSuccess ($result)
	{
		$output['success'] = 1;
		$output['data'] = $result;
		$json = json_encode($output);
		echo $json;
	}
	
	public function outputError ($errno, $errmsg, $delay_time)
	{
		if ($delay_time) {
			sleep($delay_time);
		}
		
		$output['success'] = 0;
		$output['error'] = array('error_num' => $errno, 'message' => $errmsg);
		$json = json_encode($output);
		echo $json;
		
	}
}

