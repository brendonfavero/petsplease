<?php
//api.php
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

require_once CLASSES_DIR . PHP5_DIR . 'API.class.php';

class AdminAPIManage {
	function display_api_keys($display_page = true){
		$db = true;
		include GEO_BASE_DIR.'get_common_vars.php';
		
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		
		$base = 'classifieds_url';
		if (strlen($db->get_site_setting('classifieds_ssl_url')) > 0 && $db->get_site_setting('use_ssl_in_sell_process')){
			$base = 'classifieds_ssl_url';
		}
		$api =& Singleton::getInstance('geoAPI');
		$api_url = substr($db->get_site_setting($base),0,strpos($db->get_site_setting($base),$db->get_site_setting('classifieds_file_name')));
		
		$api_url .= 'geo_api.php';
		if (defined('DEMO_MODE')) {
			$menu_loader->userNotice('Demo Mode: The Remote API system has been disabled on the demo, for security reasons.');
		}
		$master_key = (defined('DEMO_MODE'))? 'MASTER KEY HIDDEN FOR DEMO': $api->getKeyFor();
		
		$html = $menu_loader->getUserMessages();
		$html .= '<div class="page_note_error">Be sure to read the user manual concerning the information contained on this page.</div>';
		
		$html .= "
<fieldset>
	<legend>Remote API URL</legend>
	<div>
	<div class=\"row_color1\">
		<div class=\"leftColumn\">Remote API URL &nbsp; </div>
		<div class=\"rightColumn\" style=\"border: thin solid black; width: 58%; padding: 1px; padding-left: 5px;\">
			$api_url
		</div>
		<div class=\"clearColumn\"></div>
	</div>
	<br />
	</div>
</fieldset>
<fieldset>
	<legend>Remote API Security Keys</legend>
	<div>
	<div class=\"col_hdr\">Master Key - Will work for any API call</div><br />
	
	<div class=\"row_color1\">
		<div class=\"leftColumn\">Master API Key &nbsp;</div>
		<div class=\"rightColumn\" style=\"border: thin solid black; width: 58%; padding: 1px; padding-left: 5px;\">
			".$master_key."
		</div>
		<div class=\"clearColumn\"></div>
	</div>
	<br />
	<div class=\"col_hdr\">Keys to use Specific API Calls</div>
	<br />
	<div class=\"row_color1\">
		<div class=\"leftColumn\">Remote API Call Name &nbsp;</div>
		<div class=\"rightColumn\" style=\"border: thin solid black; width: 58%; padding: 1px; padding-left: 5px;\">
			Key for Remote API Call - will only work for specific call
		</div>
		<div class=\"clearColumn\"></div>
		<br />
	</div>
	";
		
		$callbacks = $api->getCallBacks();
		$methods = array_keys($callbacks);
		$row = 'row_color1';
		foreach ($methods as $methodname){
			$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
			$master_key = (defined('DEMO_MODE'))? $methodname.' KEY HIDDEN FOR DEMO': $api->getKeyFor($methodname);
			$html .= "
	<div class=\"$row\">
		<div class=\"leftColumn\">$methodname &nbsp; </div>
		<div class=\"rightColumn\" style=\"border: thin solid black; width: 58%; padding: 1px; padding-left: 5px;\">
			".$master_key."
		</div>
		<div class=\"clearColumn\"></div>
	</div>";
		}
		
		if (!defined('DEMO_MODE') && defined('IAMDEVELOPER')){
			$html .= "
	<br /><br />
	<form method='post' action=''>
	<div class=\"col_hdr\">
		I Am Developer - Enter method name
	</div>
	<br />";
			if (isset($_POST['method_name'])){
				$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
				$html .= "
	<div class=\"$row\">
		<div class=\"leftColumn\">{$_POST['method_name']} &nbsp; </div>
		<div class=\"rightColumn\" style=\"border: thin solid black; width: 58%; padding: 1px; padding-left: 5px;\">
			".$api->getKeyFor($_POST['method_name'])."
		</div>
		<div class=\"clearColumn\"></div>
	</div>
	</form>";
			}
			$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
			$html .= "
	<div class=\"$row\">
		<div class=\"leftColumn\">Enter Remote API Call: &nbsp; </div>
		<div class=\"rightColumn\">
			<input type=\"text\" name=\"method_name\" value=\"{$_POST['method_name']}\" />
		</div>
		<div class=\"clearColumn\"></div>
	</div>
	</form>";
		}
		$html .= "
	</div>
</fieldset>";
		
		
		geoAdmin::display_page($html);
	}
}