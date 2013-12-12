<?php
class addon_ppPetSelector_admin extends addon_ppPetSelector_info
{
	public function init_pages ($menuName)
	{
		menu_page::addonAddPage('addon_petselector_settings', '', 'Breed Settings', 'ppPetSelector', '');
	}

	public function display_addon_petselector_settings() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		$view = geoView::getInstance();
		$view->setBodyVar('messages', geoAdmin::getInstance()->message());

		$breedId = $_REQUEST['edit_id'];

		$db->Execute("set names 'utf8'"); 

		if ($breedId) {
			$sql = "SELECT * FROM petsplease_petselector_breed WHERE id = ?";
			$result = $db->GetRow($sql, array($breedId));

			$view->setBodyVar('detail', $result);
			$view->setBodyTpl('admin/changedetail.tpl', $this->name);
		}
		else {
			$sql = "SELECT id, pettype_id, breed FROM petsplease_petselector_breed";
			$result = $db->GetAll($sql);

			$view->setBodyVar('breeds', $result);
			$view->setBodyTpl('admin/breedlist.tpl', $this->name);
		}
	}

	public function update_addon_petselector_settings() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$vars = $_REQUEST['d'];

		if ($vars['id']) {
			// Update existing row
			$cols = array("pettype_id", "breed", "description", "height", "weight", "size", "lifespan", "hypoallergenic", 
				"colours", "coatlength", "housing", "familyfriendly", "trainability", "energy", "grooming", "shedding");

			$sets = array_map(function($col) use ($vars)  {
				return "$col = '" . addslashes($vars[$col]) . "'";
			}, $cols);

			$sql = "UPDATE petsplease_petselector_breed SET " . implode(",", $sets) . " WHERE id = " . $vars['id'];

			geoAdmin::getInstance()->message("SQL:" . $sql);
		}
		else {
			// Insert new row
			// $sql = 
			// 	"INSERT INTO petsplease_petselector_breed 
			// 	 (pettype_id, breed, description, height, weight, size, lifespan, hypoallergenic, colours, coatlength, housing, familyfriendly, trainability, energy, grooming, shedding)
			// ";	

		}



	}
}