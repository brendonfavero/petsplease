<?php

// ampse

/**
 * @package ppNews
 */
class addon_ppNews_pages extends addon_ppNews_info
{
	
	
	public function testingGround() {
			 if( isset($_GET['twitter']) ) {
				
			 }else {
				 
			     geoView::getInstance()->setBodyTpl('testingGround.tpl','ppNews');
			 }
			 
	}
	
	public function news() {
		$db = DataAccess::getInstance();
		$status = array();
		if( $_REQUEST['article'] && intval($_REQUEST['article']) > 0 ) {
			$articleId = intval($_REQUEST['article']);
			$article = array();
			$articleResult = $db->Execute("SELECT * FROM `petsplease_news` WHERE `id` = '{$articleId}'");
			if( !$articleResult || !$articleResult->RecordCount() > 0 || $article != $articleResult->FetchRow() ) {
				$status[] = "We can't find that article. Did you click on the link from our <a href='/news'>news</a> page?";
			}
			geoView::getInstance()->setBodyVars( array( 'status' => $status, 'article' => $article ) );
			geoView::getInstance()->setBodyTpl('newsViewSingle.tpl','ppNews');
		}
        // assign news articles into bodyTpl
		
			
	}
	
	public function latestList() {
		 geoView::getInstance()->setRendered(true);
		 $db = DataAccess::getInstance();
		// get latest news and echo
		
	}
	
}
