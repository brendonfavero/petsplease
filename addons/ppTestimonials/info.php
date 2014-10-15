<?php
class addon_ppTestimonials_info
{
	public $name = 'ppTestimonials';
	public $version = '1.0.0';
	public $title = 'Pets Please Testimonials';
	public $core_version_minimum = '7.1.0';
	public $author = "Ardex Technology";
	public $description = 'Pets Please Testimonials';
	public $auth_tag = 'pp_addons';

	public $pages = array(
		'add'
	);

	public $tags = array (
	);

	public $core_events = array(
		'Search_classifieds_generate_query',
	);
}
?>
