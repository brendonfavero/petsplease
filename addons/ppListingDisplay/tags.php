<?php
class addon_ppListingDisplay_tags extends addon_ppListingDisplay_info
{
	// This is a special listing tag (called with {listing addon=blaablaabla} not {addon blaa}) 
	public function extraQuestionValue($params, Smarty_Internal_Template $smarty)
	{		
		$listing_id = $params['listing_id'];
		$question_id = $params['qid'];
		$default_value = $params['default'];
		
		$extra_questions = geoListing::getExtraQuestions($listing_id);
		$extra_question = $extra_questions[$question_id];

		if (!$extra_question) {
			return $default_value;
		}
		else {
			return $extra_question['value'];
		}
	}

	public function extraCheckboxValue($params, Smarty_Internal_Template $smarty)
	{		
		$listing_id = $params['listing_id'];
		$question_id = $params['qid'];
		$true_value = $params['true'] ?: "Yes";
		$false_value = $params['false'] ?: "No";
		
		$extra_questions = geoListing::getCheckboxes($listing_id);
		$extra_question = $extra_questions[$question_id];

		if (!$extra_question) {
			return $false_value;
		}
		else {
			return $true_value;
		}
	}

	public function extraLeveledValue($params, Smarty_Internal_Template $smarty)
	{		
		$listing_id = $params['listing_id'];
		$question_id = $params['qid'];
		$level = $params['level'];
		$default_value = $params['default'];
		
		$extra_questions = geoListing::getLeveledValues($listing_id);
		$extra_levels = $extra_questions[$question_id];
		$extra_level = $extra_levels[$level];

		if (!$extra_level) {
			return $default_value;
		}
		else {
			return $extra_level['name'];
		}
	}

