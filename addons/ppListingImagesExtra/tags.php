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

		require_once('order_items/adimages.php');

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

	public function listingLogoImage($params, Smarty_Internal_Template $smarty) {
		$util = geoAddon::getUtil($this->name);
		return $util->listingLogoImage($params['listing_id']);
	}

	public function listingLogoThumb($params, Smarty_Internal_Template $smarty) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $params['listing_id'];

		$listing = geoListing::getListing($listing_id);

		$category = $listing->category;

		require_once('order_items/adlogo.php');

		if (!in_array($category, adlogoOrderItem::$allowedCategories)) {
			return '';
		}

		$sql = "SELECT * FROM `petsplease_classifieds_extraimages_urls` WHERE `type_id` = 2 AND classified_id = " . $listing_id;
		$result = $db->GetRow($sql);

		if (!$result || count($result) == 0) {
			return '';
		}

		$tpl_vars = array();
		$tpl_vars['logo'] = $result;

		$max_size = 100;

		if ($result['image_width'] >= $result['image_height'] && $result['image_width'] > 100) {
			$tpl_vars['imgwidth'] = 100;
		}
		elseif ($result['image_height'] > $result['image_width'] && $result['image_height'] > 100) {
			$tpl_vars['imgheight'] = 100;
		}

		return geoTemplate::loadInternalTemplate($params, $smarty, 'listingLogoThumb.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);		
	}
}
?>