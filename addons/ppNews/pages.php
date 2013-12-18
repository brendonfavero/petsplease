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
		
		$v = array( 'url' => '/news' );
		$v['mode'] = "home";
		$v['currentCategory'] = false;
		$v['data'] = array('comments' => array());
		$v['categories'] = false;
		$v['messages'] = array();
		$v['userId'] = geoSession::getInstance()->getUserId();
		$v['userName'] = geoSession::getInstance()->getUserName();
		if( $v['userId'] == 1 ) {
			$v['userName'] = 'HorseZONE';	
		}
		if( isset( $_REQUEST['adPreview'] )) {
			$v['adPreview'] = true;	
		}
		// pre processing
		//$v['messages'][] = print_r($_REQUEST, true);		
		
		$total = 0;
		
		//pagination
		$page = isset( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : 1;
		$pageLength = 10;
				
		
		
		// display article
		if( isset($_REQUEST['article']) ) {
			$v['mode'] = "article";
			
			$where = "`a`.`id` = '" . intval($_REQUEST['article']) . "'";
			if( !is_numeric($_REQUEST['article']) ) { // not really used, since it's parsed by htaccess, but just incase
				$where = "`a`.`hash` = '" . mysql_real_escape_string($_REQUEST['article']) . "'";
			}
			if( $v['userId'] > 1 ) {
				$where .= " AND `a`.`status` > 0";
			}
			$articleResult = $db->Execute("SELECT `a`.*, `c`.`id` as `category_id`, `c`.`label` as `category_label`, `c`.`hash` as `category_hash`, `c`.`sponsor` as `category_sponsor`
											 FROM `petsplease_news` as `a`
											LEFT JOIN `petsplease_news_categories` as `c`											
											ON `a`.`category` = `c`.`id` 									
											WHERE " . $where );
			if( $articleResult && $articleResult->RecordCount() > 0 ) {
        $v['data'] = $articleResult->FetchRow();
        $filterReg = '/[^-a-zA-Z0-9_ !()+\=.,:;\/]/';
				global $psMetaGenerator_title, $psMetaGenerator_description, $psMetaGenerator_og_title, $psMetaGenerator_og_description, $psMetaGenerator_og_image;
        $psMetaGenerator_title = $psMetaGenerator_og_title = preg_replace( '/[^-a-zA-Z0-9_ !()+\=.,:;\/]/', '', $v['data']['heading'] );
        $psMetaGenerator_og_title = "Horsezone News: $psMetaGenerator_og_title";
        $psMetaGenerator_description = $psMetaGenerator_og_description = preg_replace( $filterReg, '', $v['data']['preview'] );
        $psMetaGenerator_og_image = "http://" . $_SERVER['SERVER_NAME'].$v['data']['thumb'];

				// Save comment
				if( isset($_REQUEST['submit']) && isset($_REQUEST['comment']) ) {
					if( $v['userId'] < 1 ) {
						$v['messages'][] = "Unable to retreive login information. Please login in try again.";
					} else {
						$comment = html_entity_decode($_REQUEST['comment'], ENT_QUOTES, "UTF-8" ); 
						$comment = $this->stripTags( $comment );
						$comment = preg_replace('/\n/', '<br />', $comment );
						if( strlen($comment) < 5 ) {
							$v['messages'][] = "No comment was made - a minumum of 5 characters is required.";
						}else {
							
							// check for duplicate
							$duplicateResult = $db->Execute( sprintf(  "SELECT * FROM `petsplease_news_comments` WHERE `comment` = '%s' " ,
															mysql_real_escape_string( $comment ) ));
							if( $duplicateResult && $duplicateResult->RecordCount() > 0 ){
								$v['messages'][] = "Duplicate comment - please revise.";	
							}else {
								$commentResult = $db->Execute( sprintf( "INSERT INTO `petsplease_news_comments` ( `user_id`, `user_name`, `article_id`, `comment`, `created` ) VALUES ( '%d', '%s', '%d', '%s', '%d' )" ,
															$v['userId'],
															mysql_real_escape_string( $v['userName'] ),
															$v['data']['id'],
															mysql_real_escape_string($comment),
															time() ));
								if( $commentResult ) {
									$v['messages'][] = "Your comment was made successfully.";	
								} else {
									$v['messages'][] = "An error occurred trying to add your comment.";	
								}
							}
						}
					}					
				}
				
				// delete comment 
				if( $v['userId'] == 1 && isset($_REQUEST['delComment']) && intval($_REQUEST['delComment']) > 0 ) {
					$delResult = $db->Execute("DELETE FROM `petsplease_news_comments` WHERE `id` = '" . intval($_REQUEST['delComment']) . "'	LIMIT 1");
					if( $delResult ) {
						$v['messages'][] = "Successfully deleted comment.";	
					}else {
						$v['messages'][] = "Error removing comment. Log-out, log back in and try again.";
					}
				}
				// get comments
				$commentsResult = $db->Execute( "SELECT * FROM `petsplease_news_comments` WHERE `article_id` = '" . $v['data']['id'] . "' ORDER BY `created` ");
				if( $commentsResult && $commentsResult->RecordCount() > 0 ) {
					$v['data']['comments'] = $commentsResult->GetArray();	
				}
				$v['currentCategory'] = $v['data']['category_id'] > 0 ? $v['data']['category_id'] : false;
				$v['url'] = "/news/" . $v['data']['hash'] ;
			}			
		}
		
		
		// display requested category
		elseif ( isset($_REQUEST['category']) ) {
			$v['mode'] = 'category';	
			$v['url'] = "/news/" . 	intval( $_REQUEST['category'] );
			// get category hash
			if( !is_numeric( $_REQUEST['category'] ) ) {
				$categoryResult = $db->Execute(sprintf("SELECT `id`, `hash` FROM `petsplease_news_categories` WHERE `hash` LIKE '%s'",
          mysql_real_escape_string( $_REQUEST['category'] ) ));
				if( $categoryResult && $categoryResult->RecordCount() > 0 && $row = $categoryResult->FetchRow() ){
					$category = $row['id'];		
					$v['url'] = "/news/" . $row['hash'] ;				
				}else {
					$v['messages'][] = "Error.";// $db->ErrorMsg();
				}
			}else {
				$category = intval( $_REQUEST['category'] );
			}
			$v['currentCategory'] = $category;
			
			// get the articles
			$articlesResult = $db->Execute(sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM `petsplease_news` WHERE `category` = '%d' AND `status` = '1' ORDER BY `published` DESC, `created` DESC LIMIT %d, %d",
											$category,
											($page < 1) ? 0 : ($page - 1) * $pageLength,
											$pageLength ));
			if( $articlesResult && $articlesResult->RecordCount() > 0 ) {
				$v['data'] = $articlesResult->GetArray();
				$countResult = $db->Execute("SELECT FOUND_ROWS() as `c`");
				if( $countResult && $countResult->RecordCount() && $row = $countResult->FetchRow() ){
					$v['totalRows'] = $row['c'];
				}
				
			}
			
		}
	
		//Search news
		elseif (isset($_REQUEST['search'])) {
			$searchQuery = $_REQUEST['search'];

			$v['mode'] = 'search'; 
			$v['searchQuery'] = $searchQuery;
			
			$sql = sprintf("SELECT SQL_CALC_FOUND_ROWS `n`.*, `c`.`hash` as `category_hash`, `c`.`label` as `category_label`, 
			MATCH (`n`.heading, `n`.stripedarticle) AGAINST ('%s' IN BOOLEAN MODE) AS relevance FROM `petsplease_news` as `n`
								LEFT JOIN `petsplease_news_categories` as `c`
								ON `n`.`category` = `c`.`id`
								 WHERE `n`.`status` = '1' AND MATCH (heading, stripedarticle) AGAINST ('%s' IN BOOLEAN MODE)
								 ORDER BY relevance DESC, `n`.`published` DESC, `n`.`created` DESC LIMIT %d, %d",
											$searchQuery,
											$searchQuery,
											($page < 1) ? 0 : ($page - 1) * $pageLength,
											$pageLength );
			$articlesResult = $db->Execute($sql);
			if( $articlesResult && $articlesResult->RecordCount() > 0 ) {
				$v['data'] = $articlesResult->GetArray();
				$countResult = $db->Execute("SELECT FOUND_ROWS() as `c`");
				if( $countResult && $countResult->RecordCount() && $row = $countResult->FetchRow() ){
					$v['totalRows'] = $row['c'];
				}
			}else {
				error_log( $db->ErrorMsg() );	
			}
		}


		
		// display home news page
		else {
			// get the articles
			$v['mode'] = 'home';
			$sql = sprintf("SELECT SQL_CALC_FOUND_ROWS `n`.*, `c`.`hash` as `category_hash`, `c`.`label` as `category_label` FROM `petsplease_news` as `n`
								LEFT JOIN `petsplease_news_categories` as `c`
								ON `n`.`category` = `c`.`id`
								 WHERE `n`.`status` = '1' ORDER BY `n`.`published` DESC, `n`.`created` DESC LIMIT %d, %d",
											($page < 1) ? 0 : ($page - 1) * $pageLength,
											$pageLength );
			$articlesResult = $db->Execute($sql);
			if( $articlesResult && $articlesResult->RecordCount() > 0 ) {
				$v['data'] = $articlesResult->GetArray();
				$countResult = $db->Execute("SELECT FOUND_ROWS() as `c`");
				if( $countResult && $countResult->RecordCount() && $row = $countResult->FetchRow() ){
					$v['totalRows'] = $row['c'];
				}
			}else {
				error_log( $db->ErrorMsg() );	
			}
		}




		if( empty($v['data']) ) {
			$v['messages'][] = "No articles found.";	
		}
		
			
		// generate pagination data
		if( isset( $v['totalRows'] ) ) {
			$v['pagination'] = array(
				'page' => $page,
				'total' => ceil( $v['totalRows']  / $pageLength ),
				'ellipse_upper' => ceil( $v['totalRows']  / $pageLength ) - 6,
				'range' => range($page - 4, $page + 4)
			);

			// $v['pagination'] = array( 
			// 	'pages' => range( 1, ceil( $v['totalRows']  / $pageLength ) ),
			// 	'page' => $page
			// );	
			
		}
		
		// go get them categories!
		$categoriesResult = $db->Execute("SELECT * FROM `petsplease_news_categories` ORDER BY `order`");
		if( $categoriesResult && $categoriesResult->RecordCount() > 0 ) {
			$v['categories'] = $categoriesResult->GetArray();	
		}
		
		
		
		// $v gets set as it's keys, so $v['mode'] will be $mode in the tpl
		geoView::getInstance()->setBodyTpl('news.tpl','ppNews')->setBodyVar($v);
		
			
	}
	
	public function latestList() {
		 geoView::getInstance()->setRendered(true);
		 $db = DataAccess::getInstance();
		// get latest news and echo
		
	}
	
	private function stripTags($text) {
			$text = preg_replace(
			array(
			  // Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
			  // Add line breaks before and after blocks
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text );
		return strip_tags( $text );

	}
	
}
