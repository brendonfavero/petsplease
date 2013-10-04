<?php
//send_negative_account_balance_emails.php
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

//sends e-mails that haven't been sent yet in the e-mail queue

$emailObj = geoEmail::getInstance();


return $emailObj->cron($this);