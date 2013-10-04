<?php
//addons/core_display/admin.php
/**************************************************************************
Addon Created by Geodesic Solutions, LLC
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.1beta3-93-g89a07f2
## 
##################################

# core_display Addon

class addon_core_display_admin extends addon_core_display_info {
	
	public function init_pages () 
	{
		menu_page::addonAddPage('browsing_filter_settings', '', 'Browsing Filter Settings', 'core_display', $this->icon_image);
		
		menu_page::addonAddPage('core_featured_settings', '', 'Featured Gallery Settings', 'core_display', $this->icon_image);
	}
	
	function init_text($language_id)
	{
		$return = array (
		 	'browsing_filters_sidebar_title' => array (
				'section' => 'Browsing Filters',
		 		'name' => 'Browsing Filters - Sidebar Title',
				'desc' => '',
				'type' => 'input',
				'default' => 'Browsing Filters'
			),
			'browsing_filters_option_yes' => array (
				'section' => 'Browsing Filters',
		 		'name' => 'Browsing Filters - "yes" option',
				'desc' => '', 
				'type' => 'input',
				'default' => 'Yes'
			),
			'browsing_filters_option_no' => array (
				'section' => 'Browsing Filters',
		 		'name' => 'Browsing Filters - "no" option',
				'desc' => '', 
				'type' => 'input', 
				'default' => 'No'
			),
			'browsing_filters_filter_button' => array (
				'section' => 'Browsing Filters',
		 		'name' => 'Browsing Filters - filter button',
				'desc' => '',
				'type' => 'input',
				'default' => 'Filter'
			),
			'browsing_filters_reset_all' => array(
				'section' => 'Browsing Filters',
		 		'name' => 'Browsing Filters - reset all filters button',
				'desc' => '',
				'type' => 'input',
				'default' => 'Reset All Filters'
			),
			'browsing_filters_placeholder_low' => array(
				'section' => 'Browsing Filters',
				'name' => 'Browsing Filters - placeholder low',
				'desc' => '',
				'type' => 'input',
				'default' => 'Low'
			),
			'browsing_filters_placeholder_high' => array(
				'section' => 'Browsing Filters',
				'name' => 'Browsing Filters - placeholder high',
				'desc' => '',
				'type' => 'input',
				'default' => 'High'
			),
			'browsing_filters_more_btn' => array(
				'section' => 'Browsing Filters',
				'name' => 'Browsing Filters - expandable "more" button',
				'desc' => '',
				'type' => 'input',
				'default' => 'Show More'
			),
			'browsing_filters_less_btn' => array(
				'section' => 'Browsing Filters',
				'name' => 'Browsing Filters - expandable "less" button',
				'desc' => '',
				'type' => 'input',
				'default' => 'Show Less'
			),
			
			//Featured gallery section
			'featured_title' => array(
				'section' => 'Featured Gallery',
		 		'name' => 'Gallery Title',
				'desc' => 'Skips title if blank. Category name added to end automatically.',
				'type' => 'input',
				'default' => 'Featured in'
			),
			
			'featured_label_listing_type' => array(
				'section' => 'Featured Gallery',
		 		'name' => 'Listing Type Label',
				'desc' => '',
				'type' => 'input',
				'default' => ''
			),
			'featured_label_business_type' => array(
				'section' => 'Featured Gallery',
				'name' => 'Business Type Label',
				'desc' => '',
				'type' => 'input',
				'default' => ''
			),
			'featured_label_title' => array(
				'section' => 'Featured Gallery',
				'name' => 'Listing Title Label',
				'desc' => '',
				'type' => 'input',
				'default' => ''
			),
			'featured_label_description' => array(
				'section' => 'Featured Gallery',
				'name' => 'Description Label',
				'desc' => '',
				'type' => 'input',
				'default' => ''
			),
			'featured_label_tags' => array(
				'section' => 'Featured Gallery',
				'name' => 'Listing Tags Label',
				'desc' => '',
				'type' => 'input',
				'default' => 'Tags:'
			),
			
		);
		
		for ($i=1; $i<=20; $i++) {
			$return['featured_label_opt_'.$i] = array (
				'section' => 'Featured Gallery',
				'name' => "Optional Field $i Label",
				'desc' => '',
				'type' => 'input',
				'default' => ''
				);
		}
		//add rest per index, so that optional fields are in correct spot in array
		$return['featured_label_address'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Address Label',
			'desc' => '',
			'type' => 'input',
			'default' => ''
		);
		$return['featured_label_city'] = array(
			'section' => 'Featured Gallery',
			'name' => 'City Label',
			'desc' => '',
			'type' => 'input',
			'default' => ''
		);
		$return['featured_label_location_breadcrumb'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Location Breadcrumb Label',
			'desc' => '',
			'type' => 'input',
			'default' => ''
		);
		$return['featured_label_zip'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Zip/Postal Code Label',
			'desc' => '',
			'type' => 'input',
			'default' => ''
		);
		$return['featured_label_num_bids'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Number Bids Label',
			'desc' => '',
			'type' => 'input',
			'default' => 'Number Bids:'
		);
		$return['featured_label_price'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Price Label',
			'desc' => '',
			'type' => 'input',
			'default' => ''
		);
		$return['featured_label_start_date'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Start Date Label',
			'desc' => '',
			'type' => 'input',
			'default' => 'Start Date:'
		);
		$return['featured_label_time_left'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time Left Label',
			'desc' => '',
			'type' => 'input',
			'default' => 'Time Left:'
		);
		
		$return['featured_listing_type_classifieds'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Classified listing type',
			'desc' => '',
			'type' => 'input',
			'default' => '<img src="{external file=\'images/listing_type_classified.gif\'}" alt="Classified" />'
		);
		$return['featured_listing_type_auctions'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Auction listing type',
			'desc' => '',
			'type' => 'input',
			'default' => '<img src="{external file=\'images/listing_type_auction.gif\'}" alt="Auction" />'
		);
		$return['featured_listing_type_individual'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Individual business type',
			'desc' => '',
			'type' => 'input',
			'default' => 'Individual'
		);
		$return['featured_listing_type_business'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Business business type',
			'desc' => '',
			'type' => 'input',
			'default' => 'Business'
		);
		$return['featured_time_left_weeks'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time left - weeks',
			'desc' => '',
			'type' => 'input',
			'default' => 'weeks'
		);
		$return['featured_time_left_days'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time left - days',
			'desc' => '',
			'type' => 'input',
			'default' => 'days'
		);
		$return['featured_time_left_hours'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time left - hours',
			'desc' => '',
			'type' => 'input',
			'default' => 'hours'
		);
		$return['featured_time_left_minutes'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time left - minutes',
			'desc' => '',
			'type' => 'input',
			'default' => 'minutes'
		);
		$return['featured_time_left_seconds'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time left - seconds',
			'desc' => '',
			'type' => 'input',
			'default' => 'seconds'
		);
		$return['featured_time_left_closed'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Time left - closed',
			'desc' => '',
			'type' => 'input',
			'default' => 'closed'
		);
		$return['featured_no_listings_message'] = array(
			'section' => 'Featured Gallery',
			'name' => 'Message when no featured listings in the category',
			'desc' => '',
			'type' => 'textarea',
			'default' => 'NO FEATURED LISTINGS TO DISPLAY'
		);
		
		return $return;
	
	}
	
	
	public function display_browsing_filter_settings()
	{
		$admin = geoAdmin::getInstance();
		$view = $admin->v();
		$db = DataAccess::getInstance();
		
		$category = intval($_REQUEST['category']);
		
		if($_GET['reset'] === 'yes') {
			$db->Execute("DELETE FROM ".geoTables::browsing_filters_settings." WHERE `category` = ?", array($category));
			$db->Execute("DELETE FROM ".geoTables::browsing_filters_settings_languages." WHERE `category` = ?", array($category));
			geoAdmin::m('Browsing Filter settings reset complete.',geoAdmin::SUCCESS);
		}
		
		if(!$category) {
			$reg = geoAddon::getRegistry($this->name);
			$view->browsing_filters_enabled = $reg->browsing_filters_enabled;
			$view->browsing_filters_enabled_tooltip = geoHTML::showTooltip('Show Browsing Filters Automatically when Browsing', 'This is the switch to show browsing filters automatically, as part of {body_html}, when browsing the site. If it is turned off, <strong>no browsing filters will appear</strong> unless you use the applicable addon tag inside your templates.');
			$view->expandable_threshold = $reg->expandable_threshold;
			$view->expandable_threshold_tooltip = geoHTML::showTooltip('Expandable Filter Threshold', 'If a filter contains more than this number of choices, anything above the threshold will be put into an expandable section revealed by a "Show More" button.');
			$view->use_listing_values = $reg->use_listing_values;
			$view->use_listing_values_tooltip = geoHTML::showTooltip('Use Values', 'This affects filters on pre-valued dropdown category questions or site-wide optional fields.
					We recommend "From Pre-Valued Dropdown Values" on sites that have a lot of listings, as it can result in a lot of extra options if using values from listings.');
		}
		
		$settings = array();
		//get any existing settings
		$sql = "SELECT * FROM ".geoTables::browsing_filters_settings." WHERE category = ?";
		$result = $db->Execute($sql, array($category));
		foreach($result as $line) {
			$settings[$line['field']]['enabled'] = (bool)$line['enabled'];
			$settings[$line['field']]['dependency'] = $line['dependency'];
		}
		$sql = "SELECT * FROM ".geoTables::browsing_filters_settings_languages." WHERE category = ?";
		$result = $db->Execute($sql, array($category));
		foreach($result as $line) {
			$settings[$line['field']]['languages'][$line['language']] = geoString::fromDB($line['name']);
		}
		
		if(!$settings) {
			$view->no_settings = true;
		}
		
		$fields = geoFields::getInstance(0, $category);
		
		//check for multi-level fields
		$lField = geoLeveledField::getInstance();
		$field_ids = $lField->getLeveledFieldIds();
		$leveled_fields = array();
		foreach ($field_ids as $lev_id) {
			//go through each level, figure out if it's turned on or not
			$levels = $lField->getLevels($lev_id);
			$fieldLabel = $lField->getLeveledFieldLabel($lev_id);
			foreach ($levels as $level) {
				$name = 'leveled_'.$lev_id.'_'.$level['level'];
				if ($fields->$name->is_enabled) {
					$level['leveled_field_label'] = $fieldLabel;
					$leveled_fields[$name] = $level;
				}
			}
		}
		
		//check fields to use for Optional Fields that are in use
		for($i = 1; $i <= 20; $i++) {
			$name = 'optional_field_'.$i;
			if($fields->$name->is_enabled) {
				$optionals[$name] = ucwords(str_replace("_", " ", $name)) . ' ('.$db->get_site_setting('optional_field_'.$i.'_name').')' ;
			}
		}
		
		//NOTE: expand this condition if/as more general fields other than Price are added
		$view->general_fields_enabled = $fields->price->is_enabled;
		
		//get category info, including category-specific questions
		$view->category_name = $category ? geoCategory::getName($category, true) : '';
		$view->category_id = $category;
		
		$sql = "SELECT * FROM ".geoTables::questions_table." WHERE `category_id` = ? ORDER BY `display_order` ASC";
		$catSpec = $db->Execute($sql, array($category));
		$questions = array();
		foreach($catSpec as $question) {
			$questions[$question['question_id']] = $question['name'];
		}
		$view->catSpec = $questions;
		
		$view->languages = $db->GetAll("SELECT `language_id`, `language` FROM ".geoTables::pages_languages_table." ORDER BY `language_id`");
		$view->settings = $settings;
		$view->leveled_fields = $leveled_fields;
		$view->optionals = $optionals;
		$view->adminMsgs = geoAdmin::m();
		$view->setBodyTpl('admin/browsing_filter_settings.tpl','core_display');
	}
	
	public function update_browsing_filter_settings()
	{
		$db = DataAccess::getInstance();
		$settings = $_POST['settings'];
		$category = $_POST['category'];
		if(isset($_GET['reset'])) {
			//shouldn't be doing this here...
			unset($_GET['reset']);
		}
		
		if(!$category)  {
			$reg = geoAddon::getRegistry($this->name);
			$reg->browsing_filters_enabled = $_POST['browsing_filters_enabled'] ? 1 : 0;
			$reg->expandable_threshold = intval($_POST['expandable_threshold']);
			$reg->use_listing_values = $_POST['use_listing_values']? 1:false;
			$reg->save();
		}
		
		foreach($settings as $field => $s) {
			$enabled = ($s['enabled']) ? 1 : 0;
			$langs = $s['languages'];
			$dependency = $s['dependency'].'';
			$is_leveled = (isset($s['is_leveled']) && $s['is_leveled'] && strpos($field, 'leveled_')===0);
			
			$result = $db->Execute("REPLACE INTO ".geoTables::browsing_filters_settings." (`category`,`field`,`enabled`,`dependency`) VALUES (?,?,?,?)", array($category, $field, $enabled, $dependency));
			if (!$result) {
				geoAdmin::m('DB Error', geoAdmin::ERROR);
				return false;
			}
			if (!$is_leveled) {
				//only save names if it is not a multi-level field
				foreach ($langs as $lang_id => $name) {
					$result = $db->Execute("REPLACE INTO ".geoTables::browsing_filters_settings_languages." (`category`,`field`,`language`,`name`) VALUES (?,?,?,?)", array($category, $field, $lang_id, geoString::toDB($name)));
					if(!$result) {
						geoAdmin::m('DB Error setting language',geoAdmin::ERROR);
						return false;
					}
				}
			}
		}
		return true;
	}
	
	public function display_core_featured_settings ()
	{
		$view = geoView::getInstance();
		
		$reg = geoAddon::getRegistry($this->name);
		
		//simple vars that are getting and setting same name and value from reg
		$vars = array ('featured_show_automatically', 'featured_2nd_page',
			'featured_carousel','featured_show_listing_type',);
		
		$tpl_vars = array();
		foreach ($vars as $setting) {
			$tpl_vars[$setting] = $reg->$setting;
		}
		
		//these, we want to use defaults
		$tpl_vars['featured_max_count'] = $reg->get('featured_max_count',20);
		$tpl_vars['featured_column_count'] = $reg->get('featured_column_count',4);
		$tpl_vars['featured_levels'] = $reg->get('featured_levels', array(1=>1));
		$tpl_vars['featured_thumb_width'] = $reg->get('featured_thumb_width', 150);
		$tpl_vars['featured_thumb_height'] = $reg->get('featured_thumb_height', 150);
		$tpl_vars['featured_desc_length'] = $reg->get('featured_desc_length',20);
		
		$tpl_vars['is'] = array ('classifieds'=>geoMaster::is('classifieds'),
			'auctions'=>geoMaster::is('auctions'));
		
		$tpl_vars['adminMsgs'] = geoAdmin::m();
		
		$view->setBodyTpl('admin/featured_settings.tpl', $this->name)
			->setBodyVar($tpl_vars);
	}
	
	public function update_core_featured_settings ()
	{
		$reg = geoAddon::getRegistry($this->name);
		
		$on_off = array ('featured_show_automatically', 'featured_2nd_page',
			'featured_carousel','featured_show_listing_type',);
		foreach ($on_off as $setting) {
			$value = (isset($_POST[$setting]) && $_POST[$setting]);
			$reg->set($setting, $value);
		}
		
		$reg->set('featured_max_count', (int)$_POST['featured_max_count']);
		$reg->set('featured_column_count', (int)$_POST['featured_column_count']);
		$reg->set('featured_thumb_width', (int)$_POST['featured_thumb_width']);
		$reg->set('featured_thumb_height', (int)$_POST['featured_thumb_height']);
		$reg->set('featured_desc_length', (int)$_POST['featured_desc_length']);
		
		$levels = array();
		for ($i=1; $i<=5; $i++) {
			if (isset($_POST['featured_levels'][$i])) {
				$levels[$i] = $i;
			}
		}
		$reg->featured_levels = $levels;
		
		$reg->save();
		
		return true;
	}
	
}
