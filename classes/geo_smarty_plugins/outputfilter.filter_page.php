<?php
//outputfilter.filter_page.php
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

//this smarty plugin is nice

function smarty_outputfilter_filter_page ($output, Smarty_Internal_Template $smarty)
{
	return geoAddon::triggerDisplay('filter_display_page',$output, geoAddon::FILTER);
}
