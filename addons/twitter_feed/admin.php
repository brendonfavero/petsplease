<?php
//addons/twitter_feed/admin.php
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
## ##    612208c
## 
##################################

# twitter_feed Addon

class addon_twitter_feed_admin extends addon_twitter_feed_info {
	
	public function init_text($language_id) {
		$return_var['edit_step_button'] = array (
			'name' => 'Edit Step Selection Button',
			'desc' => 'Text for the "edit twitter name" button during the listing edit process',
			'type' => 'input',
			'default' => 'Edit Twitter Username'
		);
		$return_var['edit_step_label'] = array (
			'name' => 'Edit Step Breadcrumb Label',
			'desc' => '',
			'type' => 'input',
			'default' => 'Twitter Username'
		);
		$return_var['cart_title'] = array (
			'name' => 'Cart Title',
			'desc' => 'Label of Twitter Feed item in the Cart display',
			'type' => 'input',
			'default' => 'Twitter Feed'
		);
		$return_var['input_username_instructions'] = array (
			'name' => 'Input Username Instructions',
			'desc' => 'labels the username entry field',
			'type' => 'input',
			'default' => 'Twitter Username'
		);
		$return_var['edit_sub_title'] = array (
			'name' => 'Sub-title for Edit Listing page',
			'desc' => '',
			'type' => 'input',
			'default' => 'Twitter Feed'
		);
		$return_var['edit_desc'] = array (
			'name' => 'Description for Edit Listing page',
			'desc' => '',
			'type' => 'input',
			'default' => 'Enter your Twitter username'
		);
		$return_var['edit_submit_button_text'] = array (
			'name' => 'Submit button on Edit Listing page',
			'desc' => '',
			'type' => 'input',
			'default' => 'Next &gt;&gt;'
		);
		$return_var['edit_cancel_text'] = array (
			'name' => 'Cancel button on Edit Listing page',
			'desc' => '',
			'type' => 'input',
			'default' => 'Cancel Listing Edit'
		);
		$return_var['listing_help_box'] = array(
			'name' => 'Popup help text',
			'desc' => '',
			'type' => 'input',
			'default' => 'Enter your Twitter username here to add a feed of your most recent Tweets to your listing. Your Twitter profile must be set to public for this to work.'
		);
		
		return $return_var;
	}
	
	
	public function init_pages () {
		menu_page::addonAddPage('addon_twitter_feed_settings','','Settings',$this->name,$this->icon_image);		
	}
	
	public function display_addon_twitter_feed_settings()
	{
		$admin = geoAdmin::getInstance();
		$db = DataAccess::getInstance();
		$v = $admin->v();		
		$reg = geoAddon::getRegistry('twitter_feed');
		$v->config = $reg->config;
		$v->adminMsgs = geoAdmin::m();		
		$v->setBodyTpl('admin/admin_settings.tpl','twitter_feed');
	}
	
	public function update_addon_twitter_feed_settings()
	{
		$data = $_REQUEST['d'];
		$config = array(
			'behavior' => $data['behavior'],
			'interval' => $data['interval'],
			'rpp' => $data['rpp'],
			'defaultuser' => $data['defaultuser'],
			'hashtags' => $data['hashtags'],
			'timestamps' => $data['timestamps'],
			'avatars' => $data['avatars'],
			'width' => $data['width'],
			'autowidth' => $data['autowidth'],
			'height' => $data['height'],
			'shell' => $data['shell'],
			'heading' => $data['heading'],
			'background' => $data['background'],
			'text' => $data['text'],
			'links' => $data['links'],
		);
		$reg = geoAddon::getRegistry('twitter_feed');
		$reg->config = $config;
		$reg->save();
		return true;
	}
}