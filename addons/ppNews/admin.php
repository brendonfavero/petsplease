<?php

//ampse

/**
 * @package ppNews
 */
class addon_ppNews_admin
{
	
	public function init_pages ()
	{		
		menu_page::addonAddPage('addon_ppNews_news','','Manage','ppNews');				
	}
	

	var $default_addon_text = array (
	// TEXT FOR STORE LISTING PAGE
		'px_form' => array (
			'name' => 'Additional Text for PX Form',
			'desc' => '',
			'type' => 'textarea',
			'default' => ''
		)
	);

	/*function init_text($language_id) 
	{
		//Rename the function to remove _no_use if we need to start using addon text.
		//TODO: Need to make all "built in" text use addon text instead.
		
		return $this->default_addon_text;
		
	}*/
	
	
	public function display_addon_ppNews_news() {
		$db = DataAccess::getInstance();
		
		$vars = array( 'pathAddon' => '/addons/ppNews', 'url' => $_SERVER['REQUEST_URI']); // returned template vars
		geoView::getInstance()->setBodyTpl('newsAdmin.tpl','ppNews');
		
		// handle actions
		if( isset($_POST['create']) ){
			$insertResult = $db->Execute("INSERT INTO `petsplease_news` (`label`, `order`, `created`, `modified`, `status`) VALUES ('Untitled News Article', 9999, '" . time() . "', '" . time() . "', 0)");	
			if( $insertResult ) {
				echo $db->Insert_ID();
			}

			exit;
		}
		
		if( isset($_POST['update']) ){
			$id = intval($_POST['update']);
			$time = time();
			$columnsResult = $db->Execute("DESCRIBE `petsplease_news`");
			$result = array('success' => 0, 'status' => '', 'data' => array() );
			if( $columnsResult ) {
				$columns = $columnsResult->GetArray();
				$values = array( $time );
				$keys = array("`modified` = ?");
				foreach($columns as $column){
					if( isset($_POST[$column['Field']]) ){
						$keys[] = "`".$column['Field']."` = ?";
						$values[$column['Field']] = $_POST[$column['Field']];
					}									
				}
				$sql = 	"UPDATE `petsplease_news` SET " . implode(", ",$keys) . " WHERE `id` = '" . $id . "'";
				$updateResult = $db->Execute($sql, $values);
				if( $updateResult ) {
					$result['success'] = 1;
					$result['data']['created'] = date('l, F j Y \a\t H:i:s', $time);
					$result['data']['modified'] = date('l, F j Y \a\t H:i:s', $time);
				}				
			}
			else {
				$result['status'] = "Something bad happened. Contact support.";	
			}
			echo json_encode($result);
			exit;
		}
		
		if( isset($_POST['order']) ) {
			// order is a list of ids, 'tis all
			$ids = explode(',',$_POST['order']);
			$order = 0;
			foreach( $ids as $id) {
				$db->Execute( "UPDATE `petsplease_news` SET `order` = '" . $order . "' WHERE `id` = '" . $id . "'");
				$order++;
			}
			echo 1;
			exit;
		}
		
		if( isset($_POST['delete']) ) {
			$id = intval( $_POST['delete'] );
			$deleteResult = $db->Execute( sprintf( "UPDATE `petsplease_news` SET `status` = '-1' WHERE `id` = '%d'",
												  $_POST['delete'] ));
			if( $deleteResult ) {
				echo "1";	
			}else {
				echo "There was an error deleting. Maybe try logging out and back in?";	
			}
			exit;
		}
		
		// get  tabs
		$vars['tabs'] = array();
		$tabsResult = $db->Execute('SELECT * FROM `petsplease_news` WHERE `status` >= 0 ORDER BY `order`');
		if( $tabsResult ) {
			$vars['tabs'] = $tabsResult->GetArray();
		}		
		
		geoView::getInstance()->setBodyVar($vars); // shove vars into template (gets rendered automatically)
	}
 
	
	

}
