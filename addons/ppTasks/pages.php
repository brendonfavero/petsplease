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
                            "Your Featured Upgrade Has Expired", 
                            $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');
                            
                        geoEmail::sendMail( 
                            'brendon@ardex.com.au', 
                            "Your Featured Upgrade Has Expired", 
                            $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');
                    }
                }
            }
        }
         
        $result = $db->Execute("UPDATE `geodesic_classifieds` SET `featured_ad` = '0' WHERE ( `last_featured` + 2592000 ) < " . time() );
        if ( !$result )
            error_log( 'failure to un-feature listings' );
        
        return false;
    }

    public function dailyUpdates() {
        $this->featured_update();

        $this->day20Notify();

        $this->day50Notify();

        $this->day111Notify();

        $this->day119Notify();
        
        $this->incompleteListingNotify();
    }
    
    private function day20Notify() {
        $db = DataAccess::getInstance();

        /*
            Get all listings that have been live for at least 20 - 22 days and haven't already been sent a day 20 email
            and have never been featured
        */
        echo "Running day 20 check on " . date('r') . '<br />';

        $sql = "SELECT c.id listing_id, u.id seller_id, u.firstname, u.email, c.title ,
                       (SELECT thumb_url FROM geodesic_classifieds_images_urls i WHERE c.id = i.classified_id LIMIT 1) as thumb_url
                  FROM geodesic_classifieds c
                  JOIN geodesic_categories cat ON c.category = cat.category_id
                  JOIN geodesic_userdata u ON c.seller = u.id   
             LEFT JOIN ardex_emails_audit aud ON c.id = aud.listing_id AND aud.type = '20day'
                 WHERE aud.id IS NULL AND c.live = 1 AND c.last_featured IS NOT NULL 
                   AND sold_displayed = 0 
                   AND c.date + 1728000 < " . time() . " AND c.date + 1900800 > " . time();

        $res = $db->Execute($sql);

        echo $res->RecordCount() . " records found<br />";

        if ($res && $res->RecordCount() > 0) {
            while ($row = $res->FetchRow()) {
                $emailMessage = new geoTemplate('addon','ppTasks');
                $emailMessage->assign('firstname', $row['firstname']);
                $message =  $emailMessage->fetch('emailBody_20daynotify.tpl');

                geoEmail::sendMail( 
                    $row['email'], 
                    "Pets Please Listing - " . urldecode($row['title']), 
                    $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');

                $this->insertEmailAudit($row['email'], '20day', $row['seller_id'], $row['listing_id']);
            }
        }
    }
    
    private function day50Notify() {
        $db = DataAccess::getInstance();

        /*
            Get all listings that have been live for at least 50 - 57 days and haven't already been sent a day 50 email
        */
        echo "Running day 50 check on " . date('r') . '<br />';

        $sql = "SELECT c.id listing_id, u.id seller_id, u.firstname, u.email, c.title, cat.category_name,
                       (SELECT thumb_url FROM geodesic_classifieds_images_urls i WHERE c.id = i.classified_id LIMIT 1) as thumb_url
                FROM geodesic_classifieds c
                JOIN geodesic_userdata u ON c.seller = u.id 
                JOIN geodesic_categories cat ON c.category = cat.category_id
                LEFT JOIN ardex_emails_audit aud ON c.id = aud.listing_id AND aud.type = '50day'
                WHERE aud.id IS NULL AND c.live = 1 AND sold_displayed = 0 
                   AND c.date + 4320000 < " . time() . " AND c.date + 4492800 > " . time();

        $res = $db->Execute($sql);

        echo $res->RecordCount() . " records found<br />";

        if ($res && $res->RecordCount() > 0) {
            while ($row = $res->FetchRow()) {
                $emailMessage = new geoTemplate('addon','ppTasks');
                $emailMessage->assign('firstname', $row['firstname']);
                $message =  $emailMessage->fetch('emailBody_50daynotify.tpl');

                geoEmail::sendMail( 
                    $row['email'], 
                    "Have you sold your " . $row['category_name'] . " on petsplease.com.au yet?", 
                    $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');

                $this->insertEmailAudit($row['email'], '50day', $row['seller_id'], $row['listing_id']);
            }
        }
    }
    
    function day119Notify()
    {
        $db = DataAccess::getInstance();

        /*
            Get all listings that have been live for at least 119 - 120 days and haven't already been sent a day 119 email
        */
        echo "Running day 119 check on " . date('r') . '<br />';

        $sql = "SELECT c.id listing_id, u.id seller_id, u.firstname, u.email, c.title,
                       (SELECT thumb_url FROM geodesic_classifieds_images_urls i WHERE c.id = i.classified_id LIMIT 1) as thumb_url
                FROM geodesic_classifieds c
                JOIN geodesic_categories cat ON c.category = cat.category_id
                JOIN geodesic_userdata u ON c.seller = u.id 
                LEFT JOIN ardex_emails_audit aud ON c.id = aud.listing_id AND aud.type in ('119day', 'bulkexpirenotify')
                WHERE aud.id IS NULL AND c.live = 1 AND sold_displayed = 0 
                  AND c.ends - 129600 < " . time();

        $res = $db->Execute($sql);

        echo $res->RecordCount() . " records found<br />";

        if ($res && $res->RecordCount() > 0) {
            while ($row = $res->FetchRow()) {
                $emailMessage = new geoTemplate('addon','ppTasks');

                $hash = md5(sha1($row['listing_id'] . ':' . $row['seller_id'] . ':' . $row['email']));
                
                $emailMessage->assign('firstname', $row['firstname']);
                $emailMessage->assign('listingurl', 'http://petsplease.com.au/index.php?a=2&b=' . $row['listing_id']);
                $emailMessage->assign('listingtitle', urldecode($row['title']));
                $emailMessage->assign('extendurl', 'http://petsplease.com.au/mypetsplease');
                
                $message =  $emailMessage->fetch('emailBody_119daynotify.tpl');

                geoEmail::sendMail( 
                    $row['email'], 
                    "Your listing is due to expire tomorrow", 
                    $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');

                $this->insertEmailAudit($row['email'], '119day', $row['seller_id'], $row['listing_id']);
            }
        }
    }

    private function day111Notify() {
        $db = DataAccess::getInstance();

        /*
            Get all listings that are to end in 7 - 9 day and haven't already been sent a day 111 email
        */
        echo "Running day 111 check on " . date('r') . '<br />';

        $sql = "SELECT c.id, c.seller, u.firstname, u.email, c.title, 
                       (SELECT thumb_url FROM geodesic_classifieds_images_urls i WHERE c.id = i.classified_id LIMIT 1) as thumb_url
                FROM geodesic_classifieds c
                JOIN geodesic_categories cat ON c.category = cat.category_id
                JOIN geodesic_userdata u ON c.seller = u.id 
                LEFT JOIN ardex_emails_audit aud ON c.id = aud.listing_id AND aud.type in ('111day', 'bulkexpirenotify')
                WHERE aud.id IS NULL AND c.live = 1 AND sold_displayed = 0 
                  AND c.ends - 777600 < " . time() . " AND c.ends - 604800 > " . time();

        $res = $db->Execute($sql);

        echo $res->RecordCount() . " records found<br />";

        if ($res && $res->RecordCount() > 0) {
            while ($row = $res->FetchRow()) {
                $emailMessage = new geoTemplate('addon','ppTasks');

                $hash = md5(sha1($row['listing_id'] . ':' . $row['seller_id'] . ':' . $row['email']));

                $emailMessage->assign('firstname', $row['firstname']);
                $emailMessage->assign('listingurl', 'http://petsplease.com.au/index.php?a=2&b=' . $row['id']);
                $emailMessage->assign('listingtitle', urldecode($row['title']));
                $emailMessage->assign('extendurl', 'http://petsplease.com.au/mypetsplease');

                $message =  $emailMessage->fetch('emailBody_111daynotify.tpl');

                geoEmail::sendMail( 
                    $row['email'], 
                    "Your listing is due to expire in 7 days", 
                    $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');

                $this->insertEmailAudit($row['email'], '111day', $row['seller'], $row['id']);
            }
        }
    }

    private function incompleteListingNotify() {
        $db = DataAccess::getInstance();
        
        echo "Running incomplete listing check on " . date('r') . '<br />';
                
        $yesterday = time() - 25640;
        
        $twoDaysAgo = time() - 52200;       
        
        $sql = "SELECT u.firstname, u.id as seller, u.email, c.last_time, c.order_item from geodesic_userdata u 
        join geodesic_cart c on u.id = c.user_id
        join geodesic_order_item oi on c.order_item = oi.id
        WHERE c.last_time between unix_timestamp() - 172800 and unix_timestamp() - 86400 and oi.type = 'classified'";
        
        $results = $db->Execute($sql);
        
        echo $results->RecordCount() . " records found<br />";
        
        if ($results && $results->RecordCount() > 0) {
            while ($row = $results->FetchRow()) {
                $orderitem = geoOrderItem::getOrderItem($row['order_item']);
                $classified_variables = $orderitem->get('session_variables');
                $title = $classified_variables['classified_title'];
                $emailMessage = new geoTemplate('addon','ppTasks');
                
                $emailMessage->assign('firstname', $row['firstname']);
                $emailMessage->assign('title', $title);
                
                $message =  $emailMessage->fetch('emailBody_Incompletenotify.tpl');
                
                geoEmail::sendMail( 
                    $row['email'], 
                    "Incomplete Listing", 
                    $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');     
                    
                $this->insertEmailAudit($row['email'], 'Incomplete', $row['seller'], 0);        
            }
        }
    }

    private function insertEmailAudit($email, $type, $userid, $listingid) {
        $db = DataAccess::getInstance();

        $sql = "INSERT INTO ardex_emails_audit (email, type, date, user_id, listing_id)
                VALUES (?, ?, ?, ?, ?)";
        $db->Execute($sql, array($email, $type, time(), $userid, $listingid));
    }
    
    public function weekly_performance_emails() {
        ob_implicit_flush(true);

        ini_set('memory_limit', '900000000');
        set_time_limit(10800);

        error_reporting(E_ALL);

        $db = DataAccess::getInstance();

        $testSend = isset($_REQUEST['testSend']); //whether or not to actually send out the emails

        $page = $_REQUEST['paginate'];

        if ($page == "") {
            $page = 0;
        }

        $usersSql = "SELECT id as uid, email, firstname, lastname, company_name FROM geodesic_userdata WHERE optional_field_9 < 1 
                     AND id in (SELECT DISTINCT seller FROM geodesic_classifieds WHERE live = 1 AND sold_displayed < 1)";//. ($testSend ? " LIMIT 50" : "");

        echo $usersSql;

        $usersResult = $db->Execute($usersSql);

        echo "Got list of users...<br />";

        $isFirst = true;
        
        if ($usersResult && $usersResult->RecordCount() > 0) {
            while ($user = $usersResult->FetchRow()) {
               
                //Now we have user generate stuff for this users active listings
                $this->weekly_performance_handle_user($user, $testSend, $isFirst);
                
                $isFirst = false;
                
            }

            // if (!$testSend) {
                // $url = $db->get_site_setting( 'classifieds_url' ) . "?a=ap&addon=ampseCustom&page=weekly_performance_emails&paginate=" . ($page + 1)
                // .( empty( $_REQUEST['testSend'] ) ? '' : '&testSend=1' );
            // }

            

        }
    }
    
    public function testCloseListing() {
        geoEmail::sendMail( 
            'brendonfavero@gmail.com', 
            "Your listing has expired", 
            'test',0,0,0,0,'brendon@ardex.com.au');
            
    }
    
    public function weekly_performance_handle_user($user, $testSend, $sendToChris) {
        require_once(dirname(__FILE__).'/lib/jpgraph/src/jpgraph.php');
        require_once(dirname(__FILE__).'/lib/jpgraph/src/jpgraph_line.php');
        
        

        $db = DataAccess::getInstance();

        //last week
        $cutOff = mktime( 23, 59, 59, date("n"),  date("j")-1, date("Y") );

        //graph ticks - basically the seconds mark per day and label
        $ticks = array();
        for( $i = ( $cutOff - 3600); $i > ($cutOff - 604800); $i -= 86400 ) {
            $ticks[] = date('D d', $i);
        }
        $ticks = array_reverse( $ticks );   

        $listingsSql = "SELECT DISTINCT id, title, start_time, end_time, price, featured_ad, 
                        (SELECT thumb_url FROM geodesic_classifieds_images_urls i WHERE c.id = i.classified_id LIMIT 1) as thumb_url
                        FROM geodesic_classifieds c     
                        WHERE seller = ".$user['uid']." AND live = 1 AND sold_displayed < 1";
        $listingsResult = $db->Execute($listingsSql);

        if (!$listingsResult || $listingsResult->RecordCount() == 0) {
            return;
        }
        
        $classifieds = array();
        
        while ($classified = $listingsResult->FetchRow()) {
            
            $cap = 7; // 7 days, 6 hour increments
            $range = 604800 / $cap;
            $map = array();
            $viewMax = 0;
            for( $i = 0; $i < $cap; $i++ ) {
                $map[$i] = 0;
            }
            // go get 'em
            $viewsResult = $db->Execute("SELECT * FROM `petsplease_stats_views` WHERE `classified_id` = '" . $classified['id']. "' AND `viewed` BETWEEN " . ($cutOff - 604800) . " AND " . $cutOff . " ORDER BY `viewed` ");
            
            // build actual stats
            $classified['viewsTotal'] = 0;
            if ($viewsResult) {
                $stats = $viewsResult->GetArray();
                $classified['viewsTotal'] = $viewsResult->RecordCount();
                $numViews = $viewsResult->RecordCount();
                foreach( $stats as $stat ) {
                    $key = floor( (($cutOff - $stat['viewed']) / $range) );
                    $map[$key]++;
                }
            }
            
            $viewsResult = null;
            unset($viewsResult);

            //assign
            $map = array_reverse($map);
            $classified['views'] = $map;

            // Generate View Stats Graph
            $graph = new Graph(401,110);
            $max = max($map);
            $graph->SetScale("intlin",0, $max + ceil($max * .1) );
            $graph->SetTheme( new UniversalTheme);
            $graph->SetMargin(40,40,10,40);
            $graph->SetBox(false);
            $graph->yaxis->HideLine(false);
            $graph->yaxis->HideTicks(false,false);
            $graph->ygrid->SetFill(true,'#FFFFFF@0.5','#FFFFFF@0.5');
            $graph->xaxis->SetTickLabels( $ticks );
            $graph->xaxis->SetLabelMargin(10);
            $graph->yaxis->SetLabelMargin(5);
            $p1 = new LinePlot($map);
            $graph->Add($p1);
            $p1->SetFillGradient('#FFFFFF','#CF1419');
            $p1->SetColor('#CC7C7F');
            $fileName = $classified['id'] . "_" . $cutOff . ".png";
                     
            $graph = null;
            $p1 = null;
            unset($graph);
            unset($p1);

            //referral stats
            $cap = 7; // 7 days, 6 hour increments
            $range = 604800 / $cap;
            $map = array();
            for( $i = 0; $i < $cap; $i++ ) {
                $map[$i] = 0;
            }
            
            // go get 'em
            $referralsResult = $db->Execute("SELECT * FROM `petsplease_stats_forwards` WHERE `classified_id` = '" . $classified['id']. "' AND `sent` BETWEEN " . ($cutOff - 604800) . " AND " . $cutOff . " ORDER BY `sent` ");
            $classified['referralsTotal'] = 0;
            if ($referralsResult) {
                $stats = $referralsResult->GetArray();
                $classified['referralsTotal'] = $referralsResult->RecordCount();
                foreach( $stats as &$stat ) {
                    $key = floor( (($cutOff - $stat['sent']) / $range) );
                    $map[$key]++;
                }
            }
            $referralsResult = null;
            unset($referralsResult);

            $map = array_reverse($map);
            $classified['referrals'] = $map;

            //enquiry stats
            $cap = 7; // 7 days, 6 hour increments
            $range = 604800 / $cap;
            $map = array();
            for( $i = 0; $i < $cap; $i++ ) {
                $map[$i] = 0;
            }
            
            // go get 'em
            $enquiriesResult = $db->Execute("SELECT * FROM `petsplease_stats_enquiries` WHERE `classified_id` = '" . $classified['id']. "' AND `sent` BETWEEN " . ($cutOff - 604800) . " AND " . $cutOff . " ORDER BY `sent` ");
            $classified['enquiriesTotal'] = 0;
            if( $enquiriesResult ) {
                $stats = $enquiriesResult->GetArray();
                $classified['enquiriesTotal'] = $enquiriesResult->RecordCount();
                foreach( $stats as &$stat ) {
                    $key = floor( (($cutOff - $stat['sent']) / $range) );
                    $map[$key]++;
                }
            }
            $enquiriesResult = null;
            unset($enquiriesResult);

            $map = array_reverse($map);
            $classified['enquiries'] = $map;                        

            $classifieds[] = $classified;           
            
        }

        unset($listingsResult);

        $user['classifieds'] = $classifieds;

        $user['date'] = $cutOff;
        $user['dateEnd'] = $cutOff - 604800;
        $user['unsubscribe'] = md5(sha1( "U" . $user['email'] . $user['uid'] ));

        //Now we have a $user object that we can use to generate and send the email
        // error_log( "about to send email to user id:".$user['uid']." email: ".$user['email'] );
        $emailMessage = new geoTemplate('addon','ppTasks');
        $emailMessage->data = $user;
        $message =  $emailMessage->fetch('emailBody_weeklyAlert.tpl');

        
        
        
        
        echo "about to send email to user id:".$user['uid']." email: ".$user['email'] . "<br />";

        if ($testSend) {
            echo '!!debug only<br />';
            $email = geoEmail::sendMail("brendon@ardex.com.au", "Pets Please Weekly Listing Performance Update", $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');
            
        }
        else {
            $email = geoEmail::sendMail( $user['email'] , "Pets Please Weekly Listing Performance Update", $message, 'admin@petsplease.com.au', 'admin@petsplease.com.au', 0, 'text/html');
            // geoEmail::sendMail("brendon@ardex.com.au", "Horsezone Weekly Listing Performance Update", $message);
            error_log("sent email to uid:" . $user['uid'] . " - Email:" . $user['email'] . "\n");
        }

        if ($sendToChris) {
            // geoEmail::sendMail("brendon@ardex.com.au", "Horsezone Weekly Listing Performance Update", $message);
        }
        
        flush();

        $user = null;
    }
       
}