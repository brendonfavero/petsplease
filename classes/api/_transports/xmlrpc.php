<?php
//xmlrpc.php
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

//XML RPC transport for API, this is the primary (and only) built-in transport currently

/**
 * Requires the XMLRPC PHP Library class, as geoAPI extends IXR_Server in order
 * to communicate.
 */
require_once CLASSES_DIR . 'rpc/XMLRPC.class.php';

class xmlrpcTransport extends IXR_Server implements iApiTransport
{
	public function __construct ()
	{
		//defined so parent's constructor doesn't get called...
	}
	
	public function getType ()
	{
		return 'xmlrpc';
	}
	
	public function getParams ()
	{
		$this->_loadMessage();
		return $this->message->params;
	}
	
	public function getCall ()
	{
		$this->_loadMessage();
		return $this->message->methodName;
	}
	
	public function exitAfterOutput ()
	{
		return true;
	}
	
	public function outputSuccess ($result)
	{
		//Taken from part of IXR_Server::serve()
		
		// Is the result an error?
		if (is_a($result, 'IXR_Error')) {
			$this->error($result);
		}
		// Encode the result
		$r = new IXR_Value($result);
		$resultxml = $r->getXml();
		// Create the XML
		$xml = <<<EOD
<methodResponse>
  <params>
    <param>
      <value>
		$resultxml
      </value>
    </param>
  </params>
</methodResponse>

EOD;
		// Send it
		$this->output($xml);
	}
	
	public function outputError ($errno, $errmsg, $delay_time)
	{
		if ($delay_time) {
			sleep($delay_time);
		}
		$this->outputSuccess(new IXR_Error($errno, $errmsg));
	}
	
	private function _loadMessage ()
	{
		if ($this->message) {
			//already done
			return;
		}
		//Taken from part of IXR_Server::serve()
		
		global $HTTP_RAW_POST_DATA;
		if (!$HTTP_RAW_POST_DATA) {
			die('XML-RPC server accepts POST requests only.');
		}
		$data = $HTTP_RAW_POST_DATA;
		
		$this->message = new IXR_Message($data);
		if (!$this->message->parse()) {
			$this->error(-32700, 'parse error. not well formed');
		}
		if ($this->message->messageType != 'methodCall') {
			$this->error(-32600, 'server error. invalid xml-rpc. not conforming to spec. Request must be a methodCall');
		}
	}
}

