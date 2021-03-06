<?php
class addon_ppListingImagesExtra_info
{
	public $name = 'ppListingImagesExtra';
	public $version = '1.0.0';
	public $title = 'Pets Please Listing Images Extra';
	public $core_version_minimum = '7.1.0';
	public $author = "Ardex Technology";
	public $description = 'This addon is responsible for the extra images (user ads, user logo) under certain listing types (e.g. services)';
	public $auth_tag = 'pp_addons';

	public $tags = array(
		'listingLogoThumb'
	);

	public $listing_tags = array (
		'listingBannerImages',
		'listingLogoImage',
		'listingLogoThumb'
	);
}
?>