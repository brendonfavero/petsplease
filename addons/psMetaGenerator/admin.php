<?php
// (c) 2010 PRECISION SYSTEMS LLC
class addon_psMetaGenerator_admin
{
	
	public function init_pages ()
	{
		
		menu_page::addonAddPage('addon_psMetaGenerator_config','','Configuration','psMetaGenerator');
		menu_page::addonAddPage('addon_psMetaGenerator_page','','Pages','psMetaGenerator');
		menu_page::addonAddPage('addon_psMetaGenerator_category','','Categories','psMetaGenerator');
		
		
		
				
	}
	
	
	private static $default_addon_text = array
	();

    
	
	public function display_addon_psMetaGenerator_page ()
	{	
		$db = true;		
		include(GEO_BASE_DIR.'get_common_vars.php');
		
		//responsible for adding stuff to be displayed in the main part of the page.  Note: v() is alias for getView()
		$html = geoAdmin::getInstance()->message();
		//This one uses a smarty template to render the main part of the admin:
		geoView::getInstance()->setBodyTpl('page.tpl','psMetaGenerator');

		
		$vars = array( 'pathAddon' => '/addons/psMetaGenerator', 'url' => $_SERVER['REQUEST_URI']); // returned template vars
		 $vars ['status'] = '';
		 $vars['addonPath'] = GEO_BASE_DIR."addons/psMetaGenerator";

		$form = array('title' => '', 'descr' => '', 'keywords' => '', 'name' => '', 'description' => '');
		if($_POST['open']) {
			$pageToEdit = $_POST['page_id'] * 1;
			if( $pageToEdit < 1) {
				$addonTag = $_POST['addonTag'];
				list( $addonName, $addonPage) = explode( ":", $addonTag);
				$form = array( 'page_id' => 0, 'addonTag' => $addonTag, 'name' => $addonName, 'description' => 'Page: '.$addonPage );
				$result = $db->Execute("SELECT * FROM `ps_metaGenerator_pages` WHERE `addon` = '" . $addonTag . "'");
				if( $result && $result->RecordCount() > 0 && $row = $result->FetchRow() ) {
					$form = array_merge( $form, $row );	
				}
			}else {
				$page = $db->Execute("SELECT * FROM `geodesic_pages` as `p` LEFT JOIN `ps_metaGenerator_pages` as `m` ON `p`.`page_id` = `m`.`pid` 
							   WHERE `p`.`page_id` = '".$pageToEdit."'");
				if($page) {
					if($page->RecordCount() > 0) {
						$form = $page->FetchRow(); 
					}
				}	
			}
			
			$vars['edit'] = true;	 //default to emtpy form data
		}
		if( $_POST['save']) {
			$addonTag = "";
			$pageId = $_POST['page_id'];
			
			if( $pageId < 1 ) {
				$pageId = 0;
				$addonTag = $_POST['addonTag'];	
			}
			$insUpdSql = "SELECT `pid` FROM `ps_metaGenerator_pages` WHERE `pid` = '" . $pageId . "' AND `addon` = '" .  $addonTag . "'";
			$insertOrUpdateResult = $db->Execute( $insUpdSql);
			if( $insertOrUpdateResult && $insertOrUpdateResult->RecordCount() > 0 ) { // update
				$sql = sprintf("UPDATE `ps_metaGenerator_pages` SET  `title` = '%s', `descr` = '%s', `keywords` = '%s' WHERE `pid` = '%d' AND `addon` = '%s'" ,
								mysql_real_escape_string($_POST['title']),
								mysql_real_escape_string($_POST['descr']),
								mysql_real_escape_string($_POST['keywords']),
								$pageId,
								mysql_real_escape_string( $addonTag )
							);
			} else {
				$sql = sprintf("INSERT INTO `ps_metaGenerator_pages` (`pid`, `title`, `descr`, `keywords`, `addon`) VALUES ('%d', '%s', '%s', '%s', '%s' )",
								$pageId,
								mysql_real_escape_string($_POST['title']),
								mysql_real_escape_string($_POST['descr']),
								mysql_real_escape_string($_POST['keywords']),
								mysql_real_escape_string( $addonTag )
							);
			}
			
			
			$insert = $db->Execute($sql);
			if($insert) {
				$vars['status'] .= "Your changes have been saved.";	
			}
		}
		$section = 1;
		if( isset($_REQUEST['section']) && intval($_REQUEST['section']) > 0 ) {
			$section = intval($_REQUEST['section']);	
		}elseif( $_REQUEST['section'] == "ap" ) {
			$section = 'ap';	
		}
		
		$vars['section'] = $section;
		$pages = array();
		
		// get pages
		if( $section == 'ap' ) {
			$addonPages = geoAddon::getInstance()->getPageAddons();
			foreach( $addonPages as $addonName => $addon) {
				foreach( $addon as $addonPage ) {
					$page = array( 'page_id' => 0, 'addonTag' => $addonName.":".$addonPage, 'name' => $addonName, 'description' => 'Page: '.$addonPage , 'title' => '', 'descr' => '', 'keywords' => '');
					$result = $db->Execute("SELECT * FROM `ps_metaGenerator_pages` WHERE `addon` = '" . $addonName.":".$addonPage  . "'");
					if( $result->RecordCount() > 0 && $row = $result->FetchRow() ) {
						$page = array_merge( $page, $row );
					}
					$pages[] = $page;									
				}
			}
		}else {
			$result = $db->Execute("SELECT * FROM `geodesic_pages` as `p` LEFT JOIN `ps_metaGenerator_pages` as `m` ON `p`.`page_id` = `m`.`pid` 
							 		  WHERE `p`.`section_id` = " . $section);
			if( $result && $result->RecordCount() > 0 ) {
				while($row = $result->FetchRow()) {
					$pages[] = $row; 
				}
			}
			
		}
		
		
		 
		 
		 	//access this in template using {$setting1}
		$vars['pages'] = $pages;
		$vars['form'] = $form;
		
		$vars['questions'] = $questions;
		//accessed in template: {$setting2}
		$vars ['setting2'] = 'Second Setting.';
		
		$vars ['product_version'] = Singleton::getInstance('addon_psMetaGenerator_info')->version;
		
		geoView::getInstance()->setBodyVar($vars);

	}
	
	public function display_addon_psMetaGenerator_category ()
	{	
		$db = true;		
		include(GEO_BASE_DIR.'get_common_vars.php');
		
		//responsible for adding stuff to be displayed in the main part of the page.  Note: v() is alias for getView()
		$html = geoAdmin::getInstance()->message();
		//This one uses a smarty template to render the main part of the admin:
		geoView::getInstance()->setBodyTpl('category.tpl','psMetaGenerator');

		
		$vars = array( 'pathAddon' => '/addons/psMetaGenerator', 'url' => $_SERVER['REQUEST_URI']); // returned template vars
		 $vars ['status'] = '';
		 $vars['addonPath'] = GEO_BASE_DIR."addons/psMetaGenerator";

		$form = array('title' => '', 'descr' => '', 'keywords' => '', 'name' => '', 'description' => '');
		if($_POST['open']) {
			$pageToEdit = $_POST['category_id'] * 1;
			$page = $db->Execute("SELECT * FROM `geodesic_categories` as `c` LEFT JOIN `ps_metaGenerator_categories` as `m` ON `c`.`category_id` = `m`.`cid`
							   WHERE `c`.`category_id` = '".$pageToEdit."'");
			if($page) {
				if($page->RecordCount() > 0) {
					$form = $page->FetchRow(); 
				}
			}	
			$vars['edit'] = true;	 //default to emtpy form data
		}
		if( $_POST['save']) {
		    $page = $db->Execute("SELECT * FROM `ps_metaGenerator_categories` as `m`
                               WHERE `m`.`cid` = '".$_POST['category_id']."'");
            if($page) {
                if($page->RecordCount() > 0) {
                    $sql = sprintf("REPLACE INTO `ps_metaGenerator_categories` SET `cid` = '%d', `title` = '%s', `descr` = '%s', `keywords` = '%s', modified = '%s', status = '%s'",
                        mysql_real_escape_string($_POST['category_id']),
                        mysql_real_escape_string($_POST['title']),
                        mysql_real_escape_string($_POST['descr']),
                        mysql_real_escape_string($_POST['keywords']),
                        0,
                        0
                    );
                }
                else {
                    $sql = sprintf("INSERT INTO `ps_metaGenerator_categories`  VALUES('%d', '%s', '%s', '%s', '%s','%s')",
                        mysql_real_escape_string($_POST['category_id']),
                        mysql_real_escape_string($_POST['title']),
                        mysql_real_escape_string($_POST['descr']),
                        mysql_real_escape_string($_POST['keywords']),
                        0,
                        0
                    );
                }
               
            }
            else {
                    $sql = sprintf("INSERT INTO `ps_metaGenerator_categories`  VALUES('%d', '%s', '%s', '%s', '%s','%s')",
                        mysql_real_escape_string($_POST['category_id']),
                        mysql_real_escape_string($_POST['title']),
                        mysql_real_escape_string($_POST['descr']),
                        mysql_real_escape_string($_POST['keywords']),
                        0,
                        0
                    );
                }
                
                echo $sql;   
             
			
			
			$insert = $db->Execute($sql);
			if($insert) {
				$vars['status'] .= "Your changes have been saved.";	
			}
            else {
                $vars['status'] .= $sql;
            }
		}
		
		
		// get articles
		$result = $db->Execute("SELECT * FROM `geodesic_categories` as `c` LEFT JOIN `ps_metaGenerator_categories` as `m` ON `c`.`category_id` = `m`.`cid` ");
		 $pages = array();
		 while($row = $result->FetchRow()) {
			$pages[] = $row; 
		 }
		 	//access this in template using {$setting1}
		$vars['pages'] = $pages;
		$vars['form'] = $form;
		
		$vars['questions'] = $questions;
		//accessed in template: {$setting2}
		$vars ['setting2'] = 'Second Setting.';
		
		$vars ['product_version'] = Singleton::getInstance('addon_psMetaGenerator_info')->version;
		geoView::getInstance()->setBodyVar($vars);

	}
	
	public function display_addon_psMetaGenerator_config ()
	{	
			$db = true;		
			$admin = true;
		include(GEO_BASE_DIR.'get_common_vars.php');
		
		//responsible for adding stuff to be displayed in the main part of the page.  Note: v() is alias for getView()
		$html = geoAdmin::getInstance()->message();
		//This one uses a smarty template to render the main part of the admin:
		geoView::getInstance()->setBodyTpl('config.tpl','psMetaGenerator');
		$settingsRegistry = geoAddon::getRegistry('psMetaGenerator');
		
		 $vars = array();
		if($_POST['save']) {
			$siteName = (isset($_POST['siteName'])) ? $_POST['siteName'] : "";
			$title = (isset($_POST['title'])) ? $_POST['title'] : "";
			$description = (isset($_POST['description'])) ? $_POST['description'] : "";
			$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : "";
			$descLength = (isset($_POST['descLength'])) ? $_POST['descLength'] : "";
			$titleLength = (isset($_POST['titleLength'])) ? $_POST['titleLength'] : "";
			$autoCategory = (isset($_POST['autoCategory'])) ? $_POST['autoCategory'] : "";
			$autoCategoryExtra = (isset($_POST['autoCategoryExtra'])) ? $_POST['autoCategoryExtra'] : "";
			
			$settingsRegistry->set('siteName', $siteName); 
			$settingsRegistry->set('title', $title);
			$settingsRegistry->set('description', $description);
			$settingsRegistry->set('keywords', $keywords);
			$settingsRegistry->set('descLength', $descLength); 
			$settingsRegistry->set('titleLength', $titleLength); 
			$settingsRegistry->set('autoCategory', $autoCategory);
			$settingsRegistry->set('autoCategoryExtra', $autoCategoryExtra);  
			$settingsRegistry->save();
			$admin->message("Settings saved.");
		}
		$vars ['status'] = '';
		$vars['siteName'] = 	   $settingsRegistry->get('siteName');
		$vars['title'] = 	   $settingsRegistry->get('title');
		$vars['description'] = $settingsRegistry->get('description');
		$vars['keywords'] =    $settingsRegistry->get('keywords');
		$vars['descLength'] =  $settingsRegistry->get('descLength');
		$vars['titleLength'] =  $settingsRegistry->get('titleLength');
		$vars['autoCategory'] =  $settingsRegistry->get('autoCategory');
		$vars['autoCategoryExtra'] =  $settingsRegistry->get('autoCategoryExtra');
		
		$vars ['product_version'] = Singleton::getInstance('addon_psMetaGenerator_info')->version;
		
		geoView::getInstance()->setBodyVar($vars);

	}
	

}