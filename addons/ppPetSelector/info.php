<?php
class addon_ppPetSelector_info
{
	public $name = 'ppPetSelector';
	public $version = '1.0.0';
	public $title = 'Pets Please Pet Selector';
	public $core_version_minimum = '7.1.0';
	public $author = "Ardex Technology";
	public $description = 'Pets Please Pet Selector';
	public $auth_tag = 'pp_addons';

	public $pages = array(
		'detail'
		// 'imageUploader'
	);

	public $tags = array (
	);

	public $core_events = array(
		'Search_classifieds_generate_query',
	);
}
?>