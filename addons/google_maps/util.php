<?php
//addons/google_maps/util.php

/**************************************************************************
Addon Created by Geodesic Solutions, LLC
Copyright (c) 2001-2009 Geodesic Solutions, LLC
All rights reserved
http://www.geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########SVN Build Data##########
##                              ##
## This File's Revision:        ##
##  $Rev:: 13459              $ ##
## File last change date:       ##
##  $Date:: 2008-07-17 16:38:#$ ##
##                              ##
##################################

# google_maps Addon
require_once ADDON_DIR . 'google_maps/info.php';

class addon_google_maps_util extends addon_google_maps_info
{
    public $coordinates, $location, $locationLong;
    
    public function core_notify_display_page ()
    {
        //insert stuff into head
        $this->initHead();
    }
    
    public function initHead ()
    {
        $reg = geoAddon::getRegistry($this->name);
        if (!$reg->apikey || (!defined('IN_ADMIN') && $reg->off)) {
            return;
        }
        $view = geoView::getInstance();
        if (!defined('IN_ADMIN') && !$view->classified_id) {
            //id NOT set
            return;
        }
        
        $pre = (defined('IN_ADMIN'))? '../':'';
        $urls[] = '//maps.googleapis.com/maps/api/js?&key=AIzaSyAW1k0SWtfAGbhqIlcy8Gy7XSbCN9Nszbg&sensor=false';
        $urls[] = $pre.'addons/google_maps/maps.js';
        
        $view->addJScript($urls);
        //must have utf8 charset, I think...  Remove this if it's not needed.
        if (!defined('IN_ADMIN')) $view->addTop('<meta http-equiv="content-type" content="text/html; charset=utf-8" />');
    }
    
    private function _getCoodinates()
    {
        $listingId = (int)geoView::getInstance()->classified_id;

        if (isset($this->coordinates)) {
            return $this->coordinates;
        }
        $location = $this->_getLocation();
        
        if (!$location) {
            return;
        }

        //Get cached result if it exists otherwise continue
        $db = 1;
        require GEO_BASE_DIR.'get_common_vars.php';

        $sql = "SELECT * FROM ardex_listingmaps_cache WHERE listing_id = ?";
        $row = $db->GetRow($sql, array((int)$listingId));

        if ($row) {
            //result was found
            if ($row['location'] == urlencode($location)) {
                if ($row['coordinates'] != "") {
                    $this->coordinates = $row['coordinates'];
                }
                return;
            }
            //if locations don't match need to get it for the new location
        }

        if (!function_exists('curl_init')) {
            //not able to do anything w/o curl_init
            return;
        }

        $location = urlencode($location);
        $url = "http://maps.googleapis.com/maps/api/geocode/xml?address=$location&sensor=false&region=au";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //hack to get google to return utf-8 encoded string
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $info = simplexml_load_string($response);
        $code = $info->status;

        if ($code == "ZERO_RESULTS") {
            //couldn't get the coords
            //if not a service issue then log as incorrect
            $sql = "INSERT INTO ardex_listingmaps_cache (listing_id, location) VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE location = ?, coordinates = null";
            $db->Execute($sql, array((int)$listingId, $location, $location));
            return;
        }
        elseif ($code == "OK") {
            $points = $info->result->geometry->location;
            $this->coordinates = $points->lat . ',' . $points->lng;

            //Coordinate successfully retrieved, cache it CACHE IT NOW!
            $sql = "INSERT INTO ardex_listingmaps_cache (listing_id, location, coordinates) VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE location = ?, coordinates = ?";
            $res = $db->Execute($sql, array((int)$listingId, $location, $this->coordinates, $location, $this->coordinates));

            return $this->coordinates;
        }
    }
    
    private function _getLocation()
    {
        if (defined('IN_ADMIN')) {
            $this->location = '3333 California St San Francisco CA 94118';
            $this->locationLong = "<strong>Admin Map Preview Listing Title</strong><br />
            3333 California St<br />
            San Francisco CA 94118<br />
            United States of America";
        }
        if (isset($this->location)) {
            //already got it
            return $this->location;
        }
        
        $reg = geoAddon::getRegistry($this->name);
        if(!$reg->apikey) {
            return;
        }
        $listingId = (int)geoView::getInstance()->classified_id;
        if (!$listingId){
            return;
        }
        
        $listing  = geoListing::getListing($listingId);
        if(!$listing) {
            return;
        }
        
        $zip = (trim($listing->mapping_zip))? ', '.$listing->mapping_zip : '';
        $state = ($listing->mapping_state != 'none')? $listing->mapping_state: '';
        $country = ($listing->mapping_country != 'none')? $listing->mapping_country: '';

        if ($country == "New+Zealand")
        {
            if ($state == "NI") $state = "North+Island";
            else if ($state == "SI") $state = "South+Island";
        }

        $loc = "{$listing->mapping_address} {$listing->mapping_city} {$state}$zip {$country}";
        $loc = geoString::fromDB($loc);
        $this->location = $loc;
        
        $this->locationLong = "<strong>".geoString::fromDB($listing->title)."</strong><br />
            ".geoString::fromDB($listing->mapping_address)."<br />".geoString::fromDB($listing->mapping_city)
            ." ".geoString::fromDB($listing->mapping_state)." ".geoString::fromDB($listing->mapping_zip)
            ."<br />".geoString::fromDB($listing->mapping_country);
        
        return $loc; 
        
    }
    
    /**
     * Gets the HTML necessary for displaying google map for a listing.
     * 
     * @return string
     */
    public function getMap()
    {
        $reg = geoAddon::getRegistry($this->name);
        if(!defined('IN_ADMIN') && $reg->off) {
            return false;
        }
        $this->_getCoodinates();
        if (!$this->coordinates) {
            //something went wrong when getting coords
            return '';
        }
        
        $tpl = new geoTemplate('addon',$this->name);
        $tpl->msgs = geoAddon::getText('geo_addons','google_maps');
        $tpl->location = ($this->locationLong)? $this->locationLong : $this->location;
        $tpl->coords = $this->coordinates;
        $tpl->width = (int)$reg->get('width',600);
        $tpl->height = (int)$reg->get('height',400);
        
        return $tpl->fetch('map.tpl');
    }
    
    public function getMapMini()
    {
        $reg = geoAddon::getRegistry($this->name);
        if(!defined('IN_ADMIN') && $reg->off) {
            return false;
        }
        $this->_getCoodinates();
        if (!$this->coordinates) {
            //something went wrong when getting coords
            return '';
        }
        
        $tpl = new geoTemplate('addon',$this->name);
        $tpl->msgs = geoAddon::getText('geo_addons','google_maps');
        $tpl->location = ($this->locationLong)? $this->locationLong : $this->location;
        $tpl->coords = $this->coordinates;
        $tpl->width = 213;
        $tpl->height = 142;
        
        return $tpl->fetch('map.tpl');
    }
}