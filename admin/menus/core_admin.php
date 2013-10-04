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
## ##    6.0.7-24-ga6a2a52
## 
##################################

//Loads the menu for core admin pages

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');


include 'sections/top_level.php'; // hidden/standalone pages

$parent_key = $head_key = 0;

//include menu sections
include 'sections/site_setup.php';
include 'sections/registration_setup.php';
include 'sections/listing_setup.php';
include 'sections/email_setup.php';
include 'sections/feedback.php';
include 'sections/categories.php';

$parent_key = $head_key = 1;

include 'sections/users_groups.php';
include 'sections/pricing.php';
include 'sections/payments.php';
include 'sections/orders.php';
include 'sections/geographic_setup.php';

$parent_key = $head_key = 2;

include 'sections/pages_management.php';
include 'sections/page_modules.php';
include 'sections/addons.php';
include 'sections/design.php';
include 'sections/languages.php';
include 'sections/admin_tools.php';

//let addons know we're using this menu
$addon = geoAddon::getInstance();
$addon->initAdmin('core_admin');