	public function extraMultiCheckboxDisplay($params, Smarty_Internal_Template $smarty)
	{		
		$joined = $params['joined'];
		$values = explode(";", $joined);

		$tpl_vars = array('values' => $values);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'multicheckDisplay.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}

	public function extraMultiCheckboxSelect($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$type = $params['typeid'];
		$listingfield = $params['listingfield'];
		$value = $params['value'];
		$values = explode(";", $value);

		$sql = "SELECT * FROM ".geoTables::sell_choices_table." WHERE `type_id` = ".$type." ORDER BY `display_order`,`value`";
		$options = $db->GetAll($sql);

		foreach ($options as &$option) {
			if (in_array($option['value'], $values)) {
				$option['checked'] = true;
			}
		}

		$tpl_vars = array(
			'listingfield' => $listingfield,
			'options' => $options
		);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'multicheckSelector.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}


	public function extraLeveledMutliCheckboxDisplay($params, Smarty_Internal_Template $smarty)
	{		
		$fieldvalue = $params['joined'];

		$strbygroup = explode("|", $fieldvalue);

		$groups = array();
		foreach ($strbygroup as $strgroup) {
			$startbrace = strpos($strgroup, "{");

			$grouplabel = substr($strgroup, 0, $startbrace);
			$groupvaluesstr = substr($strgroup, $startbrace + 1, -1);

			$groupvalues = explode(";", $groupvaluesstr);

			$groups[$grouplabel] = $groupvalues;
		}

		$tpl_vars = array('groups' => $groups);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'leveledmulticheckDisplay.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}


	public function extraLeveledMutliCheckboxSelect($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$type = $params['typeid'];
		$listingfield = $params['listingfield'];
		$fieldvalue = $params['value'];

		$strbygroup = explode("|", $fieldvalue);

		$values = array();
		foreach ($strbygroup as $strgroup) {
			$startbrace = strpos($strgroup, "{");

			$grouplabel = substr($strgroup, 0, $startbrace);
			$groupvaluesstr = substr($strgroup, $startbrace + 1, -1);

			$groupvalues = explode(";", $groupvaluesstr);
			$values = array_merge($values, $groupvalues);
		}

		$sql = "SELECT parent, name as value, field.id FROM ".geoTables::leveled_field_value." field JOIN ".geoTables::leveled_field_value_languages." language ON field.id = language.id  
				WHERE `leveled_field` = ".$type." AND enabled='yes' ORDER BY `level`,`display_order`,`name`";
		$options = $db->GetAll($sql);

		$groups = array();
		foreach ($options as &$option) {
			$option['value'] = urldecode($option['value']);

			if ($option['parent'] == 0) {
				$option['values'] = array();
				$groups[$option['id']] = $option;
			}
			else {
				if (in_array($option['value'], $values)) {
					$option['checked'] = true;
				}

				$groups[$option['parent']]['values'][] = $option;
			}
		}

		$tpl_vars = array(
			'listingfield' => $listingfield,
			'groups' => $groups
		);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'leveledmulticheckSelector.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}


	public function storeCategories($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $params['listing_id'];
		$listing = geoListing::getListing($listing_id);

		$seller = $listing->seller;

		$selectedCategory = $_REQUEST['c'];

		$sql = "SELECT c_cat.category_id, c_cat.category_name, cat_parent.category_id as category2_id, 
					   cat_parent.category_name as parent_category, count(c.id) as listings 
				  FROM geodesic_classifieds c
				  JOIN geodesic_categories c_cat ON c.category = c_cat.category_id
				  JOIN geodesic_categories cat_parent ON c_cat.parent_id = cat_parent.category_id
				 WHERE cat_parent.parent_id = 315 -- Pet Products
				   AND c.live = 1
				   AND c.seller = ?
				 GROUP BY c_cat.category_name, cat_parent.category_name
				 ORDER BY cat_parent.category_name, c_cat.category_name";

		$result = $db->GetAll($sql, array($seller));

		$categories = array();
		foreach ($result as &$row) {
			if (!array_key_exists($row['category2_id'], $categories)) {
				$categories[$row['category2_id']] = array('name' => $row['parent_category'], 'categories' => array());
			}

			$categories[$row['category2_id']]['categories'][] = array('id' => $row['category_id'], 'name' => $row['category_name']);
		}

		$tpl_vars = array();
		$tpl_vars['link'] = "index.php?a=2&b=" . $listing_id . '';
		$tpl_vars['categories'] = $categories;
		$tpl_vars['currentcategory'] = $selectedCategory;

		return geoTemplate::loadInternalTemplate($params, $smarty, 'storeCategories.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}

	public function storeProducts($params, Smarty_Internal_Template $smarty)
	{
		$page_size = 15;
		$products_category = 315;

		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $params['listing_id'];
		$listing = geoListing::getListing($listing_id);

		$seller = $listing->seller;

		$categoryid = isset($_REQUEST['c']) ? $_REQUEST['c'] : $products_category;
		$page = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);


		$in_statement = geoCategory::getInStatement($categoryid);
		$record_start = ($page - 1) * $page_size;

		$sql = "SELECT SQL_CALC_FOUND_ROWS *
			      FROM geodesic_classifieds c
			     WHERE c.seller = ? AND c.live = 1 AND c.category ".$in_statement."
			     LIMIT ?, ?";

		$result = $db->GetAll($sql, array($seller, $record_start, $page_size));
		$total_count = $db->GetOne("SELECT FOUND_ROWS()");

		foreach ($result as &$listing) {
			// Need to get preview images for listings (code adapted from Browse.class)
			$no_image_url = ($this->messages[500795])? geoTemplate::getURL('',$this->messages[500795]) : '';
			$photo_icon_url = ($this->messages[500796])? geoTemplate::getURL('',$this->messages[500796]) : '';
			if ($listing['image'] > 0) {
					$listing['full_image_tag'] = true;
					$width = $height = 0;
					$listing['image'] = geoImage::display_thumbnail($listing['id'], $width, $height, 1);
			} else if ($no_image_url && $this->configuration_data['photo_or_icon'] == 1) {
				$listing['full_image_tag'] = false;
				$listing['image'] = $no_image_url;
			} else {
				$listing['full_image_tag'] = true;
				$listing['image'] = '';
			}
			//

			// Format price (code adapted from Browse.class)
			$listing['price'] = geoString::displayPrice($listing['price'], $listing['precurrency'], $listing['postcurrency'], 'listing');
			//
		}

	
		$tpl_vars = array();
		$tpl_vars['listings'] = $result;

		$totalPages = ceil($total_count / $page_size);
		$link = 'index.php?a=2&b='.$listing_id.($categoryid != $products_category ? '&c=' . $categoryid : '');

		if ($totalPages > 0) {
			$tpl_vars['pagination'] = geoPagination::getHTML($totalPages, $page, $link . '&p=');
		}
		
		return geoTemplate::loadInternalTemplate($params, $smarty, 'storeProducts.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}
}