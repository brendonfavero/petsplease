<?php
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
## ##    6.0.7-2-gc953682
## 
##################################

// DON'T FORGET THIS
if(class_exists( 'admin_AJAX' ) or die());

class ADMIN_AJAXController_price_plan_items extends admin_AJAX {
	public function display_config()
	{
		$cjax = geoCJAX::getInstance();
		$admin = geoAdmin::getInstance();
		
		if (!$admin->isAllowed('pricing_edit_plans')){
			$admin->userError('No access.');
			$cjax->message($admin->getUserMessages(),5);
			return;
		}
		
		$all_items = geoOrderItem::getOrderItemTypes();
		
		$item_name = $cjax->get('item');
		if (!isset($all_items[$item_name]) || !class_exists($all_items[$item_name]['class_name'])) {
			//could not find that order item!
			$admin->userError('Could not get that order item!');
			$cjax->message($admin->getUserMessages(),5);
			return;
		}
		$order_item = Singleton::getInstance($all_items[$item_name]['class_name']);
		$price_plan_id = intval($cjax->get('price_plan_id'));
		$category_id = intval($cjax->get('category_id'));
		$plan_item = geoPlanItem::getPlanItem($item_name, $price_plan_id,$category_id);
		$config = $order_item->adminPlanItemConfigDisplay($plan_item);
		
		$cjax->link = true;
		$cancel  = $cjax->call("AJAX.php?controller=price_plan_items&action=cancel&item={$item_name}&price_plan_id=$price_plan_id&category_id=$category_id");
		$cjax->link = true;
		$save = $cjax->form("AJAX.php?controller=price_plan_items&action=save_config&item={$item_name}&price_plan_id=$price_plan_id&category_id=$category_id","frm_all_settings");
		
		//replace config button with save and cancel buttons
		$save_cancel = "<a id='save_$item_name' href='javascript:void(0);' onclick=\"saveItem('$item_name','$price_plan_id','$category_id', ".geoUtil::time().");\" class='mini_button'>Save</a>";
		$save_cancel .= "<a id='cancel_$item_name' href='javascript:void(0);' onclick=\"cancelItem('$item_name','$price_plan_id','$category_id');\" class='mini_cancel' style='margin-left: 4px;'>Cancel</a>";
		
		$cjax->update('update_config_'.$item_name,$save_cancel);
		
		$html = "
		<div style='position:relative;' id='form_$item'>
			<div class='configBox'>$config</div>
		</div>";
		
		$cjax->update('container_'.$item_name,$html);
		return;
	}
	public function getConfig ($item, $price_plan, $category ) {
		return "<a id='configure' href='javascript:void(0);' onclick=\"configureItem('$item','$price_plan', '$category', ".geoUtil::time().")\" class='mini_button'>Configure</a>";
	}
	public function save ()
	{
		$cjax = geoCJAX::getInstance();
		$admin = geoAdmin::getInstance();
		
		if (!$admin->isAllowed('pricing_edit_plans','update')){
			$admin->userError('No access.');
			$cjax->message($admin->getUserMessages(),5);
			return;
		}
		$_POST = $_GET;
		
		$all_items = geoOrderItem::getOrderItemTypes();
		
		$item_name = $cjax->get('item');
		
		if (!isset($all_items[$item_name]) || !class_exists($all_items[$item_name]['class_name'])) {
			//could not find that order item!
			$admin->userError('Could not get that order item!');
			$cjax->message($admin->getUserMessages(),5);
			return;
		}
		$price_plan_id = intval($cjax->get('price_plan_id'));
		$category_id = intval($cjax->get('category_id'));
		
		$order_item = Singleton::getInstance($all_items[$item_name]['class_name']);
		$plan_item = geoPlanItem::getPlanItem($item_name, $price_plan_id,$category_id);
		$status = $order_item->adminPlanItemConfigUpdate($plan_item);
		$cjax->update('update_config_'.$item_name, $this->getConfig($item_name, $price_plan_id, $category_id));
		$cjax->update("container_$item_name",'');
		if ($status) {
			//it is saved, so hide the box, and display a message
			$admin->userSuccess('Settings Saved.');
		} else if ($status === false) {
			//saving failed.
			$admin->userError('Settings not saved.');
		}
		$cjax->message($admin->getUserMessages(),3);
		$plan_item->save();
	}
	
	
	
	public function cancel()
	{
		$cjax = geoCJAX::getInstance();
		$item =  $cjax->get('item');
		$price_plan_id = intval($cjax->get('price_plan_id'));
		$category_id = intval($cjax->get('category_id'));
		
		$cjax->link = true;
		$response = $cjax->call("AJAX.php?controller=price_plan_items&action=display_config&item=$item&price_plan_id=$price_plan_id&category_id=$category_id");
		$cjax->update('update_config_'.$item,$this->getConfig($item, $price_plan_id, $category_id));
		$cjax->update("container_$item");
		
		//$cjax->show("order_$item");
	}
	
	
	public function updateRequireAdmin ()
	{
		$cjax = geoCJAX::getInstance();
		$admin = geoAdmin::getInstance();
		
		if (!$admin->isAllowed('pricing_items','update')){
			$admin->userError('No access.');
			$cjax->message($admin->getUserMessages(),5);
			return;
		}
		
		$all_items = geoOrderItem::getOrderItemTypes();
		
		$item_name = $cjax->get('item');
		if (!isset($all_items[$item_name]) || !class_exists($all_items[$item_name]['class_name'])) {
			//could not find that order item!
			$admin->userError('Could not get that order item!');
			$cjax->message($admin->getUserMessages(),5);
			return;
		}
		$order_item = Singleton::getInstance($all_items[$item_name]['class_name']);
		$plan_item = geoPlanItem::getPlanItem($item_name, $cjax->get('price_plan_id'),$cjax->get('category_id'));
		
		$admin_required=($cjax->get('require_admin_approval'))? 1: false;
		$oldValue = $plan_item->getNeedAdminApproval();
		$newValue = ($oldValue) ? false : 1;
		$plan_item->setNeedAdminApproval($newValue);
		$plan_item->save();
		
		$require_onclick = $cjax->call('AJAX.php?controller=price_plan_items&action=updateRequireAdmin&item='.$plan_item->getOrderItem().'&price_plan_id='.$plan_item->getPricePlan().'&category_id='.$plan_item->getCategory());
		if ($newValue) {
			$admin->userSuccess('Item now requires admin approval before going live.');
			$button = '<a href="javascript:void(0);" id="requireAdmin'.$item_name.'" '.$require_onclick.' class="mini_cancel">Stop requiring admin approval</a>';
		} else {
			$admin->userSuccess('Item will now go live automatically without admin approval.');
			$button = '<a href="javascript:void(0);" id="requireAdmin'.$item_name.'" '.$require_onclick.' class="mini_button">Require admin approval</a>';
		}
		$cjax->message($admin->getUserMessages(),3);
		
		//change button text
		
		$cjax->update('requireAdmin'.$item_name, $button);
	}
}