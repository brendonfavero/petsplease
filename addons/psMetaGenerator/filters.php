<?php

/**
 * MetaGenerator Filters
 * returns template data for specific pids
 */

class addon_psMetaGenerator_filters {

  private $db;

  public function __construct() {
    $this->db = DataAccess::getInstance();
  }

  // Sellers other listings page - returns [ USERNAME ]
  public function pid_55 () {
    $seller = intval( isset( $_GET['b'] ) ? $_GET['b'] : 0 );
    if ( $seller ) {
      $sellerResult = $this->db->Execute( "SELECT `username`, `id` as `user_id`, CONCAT( `firstname`, ' ', `lastname` ) as `name`
        FROM `geodesic_userdata` WHERE `id` = '$seller'" );
      if ( $sellerResult && $sellerResult->RecordCount() && $row = $sellerResult->FetchRow() ) {
        return $row;
      }
    }
    return array();
  }
}

