<?php
// admin_user_account_settings.php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.1beta4-28-g882f740
## 
##################################

/**
 * Switches, toggles, and the like for options relating to the User Account Home Page.
 * 
 * 
 * @package geo_admin
 */

class admin_user_account_settings extends Admin_site
{

	public function display_user_account_settings()
	{
		$db = DataAccess::getInstance();
		$admin = geoAdmin::getInstance();
		$view = $admin->v();
		$view->admin_messages = $admin->message();
		$main_settings = $boxes = '';
		
		$tooltip_text = 'As of Geo version 4.0.0, the "My Account Home Page" has been completely redone to show various user statistics, and relies on the My Account Links module to show links to My Account subpages. ';
		$tooltip_text .= 'You may select to use either the old (User Account Home Page) or new (My Account Page) style for this page. If you elect to use the new style, be sure to include the <strong>My Account Links</strong> module on that page, ';
		$tooltip_text .= 'so that your users will still be able to access their account information and subpages.';
		$tooltip = geoHTML::showTooltip('My Account Home Type', $tooltip_text);
		$setting = $this->db->get_site_setting('my_account_home_type');
		$radios = '<input type="radio" name="b[my_account_home_type]" '.(($setting == 1)?'checked="checked"':'').' value="1" /> My Account Page - <em>(New)</em><br />
				   <input type="radio" name="b[my_account_home_type]" '.(($setting != 1)?'checked="checked"':'').' value="0" /> User Account Home Page - <em>(Old)</em>';
		$main_settings .= geoHTML::addOption('My Account Home Type '.$tooltip, $radios);
		
		$setting = $db->get_site_setting('post_login_page') ? $db->get_site_setting('post_login_page') : 0;
		$url = $db->get_site_setting('post_login_url') ? $db->get_site_setting('post_login_url') : '';
		$tooltip = geoHTML::showTooltip('Post-Login Landing Page', 'Choose the page you would like to appear once a user has successfully logged in.');
		$radios = '<input type="radio" name="b[post_login_page]" '.(($setting == 0)?'checked="checked"':'').' value="0" /> My Account Home<br />
				   <input type="radio" name="b[post_login_page]" '.(($setting == 1)?'checked="checked"':'').' value="1" /> Site Home<br />
				   <input type="radio" name="b[post_login_page]" '.(($setting == 2)?'checked="checked"':'').' value="2" /> Other:
				   		<input type="text" name="b[post_login_url]" value="'.$url.'" placeholder="http://example.com/home.html" size="30" />';
		$main_settings .= geoHTML::addOption('Post-Login Landing Page '.$tooltip, $radios);
		
		$table_rows = ($db->get_site_setting('my_account_table_rows')) ? $db->get_site_setting('my_account_table_rows') : 5;
		$tooltip = geoHTML::showTooltip('Number of rows per table', 'Choose the maximum number of rows you would like to appear in information tables on the My Account Page (no effect if using the old User Account Home Page).');
		$main_settings .= geoHTML::addOption('Number of rows per table '.$tooltip, '<input type="text" name="b[my_account_table_rows]" size="3" maxlength="2" value="'.$table_rows.'" />');
		
		$setting = $db->get_site_setting('show_addon_icons') ? $db->get_site_setting('show_addon_icons') : 0;
		$tooltip = geoHTML::showTooltip('Show icons for addons in "My Account Links"', 'The icons that appear alongside entires for addons in the My Account Links module are disabled by default. You can turn them on here.');
		$radios = '<input type="radio" name="b[show_addon_icons]" '.(($setting != 1)?'checked="checked"':'').' value="0" /> Off<br />
				   <input type="radio" name="b[show_addon_icons]" '.(($setting == 1)?'checked="checked"':'').' value="1" /> On';
		$main_settings .= geoHTML::addOption('Show icons for addons in "My Account Links" '.$tooltip, $radios);
		
		//reset alternating colors
		geoHTML::resetRowColor();
		
		
		//New Messages box
		$boxes .= geoHTML::addOption('New Messages', '<input type="checkbox" name="b[my_account_show_new_messages]" value="1" '.(($db->get_site_setting('my_account_show_new_messages'))?'checked="checked"':'').' />');
		
		//Account Balance box
		//(only show if using Account Balance)
		//TODO: move this code into the AB-gateway file?
		$gateway = geoPaymentGateway::getPaymentGateway('account_balance');
		if($gateway && $gateway->getEnabled()) {
			$boxes .= geoHTML::addOption('Account Balance', '<input type="checkbox" name="b[my_account_show_account_balance]" value="1" '.(($db->get_site_setting('my_account_show_account_balance'))?'checked="checked"':'').' />');
		}
		
		//Auctions box
		if(geoMaster::is('auctions')) {
			$boxes .= geoHTML::addOption('Auctions', '<input type="checkbox" name="b[my_account_show_auctions]" value="1" '.(($db->get_site_setting('my_account_show_auctions'))?'checked="checked"':'').' />');
		}
		
		//Classifieds box
		if(geoMaster::is('classifieds')) {
			$boxes .= geoHTML::addOption('Classifieds', '<input type="checkbox" name="b[my_account_show_classifieds]" value="1" '.(($db->get_site_setting('my_account_show_classifieds'))?'checked="checked"':'').' />');
		}

		//Recently sold box
		//available in all Auctions, but only Enterprise Classifieds
		if(geoMaster::is('auctions') || geoPC::is_ent()) {
			$html = '<input type="checkbox" name="b[my_account_show_recently_sold]" value="1" '.(($db->get_site_setting('my_account_show_recently_sold'))?'checked="checked"':'').' /><br />
					 A listing is "recently sold" if it ended in the past <input type="text" size="3" maxlength="2" name="b[my_account_recently_sold_time]" value="'.(($db->get_site_setting('my_account_recently_sold_time'))).'" /> days.';
			$boxes .= geoHTML::addOption('Recently Sold', $html);
		}
		
		$tpl_vars = array();
		$tpl_vars['verify_accounts'] = $db->get_site_setting('verify_accounts');
		$tpl_vars['nonverified_require_approval'] = $db->get_site_setting('nonverified_require_approval');
		$tpl_vars['auto_verify_with_payment'] = $db->get_site_setting('auto_verify_with_payment');
		
		//addons can put switches here, too!
		//see Storefront for an example
		$addonBoxes = geoAddon::triggerDisplay('my_account_admin_options_display', null, geoAddon::ARRAY_STRING);
		foreach($addonBoxes as $addon) {
			$boxes .= $addon;
		}
		
		$view->main_settings = $main_settings;
		$view->boxes = $boxes;
		$view->setBodyTpl('user_account_settings.tpl')
			->setBodyVar($tpl_vars);
		return true;
	}
	
	
	public function update_user_account_settings()
	{
		$db = DataAccess::getInstance();
		$settings = $_POST['b'];
		
		//save verify user stuff
		$this->db->set_site_setting('verify_accounts', ((isset($_POST['verify_accounts'])&&$_POST['verify_accounts'])? 1 : false));
		$this->db->set_site_setting('nonverified_require_approval', ((isset($_POST['nonverified_require_approval'])&&$_POST['nonverified_require_approval'])? 1 : false));
		$this->db->set_site_setting('auto_verify_with_payment', ((isset($_POST['auto_verify_with_payment'])&&$_POST['auto_verify_with_payment'])? 1 : false));

		//save main settings
		if(!$this->db->set_site_setting("my_account_home_type",$settings["my_account_home_type"])) return false;
		if(!$this->db->set_site_setting("my_account_table_rows",$settings["my_account_table_rows"])) return false;
		if(!$this->db->set_site_setting("show_addon_icons",$settings["show_addon_icons"])) return false;
		
		if(!$this->db->set_site_setting("post_login_page",$settings["post_login_page"])) return false;
		//only save post url if using the custom landing page setting
		$post_url = ($settings['post_login_page']  == 2) ? $settings['post_login_url'] : '';
		if(!$this->db->set_site_setting("post_login_url",$post_url)) return false;

		//save box toggles
		$setting = $settings["my_account_show_new_messages"] ? 1 : 0;
		if(!$this->db->set_site_setting("my_account_show_new_messages",$setting)) return false;
		
		$gateway = geoPaymentGateway::getPaymentGateway('account_balance');
		$setting = ($gateway && $gateway->getEnabled() && $settings["my_account_show_account_balance"]) ? 1 : 0;
		if(!$this->db->set_site_setting("my_account_show_account_balance",$setting)) return false;
		
		$setting = (geoMaster::is('auctions') && $settings["my_account_show_auctions"]) ? 1 : 0;
		if(!$this->db->set_site_setting("my_account_show_auctions",$setting)) return false;
		
		$setting = (geoMaster::is('classifieds') && $settings["my_account_show_classifieds"]) ? 1 : 0;
		if(!$this->db->set_site_setting("my_account_show_classifieds",$setting)) return false;
		
		$setting = ((geoMaster::is('auctions') || geoPC::is_ent()) && $settings["my_account_show_recently_sold"]) ? 1 : 0;
		if(!$this->db->set_site_setting("my_account_show_recently_sold",$setting)) return false;
		$setting = ((geoMaster::is('auctions') || geoPC::is_ent()) && $settings["my_account_recently_sold_time"]) ? $settings["my_account_recently_sold_time"] : 30;
		if(!$this->db->set_site_setting("my_account_recently_sold_time",$setting)) return false;
		
		geoAddon::triggerUpdate('my_account_admin_options_update',$settings);
		
		return true;
	}
	
}
