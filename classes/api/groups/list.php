<?php
//groups/list.php
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

//Gets a list of users, with data requested
$sql = "SELECT * FROM ".geoTables::groups_table;


return $this->db->GetAll($sql);
