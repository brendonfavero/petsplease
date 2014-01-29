<?php
class addon_ppSearch_tags extends addon_ppSearch_info
{
	public function searchSidebar ($params, Smarty_Internal_Template $smarty) {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$tpl_vars = array();

		if ($params['simple']) {
			$tpl_vars['simplesearch'] = true;
		}

		// Work search query into usable format
		$queryurl = html_entity_decode($params['queryurl']);
		$urlparts = parse_url('fake.com/' . $queryurl); // can't be bothered detecting if $queryurl has file included or not so just using this
		$querystring = $urlparts['query'];
		$search_parms = array();
		parse_str($querystring, $search_parms);

		// Get all categories
		$sql = "SELECT category_id, category_name, parent_id FROM geodesic_categories ORDER BY display_order, category_name";
		$result = $db->GetAll($sql);
        
		// Need to explicity define info about selected category
		$selectedCategory = $search_parms['c'];
        
        $classifiedCategories = array(308, 315, 415);
        $parentCat = geoCategory::getParent($selectedCategory);
        $topCat = geoCategory::getParent($parentCat);
        
        $categories['items'] = 421;
        $categories['profiles'] = 422;
        $categories['shop'] = 412;
        
        $tpl_vars['categories'] = $this->buildCategoryChildren($categories, $result);
        
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
						else {
							foreach ($subcat['subcategories'] as $subcat2) {
								if ($subcat2['category_id'] == $selectedCategory) {
									$tpl_vars['topcat'] = $topcat['category_id'];
									$tpl_vars['subcat'] = $subcat['category_id'];
									$tpl_vars['subcat2'] = $subcat2['category_id'];			
								}
							}
						}
					}
				}
			}
		}

		// Zip search distances
		$tpl_vars['zip_distances'] = array(5, 10, 15, 20, 25, 30, 40, 50, 75, 100, 200, 300, 400, 500);

		if (empty($search_parms['b']['location_distance'])) {
			$search_parms['b']['location_distance'] = 25;
		}

		// Pet Breeds
		$sql = "SELECT * 
				FROM geodesic_classifieds_sell_question_choices 
			   WHERE type_id in (42, 43, 45, 46, 47)
			   ORDER BY type_id, display_order, value";
		$result = $db->GetAll($sql);

		$breeds = array();
		foreach ($result as $row) {
			switch ($row['type_id']) {
				case 42: // Dog
				$breeds['dog'][] = $row['value'];
				break;

				case 43: // Cat
				$breeds['cat'][] = $row['value'];
				break;

				case 45: // Bird
				$breeds['bird'][] = $row['value'];
				break;

				case 46: // Reptile
				$breeds['reptile'][] = $row['value'];
				break;

				case 47: // Other
				$breeds['other'][] = $row['value'];
			}
		}

		// Need to get bird breeds as well
		$sql = "SELECT parent, name as value, field.id FROM ".geoTables::leveled_field_value." field JOIN ".geoTables::leveled_field_value_languages." language ON field.id = language.id  
				WHERE `leveled_field` = ? AND enabled='yes' ORDER BY `level`,`display_order`,`name`";
		$result = $db->GetAll($sql, 5); // Fish Breeds Multi

		$fish_breeds = array();
		foreach ($result as &$row) {
			$row['value'] = urldecode($row['value']);

			if ($row['parent'] == 0) {
				$row['values'] = array();
				$fish_breeds[$row['id']] = $row;
			}
			else {
				if (in_array($row['value'], $values)) {
					$row['checked'] = true;
				}

				$fish_breeds[$row['parent']]['values'][] = $row;
			}
		}

		$breeds['fish'] = $fish_breeds;
		$tpl_vars['breeds'] = $breeds;

		// Pet Types (for Breeders, etc)
		$tpl_vars['pettypes'] = array(
			"dog" => "Dogs",
			"cat" => "Cats",
			"bird" => "Birds",
			"fish" => "Fish",
			"reptile" => "Reptiles",
			"other" => "Other"
		);

		// Service Types
		$sql = "SELECT value 
				FROM geodesic_classifieds_sell_question_choices 
			   WHERE type_id = 40
			   ORDER BY type_id, display_order, value";
		$result = $db->GetCol($sql);
		$tpl_vars['services'] = $result;

		// Dog Sizes
		$sql = "SELECT value
		          FROM geodesic_classifieds_sell_question_choices
		         WHERE type_id = 41
			  ORDER BY display_order, value";
		$result = $db->GetCol($sql);
		$tpl_vars['dogsizes'] = $result;

		// Cat Hair Length
		$sql = "SELECT value
		          FROM geodesic_classifieds_sell_question_choices
		         WHERE type_id = 44
			  ORDER BY display_order, value";
		$result = $db->GetCol($sql);
		$tpl_vars['cathairlength'] = $result;

		// Pet Product Types
		$sql = "SELECT value
		          FROM geodesic_classifieds_sell_question_choices
		         WHERE type_id = 40
			  ORDER BY display_order, value";
		$result = $db->GetCol($sql);
		$tpl_vars['productTypes'] = $result;

		// Sort options
		$tpl_vars['sort_options'] = array(
			0 => "",
			4 => "Latest",
			3 => "Oldest",
			1 => "Lowest Price",
			2 => "Highest Price",
			5 => "Title"
		);

		$tpl_vars['search_parms'] = $search_parms;

		return geoTemplate::loadInternalTemplate($params, $smarty, 'searchSidebar.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);		
	}

	private function buildCategoryChildren($parent_id, $all_categories) {
		$categories = array();
		foreach ($all_categories as $category) {
		    if ($category['category_id'] == $parent_id['shop']) {
		        $category['subcategories'] = $this->buildCategoryChildren($category['category_id'], $all_categories);
                $categories[] = $category;
		    }
			if ($category['parent_id'] == $parent_id['items'] || $category['parent_id'] == $parent_id['profiles']) {
				$category['subcategories'] = $this->buildCategoryChildren($category['category_id'], $all_categories);
				$categories[] = $category;
			}
		}

		return $categories;
	}
}