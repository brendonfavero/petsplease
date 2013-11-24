<?php
class addon_ppStoreSeller_info
{
	public $name = 'ppStoreSeller';
	public $version = '1.0.0';
	public $title = 'Pets Please Store Seller Process';
	public $core_version_minimum = '7.1.0';
	public $author = "Ardex Technology";
	public $description = 'Pets Please Store Seller Process';
	public $auth_tag = 'pp_addons';

	public $tags = array (
	);

	public $pages = array(
		'buyNow',
		'ipnNotify',
		'merchantCart',
		'checkout'
	);

	public $core_events = array(
		// 'Search_classifieds_generate_query',
	);
}
?>