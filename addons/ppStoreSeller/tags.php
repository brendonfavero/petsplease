<?php
class addon_ppStoreSeller_tags extends addon_ppStoreSeller_info
{
	public function miniCart($params, Smarty_Internal_Template $smarty) {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$user_id = geoSession::getInstance()->getUserId();

		$tpl_vars = array();

		$sql = "SELECT mcart.qty, c.price, c.optional_field_20 shipping
				FROM petsplease_merchant_cart mcart JOIN geodesic_classifieds c ON mcart.listing_id = c.id 
				WHERE user_id = ? ORDER BY c.seller ASC, mcart.time_added DESC";
		$cart_items = $db->GetAll($sql, array($user_id));

		$total_price = 0;
		$total_items = 0;
		foreach ($cart_items as $cart_item) {
			$item_total = $cart_item['qty'] * ($cart_item['price'] + $cart_item['shipping']);
			$total_price += $item_total;
			$total_items += 1;
		}

		$tpl_vars['price_display'] = geoString::displayPrice($total_price);
		$tpl_vars['items'] = $total_items;

		return geoTemplate::loadInternalTemplate($params, $smarty, 'miniCart.tpl',
		geoTemplate::ADDON, $this->name, $tpl_vars);		
	}
}