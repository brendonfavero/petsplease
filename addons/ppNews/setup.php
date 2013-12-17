<?php

// ampse

/**
 * @package ppNews
 */
 
class addon_ppNews_setup
{
	
	
	public function install ()
	{
		//script to install a fresh copy.
		//for demonstration, this script sets up a dummy database table.
		
		//get $db connection, $addon object (to display messages), and $cron object - use get_common_vars.php to be forward compatible
		//see that file for documentation.
		$admin = true;
		include(GEO_BASE_DIR.'get_common_vars.php');
	
		//If it made it all the way, then the installation was a success...
		$admin->userSuccess('Ampse Installed');
		return true;
	} 
	
	
	public function uninstall ()
	{
		//script to uninstall the example addon.
		
		//get $db connection, $admin object (to display messages), and $cron object - use get_common_vars.php to be forward compatible
		//see that file for documentation.
		$admin = true;
		include(GEO_BASE_DIR.'get_common_vars.php');
		
		
		

		$admin->userSuccess('Ampse Removed');
		return true;
	}
	

	function upgrade ($from_version = false)
	{
		//Get an instance of the geoAdmin object, so we can use it
		//to display messages, and get instance of geoCron object so
		//we can add new cron tasks to the system
		$admin = true;
		include GEO_BASE_DIR . 'get_common_vars.php';
		
		
	
		$admin->userSuccess('Upgrade completed with no problems.');
		//if upgrade is successful, return true.
		return true;
	}
}