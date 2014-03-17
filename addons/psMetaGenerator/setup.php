<?php
// (c) 2010 PRECISION SYSTEMS LLC
class addon_psMetaGenerator_setup
{
	
	
	public function install ()
	{
		//script to install a fresh copy.
		//for demonstration, this script sets up a dummy database table.
		
		//get $db connection, $addon object (to display messages), and $cron object - use get_common_vars.php to be forward compatible
		//see that file for documentation.
		$admin = true;
		$db = true;		
			include(GEO_BASE_DIR.'get_common_vars.php');	
		
		$fail = array();
		
		$sql[] = "
		CREATE TABLE  IF NOT EXISTS`ps_metaGenerator_pages` (
		  `pid` int(10) unsigned NOT NULL,
		  `title` tinytext NOT NULL,
		  `descr` text NOT NULL,
		  `keywords` text NOT NULL,
		  `modified` int(10) unsigned NOT NULL,
		  `status` tinyint(4) NOT NULL,
		  PRIMARY KEY  (`pid`)
		)";
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `ps_metaGenerator_categories` (
		  `cid` int(10) unsigned NOT NULL,
		  `title` tinytext NOT NULL,
		  `descr` text NOT NULL,
		  `keywords` text NOT NULL,
		  `modified` int(10) unsigned NOT NULL,
		  `status` tinyint(4) NOT NULL,
		  PRIMARY KEY  (`cid`)
		  )";
		
		foreach($sql as $q) {
			$result = $db->Execute($q);
			if (!$result){
				$fail[] = $db->ErrorMsg();
			}
		}
		if (!empty($fail)){
			//query failed, display message and return false.
			foreach($fail as $f) {
				$admin->userError('Database execution error, installation failed.'. $f);
			}
			return false;
		} else {
			//Normally, you do not need to give this much info, or any info at all, this is
			//just used to demonstrate the use of the userNotice, userError, and userSuccess methods.
			$admin->userNotice('Database tables created successfully.');
		}
		
		
		
			
		//If it made it all the way, then the installation was a success...		
		$admin->userSuccess('The meta generator addon installation script completed.');
		return true;
	} 

	public function uninstall ()
	{
		//script to uninstall the example addon.
		
		//get $db connection, $admin object (to display messages), and $cron object - use get_common_vars.php to be forward compatible
		//see that file for documentation.
		$admin = true;
		$db = true;
		include(GEO_BASE_DIR.'get_common_vars.php');
		
		$db->Execute("DROP TABLE `ps_metaGenerator_pages`");
		$db->Execute("DROP TABLE `ps_metaGenerator_categories`");
		$admin->userSuccess('Addon un-install script completed.');
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
	
	/**
	 * Optional, remove function if not needed.  This is run when the 
	 * addon is enabled, in addition to the 
	 * normal stuff that is done by the addon back end.
	 *
	 * @return boolean True to finish enableing the addon, false to not 
	 *  enable the addon.
	 */
	function enable ()
	{
		//not required.  
		
		//function to change status from disabled to enabled.
		//Typically, this function is not needed, as the addon framework does
		//the work automatically.

		//If you wanted, you could display notices, errors, or success messages like the install() function does.
		return true;
	}
	
	/**
	 * Optional, remove function if not needed.  This is run when 
	 * the addon is disabled, in addition to the 
	 * normal stuff that is done by the addon back end.
	 *
	 * @return boolean True to finish disabling the addon, false to not 
	 *  disable the addon.
	 */
	function disable()
	{
		//not required, unless enable function is used.
		
		//function to change status from enabled to disabled.
		//Typically, this function is not needed, as the addon framework does
		//the work automatically.
		
		//If you wanted, you could display notices, errors, or success messages like the install() function does.
		return true;
	}
}