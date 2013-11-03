<?php
class addon_ppSearch_pages extends addon_ppSearch_info
{
	public function ajaxSearch() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$tpl_vars = array();

		// Get all categories
		$sql = "SELECT category_id, category_name, parent_id FROM geodesic_categories ORDER BY parent_id, display_order, category_name";
		$result = $db->GetAll($sql);

		
	}
}