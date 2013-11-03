<?php
class addon_ppSearch_tags extends addon_ppSearch_info
{
	public function search ($params, Smarty_Internal_Template $smarty)
	{
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$tpl_vars = array();

		// Get all categories
		$sql = "SELECT category_id, category_name, parent_id FROM geodesic_categories ORDER BY display_order, category_name";
		$result = $db->GetAll($sql);

		$tpl_vars['categories'] = $this->buildCategoryChildren(0, $result);




		
		// $cats = array();

		// // Get top-level categories
		// $sql = "SELECT category_id, category_name FROM geodesic_categories WHERE parent_id = 0 ORDER BY display_order";
		// $topCats = $db->GetAll($sql);

		// $topcatids = array();

		// foreach ($topCats as $topCat) {
		// 	$catrow = array();
		// 	$catrow['id'] = $topCat['category_id'];
		// 	$catrow['name'] = $topCat['category_name'];
		// 	$catrow['sub'] = array();
		// 	$cats[] = $catrow;

		// 	$topcatids[] = $catrow['id'];
		// }

		// // Get second-level categories
		// $in_parents = "IN (" . implode(',', $topcatids) . ")";
		// $sql = "SELECT category_id, category_name, parent_id FROM geodesic_categories WHERE parent_id " . $in_parents . " ORDER BY display_order";
		// $nextCats = $db->GetAll($sql);

		// foreach ($nextCats as $nextCat) {
		// 	$catrow = array();
		// 	$catrow['id'] = $nextCat['category_id'];
		// 	$catrow['name'] = $nextCat['category_name'];

		// 	foreach ($cats as $topCat) {
		// 		if ($topCat['id'] == $nextCat['parent_id']) {
		// 			$topCat['sub'][] = $catrow;
		// 		}
		// 	}
		// }

		// $tpl_vars['categories'] = $cats;

		return geoTemplate::loadInternalTemplate($params, $smarty, 'search.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}

	private function buildCategoryChildren($parent_id, $all_categories) {
		$categories = array();

		foreach ($all_categories as $category) {
			if ($category['parent_id'] == $parent_id) {
				$category['subcategories'] = $this->buildCategoryChildren($category['category_id'], $all_categories);
				$categories[] = $category;
			}
		}

		return $categories;
	}

	public function searchSidebar ($params, Smarty_Internal_Template $smarty) {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$tpl_vars = array();

		// Work search query into usable format
		$queryurl = html_entity_decode($params['queryurl']);
		$urlparts = parse_url('fake.com/' . $queryurl); // can't be bothered detecting if $queryurl has file included or not so just using this
		$querystring = $urlparts['query'];
		$search_parms = array();
		parse_str($querystring, &$search_parms);
		$tpl_vars['search_parms'] = $search_parms;

		// Get all categories
		$sql = "SELECT category_id, category_name, parent_id FROM geodesic_categories ORDER BY display_order, category_name";
		$result = $db->GetAll($sql);

		$tpl_vars['categories'] = $this->buildCategoryChildren(0, $result);

		// Need to explicity define info about selected category
		$selectedCategory = $search_parms['c'];
		if ($selectedCategory) {
			foreach ($tpl_vars['categories'] as $topcat) {
				if ($topcat['category_id'] == $selectedCategory) {
					$tpl_vars['topcat'] = $topcat['category_id'];
				}
				else {
					foreach ($topcat['subcategories'] as $subcat) {
						if ($subcat['category_id'] == $selectedCategory) {
							$tpl_vars['topcat'] = $topcat['category_id'];
							$tpl_vars['subcat'] = $subcat['category_id'];
						}
					}
				}
			}
		}

		// Zip search distances
		$tpl_vars['zip_distances'] = array(5, 10, 15, 20, 25, 30, 40, 50, 75, 100, 200, 300, 400, 500);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'searchSidebar.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);		
	}
}