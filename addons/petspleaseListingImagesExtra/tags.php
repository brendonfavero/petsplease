<?php
class addon_petspleaseListingImagesExtra_tags extends addon_petspleaseListingImagesExtra_info
{
	/* Show scrolling banner ads against listing */
	public function listingBannerImages($params, Smarty_Internal_Template $smarty) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $params['listing_id'];

		return geoTemplate::loadInternalTemplate($params, $smarty, 'listingBannerImages.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);		
	}
}
?>