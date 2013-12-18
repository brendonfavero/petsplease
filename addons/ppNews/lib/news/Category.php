<?php


class Category {
   
   private  $db;
   public  $messages = array();
     
   public function __construct($db) {
	  $this->db = $db;   
   }
   
   public function run( $action, $postVars ) {
	   switch( $action ) {
			case 'add':
					if( !empty($postVars['label']) ) {
						return $this->add( $postVars['label'] ) ;   
					}else {
						$this->message('Label missing');	
					}
				break;
			
			case 'edit':
					if( !empty($postVars['id'])  &&  !empty($postVars['label'])) {
						return $this->edit( $postVars['id'], $postVars['label'] ) ;   
					}else {
						$this->message('Identifier or label missing');	
					}	
				break;
			case 'delete':
					if( !empty($postVars['id']) ) {
						return $this->delete( $postVars['id'] ) ;   
					}else {
						$this->message('Identifier missing');	
					}	
				break;	
				
			case 'reorder':
					if( !empty($postVars['order']) ) {
						return $this->reorder( $postVars['order'] ) ;   
					}else {
						$this->message('Order missing');	
					}	
				break;	
	   }
		return false;
   }
   
   public function getAll() {
	   $result = $this->db->Execute('SELECT * FROM `petsplease_news_categories` ORDER BY `order`  ');
	   if( $result ) {
		   return $result->GetArray();   
	   }
	   return array();
   }
   
   public function getPublic() {
	   $result = $this->db->Execute('SELECT * FROM `petsplease_news_categories` WHERE `id` > 0  ORDER BY `order` ');
	   if( $result ) {
		   return $result->GetArray();   
	   }
	   return array();
   }
   
   public function add( $label ) {
	    $hash = preg_replace('/[^a-zA-Z0-9\']+/', '-', $label);
  	    $hash = strtolower( preg_replace('/-+$|\'/', '', $hash ) );
	    $result = $this->db->Execute(sprintf( "INSERT INTO  `petsplease_news_categories` (`label`, `hash`, `order` ) VALUES ('%s', '%s', '1' )",
							mysql_real_escape_String($label),
							mysql_real_escape_string($hash)));
	   if( $result ) {
		   return $this->db->Insert_ID();
	   }
	   return false;
   }
   
   public function delete( $id ) {
	   $id = intval($id);
	   if( $id > 0 ) {
		    $result = $this->db->Execute("DELETE FROM `petsplease_news_categories` WHERE `id` =  '" . $id . "' LIMIT 1");
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }
   }
   
   public function edit( $id, $label ) {
	   $id = intval($id);
	   if( $id > 0 && strlen($label) > 0) {
			 $hash = preg_replace('/[^a-zA-Z0-9\']+/', '-', $label);
  			 $hash = strtolower( preg_replace('/-+$|\'/', '', $hash ) );
		    $result = $this->db->Execute(sprintf( "UPDATE `petsplease_news_categories` SET `label` = '%s', `hash` = '%s' WHERE `id` = '%d'",
							mysql_real_escape_string($label),
							mysql_real_escape_string($hash),
							$id ));
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }else {
		   $this->message('Id or Label mis-match');   
	   }
   }
   
   public function move( $id, $category ) {
	    $id = intval($id);
		$category = intval($category);
	   if( $id > 0) {
		    $result = $this->db->Execute(sprintf( "UPDATE `petsplease_news_categories` SET `category` = '%d' WHERE `id` = '%d'",
							$category,
							$id ));
		   if( $result ) {
			   return true;
		   }
		   return false;
	   }else {
		   $this->message('ID or category missing');   
	   }
   }
   
   public function reorder( $hash ) {
	   $ids = explode(',', $hash);
	   $order = 2;
	   foreach( $ids as $id ) {
			$id = intval($id);
			$result = $this->db->Execute("UPDATE `petsplease_news_categories` SET `order` = '" . $order . "' WHERE `id` = '" . $id . "'"); 
			$order++;
			if( !$result ) {
			  	$this->message( ' error changing order for item id ' . $id );
			}
	   }
	   return true;
   }
   
   public function message( $message ) {
	   $this->messages[] = $message;
   }
   
  

}
?>