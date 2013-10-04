<?php
//admin_classauction_tools.php
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
## ##    7.2beta1-153-gd6a581e
## 
##################################

class Admin_classauction_tools extends Admin_site
{
	function display_admin_tools_uploader()
	{
		if(is_file('bulk_uploader/bulk_uploader_class.php'))
		{
			include_once('bulk_uploader/bulk_uploader_class.php');
			
			$bulk = new bulk_uploader($this->db, $product_configuration);
			if(isset($_REQUEST["deleteLog"])) {
				$bulk->deleteLog($_REQUEST["deleteLog"]);
			}
			if(isset($_REQUEST["bulkData"])) {
				$bulk->sessionHandler->free("bulkData");
				foreach($_REQUEST["bulkData"] as $key => $value) {
					$bulk->sessionHandler->set($key,$value);
				}
			}
			if(isset($_FILES["csvData"])) {
				$bulk->sessionHandler->set("fileName",$bulk->setCSVData($_FILES["csvData"]["tmp_name"]));
				$bulk->sessionHandler->free("bulkColumn");
			}
			if(isset($_REQUEST["bulkColumn"])) {
				$bulk->sessionHandler->free("bulkColumn");
				foreach($_REQUEST["bulkColumn"] as $key => $value) {
				$bulk->sessionHandler->set("bulkColumn",$value,$key);
				}
			}
			if(isset($_REQUEST["bulkDefaultColumn"])) {
				$bulk->sessionHandler->free("bulkDefaultColumn");
				foreach($_REQUEST["bulkDefaultColumn"] as $key => $value) {
				$bulk->sessionHandler->set("bulkDefaultColumn",$value,$key);
				}
			}
			if(isset($_REQUEST["bulkDefaultData"])) {
				$bulk->sessionHandler->free("bulkDefaultData");
				foreach($_REQUEST["bulkDefaultData"] as $key => $value) {
				$bulk->sessionHandler->set("bulkDefaultData",$value,$key);
				}
			}
			if(isset($_REQUEST["bulkCustomTitle"])) {
				$bulk->sessionHandler->free("bulkCustomTitle");
				foreach($_REQUEST["bulkCustomTitle"] as $key => $value) {
				$bulk->sessionHandler->set("bulkCustomTitle",$value,$key);
				}
			}
			if($_REQUEST["bulkDuration"]["method"]==="0") {
				$bulk->sessionHandler->set("startTime",time());
				$bulk->sessionHandler->set("endTime",time()+$_REQUEST["bulkDuration"]["fixed"]);
			} elseif($_REQUEST["bulkDuration"]["method"]==="1") {
				$bulk->sessionHandler->set("startTime",mktime($_REQUEST["bulkDuration"]["start"]["hour"],$_REQUEST["bulkDuration"]["start"]["minute"],0,$_REQUEST["bulkDuration"]["start"]["month"],$_REQUEST["bulkDuration"]["start"]["day"],$_REQUEST["bulkDuration"]["start"]["year"]));
				$bulk->sessionHandler->set("endTime",mktime($_REQUEST["bulkDuration"]["end"]["hour"],$_REQUEST["bulkDuration"]["end"]["minute"],0,$_REQUEST["bulkDuration"]["end"]["month"],$_REQUEST["bulkDuration"]["end"]["day"],$_REQUEST["bulkDuration"]["end"]["year"]));
			}
			if(isset($_REQUEST["bulkUpgrades"])) {
				foreach($_REQUEST["bulkUpgrades"] as $key => $value) {
					$bulk->sessionHandler->set($key,$value);
				}
			}
			$bulk->addDynamicDropDownValues();
			$bulk->displayBulkUploader();
			
		}
	}
	function update_admin_tools_uploader(){}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_admin_tools_license()
	{
		if (isset($_GET['licenseRefreshed']) && $_GET['licenseRefreshed']) {
			geoAdmin::m('License Data successfully refreshed from geodesicsolutions.com license server.', geoAdmin::SUCCESS);
		}
		
		$view = geoView::getInstance();
		
		$leased = geoPC::is_leased();
		
		$tpl_vars = array();
		if (defined('DEMO_MODE')) {
			$key = 'DEMO';
		} else if (defined('DEMO_MODE_TEXT')) {
			$key = DEMO_MODE_TEXT;
		} else {
			$key = $this->db->get_site_setting('license');
		}
		$tpl_vars['licenseKey'] = $key;
		$tpl_vars['leased'] = $leased;
		$tpl_vars['force_powered_by'] = geoPC::force_powered_by();
		$tpl_vars['trial'] = geoPC::is_trial();
		$tpl_vars['demo'] = defined('DEMO_MODE');
		$tpl_vars['license_only'] = geoPC::license_only();
		
		$exp = geoPC::getLocalLicenseExpire();
		if (!defined('DEMO_MODE_TEXT') && $exp != 'never' && $exp != 'pending...' && $exp > 0) {
			$exp = date ('F j, Y', $exp);
		}
		
		$tpl_vars['localExpire'] = (defined('DEMO_MODE'))? 'DEMO': $exp;
		
		$exp = geoPC::getLicenseExpire();
		if (!defined('DEMO_MODE_TEXT') && $exp != 'never' && $exp != 'pending...' && $exp > 0) {
			$exp = date ('F j, Y', $exp);
		}
		$tpl_vars['licenseExp'] = (defined('DEMO_MODE'))? 'DEMO': $exp;
		
		$tpl_vars['admin_msgs'] = geoAdmin::m();
		
		$tpl_vars['show_upgrade_pricing'] = (geoPC::is_ent()) ? false : true;
		$tpl_vars['maxSeats'] = geoPC::maxSeats();
		$tpl_vars['maxSeats'] = ($tpl_vars['maxSeats']==-1)? 'Unlimited' : $tpl_vars['maxSeats'];
		
		$view->setBodyTpl('licenseInfo.tpl')
			->setBodyVar($tpl_vars);
	}
	function update_admin_tools_license()
	{
		$onlyData = ($_POST['clearType'] !== 'key');
		geoPC::clearLicenseKey($onlyData);
		
		if (!$onlyData) {
			//log the user out.
			$session = geoSession::getInstance();
			$session->logOut();
			trigger_error('DEBUG LICENSE: LOCAL: License key manually cleared by the admin user.');
		} else {
			trigger_error('DEBUG LICENSE: LOCAL: Local license data being manually refreshed by the admin user.');
		}
		include GEO_BASE_DIR.'app_bottom.php';
		header('Location: index.php?page=admin_tools_license&licenseRefreshed=1');
		exit;
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_admin_tools_view_ads()
	{
		// Variables
		$content = "";
		$usr_cache = array();
		$sql_query = "";

		// Begin the table
		$content .= "
			<fieldset id='SearchListings'><legend>Search Expired Listings</legend><table cellpadding=2 cellspacing=1 width=\"75%\"><tr><td align=center>";		
		
		// Days Dropdown list
		$content .= "
			<form method='post' action=''><span class=medium_font>Expired for: 
				<select id='age' name='age'>";
		for( $x=1; $x <= 45; $x++)
		{
			$content .= "
					<option value='".$x."'".(($_REQUEST['age'] == $x)? " selected" : "").">".$x."</option>";
		}
							
		$content .= "
				</select> Days </span><br><br>
				<input type='submit' name='auto_save' value='Go'>
			</form>";
				
		$content .= "
			</td></tr></table></fieldset>";
		
		// Set the page for pagination
		if ( $_REQUEST['show'] )
		{
			$page = $_REQUEST['show'];
			$page_start = (($page-1) * 25);
			$limit = $page_start.",25";
		}else{
			$page = 1;
			$limit = "0,25";
		}
			
		// Expired Classifieds SQL
		$sql_query = "SELECT * FROM ".$this->classifieds_table." WHERE ends < ".time()." AND ends > ".(time()-($_REQUEST['age']*24*60*60))." AND live = 0";
		$sql_query .= " ORDER BY ends DESC LIMIT ".$limit;
		$rs = $this->db->Execute($sql_query);
		if(!$rs)
			return false;
		
		// Count the expired classifides
		$sql_query = "SELECT count(*) as total FROM ".$this->classifieds_table." WHERE ends < ".time()." AND ends > ".(time()-($_REQUEST['age']*24*60*60))." AND live = 0";
		$total_rs  = $this->db->Execute($sql_query);
		if(!$total_rs)
			return false;
			
		$total = $total_rs->FetchRow();
		$number_of_pages = ceil($total['total'] / 25);
		
		// Begin the table
		$content .= "
			<fieldset id='ExpiredListings'><legend>Search Results</legend><table cellpadding=2 cellspacing=1 width=\"75%\">";
		
		
		// Loop through the Ads
		if ( $rs->RecordCount() ) {
			$content .= "
				<tr>
					<td class=col_hdr_left valign=top align=center><b>ID</b></td>
					<td class=col_hdr_left valign=top align=center><b>Title</b></td>
					<td class=col_hdr_left valign=top align=center><b>Seller</b></td>
					<td class=col_hdr_left valign=top align=center><b>Description</b></td>
					<td class=col_hdr_left valign=top align=center><b>Ended</b></td>
					<td class=col_hdr_left valign=top align=center><b>Details</b></td>
				</tr>";
			while ( $ad = $rs->FetchRow() )
			{
				if ( !$usr_cache[$ad['seller']] )
				{
					$sql_query = "SELECT id,username FROM ".$this->userdata_table." WHERE id = ".$ad['seller'];
					$usr_rs = $this->db->Execute($sql_query);
					
					if ($usr_rs->RecordCount() == 1)
					{
						$usr = $usr_rs->FetchRow();
						$usr_cache[$usr['id']] = urldecode($usr['username']);
					}
				}
							
					
				$content .="
					<tr class=".$this->get_row_color().">
						<td class=medium_font valign=top align=center>".$ad['id']."</td>
						<td class=medium_font><a href='index.php?mc=users&page=users_view_ad&b=".$ad['id']."'><span class=medium_font>".urldecode($ad['title'])."</span></a></td>
						<td class=medium_font valign=top align=center><a href=index.php?mc=users&page=users_view&b=".$ad['seller']."><span class=medium_font>".$usr_cache[$ad['seller']]." (".$ad['seller'].")</span></a></td>
						<td class=medium_font>".geoString::specialChars(substr(strip_tags(geoString::fromDB($ad['description'])),0,50))."...</td>
						<td class=medium_font valign=top align=center>".date('m-d-y h:i',$ad['ends'])."</td>
						<td class=medium_font valign=top align=center><a href='index.php?mc=users&page=users_restart_ad&b=".$ad['id']."'><span class=medium_font>Restart</span></a></td>
					</tr>";
					$this->row_count++;		
			}
		
			
			// Begin Pagination
			$content .= "
					<tr>
						<td class=medium_font colspan=3>Page ";
					
			for ($x = 1; $x <= $number_of_pages; $x++)
			{
				if ($page == $x)
				{
					$content .= $x."&nbsp;";
				}else{
					$content .=  "<a href=?mc=admin_tools_settings&page=admin_tools_view_ads&show=".$x."&age=".$_REQUEST['age'].">".$x."</a>&nbsp;";
				}
			}
			$content .= "
						</td>
						<td class=medium_font colspan=3 align=right>Showing Results ".((($page-1)*25)? (($page-1)*25) : '1')." to ".(($total['total'] < ($page*25))? $total['total'] : $page*25)."</td>
					</tr>";
		}
		else
		{
			$content .= "
				<tr>
					<td><div class='page_note_error'>There are no expired listings to display for this time-frame.</div></td>
				</tr>";
		}
		$content .= "
			</table></fieldset>";

		// Apply to page
		$this->body.=$content;
		// Display page
		$this->display_page();
	}
	function update_admin_tools_view_ads()
	{
		
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	/*
	 * This function provides a way to clean up orphaned image files on a server. Usually, image files will be removed when listings are archived,
	 * but sometimes we've seen a few sneak by, so this should clean those out 
	 */
	function display_admin_tools_clean_images()
	{
		if($_GET['auto_save'] == 2) {
			$this->update_admin_tools_clean_images();
		}
		
		$html = geoAdmin::m();
		$html .= '<fieldset><legend>Remove Orphaned Images</legend>';
		$html .= '<div class="page_note">This tool will search the user images folder for any files that are no longer referenced by listings in the database. Any of these "orphaned" listings will be permanently deleted from the server.
		this will not affect your listings in any way, and is normally not needed, but it may help free up disk space on some server configurations, especially older sites or those with lots of uploaded images. If your server has a large number of uploaded files, this may take a while.
		Click the button below to proceed, but do so with caution, as this action cannot be undone.</div>';
		
		$html .= '<div class="center"><a href="index.php?mc=admin_tools_settings&amp;page=admin_tools_clean_images&amp;auto_save=1" class="mini_button lightUpLink">Remove Orphaned Images</a></div></fieldset>';
		geoAdmin::getInstance()->display_page($html);
		
		
	}

	function update_admin_tools_clean_images()
	{
		$db = DataAccess::getInstance();

		//pull array of filenames from images_urls table
		$sql = "SELECT full_filename, thumb_filename FROM ".geoTables::images_urls_table;
		$result = $db->Execute($sql);
		$filenames = array();
		while($row = $result->FetchRow()) {
			$filenames[] = $row['full_filename'];
			$filenames[] = $row['thumb_filename'];
		}
		
		$folder = $db->GetOne("SELECT image_upload_path FROM ".$this->ad_configuration_table);

		$dir = dir($folder);
		$count = 0;
		while(false !== ($filename = $dir->read())) {
			//loop through user_images dir
			
			if($filename == '.' || $filename == '..' || substr($filename, 0, 1) == '_' || is_dir($folder.$filename)) {
				//skip filenames we don't care about
				continue;
			} 
			
			//compare filename to array
			if(!in_array($filename, $filenames) && is_file($folder.$filename)) {
				unlink($folder.$filename); //delete file if not found
				$count++; //count number of files deleted
			}
		}
		
		if($count) {
			geoAdmin::m("Removed $count orphaned images.", geoAdmin::NOTICE);
		} else {
			geoAdmin::m("No orphaned images were found.", geoAdmin::NOTICE);
		}
	}
	
}
