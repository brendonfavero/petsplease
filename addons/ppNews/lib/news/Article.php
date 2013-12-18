<?php


class Article {
   
   private  $db;
   public $messages = array();
   public $admin = false;
     
   public function __construct($db) {
	  $this->db = $db;   
   }
   
   public function run( $action, $postVars ) {
	   switch( $action ) {
			case 'add':
					return $this->add( "Untitled Article", 
						( isset($postVars['category']) ? $postVars['category'] : 0 ) ) ;   
				break;
			
			case 'edit':
					if( !empty($postVars['id'])  && !empty($postVars['heading'])  && !empty($postVars['article'])) {
						return $this->edit( $postVars['id'], $postVars['heading'], $postVars['article'] ) ;   
					}else {
						$this->message('Identifier or data missing');	
					}	
				break;
			case 'remove':
					if( !empty($postVars['id']) ) {
						return $this->remove( $postVars['id'] ) ;   
					}else {
						$this->message('Identifier missing');	
					}	
				break;	
				
			case 'enable':
					if( !empty($postVars['id']) ) {
						return $this->enable( $postVars['id'] ) ;   
					}else {
						$this->message('Identifier missing');	
					}	
				break;
				
			case 'disable':
					if( !empty($postVars['id']) ) {
						return $this->disable( $postVars['id'] ) ;   
					}else {
						$this->message('Identifier missing');	
					}	
				break;
			case 'move' :
					if( isset( $postVars['id'] ) &&  isset( $postVars['category'] ) ) {
						return $this->move( $postVars );
					}else {
						$this->message( 'ID  or Category missing');	
					}
				break;
			case 'get':
					if( isset( $postVars['category'] ) ) {
						if( $this->admin ) {
							return $this->getAll($postVars['category']);
						}else {
							return $this->getPublic($postVars['category']);
						}
					}elseif ( isset( $postVars['id'] ) ) {
						return $this->getOne( $postVars['id'] );
					}else {
						$this->message( 'Category ID missing' );	
					}
				break;
			case 'save':
					if( isset( $postVars['id'] ) ) {
						return $this->save( $postVars );
					}else {
						$this->message( 'ID missing');	
					}
				break;
			
	   }
		return false;
   }
   
   public function getAll($category) {
	   $category = intval($category);
	   $result = $this->db->Execute("SELECT `id`, `category`, `heading`, `hash`, `created`, `modified`, `status`, `published`, `publishedtime`, `locale` FROM `petsplease_news` WHERE `category` =  '" . $category . "' ORDER BY `published` DESC, `created` DESC ");
	   if( $result ) {
		   return $result->GetArray();   
	   }
	   return array();
   }
   
   public function getPublic() {
	   $result = $this->db->Execute("SELECT `id`, `category`, `heading`, `hash`, `preview`, `thumb`, `commentsCount`, `created`, `modified`, `status`, `published`, `publishedtime`, `locale` FROM `petsplease_news` ORDER BY `published` DESC, `created` DESC  WHERE `status` > 0");
	   if( $result ) {
		   return $result->GetArray();   
	   }
	   return array();
   }
   
   public function add( $heading, $category) {
	    $result = $this->db->Execute(sprintf( "INSERT INTO  `petsplease_news` (`heading`,  `status`, `created`, `modified`, `category`, `published` ) VALUES ('%s', '0', '%d', '%d', '%d', '%d')",
							mysql_real_escape_string($heading),							
							time(),
							time(),
							$category,
							time()));
	   if( $result ) {
		   return $this->getOne( $this->db->Insert_ID() );
	   }
	   return false;
   }
   
   public function getOne( $id ) {
		$result = $this->db->Execute("SELECT * FROM `petsplease_news` WHERE `id` = '" . intval( $id ) . "'");
		if( $result && $result->RecordCount() ) {
			$data = $result->FetchRow() ;
			$data['raw'] = $this->cp1252_to_utf8($data['raw']);
			return array($data);
		}else {
			return false;	
		}
   }
   
   public function addFile( $file, $id ) {
      // get current list
      $filesResult = $this->db->Execute( "SELECT `files` FROM `petsplease_news` WHERE `id` = '" . intval( $id ) . "'");
      if ( $filesResult && $filesResult->RecordCount() ) {
        $files = $filesResult->FetchRow();
        $files = strlen( $files['files'] ) > 0 ? json_decode( $files['files'], true ) : array();
        $files[] = $file;
        $saveResult = $this->db->Execute( "UPDATE `petsplease_news` SET `files` = '" . json_encode( $files ) . "' WHERE `id` = '" . intval( $id ) . "'");
        if ( $saveResult ) {
          return true;
        }else {
          return "error saving";
        }
      }else {
        return "error selecting";
      }
      return false;
   }

