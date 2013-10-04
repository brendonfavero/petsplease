<?php
//addons/charity_tools/admin.php
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
## ##    7.2beta3-4-gb2b3265
## 
##################################

# Charity Tools

require_once ADDON_DIR.'charity_tools/info.php';

class addon_charity_tools_admin extends addon_charity_tools_info
{
	public function init_pages()
	{
		menu_page::addonAddPage('addon_charity_tools_settings','','Settings',$this->name);
		menu_page::addonAddPage('addon_charity_tools_charitable_report','','Charitable Badge Reports',$this->name);
	}
	
	public function display_addon_charity_tools_settings()
	{
		$db = DataAccess::getInstance();
		
		if(isset($_GET['deleteCharitable']) && $_GET['deleteCharitable']) {
			$sql = "DELETE FROM `geodesic_addon_charity_tools_charitable` WHERE `id` = ?";
			$db->Execute($sql, array($_GET['deleteCharitable']));
		}
		
		$reg = geoAddon::getRegistry($this->name);
		$tpl_vars['adminMessages'] = geoAdmin::m();

		$tpl_vars['use_neighborly'] = $reg->use_neighborly;
		$tpl_vars['neighborly_duration'] = $reg->get('neighborly_duration',12);
		$tpl_vars['neighborly_image'] = $reg->get('neighborly_image');
		$tpl_vars['neighborly_preview'] = geoTemplate::getUrl('images', 'addon/charity_tools/'.$tpl_vars['neighborly_image']);
		
		
		$sql = "SELECT * FROM `geodesic_addon_charity_tools_charitable`";
		$result = $db->Execute($sql);
		foreach($result as $c) {
			$tpl_vars['charitables'][$c['id']] = array(
				'name' => geoString::fromDB($c['name']),
				'image' => geoTemplate::getUrl('images', 'addon/charity_tools/'.$c['image']),
				'region' => $c['region'] ? geoRegion::getNameForRegion($c['region']) : 'none',
				'deleteLink' => 'index.php?page=addon_charity_tools_settings&deleteCharitable='.$c['id']
			);
		}
		
		$tpl_vars['newRegion'] = geoRegion::regionSelector('nc[region]');
		
		geoView::getInstance()->setBodyTpl('admin/settings.tpl', $this->name)
			->setBodyVar($tpl_vars);
	}
	
	public function update_addon_charity_tools_settings()
	{
		$reg = geoAddon::getRegistry($this->name);
		$settings = $_POST['settings'];
		
		if($settings) {
			$reg->use_neighborly = (isset($settings['use_neighborly']) && $settings['use_neighborly'] == 1) ? 1 : false;
			$reg->neighborly_image = $settings['neighborly_image'];
			$reg->neighborly_duration = ($settings['neighborly_duration']) ? $settings['neighborly_duration'] : 12;
		}
		
		$newCharitable = $_POST['nc'];
		$name = geoString::toDB($newCharitable['name']);
		$image = $newCharitable['image'];
		
		$region = 0;
		while(($r = array_pop($newCharitable['region'])) !== null) {
			if($r) {
				$region = $r;
				break;
			}
		}
		if($name && $image) {
			$db = DataAccess::getInstance();
			$sql = "INSERT INTO `geodesic_addon_charity_tools_charitable` (`name`,`image`,`region`) VALUES (?,?,?)";
			$db->Execute($sql, array($name,$image,$region));
		}
		
		$reg->save();
		return true;
	}
	
	public function display_addon_charity_tools_charitable_report()
	{
		$db = DataAccess::getInstance();
		if($_POST['d']) {
			$startDate = strtotime($_POST['d']['start_date']);
			$endDate = strtotime($_POST['d']['end_date']);
			
			$sql = "SELECT * FROM `geodesic_addon_charity_tools_charitable_purchases` as p, `geodesic_addon_charity_tools_charitable` as c WHERE p.purchased_badge = c.id AND `time` BETWEEN ? AND ? ORDER BY `region`";
			$result = $db->Execute($sql, array($startDate, $endDate));
			if(!$result || $result->RecordCount() == 0) {
				geoAdmin::m('Found no charitable badge purchases for this timeframe');
			} else {
				foreach($result as $purchase) {
					
					$tpl_vars['badgeData'][$purchase['purchased_badge']]['total'] += $purchase['price'];
					$tpl_vars['purchases'][$purchase['purchased_badge']][] = array(
						'listing' => $purchase['listing'],
						'time' => date('M d Y',$purchase['time']),
						'price' => geoString::displayPrice($purchase['price'])
					);
					
					if(!$tpl_vars['badgeData'][$purchase['purchased_badge']]['name']) {
						$tpl_vars['badgeData'][$purchase['purchased_badge']]['name'] = geoString::fromDB($purchase['name']);
					}
					if(!$tpl_vars['badgeData'][$purchase['purchased_badge']]['region']) {
						$tpl_vars['badgeData'][$purchase['purchased_badge']]['region'] = geoRegion::getNameForRegion($purchase['region']);
					}
				}
			}
		}
		
		
		$tpl_vars['adminMsgs'] = geoAdmin::m();
		geoView::getInstance()->setBodyTpl('admin/charitable_report.tpl', $this->name)
			->setBodyVar($tpl_vars)
			->addCssFile('css/calendarview.css')
			->addJScript('../js/calendarview.js');
	}
	
	public function init_text ($languageId)
	{
		$return = array
		(
			'charitable_badge_label' => array (
				'name' => 'Charitable Badge Label',
				'desc' => 'Labels the charitable badge selection box, on the "other details" step',
				'type' => 'input',
				'default' => 'Charitable Badge',
				'section' => 'Charitable Badge'
			),
			'charitable_badge_selection_error' => array (
				'name' => 'Charitable Badge Selection Error',
				'desc' => 'Shown when a valid Charitable Badge is not selected',
				'type' => 'input',
				'default' => 'You must select a specific Charitable Badge',
				'section' => 'Charitable Badge'
			),
			'charitable_badge_cart_title' => array (
				'name' => 'Charitable Badge Cart Title',
				'desc' => 'Shown as the name of the Charitable Badge item in the cart',
				'type' => 'input',
				'default' => 'Charitable Badge',
				'section' => 'Charitable Badge'
			),
		);
		
		return $return;
	}
}