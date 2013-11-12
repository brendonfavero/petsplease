<?php
class addon_ppListingImagesExtra_tags extends addon_ppListingImagesExtra_info
{
	/* Show scrolling banner ads against listing */
	public function listingBannerImages($params, Smarty_Internal_Template $smarty) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $params['listing_id'];

		$listing = geoListing::getListing($listing_id);

		$category = $listing->category;

		require_once(CLASSES_DIR.'order_items/adimages.php');

		if (!in_array($category, adimagesOrderItem::$allowedCategories)) {
			return '';
		}

		$sql = "SELECT * FROM `petsplease_classifieds_extraimages_urls` WHERE `type_id` = 1 AND classified_id = " . $listing_id;
		$result = $db->GetAll($sql);

		if (!$result || count($result) == 0) {
			return '';
		}

		$tpl_vars = array();
		$tpl_vars['images'] = $result;

		return geoTemplate::loadInternalTemplate($params, $smarty, 'listingBannerImages.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);		
	}
}
?>