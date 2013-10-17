<?php
class addon_ppHomeSearch_tags extends addon_ppHomeSearch_info
{
	public function search ($params, Smarty_Internal_Template $smarty)
	{
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		
		$tpl_vars = array();
		
		$cats = array();

		// Get top-level categories
		$sql = "SELECT category_id, category_name FROM geodesic_categories WHERE parent_id <> 0 ORDER BY display_order";
		$topCats = $db->GetAll($sql);

		$topcatids = array();

		foreach ($topCats as $topCat) {
			$catrow = array();
			$catrow['id'] = $topCat['category_id'];
			$catrow['name'] = $topCat['category_name'];
			$catrow['sub'] = array();
			$cats[] = $catrow;

			$topcatids[] = $catrow['id'];
		}

		// Get second-level categories
		$in_parents = "IN (" . implode(',', $topcatids) . ")";
		$sql = "SELECT category_id, category_name, parent_id FROM geodesic_categories WHERE parent_id " . $in_parents . " ORDER BY display_order";
		$nextCats = $db->GetAll($sql);

		foreach ($nextCats as $nextCat) {
			
		}

		$tpl_vars['categories'] = $cats;

		return geoTemplate::loadInternalTemplate($params, $smarty, 'search.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}
}