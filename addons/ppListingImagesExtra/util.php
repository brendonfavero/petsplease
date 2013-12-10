<?php
class addon_ppListingImagesExtra_util extends addon_ppListingImagesExtra_info
{
	public function listingLogoImage($listing_id) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

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

		$tpl = new geoTemplate('addon', $this->name);
		$tpl->assign($tpl_vars);
		return $tpl->fetch('listingLogoImage.tpl');
	}

	// public function listingLogoThumb($params, Smarty_Internal_Template $smarty) {
	// 	$db = true;
	// 	require (GEO_BASE_DIR."get_common_vars.php");

	// 	$listing_id = $params['listing_id'];

	// 	$listing = geoListing::getListing($listing_id);

	// 	$category = $listing->category;

	// 	require_once(CLASSES_DIR.'order_items/adlogo.php');

	// 	if (!in_array($category, adlogoOrderItem::$allowedCategories)) {
	// 		return '';
	// 	}

	// 	$sql = "SELECT * FROM `petsplease_classifieds_extraimages_urls` WHERE `type_id` = 2 AND classified_id = " . $listing_id;
	// 	$result = $db->GetRow($sql);

	// 	if (!$result || count($result) == 0) {
	// 		return '';
	// 	}

	// 	$tpl_vars = array();
	// 	$tpl_vars['logo'] = $result;

	// 	$max_size = 100;

	// 	if ($result['image_width'] >= $result['image_height'] && $result['image_width'] > 100) {
	// 		$tpl_vars['imgwidth'] = 100;
	// 	}
	// 	elseif ($result['image_height'] > $result['image_width'] && $result['image_height'] > 100) {
	// 		$tpl_vars['imgheight'] = 100;
	// 	}

	// 	return geoTemplate::loadInternalTemplate($params, $smarty, 'listingLogoThumb.tpl',
	// 			geoTemplate::ADDON, $this->name, $tpl_vars);		
	// }

	public function core_notify_geoListing_remove ($vars)
	{
		$db = DataAccess::getInstance();

		//delete url images
		$sql = "SELECT `image_id` FROM petsplease_classifieds_extraimages_urls WHERE `classified_id`=?";
		$get_url_result = $db->Execute($sql, array($vars['listingId']));
		if (!$get_url_result) {
			trigger_error('ERROR SQL: Error getting images, using sql: '.$sql.', Error: '.$db->ErrorMsg());
			return false;
		}
		while ($row = $get_url_result->FetchRow()) {
			//using this way as it takes less resources than something like FetchAll
			$imageId = (int)$row['image_id'];
			if ($imageId) {
				$removeImage = ppExtraImage::remove($imageId);
			}
		}
	}
}
?>