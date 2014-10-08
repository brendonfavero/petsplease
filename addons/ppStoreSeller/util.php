<?php
class addon_ppStoreSeller_util extends addon_ppStoreSeller_info
{
	const SHOP_CATEGORY = 412;
	const PRODUCT_CATEGORY = 315;
	const SHELTER_CATEGORY = 420;

	public function userHasStoreListing($user_id = 0) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		if ($user_id == 0) {
			$user_id = geoSession::getInstance()->getUserId();
		}

		if ($user_id == 0) return false;

		$sql = "SELECT COUNT(*) FROM geodesic_classifieds WHERE seller = ? AND category = ? AND live = 1";
		$result = $db->GetOne($sql, array($user_id, self::SHOP_CATEGORY));

		return $result > 0;
	}

	public function listingIsValidStoreProduct($listing_id, $include_classifieds = false) {
		$listing = geoListing::getListing($listing_id);
		
		// Check if the listing falls under product category
		$categories = geoCategory::getTree($listing->category);
		
        $top_category = reset($categories);
		if ($top_category['category_id'] != self::PRODUCT_CATEGORY) {
			return false;
		}

		// Check if the listing merchant product field is 1 (optional_field_1)
		if ($listing->optional_field_2 < 1 && !$include_classifieds) { 
			return false;
		}

		// Check if product is connected to an active store
		$seller_id = $listing->seller;
		if (!$this->userHasStoreListing($seller_id)) {
			return false;
		}

		return true;
	}

	public function getUserStoreListing($user_id) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$sql = "SELECT * FROM geodesic_classifieds WHERE seller = ? AND category = ? AND live = 1";
		$result = $db->GetRow($sql, array($user_id, self::SHOP_CATEGORY));

		if ($result) {
			geoListing::addListingData($result);
			return geoListing::getListing($result['id']);
		}

		return false;
	}

	public function core_filter_listing_placement_category_query($query)
	{
		// Enforce one listing per category rule for Shop and Shelter categories
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$catTbl = geoTables::categories_table;
		$user_id = geoSession::getInstance()->getUserId();
		$limited = array(self::SHELTER_CATEGORY, self::SHOP_CATEGORY);		

		if (!empty($limited)) {
			$in_limited = '(' . implode(",", $limited) . ')';

			$sql = "SELECT category FROM geodesic_classifieds WHERE seller = ? AND category in ".$in_limited." AND live = 1";
			$result = $db->GetCol($sql, array($user_id));

			if (!empty($result)) {
				$in_cats = '(' . implode(",", $result) . ')';
				$query->where("$catTbl.category_id not in ".$in_cats);
			}
		}
	}
}
?>