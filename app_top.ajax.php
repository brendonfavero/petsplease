<?php
//app_top.main.php
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

header('Cache-Control: no-cache');
header('Expires: -1');
header('Pragma: no-cache');

define('AJAX', 1);

require_once "app_top.common.php";

//set header for charset, otherwise it won't show up right for weird charsets..
$charset = geoString::getCharsetTo();
if (!$charset){
	//if not using charsetTo, then use the charsetclean setting.
	$charset = geoString::getCharset();
}
//Necessary for weird charsets like arabian, do not change!
header('Content-Type: application/x-javascript; charset='.$charset);

if (isset ($HTTP_SERVER_VARS))
{
	$_SERVER = $HTTP_SERVER_VARS;
}
/*if (!isset($session)){
	$session = true; include GEO_BASE_DIR.'get_common_vars.php';
}
$session->initSession();*/