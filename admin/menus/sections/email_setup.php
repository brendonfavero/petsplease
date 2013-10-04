<?php
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

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

//----------------- E-MAIL SETUP
menu_category::addMenuCategory('email_setup',$parent_key,'E-Mail Setup','admin_images/menu_mail.gif','','',$head_key);
	
	menu_page::addPage('email_general_config','email_setup','General E-Mail Settings','menu_mail.gif','admin_email_config.php', 'Email_configuration');

	menu_page::addPage('email_notify_config','email_setup','Notification E-mail Settings','menu_mail.gif','admin_email_config.php', 'Email_configuration');