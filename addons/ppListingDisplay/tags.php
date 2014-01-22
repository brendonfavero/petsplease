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


	public function specialListingBox($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $params['listing_id'];
		$listing = geoListing::getListing($listing_id);

		$seller = $listing->seller;

		$util = geoAddon::getUtil($this->name);


		$leadListing = null;
		$listings = array();

		$grab = function($category, $onlyFirst) use (&$util, $seller, $listing_id) {
			$listings = $util->getUsersSpecialListings($seller, $category, $onlyFirst);

			$extractor = function($listing) {
				return array(
					'id' => $listing['id'],
					'title' => $listing['title'],
					'category' => $listing['category']
				);
			};

			if ($onlyFirst && !!$listings && $listings['id'] != $listing_id) {
				return $extractor($listings);			
			}
			else if (!$onlyFirst && !empty($listings)) {
				$filtered = array_filter($listings, function($listing) use ($listing_id) 
					{ return $listing['id'] != $listing_id; });
				return array_map($extractor, $filtered);
			}
		};

		// Shop listing
		$shopListing = $grab(412, true);
		if ($shopListing) {
			$shopUtil = geoAddon::getUtil('ppStoreSeller');
			if($shopUtil->listingIsValidStoreProduct($listing_id, true) && !$leadListing) {
				$leadListing = $shopListing;
			}
			else {
				$listings[] = $shopListing;
			}
		}

		// Shelters
		$shelterListing = $grab(420, true);
		if (!empty($shelterListing)) {
			// Is this a pet for sale?
			$categories = geoCategory::getTree($listing->category);
			$topcat = reset($categories);

			if ($topcat['category_id'] == 308 && !$leadListing) {
				// Shift the listing off so it wont be shown twice
				$leadListing = $shelterListing;
			}

			if (!empty($shelterListing)) {
				$listings[] = $shelterListing;
			}			
		}

		// Breeders
		$breederListings = $grab(316);
		if (!empty($breederListings)) {
			// Is this a pet for sale?
			$categories = geoCategory::getTree($listing->category);
			$topcat = reset($categories);

			if ($topcat['category_id'] == 308 && !$leadListing) {
				// Shift the listing off so it wont be shown twice
				$leadListing = array_shift($breederListings);
			}

			if (!empty($breederListings)) {
				$listings = array_merge($listings, $breederListings);
			}
		}

		// Accomodation
		$ls = $grab(411);
		if (!empty($ls)) $listings = array_merge($listings, $ls); 

		// Services
		$ls = $grab(318);
		if (!empty($ls)) $listings = array_merge($listings, $ls); 

		// Clubs
		$ls = $grab(319);
		if (!empty($ls)) $listings = array_merge($listings, $ls); 


		if (!$leadListing && empty($listings)) {
			return '';
		}

		if ($leadListing) {
			$ppImagesUtil = geoAddon::getUtil('ppListingImagesExtra');
			$leadListing['logo'] = $ppImagesUtil->listingLogoImage($leadListing['id']);
		}

		$tpl_vars = array(
			'lead' => $leadListing,
			'listings' => $listings
		);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'specialListingBox.tpl',
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

	public function listingsEmbed($params, Smarty_Internal_Template $smarty)
	{
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$page_size = 15;
		$archCategory = $params['category'] or die("No category supplied to listingsEmbed");

		$listing_id = $params['listing_id'];
		$listing = geoListing::getListing($listing_id);

		$seller = $listing->seller;

		$categoryid = isset($_REQUEST['c']) ? $_REQUEST['c'] : $archCategory;
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
		
		return geoTemplate::loadInternalTemplate($params, $smarty, 'listingsEmbed.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}

	public function headerImageClass($params, Smarty_Internal_Template $smarty) {
		// Depending where on the site we are we output a different image to be shown in the header

		// if we're searching and we're on a specific pet type, show it

		// if we're on a listing (pet for sale) show pets image

		// other wise show default

		$listingid = $_REQUEST['a'] == 2 ? $_REQUEST['b'] : false; 
		if ($listingid) {
			$listing = geoListing::getListing($listingid);
            $listingdata = $listing->toArray();
		}       

		if ($_REQUEST['c'] == 309 || ($listing && $listing->category == 309)) {
			return "headerimg-dog";
		} 
		else if ($_REQUEST['c'] == 310 || ($listing && $listing->category == 310)) {
			return "headerimg-cat";
		}
		else if ($_REQUEST['c'] == 311 || ($listing && $listing->category == 311)) {
			return "headerimg-bird";
		}
		else if ($_REQUEST['c'] == 312 || ($listing && $listing->category == 312)) {
			return "headerimg-fish";
		}
		else if ($_REQUEST['c'] == 313 || ($listing && $listing->category == 313)) {
			return "headerimg-reptile";
		}
		else if ($_REQUEST['c'] == 314 || ($listing && $listing->category == 314)) {
			return "headerimg-other";
		}
        else if ($_REQUEST['c'] == 320 || ($listing && self::getParentCategory($listing->category) == 320)) {
            return "headerimg-dogproduct";
        }
        else if ($_REQUEST['c'] == 321 || ($listing && self::getParentCategory($listing->category) == 321)) {
            return "headerimg-catproduct";
        }
        else if ($_REQUEST['c'] == 322 || ($listing && self::getParentCategory($listing->category) == 322)) {
            return "headerimg-birdproduct";
        }
        else if ($_REQUEST['c'] == 323 || ($listing && self::getParentCategory($listing->category) == 323)) {
            return "headerimg-fishproduct";
        }
        else if ($_REQUEST['c'] == 324 || ($listing && self::getParentCategory($listing->category) == 324)) {
            return "headerimg-reptileproduct";
        }
        else if ($_REQUEST['c'] == 326 || ($listing && self::getParentCategory($listing->category) == 326)) {
            return "headerimg-otherproduct";
        }
        else if ($_REQUEST['b']['specpettype'] == 'dog' || ($listing && isset($listingdata['optional_field_8']->optional_field_8))) {
            return "headerimg-dogbreeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'cat' || ($listing && isset($listing->optional_field_9))) {
            return "headerimg-catbreeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'bird' || ($listing && isset($listing->optional_field_10))) {
            return "headerimg-birdbreeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'fish' || ($listing && isset($listing->optional_field_11))) {
            return "headerimg-fishbreeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'reptile' || ($listing && isset($listing->optional_field_12))) {
            return "headerimg-reptilebreeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'other' || ($listing && isset($listing->optional_field_13))) {
            return "headerimg-otherbreeders";
        }
		else {
			return "headerimg-allpets";
		}
	}

    public function headerTextClass($params, Smarty_Internal_Template $smarty) {
        $listingid = $_REQUEST['a'] == 2 ? $_REQUEST['b'] : false; 
        if ($listingid) {
            $listing = geoListing::getListing($listingid);
        }

        if ($_REQUEST['c'] == 309 || ($listing && $listing->category == 309)) {
            return "Dogs for Sale";
        } 
        else if ($_REQUEST['c'] == 310 || ($listing && $listing->category == 310)) {
            return "Cats for Sale";
        }
        else if ($_REQUEST['c'] == 311 || ($listing && $listing->category == 311)) {
            return "Birds for Sale";
        }
        else if ($_REQUEST['c'] == 312 || ($listing && $listing->category == 312)) {
            return "Fish for Sale";
        }
        else if ($_REQUEST['c'] == 313 || ($listing && $listing->category == 313)) {
            return "Reptiles for Sale";
        }
        else if ($_REQUEST['c'] == 314 || ($listing && $listing->category == 314)) {
            return "Other Pets for Sale";
        }
        else if ($_REQUEST['c'] == 320 || ($listing && self::getParentCategory($listing->category) == 320)) {
            return "Dog Products for Sale";
        }
        else if ($_REQUEST['c'] == 321 || ($listing && self::getParentCategory($listing->category) == 321)) {
            return "Cat Products for Sale";
        }
        else if ($_REQUEST['c'] == 322 || ($listing && self::getParentCategory($listing->category) == 322)) {
            return "Bird Products for Sale";
        }
        else if ($_REQUEST['c'] == 323 || ($listing && self::getParentCategory($listing->category) == 323)) {
            return "Fish Products for Sale";
        }
        else if ($_REQUEST['c'] == 324 || ($listing && self::getParentCategory($listing->category) == 324)) {
            return "Reptile Products for Sale";
        }
        else if ($_REQUEST['c'] == 326 || ($listing && self::getParentCategory($listing->category) == 326)) {
            return "Other Pet Products for Sale";
        }
        else if ($_REQUEST['b']['specpettype'] == 'dog' || ($listing && isset($listing->optional_field_8))) {
            return "Dog Breeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'cat' || ($listing && isset($listing->optional_field_9))) {
            return "Cat Breeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'bird' || ($listing && isset($listing->optional_field_10))) {
            return "Bird Breeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'fish' || ($listing && isset($listing->optional_field_11))) {
            return "Fish Breeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'reptile' || ($listing && isset($listing->optional_field_12))) {
            return "Reptile Breeders";
        }
        else if ($_REQUEST['b']['specpettype'] == 'other' || ($listing && isset($listing->optional_field_13))) {
            return "Other Breeders";
        }
        else {
            return "Pets and Products for Sale";
        }
    }
    

    private function getParentCategory($category_id) {
        $db = DataAccess::getInstance();
    
        $catSql = "SELECT parent_id as category_parent FROM geodesic_categories cat WHERE cat.category_id = " . $category_id;
        $catResult = $db->Execute($catSql);
    
        if ($catResult && $catResult->RecordCount() > 0 && $listing = $catResult->FetchRow()) {
            return $listing['category_parent'];
        }
    }
}