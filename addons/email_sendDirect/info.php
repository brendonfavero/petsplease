<?php
//addons/email_sendDirect/info.php
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
## ##    6.0.7-216-g9eb2443
## 
##################################

# Email Send Direct Addon (Main e-mail sender)

class addon_email_sendDirect_info{
	//The following are required variables
	var $name = 'email_sendDirect';
	var $version = '2.0.1';
	var $title = 'Main E-Mail Sender';
	var $author = "Geodesic Solutions LLC.";
	var $description = 'This is the main e-mail sender.  It sends e-mail using 
linux sendmail function, or using SMTP connection, or using the native mail() function, depending on settings in the admin.
<br /><br />
It sends the e-mail right away.  If your e-mail settings are mis-configured, it
can cause pages that send out e-mails to "freeze up".';

	var $icon_image = 'menu_mail.gif'; //located in addons/example/icon.gif
	var $info_url = 'http://geodesicsolutions.com/component/content/article/55-miscellaneous/214-main-email-sender.html?directory=64';
	var $core_events = array ('email', 'app_bottom');
	
	var $exclusive = array(
		'email' => true,  //do not load this addon at the same time
						//as another addon using e-mail
		'app_bottom' => false//does not matter about app_bottom.
	);
}

/**
 * Storefront Changelog
 * 
 * 2.0.1 - 7.0.0
 *  - Add ability to have e-mail header (not bumping version since this is system addon)
 * 
 * 2.0.1 - 5.1.2
 *  - (re?)added ability to specify e-mail salutation.
 *  - No need to actually bump version, a and this is sytem addon, so staying at 2.0.1
 *  
 * 2.0.1 - 4.0.0RC11
 *  - First version using changelog block for e-mail addon
 *  - Added "force from e-mail" as a setting, for sites that won't send e-mails unless the from is valid on the
 *    same domain name or whatever.
 * 
 */