    public function removeFile( $file, $id ) {
      // get current list
      $filesResult = $this->db->Execute( "SELECT `files` FROM `petsplease_news` WHERE `id` = '" . intval( $id ) . "'");
      if ( $filesResult && $filesResult->RecordCount() ) {
        $files = $filesResult->FetchRow();
        if ( strlen( $files['files'] ) > 0  && $files = json_decode( $files['files'], true ) ) {
          $newFiles = array();
          foreach ( $files as $f ) {
            if ( $f != $file ) {
              $newFiles[] = $f;
            }
          }
        }
        $saveResult = $this->db->Execute( "UPDATE `petsplease_news` SET `files` = '" . json_encode( $newFiles ) . "' WHERE `id` = '" . intval( $id ) . "'");
        if ( $saveResult ) {
          return true;
        } else {
          return "error saving for $file and id: $id";
        }
      } else {
          return "error selecting for $file and id: $id";
      }
      return false;
   }

   
   public function remove( $id ) {
	   $id = intval($id);
	   if( $id > 0 ) {
		    $result = $this->db->Execute("DELETE FROM `petsplease_news` WHERE `id` =  '" . $id . "' LIMIT 1");
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }
   }
   
   public function edit( $id, $heading, $article ) {
	   $id = intval($id);

	   $articleToInsert = mysql_real_escape_string($article);
	   $strippedArticle = str_replace('><', '> <', $articleToInsert);
	   $strippedArticle = strip_tags($strippedArticle);

	   if( $id > 0 && strlen($label) > 0) {
		    $result = $this->db->Execute(sprintf( "UPDATE `petsplease_news` SET `heading` = '%s', `article` = '%s', `stripedarticle` = '%s' , `modified` = '%d' WHERE `id` = '%d'",
							mysql_real_escape_string($heading),
							$article,
							$strippedArticle,
							time(),
							$id ));
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }else {
		   $this->message('Id or Label mis-match');   
	   }
   }
   
   public function save($vars) {
		$columnsResult = $this->db->Execute("DESCRIBE `petsplease_news`");
		
		//article
		$vars['article'] = $vars['thumb'] = "";
		if( isset($vars['raw']) ){
			list( $vars['preview'], $vars['article']) = $this->getPreview( $vars['raw'] );
			$vars['thumb'] = $this->getThumb($vars['raw']);
			$this->message(" um, tried it" );
		}else {
			$this->message( "no article" );	
		}
		
		if( isset($vars['heading']) ) {
			 $vars['hash'] = preg_replace('/[^a-zA-Z0-9\']+/', '-', $vars['heading'] );
  			 $vars['hash'] = strtolower( preg_replace('/-+$|\'/', '', $vars['hash'] ) ) . "-" . $vars['id'];
			 	
		}

		$prepedStripArticle = str_replace('><', '> <', $vars['article']);
		$vars['stripedarticle'] = strip_tags($prepedStripArticle);
		
		if( $columnsResult ) {
			$columns = $columnsResult->GetArray();
			$insertValues = array();
			$insertKeys = array();
			foreach($columns as $column){
				if( $column['Field'] != "id" && isset($vars[$column['Field']]) ){
					$insertKeys[] = "`".$column['Field']."` = ?";
					$insertValues[$column['Field']] = $vars[$column['Field']];
				}									
			}
			$sql = 	"UPDATE `petsplease_news` SET " . implode(", ",$insertKeys) . " WHERE `id` = '" . $vars['id'] . "'";
			$this->message($sql);
			$insertResult = $this->db->Execute($sql, $insertValues);
			if( $insertResult ) {
				return true;
			}				
		}
		else {
			$this->message("Something bad happened. Contact Support.");
		}   
		return false;
   }
   
   public function move($vars) {
		    $result = $this->db->Execute(sprintf( "UPDATE `petsplease_news` SET `category` = '%d' WHERE `id` = '%d'",
							mysql_real_escape_string($vars['category']),
							mysql_real_escape_string($vars['id'])
							));
		   if( $result ) {
			   return true;
		   }
		   return false;
   }
   
   public function enable( $id ) {
	   $id = intval($id);
	   if( $id > 0 ) {
		    $result = $this->db->Execute("UPDATE `petsplease_news` SET `status` = '1' WHERE `id` =  '" . $id . "'");
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }
   }
   public function disable ($id ) {
	    $id = intval($id);
	   if( $id > 0 ) {
		    $result = $this->db->Execute("UPDATE `petsplease_news` SET `status` = '0' WHERE `id` =  '" . $id . "'");
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }
   }
   
   public function message( $message ) {
	   $this->messages[] = $message;
   }
   
