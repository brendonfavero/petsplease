<?php
class addon_ppSearch_info
{
	public $name = 'ppSearch';
	public $version = '1.0.0';
	public $title = 'Pets Please Search Helper';
	public $core_version_minimum = '7.1.0';
	public $author = "Ardex Technology";
	public $description = 'Pets Please Search Helper';
	public $auth_tag = 'pp_addons';

	public $tags = array (
		'searchSidebar'
	);

	public $core_events = array(
		'Search_classifieds_generate_query',
	);
}
?>