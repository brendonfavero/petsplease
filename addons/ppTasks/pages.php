<?php
class addon_ppTasks_pages extends addon_ppTasks_info
{
	public function featured_update() {
        $db = DataAccess::getInstance();
        echo "Running featured check on " . date('r') . '<br />';
        
        $featureds = $db->Execute( 
            "SELECT `c`.`id`, `o`.`order_item`, `o`.`val_complex`, `u`.`email`, `u`.`firstname`, `u`.`id` user_id, c.title
               FROM `geodesic_classifieds` as `c` 
               JOIN `geodesic_userdata` as `u` ON `c`.`seller` = `u`.`id`
               JOIN `geodesic_order_item_registry` as `o` ON `c`.`order_item_id` = `o`.`order_item` 
              WHERE `c`.`featured_ad` > 0 AND `o`.`index_key` = 'session_variables' 
                AND (`c`.`last_featured` + 2592000 ) < " . time() );
                
    
        echo $featureds->RecordCount() . " records found to unfeature<br />";

        if ( $featureds && $featureds->RecordCount() > 0 ) {
            while( $row = $featureds->FetchRow() ) {
                if ( !empty( $row[ 'val_complex' ] ) ) {
                    $data = unserialize( urldecode( $row[ 'val_complex' ] ));
                    $data['featured_ad'] = 0;
                    $data = urlencode( serialize( $data ) );

                    $update = $db->Execute( "UPDATE `geodesic_order_item_registry`  SET `val_complex` = '" . $data . "' WHERE `order_item` = '" . $row['order_item'] . "' AND `index_key` = 'session_variables'" );
                    
                    if ( !$update ) {
                        error_log( 'failure to un-feature listing session_variable for ' . $row['id'] );
                    }
                    else {
                        $emailMessage = new geoTemplate('addon','ppTasks');
                        $emailMessage->assign('firstname', $row['firstname']);
                        $emailMessage->assign('listingurl', 'http://petsplease.com.au/index.php?a=2&b=' . $row['id']);
                        $emailMessage->assign('listingtitle', urldecode($row['title']));
                        $message =  $emailMessage->fetch('emailBody_featureExpire.tpl');

                        geoEmail::sendMail( 
                            $row['email'], 
                            "Pets Please Featured Listing Expired", 
                            $message, 'natasha@petsplease.com.au', 'natasha@petsplease.com.au', 0, 'text/html');
                            
                        geoEmail::sendMail( 
                            'brendon@ardex.com.au', 
                            "Pets Please Featured Listing Expired", 
                            $message, 'natasha@petsplease.com.au', 'natasha@petsplease.com.au', 0, 'text/html');
                    }
                }
            }
        }
         
        $result = $db->Execute("UPDATE `geodesic_classifieds` SET `featured_ad` = '0' WHERE ( `last_featured` + 2592000 ) < " . time() );
        if ( !$result )
            error_log( 'failure to un-feature listings' );
        
        return false;
    }
}