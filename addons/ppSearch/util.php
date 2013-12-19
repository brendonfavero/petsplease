<?php
class addon_ppSearch_util extends addon_ppSearch_info
{
	const CATEGORY_PETSFORSALE_DOG = 309;
	const CATEGORY_PETSFORSALE_CAT = 310;
	const CATEGORY_PETSFORSALE_BIRD = 311;
	const CATEGORY_PETSFORSALE_FISH = 312;
	const CATEGORY_PETSFORSALE_REPTILE = 313;
	const CATEGORY_PETSFORSALE_OTHER = 314;

	const CATEGORY_BREEDER = 316;

	const BREEDER_DOG_BREEDS_COL = "optional_field_8";
	const BREEDER_CAT_BREEDS_COL = "optional_field_9";
	const BREEDER_BIRD_BREEDS_COL = "optional_field_10";
	const BREEDER_FISH_BREEDS_COL = "optional_field_11";
	const BREEDER_REPTILE_BREEDS_COL = "optional_field_12";
	const BREEDER_OTHER_BREEDS_COL = "optional_field_13";

	public function core_Search_classifieds_generate_query ($vars)
	{
		$searchClass = $vars['this'];
		$classTable = geoTables::classifieds_table;
		$questionTable = geoTables::classified_extra_table;
		$levT = geoTables::listing_leveled_fields;
		
		$query = $searchClass->db->getTableSelect(DataAccess::SELECT_SEARCH);

		// Hide from results sellable products that have run out of stock
		$query->where("($classTable.`optional_field_1` != 1 OR $classTable.`optional_field_2` > 0)");

		// Is Sold?
		$isSold = $searchClass->search_criteria["sold_displayed"];
		if ($isSold) {
			$query->where("$classTable.`sold_displayed` = $isSold", 'sold_displayed');	
		}	

		// Pets for Sale breeds? (except for fish)
		$breed_criteria = $searchClass->search_criteria["breed"];
		$breed_criteria = mysql_real_escape_string($breed_criteria);
		$urlencodedBreed = urlencode($breed_criteria);

		if ($breed_criteria && in_array($searchClass->site_category,
				array(self::CATEGORY_PETSFORSALE_DOG, self::CATEGORY_PETSFORSALE_CAT, 
				self::CATEGORY_PETSFORSALE_BIRD, self::CATEGORY_PETSFORSALE_REPTILE, self::CATEGORY_PETSFORSALE_OTHER))) {
			$subQuery = new geoTableSelect($questionTable);
			$subQuery->where("$questionTable.`classified_id` = $classTable.`id`");

			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_DOG)
				$subQuery->orWhere("$questionTable.`question_id`=171", 'pets_breed_question')
					     ->orWhere("$questionTable.`question_id`=172", 'pets_breed_question');
			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_CAT)
				$subQuery->orWhere("$questionTable.`question_id`=178", 'pets_breed_question')
					     ->orWhere("$questionTable.`question_id`=179", 'pets_breed_question');
			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_BIRD)
				$subQuery->where("$questionTable.`question_id`=185", 'pets_breed_question');
			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_REPTILE)
				$subQuery->where("$questionTable.`question_id`=188", 'pets_breed_question');
			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_OTHER)
				$subQuery->where("$questionTable.`question_id`=189", 'pets_breed_question');

			$subQuery->where("$questionTable.`value` = \"$urlencodedBreed\"",'pets_breed');
			$query->where("EXISTS ($subQuery)");
		}

		// Fish for Sale breed
		if ($breed_criteria && $searchClass->site_category == self::CATEGORY_PETSFORSALE_FISH) {
			$subQuery = new geoTableSelect($levT);
			$subQuery->where("$levT.`listing` = $classTable.`id`")
					 ->where("$levT.`leveled_field` = 5") // Fish Breed
					 ->where("$levT.`default_name` = \"$urlencodedBreed\""); // don't care if we match on lvl 1 or 2
			$query->where("EXISTS ($subQuery)");
		}

		// Breeder breeds?
		if ($searchClass->site_category == self::CATEGORY_BREEDER) {
			$breeder_type = $searchClass->search_criteria["specpettype"];

			if ($breeder_type) {
				$columnToSearch = "";
				switch ($breeder_type) {
					case "dog":
					$columnToSearch = self::BREEDER_DOG_BREEDS_COL;
					break;

					case "cat":
					$columnToSearch = self::BREEDER_CAT_BREEDS_COL;
					break;

					case "bird":
					$columnToSearch = self::BREEDER_BIRD_BREEDS_COL;
					break;

					case "fish":
					$columnToSearch = self::BREEDER_FISH_BREEDS_COL;
					break;

					case "reptile":
					$columnToSearch = self::BREEDER_REPTILE_BREEDS_COL;
					break;

					case "other":
					$columnToSearch = self::BREEDER_OTHER_BREEDS_COL;
					break;				
				}

				if ($columnToSearch != "") {
					if ($breed_criteria) {
						$query->where("$classTable.`$columnToSearch` LIKE \"%$urlencodedBreed%\"", "breeder_breed");
					}
					else {
						$query->where("$classTable.`$columnToSearch` <> ''", "breeder_breed");
					}
				}
				else {
					// If parameters are invalid we should show nothing
					$query->where("true = false", "breeder_breed");
					return;
				}
			}
		}

		// Service
		$service_criteria = $searchClass->search_criteria["service"];
		if ($service_criteria) {
			$service_criteria = mysql_real_escape_string($service_criteria);
			$urlencodedService = urlencode($service_criteria);

			$query->where("$classTable.`optional_field_1` LIKE \"%$urlencodedService%\"", "services_service");
		}

		// Dog Size
		$dogsize_criteria = $searchClass->search_criteria["dog_size"];
		if ($dogsize_criteria) {
			$dogsize_criteria = mysql_real_escape_string($dogsize_criteria);
			$urlencodedDogsize = urlencode($dogsize_criteria);

			$subQuery = new geoTableSelect($questionTable);
			$subQuery->where("$questionTable.`classified_id` = $classTable.`id`");
			$subQuery->where("$questionTable.`question_id`=168");
			$subQuery->where("$questionTable.`value` = \"$urlencodedDogsize\"");
			$query->where("EXISTS ($subQuery)");
		}

		$purebred_criteria = $searchClass->search_criteria["purebred_only"];
		if ($purebred_criteria) {
			$subQueryBreed1 = new geoTableSelect($questionTable);
			$subQueryBreed1->where("$questionTable.`classified_id` = $classTable.`id`");

			$subQueryBreed2 = new geoTableSelect($questionTable);
			$subQueryBreed2->where("$questionTable.`classified_id` = $classTable.`id`");

			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_DOG) {
				$subQueryBreed1->where("$questionTable.`question_id`=171");
				$subQueryBreed2->where("$questionTable.`question_id`=172");
			}
			if ($searchClass->site_category == self::CATEGORY_PETSFORSALE_CAT) {
				$subQueryBreed1->where("$questionTable.`question_id`=178");
				$subQueryBreed2->where("$questionTable.`question_id`=179");
			}

			$subQueryBreed1->where("$questionTable.`value` <> ''");
			$subQueryBreed2->where("$questionTable.`value` <> ''");
			$query->where("EXISTS ($subQueryBreed1)");
			$query->where("NOT EXISTS ($subQueryBreed2)");
		}
		
		$adobtable_criteria = $searchClass->search_criteria["adoptable_only"];
		if ($adobtable_criteria) {
			
			$sql = "SELECT 1 from geodesic_classifieds as shelters where shelters.category = 420 and shelters.seller = $classTable.seller";
			$query->where("EXISTS ($sql)");
			 
		}

		$this->handleLocationSearch(&$searchClass->search_criteria, &$query);
	}

	private function handleLocationSearch($fields, $query) {
		$db = DataAccess::getInstance();
		$classTable = geoTables::classifieds_table;

		$location = $fields['location'];
		$distance = $fields['location_distance'];

		if (empty($location)) return;

		$postcode = null;
		if (!is_numeric($location)) {
			// We assume they've passed in a suburb, we need to find the associated postcode for it.
			// Clean input
			$location = strtolower($location);
			$location = str_replace(",", " ", $location); // remove commas
			$location = str_replace(array("'", "&#039;"), "", $location); // remove apostrophies
			$location = trim($location);
			$location = preg_replace('!\s+!', ' ', $location); // remove consecutive whitespace

			// see if there's a postcode at the end
			$location_split = explode(" ", $location);
			$location_lastterm = array_pop($location_split);
			if (is_numeric($location_lastterm)) {
				$postcode = $location_lastterm;
				$location = implode(" ", $location_split);
			}

			// try pull a state out of the field
			$states = array( // format => odd = abbreviation, even = full name
				'nsw', 'new south wales', 'qld', 'queensland', 'nt', 'northern territory', 
				'sa', 'south australia', 'tas', 'tasmania', 'vic', 'victoria', 
				'wa', 'western australia', 'act', 'australian capital territory'
			);
			$states_abbrToRegionId = array(
				'nsw' => 355, 'qld' => 357, 'nt' => 356, 'sa' => 358, 
				'tas' => 359, 'vic' => 360, 'wa' => 361, 'act' => 351
			);

			$state_exceptions = array('mount victoria', 'port victoria', 'university of tasmania');

			// Only check for state if location is not among the exceptions
			$location_state = null;
			if (!in_array($location, $state_exceptions)) {
				for ($i = 0; $i < count($states); $i++) {
					$state = $states[$i];

					if ($this->endsWithWord($location, $state)) {
						// Take the state off the end
						$location = substr($location, 0, strlen($location) - strlen($state));
						$location = trim($location);

						$location_state = ($i&1 ? $states[$i - 1] : $state); // use previous if odd 
					}
				}
			}

			if (!empty($location_state) && empty($location) && !$postcode) {
				// State only - we don't need to do proximity search, we can just match directly on state
				$fields['location'] = strtoupper($location_state);
				unset($fields['location_distance']);

				if (!$postcode) {
					$regionId = $states_abbrToRegionId[$location_state];
					$regionTbl = "geodesic_listing_regions";
					$query->where("EXISTS(SELECT 1 FROM $regionTbl WHERE $regionTbl.listing = $classTable.id AND level = 2 AND region = $regionId)");
					return;
				}
			}
			else {
				// Suburb + maybe state, need to grab a postcode
				if (!$postcode) {
					$sql = "SELECT postcode, suburb, state FROM petsplease_location_suburbs WHERE suburb = ?";
					if ($location_state) $sql .= " AND state = '".$location_state."'";

					$result = $db->GetAll($sql, array($location));

					if (count($result) >= 1) {
						$postcode = $result[0]['postcode'];
						$location_state = $result[0]['state'];
					}

					if (count($result) > 1) {
						$querystring = $_SERVER['QUERY_STRING'];
						$search_parms = array();
						parse_str($querystring, &$search_parms);

						foreach ($result as &$locationrow) {
							$search_parms['b']['location'] = ucwords($locationrow['suburb']) . ', ' . strtoupper($locationrow['state']) . ' ' . $locationrow['postcode'];
							$locationrow['querystring'] = http_build_query($search_parms);
						}

						geoView::getInstance()->setBodyVar('multiple_locations_found', $result);
					}
				}

				$fields['location'] = ucwords($location) . ', ' . strtoupper($location_state) . ' ' . $postcode;
			}
		}
		else {
			$postcode = $location;
		}

		if ($postcode) {
			// Pass the actual creating of the sql to the zipsearch addon
			$zipsearchUtil = geoAddon::getUtil("zipsearch");
			$search_sql = $zipsearchUtil->getSearchSql($postcode, $distance);
			if ($search_sql) {
				//if we have distance query, replace the built-in where with this one.
				$query->where($search_sql, 'location_zip');
			}
		}
		else {
			// Location could not be found
			$query->where('true = false');
			geoView::getInstance()->setBodyVar('invalid_location_entered', true);
		}
	}

	function endsWithWord($haystack, $needle) {
		if ($needle === "" || $needle === $haystack) {
			return true;
		}
		else if (substr($haystack, -strlen(' '.$needle)) === ' '.$needle) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>