   private function getPreview($text, $autoLength = 180) {
		$allowedTags = '';
		
		$previewText = trim(strip_tags( $text, $allowedTags ));
		$previewText = preg_replace( '/(&nbsp;\s?|\s)+/', ' ', $previewText );
	
		$start = strpos( $previewText, "[PREVIEW]");
		$start = ($start === false) ? 0 : $start + 9;
		$end = strpos( $previewText, "[/PREVIEW]");
		$preview = substr( $previewText,$start, $end - $start ) . "...";
		
		$text = str_replace(array( "[PREVIEW]", "[/PREVIEW]"), "",  $text);
		
		// auto preview
		if( !$end ){
			if( strlen($previewText) < $autoLength + 10 ) {
				$preview = $previewText;	
			}else {
				$end = strpos( $previewText, ' ', $autoLength);			
				$end = ( $end - $autoLength > 10 ) ? $autoLength : $end;
				$preview = substr( $previewText, 0, $end ) . "...";
			}
			
		}
		
		return array( $preview, $text );
   }
   
  private function getThumb($text) {
		$classLoc = strpos($text, 'class="preview');
		$src = "";
		$offset = false;
	
		if( !$classLoc ) {
			$imgTagLoc = strpos($text, "<img");
			if( $imgTagLoc !== false) {
				$srcLoc = strpos($text, 'src="', $imgTagLoc) + 5;
				$endSrcLoc = strpos($text, '"', $srcLoc);
				$src = substr( $text, $srcLoc, $endSrcLoc-$srcLoc);		
			}else {
				// there ain't no img tag, y'all!
				return "";
			}
		}else {
			$srcLoc = strrpos($text, 'src="', $classLoc - strlen($text) ) + 5;
			$src = substr( $text, $srcLoc , $classLoc - $srcLoc -2 );
		}
		if( strpos($src, "_tn.") < 1 ) { 
			$srcPieces = explode(".", $src);
			$srcPieces[ count($srcPieces) - 2 ] .= "_tn";
			return implode(".", $srcPieces);
		}
		
	 	return $src;	
	}
	
public function cp1252_to_utf8($str) {
	 $cp1252_map = array(
    "\xc2\x80" => "\xe2\x82\xac", /* EURO SIGN */
    "\xc2\x82" => "\xe2\x80\x9a", /* SINGLE LOW-9 QUOTATION MARK */
    "\xc2\x83" => "\xc6\x92",     /* LATIN SMALL LETTER F WITH HOOK */
    "\xc2\x84" => "\xe2\x80\x9e", /* DOUBLE LOW-9 QUOTATION MARK */
    "\xc2\x85" => "\xe2\x80\xa6", /* HORIZONTAL ELLIPSIS */
    "\xc2\x86" => "\xe2\x80\xa0", /* DAGGER */
    "\xc2\x87" => "\xe2\x80\xa1", /* DOUBLE DAGGER */
    "\xc2\x88" => "\xcb\x86",     /* MODIFIER LETTER CIRCUMFLEX ACCENT */
    "\xc2\x89" => "\xe2\x80\xb0", /* PER MILLE SIGN */
    "\xc2\x8a" => "\xc5\xa0",     /* LATIN CAPITAL LETTER S WITH CARON */
    "\xc2\x8b" => "\xe2\x80\xb9", /* SINGLE LEFT-POINTING ANGLE QUOTATION */
    "\xc2\x8c" => "\xc5\x92",     /* LATIN CAPITAL LIGATURE OE */
    "\xc2\x8e" => "\xc5\xbd",     /* LATIN CAPITAL LETTER Z WITH CARON */
    "\xc2\x91" => "\xe2\x80\x98", /* LEFT SINGLE QUOTATION MARK */
    "\xc2\x92" => "\xe2\x80\x99", /* RIGHT SINGLE QUOTATION MARK */
    "\xc2\x93" => "\xe2\x80\x9c", /* LEFT DOUBLE QUOTATION MARK */
    "\xc2\x94" => "\xe2\x80\x9d", /* RIGHT DOUBLE QUOTATION MARK */
    "\xc2\x95" => "\xe2\x80\xa2", /* BULLET */
    "\xc2\x96" => "\xe2\x80\x93", /* EN DASH */
    "\xc2\x97" => "\xe2\x80\x94", /* EM DASH */

    "\xc2\x98" => "\xcb\x9c",     /* SMALL TILDE */
    "\xc2\x99" => "\xe2\x84\xa2", /* TRADE MARK SIGN */
    "\xc2\x9a" => "\xc5\xa1",     /* LATIN SMALL LETTER S WITH CARON */
    "\xc2\x9b" => "\xe2\x80\xba", /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
    "\xc2\x9c" => "\xc5\x93",     /* LATIN SMALL LIGATURE OE */
    "\xc2\x9e" => "\xc5\xbe",     /* LATIN SMALL LETTER Z WITH CARON */
    "\xc2\x9f" => "\xc5\xb8"      /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
);

        return  strtr(utf8_encode($str), $cp1252_map);
}
	
   

}

?>
