<?php
//session/touch.php
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

if (!defined('IN_GEO_API')){
	exit('No access.');
}

/*
 * A simple api-call, that updates the time that a particular session has been accessed.
 */

$session_id = (isset($args['session_id']))? $args['session_id']: 0;

$ip = (isset($args['ip']))? $args['ip']: 0;

$user_agent = (isset($args['user_agent']))? $args['user_agent']: 0;

if (!($ip && strlen($session_id) == 32 && $user_agent)){
	return $this->failure('Fields session_id, ip, and user_agent are all required.');
}

$this->session->touchSession($session_id);
return "Session touched.";