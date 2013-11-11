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
	}
}
?>