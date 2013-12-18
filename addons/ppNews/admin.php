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
		require_once( dirname(__FILE__) . '/lib/news/Article.php' );
		require_once( dirname(__FILE__) . '/lib/news/Category.php' );
		$category = new Category($db);
		$article = new Article($db);
		$article->admin = true;
		
		// ajax only
		if( isset($_POST['category_action']) ) {
			$result = $category->run( $_POST['category_action'], $_POST );
			echo json_encode( array('result' => $result, 'messages' => $category->messages ) );
			exit;
		}
		if( isset($_POST['article_action']) ) {
			$result = $article->run( $_POST['article_action'], $_POST );
			echo json_encode( array('result' => $result, 'messages' => $article->messages ) );
			exit;
		}
		if( isset($_REQUEST['megaUpdate']) ) {
			$result = $db->Execute("SELECT * FROM `petsplease_news` WHERE `id` > 63");
			if( $result && $result->RecordCount() > 0 ) {
				while( $row = $result->FetchRow() ) {
					echo $article->run('save', $row);
				}
			}else {
				echo "error in query";
			}
			exit;
		}




		// first run stuff
		$vars = array( 'pathAddon' => '/addons/ppNews', 'url' => $_SERVER['REQUEST_URI']); // returned template vars
		$vars['categories'] = $category->getAll();

		geoView::getInstance()->setBodyTpl('newsAdmin.tpl','ppNews');
		geoView::getInstance()->setBodyVar($vars);
	}
 
	
	

}
