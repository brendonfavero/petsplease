<?php
class addon_ppListingDisplay_util extends addon_ppListingDisplay_info
{
	public function core_notify_Display_ad_display_classified_after_vars_set ($vars)
	{ // $vars = {id, return, preview, autoDisplay}
		// Allow the listing template to access the listings category tree
		$view = geoView::getInstance();

		$listing = geoListing::getListing($vars['id']);
		$categories = geoCategory::getTree($listing->category);
		$firstcat = reset($categories);
		$secondcat = next($categories);

		$view->topcategory = $firstcat['category_id'];
		$view->subcategory = $secondcat['category_id'];
	}

	public function getUsersSpecialListings($user_id, $topcategory, $firstOnly = false) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		if ($user_id == 0) return false;

		$sql = "SELECT * FROM geodesic_classifieds WHERE seller = ? AND category = ? AND live = 1";
		if ($firstOnly) {
			$result = $db->GetRow($sql, array($user_id, $topcategory));
		}
		else {
			$result = $db->GetAll($sql, array($user_id, $topcategory));
		}

		if (!$result || empty($result))
			return null;

		return $result;
	}
    
    public function getUsersOtherListings($user_id, $listing_id) {
        $db = true;
        require (GEO_BASE_DIR."get_common_vars.php");

        if ($user_id == 0) return false;

        $sql = "SELECT c.id, c.title, cat.category_name, iu.thumb_url, c.price FROM petsplease.geodesic_classifieds c 
                    JOIN geodesic_categories cat ON c.category = cat.category_id
                    JOIN geodesic_classifieds_images_urls iu ON c.id = iu.classified_id
                    WHERE seller = ? AND live = 1 AND iu.display_order = 1 AND c.id != ?
                    ORDER BY RAND() LIMIT 3";
        $result = $db->GetAll($sql, array($user_id, $listing_id));
        
        if (!$result || empty($result))
            return null;

        return $result;
    }

	public function getLeadImageDataForListing($listing_id) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		if (!($listing_id > 0)) 
			return false;

		$sql = "SELECT * FROM geodesic_classifieds_images_urls WHERE classified_id = ? AND display_order = 1";
		$result = $db->GetRow($sql, array($listing_id));

		if ($result && $result['image_id'] && $result['image_id'] > 0) {
			return $result;
		}
		else {
			return false;
		}
	}
}