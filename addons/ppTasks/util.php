<?php
class addon_ppTasks_util extends addon_ppTasks_info
{
	public function core_Search_classifieds_generate_query ($vars) {
		// Simply need to get the current breed being viewed and assign it to the View itself
		$db = DataAccess::getInstance();
		$searchClass = $vars['this'];
		$breed = $searchClass->search_criteria['breed'];

		if ($breed != "") {
			$sql = "SELECT * FROM petsplease_petselector_breed WHERE breed = ?";
			$breed_info = $db->GetRow($sql, array($breed));

			geoView::getInstance()->setBodyVar("viewing_breed", $breed_info);
		}
	}
}
