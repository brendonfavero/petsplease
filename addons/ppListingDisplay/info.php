<?php
class addon_ppListingDisplay_info
{
	public $name = 'ppListingDisplay';
	public $version = '1.0.0';
	public $title = 'Pets Please Listing Display';
	public $core_version_minimum = '7.1.0';
	public $author = "Ardex Technology";
	public $description = 'Custom listing template overrides';
	public $auth_tag = 'pp_addons';

	public $tags = array (
		'extraMultiCheckboxDisplay',
		'extraMultiCheckboxSelect',
		'extraLeveledMutliCheckboxDisplay',
		'extraLeveledMutliCheckboxSelect',

		'headerImageClass',
		'headerTextClass'
	);

	public $listing_tags = array (
		'extraQuestionValue', 
		'extraCheckboxValue',
		'extraLeveledValue',
		'storeCategories',
		'listingsEmbed',
		'specialListingBox',
		'profileHeader',
		'otherAds'
	);

	public $core_events = array ('notify_Display_ad_display_classified_after_vars_set');
}
?>