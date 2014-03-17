<?php
// (c) 2010 PRECISION SYSTEMS LLC

class addon_psMetaGenerator_info
{

	var $name = 'psMetaGenerator';	
	
	var $version = '1.1.0';	
	
	var $core_version_minimum = '4.0.5';	
	
	var $title = 'PS Meta Generator';	
	
	var $author = "Precision Systems LLC.";	
	
	var $description = 'This addon lets you control the meta information for each page, category and listing with configurable options.';
	
	var $auth_tag = 'ps_addons';	
	
	var $icon_image = 'icon.png';	

	var $upgrade_url = 'http://www.precisionmoney.com';
	
	var $author_url = 'http://www.precisionmoney.com';
	
	var $info_url = 'http://www.precisionmoney.com';
	
	var $core_events = array ( 'filter_display_page', 'notify_display_page'	);
	


}