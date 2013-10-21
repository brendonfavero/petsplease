<?php
class addon_ppListingDisplay_util extends addon_ppListingDisplay_info
{
	/**
	 * This is called when display listing details, right after all the listing
	 * template vars are set.  This would be a good place to change/remove a
	 * specific template var, for instance if you did not want to display a 
	 * specific bit of info in certain cases.
	 * 
	 * @param array $vars An associative array of vars
	 * @since Geo Version 4.0.4
	 */
	public function core_notify_Display_ad_display_classified_after_vars_set ($vars)
	{ // $vars = {id, return, preview, autoDisplay}
		// Allow the listing template to access the listings category tree
		$view = geoView::getInstance();

		$listing = geoListing::getListing($vars['id']);
		$categories = geoCategory::getTree($listing->category);
		$firstcat = reset($categories);
		$secondcat = next($categories);

		$view->topcategory = $firstcat['category_id'];
		$view->nextcategory = $secondcat['category_id'];


		// if ($topcat == 308) { // Pets for Sale
		// 	$view->categorytpl = "pets_for_sale.tpl";
		// }
		// elseif ($topcat == 315) {// Pet Products
		// 	$view->category_tpl_text = "Should load pet products category here";
		// }
		// elseif ($topcat == 316) { // Breeders
		// 	$view->category_tpl_text = "Should load pet breeders category here";
		// }
		// elseif ($topcat == 318) { // Services
		// 	$view->category_tpl_text = "Should load pet services category here";
		// }
		// elseif ($topcat == 319) { // Clubs
		// 	$view->category_tpl_text = "Should load pet clubs category here";
		// }
		// elseif ($topcat == 411) { // Accomodation
		// 	$view->category_tpl_text = "Should load pet accomodation category here";
		// }
		// else {
		// 	$view->category_tpl_text = "No template override exists for top level category: " . $topcat;
		// }
	}
}